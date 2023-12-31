<?php
namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\Customers;
use App\Models\Core\Images;
use App\Models\Core\Setting;
use App\Models\Core\Languages;
use App\Models\Core\Redemption;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Core\States;

class CustomersController extends Controller
{
    //
    public function __construct(Customers $customers, Setting $setting, Redemption $redeem)
    {
        $this->Customers = $customers;
        $this->Redemption = $redeem;
        $this->myVarsetting = new SiteSettingController($setting);
    }

    public function display()
    {
        $title = array('pageTitle' => Lang::get("labels.ListingCustomers"));
        $language_id = '1';

        $customers = $this->Customers->paginator();

        $result = array();
        $index = 0;
        foreach($customers as $customers_data){
            array_push($result, $customers_data);

            $devices = DB::table('devices')->where('user_id','=',$customers_data->id)->orderBy('created_at','DESC')->take(1)->get();
            $result[$index]->devices = $devices;
            $index++;
        }

        $customerData = array();
        $message = array();
        $errorMessage = array();

        $customerData['message'] = $message;
        $customerData['errorMessage'] = $errorMessage;
        $customerData['result'] = $customers;
        return view("admin.customers.index", $title)->with('customers', $customerData);
    }

    public function add(Request $request)
    {
        $title = array('pageTitle' => Lang::get("labels.AddCustomer"));
        $images = new Images;
        $allimage = $images->getimages();
        $language_id = '1';
        $customerData = array();
        $message = array();
        $errorMessage = array();
        $states = States::all();
        $customerData['countries'] = $this->Customers->countries();
        $customerData['states'] = $states;
        $customerData['message'] = $message;
        $customerData['errorMessage'] = $errorMessage;
        return view("admin.customers.add", $title)->with('customers', $customerData)->with('allimage',$allimage);
    }


    //add addcustomers data and redirect to address
    public function insert(Request $request)
    {
        $language_id = '1';
        //get function from other controller
        $images = new Images;
        $allimage = $images->getimages();

        $customerData = array();
        $message = array();
        $errorMessage = array();

        //check email already exists
        $existEmail = $this->Customers->email($request);
        $this->validate($request, [
            'customers_firstname' => 'required',
            'customers_lastname' => 'required',
/*            'customers_gender' => 'required',
*/            //'image_id' => 'required',
            /*'customers_dob' => 'required',*/
            'customers_telephone' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'isActive' => 'required',
        ]);


        if (count($existEmail)> 0 ) {
            $messages = Lang::get("labels.Email address already exist");
            return Redirect::back()->withErrors($messages)->withInput($request->all());
        } else {
            $customers_id = $this->Customers->insert($request);
            $address_book = \DB::table('address_book')->insertGetId([
                'entry_postcode'            =>   request()->entry_postcode,
                'entry_street_address'      =>   request()->entry_street_address
            ]);

            $user_to_address = \DB::table('user_to_address')->insert([
                'user_id'                   =>   $customers_id,
                'address_book_id'           =>   $address_book
            ]);
            //return redirect('admin/customers/address/display/' . $customers_id)->with('update', 'Customer has been created successfully!');
            return redirect('admin/customers/display')->with('update', 'Customer has been created successfully!');
        }
    }

    public function diplayaddress(Request $request){

        $title = array('pageTitle' => Lang::get("labels.AddAddress"));

        $language_id   				=   $request->language_id;
        $id            				=   $request->id;

        $customerData = array();
        $message = array();
        $errorMessage = array();

        $customer_addresses = $this->Customers->addresses($id);
        $countries = $this->Customers->country();

        $customerData['message'] = $message;
        $customerData['errorMessage'] = $errorMessage;
        $customerData['customer_addresses'] = $customer_addresses;
        $customerData['countries'] = $countries;
        $customerData['user_id'] = $id;

        return view("admin.customers.address.index",$title)->with('data', $customerData);
    }


    //add Customer address
    public function addcustomeraddress(Request $request){
      $customer_addresses = $this->Customers->addcustomeraddress($request);
      return $customer_addresses;
    }

    public function editaddress(Request $request){

      $user_id                 =   $request->user_id;
      $address_book_id         =   $request->address_book_id;

      $customer_addresses = $this->Customers->addressBook($address_book_id);
      $countries = $this->Customers->countries();
      $zones = $this->Customers->zones($customer_addresses);
      $customers = $this->Customers->checkdefualtaddress($address_book_id);

      $customerData['user_id'] = $user_id;
      $customerData['customer_addresses'] = $customer_addresses;
      $customerData['countries'] = $countries;
      $customerData['zones'] = $zones;
      $customerData['customers'] = $customers;

      return view("admin/customers/address/editaddress")->with('data', $customerData);
    }

    //update Customers address
    public function updateaddress(Request $request){
      $customer_addresses = $this->Customers->updateaddress($request);
      return ($customer_addresses);
    }

    public function deleteAddress(Request $request){
      $customer_addresses = $this->Customers->deleteAddresses($request);
      return redirect()->back()->withErrors([Lang::get("labels.Delete Address Text")]);
    }

    //editcustomers data and redirect to address
    public function edit(Request $request){

      $images           = new Images;
      $allimage         = $images->getimages();
      $title            = array('pageTitle' => Lang::get("labels.EditCustomer"));
      $language_id      =   '1';
      $id               =   $request->id;

      $customerData = array();
      $message = array();
      $errorMessage = array();
      $customers = $this->Customers->edit($id);
      $states = States::all();
      $user_to_address = \DB::table('user_to_address')->where('user_id',$request->id)->first();
      $customers_address = \DB::table('address_book')->where('address_book_id',$user_to_address->address_book_id)->first();

      $customerData['message'] = $message;
      $customerData['errorMessage'] = $errorMessage;
      $customerData['states'] = $states;
      $customerData['countries'] = $this->Customers->countries();
      $customerData['customers_address'] = $customers_address;
      $customerData['customers'] = $customers;

      return view("admin.customers.edit",$title)->with('data', $customerData)->with('allimage', $allimage);
    }

    //add addcustomers data and redirect to address
    public function update(Request $request){
        $language_id  =   '1';
        $user_id				  =	$request->customers_id;

        $customerData = array();
        $message = array();
        $errorMessage = array();

        //get function from other controller
        if($request->image_id!==null){
            $customers_picture = $request->image_id;
        }	else{
            $customers_picture = $request->oldImage;
        }

        if($request->image_id){
            $uploadImage = $request->image_id;
            $uploadImage = DB::table('image_categories')->where('image_id',$uploadImage)->select('path')->first();
            $customers_picture = $uploadImage->path;
        }	else{
            $customers_picture = $request->oldImage;
        }

        $user_to_address = \DB::table('user_to_address')->where('user_id',$user_id)->first();
        $customers_address = \DB::table('address_book')->where('address_book_id',$user_to_address->address_book_id)->first();
        $user_data = array(
            'gender'   		 	=>   $request->gender,
            'first_name'		=>   $request->first_name,
            'last_name'		 	=>   $request->last_name,
            'state_id'      =>   $request->state_id,
            'dob'	 			 	  =>	 $request->dob,
            'email'	 		    =>   $request->email,
            'phone'	 	      =>	 $request->phone,
            'status'		    =>   $request->status,
            'avatar'	 		  =>	 $customers_picture,
            'updated_at'    => date('Y-m-d H:i:s'),
        );

        $address_book = \DB::table('address_book')->where('address_book_id',$customers_address->address_book_id)
            ->update([
                'entry_postcode'            =>   request()->entry_postcode,
                'entry_street_address'      =>   request()->entry_street_address
            ]);

        $customer_data = array(
          'customers_newsletter'   		 	=>   0,
          'updated_at'    => date('Y-m-d H:i:s'),
        );


        if($request->changePassword == 'yes'){
            $user_data['password'] = Hash::make($request->password);
        }

        //check email already exists
        if($request->old_email_address!=$request->email){
            $existEmail = $this->Customers->extendemail($request);
            if(count($existEmail)>0){
                $messages = Lang::get("labels.Email address already exist");
                return Redirect::back()->withErrors($messages)->withInput($request->all());
            }else{
               $this->Customers->updaterecord($customer_data,$user_id,$user_data);
               return redirect('admin/customers/display/');
            }
        }else{
            $this->Customers->updaterecord($customer_data,$user_id,$user_data);
            return redirect('admin/customers/display/');
        }
    }

	public function history(Request $request){

		$title = array('pageTitle' => Lang::get("labels.ListingBanks"));
		$language_id = '1';
		$myid = $request->id;

		$result = array();
		$message = array();
		$errorMessage = array();

	
		$admins = DB::table('promotion_redemption')->where('customer_id','=', $myid)->get();
		
		
		foreach($admins as $admin) {
		

			$admin->customer_name = $this->Redemption->customername($admin->customer_id);
			$admin->referral_name = $this->Redemption->salesname($admin->sales_agent);
			$admin->promotion_name = $this->Redemption->promotionname($admin->promotion_id);

        }
		//dd($admins);
		

		
		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		$result['admins'] = $admins;

		return view("admin.customers.history",$title)->with('result', $result);
       
	}


    public function delete(Request $request){
      $this->Customers->destroyrecord($request->users_id);
      return redirect()->back()->withErrors([Lang::get("labels.DeleteCustomerMessage")]);
    }

    public function filter(Request $request){
      $filter    = $request->FilterBy;
      $parameter = $request->parameter;

      $title = array('pageTitle' => Lang::get("labels.ListingCustomers"));
      $customers  = $this->Customers->filter($request);

      $result = array();
      $index = 0;
      foreach($customers as $customers_data){
          array_push($result, $customers_data);

          $devices = DB::table('devices')->where('user_id','=',$customers_data->id)->orderBy('created_at','DESC')->take(1)->get();
          $result[$index]->devices = $devices;
          $index++;
      }

      $customerData = array();
      $message = array();
      $errorMessage = array();

      $customerData['message'] = $message;
      $customerData['errorMessage'] = $errorMessage;
      $customerData['result'] = $customers;

      return view("admin.customers.index",$title)->with('customers', $customerData)->with('filter',$filter)->with('parameter',$parameter);
    }
}
