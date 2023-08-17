<?php
namespace App\Http\Controllers\AdminControllers;
use App\Models\Core\Languages;
use App\Models\Core\Setting;
use App\Models\Admin\Admin;
use App\Models\Core\Order;
use App\Models\Core\Customers;
use App\Models\Core\States;
use App\Models\Core\Cities;
use App\Models\Core\Makes;
use App\Models\Core\Cars;
use App\Models\Core\User;
use App\Models\Core\Template;
use App\Models\Core\Banks;
use App\Models\Core\Segments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Exception;
use App\Models\Core\Images;
use Validator;
use Hash;
use Auth;
use ZipArchive;
use File;
use Mail;
use Carbon\Carbon;

class BankController extends Controller
{
    //
    private $domain;
    public function __construct(Banks $bank,Admin $admin, Setting $setting, Order $order, Customers $customers, Cities $cities, States $States, Makes $makes, Cars $cars, User $user)
    {
		$this->bank =$bank;
        $this->Setting = $setting;
        $this->Admin = $admin;
        $this->Order = $order;
		$this->Customers = $customers;
		$this->States = $States;
		$this->Cities = $cities;
		$this->Makes = $makes;
		$this->Cars = $cars;
		$this->User = $user;
    }

    public function banks(Request $request){
		$title = array('pageTitle' => Lang::get("labels.ListingBanks"));
		$language_id = '1';

		$result = array();
		$message = array();
		$errorMessage = array();

		// $admins = Banks::all();

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		// $result['admins'] = $admins;

		// test
		$bank = $this->bank->paginator(30);
      	// return view("admin.state.index")->with('state',$state);

		return view("admin.bank.index",$title)->with('result', $result)->with('bank', $bank);
	}

	public function addbanks(Request $request){
		$title = array('pageTitle' => Lang::get("labels.addbanks"));

		$result = array();
		$message = array();
		$errorMessage = array();
		$images = new Images;
		$allimage = $images->getimages();
		//get function from ManufacturerController controller
		$myVar = new AddressController();
		$result['countries'] = $myVar->getAllCountries();
		$state = $this->States->getter();
		$result['state'] = $state;
		$city = $this->Cities->getter();
        $result['city'] = $city;
		$adminTypes = DB::table('user_types')->where('isActive', 1)->where('user_types_id','>','10')->get();
		$result['adminTypes'] = $adminTypes;

		return view("admin.bank.add",$title)->with('result', $result)->with('allimage',$allimage);
	}

    public function insertBanks(Request $request){
        // dd($request->all());
		$time = Carbon::now();
		$currentUser = Auth()->user()->id;
		$uploadImage = '';

		if ($request->hasFile('logo')) {
            $time = Carbon::now();
			$image = $request->file('logo');
            $extensions = Setting::imageType();
            if ($image and in_array($request->logo->extension(), $extensions)) {
				// getting size
                $size = getimagesize($image);
                list($width, $height, $type, $attr) = $size;
                // Getting the extension of the file
                $extension = $image->getClientOriginalExtension();
                // Creating the directory, for example, if the date = 18/10/2017, the directory will be 2017/10/
                $directory = date_format($time, 'Y') . '/' . date_format($time, 'm');
                // Creating the file name: random string followed by the day, random number and the hour
                $filename = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                // This is our upload main function, storing the image in the storage that named 'public'
                $upload_success = $image->storeAs($directory, $filename, 'public');
				$destinaton_path = public_path('images/logo/');
				$image->move($destinaton_path,$filename);
            }
		}
		else {
			$filename = "";
		}
		

        $branch_id = DB::table('banks')->insertGetId([
			'bank_name' => $request->bank_name,
			'sort' => $request->sort,
            'logo' => $request->logo,
			'created_at' => date('Y-m-d H:i:s')
		]);
        $message = Lang::get("labels.BankAddedMessage");
		return redirect()->back()->with('message', $message);
    }

	public function editbanks(Request $request)
    {
        $title = array('pageTitle' => Lang::get("labels.EditBank"));
		$myid = $request->id;
		
		$result = array();
		$message = array();
		$errorMessage = array();
		$images = new Images;
		$allimage = $images->getimages();
		//get function from other controller
		$myVar = new AddressController();
		$result['countries'] = $myVar->getAllCountries();
		$adminTypes = DB::table('user_types')->where('isActive', 1)->where('user_types_id','>','10')->get();
		
		$state = $this->States->getter();
		$result['state'] = $state;
		$city = $this->Cities->getter();
        $result['city'] = $city;

		$result['adminTypes'] = $adminTypes;
		
		$result['myid'] = $myid;

        $admins = DB::table('banks')->where('bank_id','=', $myid)->get();
		$zones = 0;

		$user_to_address = DB::table('user_to_address')->where('user_id','=', $admins[0]->bank_id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->get();
		}else{
			$address = array();
		}
		$result['address'] = $address;

		if($zones>0){
			$result['zones'] = $zones;
		}else{
			$zones = new \stdClass;
			$zones->zone_id = "others";
			$zones->zone_name = "Others";
			$result['zones'][0] = $zones;
		}
		//echo "a";exit();
		// $image_path = DB::table('image_categories')->where('image_id', $admins[0]->ssm)->where('image_type', 'ACTUAL')->get();
		//echo "a";echo "<pre>";print_r($image_path);echo "</pre>";echo $admins[0]->ssm;exit();
        $result['admins'] = $admins;
		// $result['image_path'] = $image_path;
		return view("admin.bank.edit",$title)->with('result', $result)->with('allimage', $allimage);
	}

    public function updateBanks(Request $request){
        $time = Carbon::now();
        $extensions = array('pdf');
		$currentUser = Auth()->user()->id;
		$bank_id    =   $request->myid;

		if ($request->hasFile('logo')) {
            $time = Carbon::now();
			$image = $request->file('logo');
            $extensions = Setting::imageType();
            if ($image and in_array($request->logo->extension(), $extensions)) {
				// getting size
                $size = getimagesize($image);
                list($width, $height, $type, $attr) = $size;
                // Getting the extension of the file
                $extension = $image->getClientOriginalExtension();
                // Creating the directory, for example, if the date = 18/10/2017, the directory will be 2017/10/
                $directory = date_format($time, 'Y') . '/' . date_format($time, 'm');
                // Creating the file name: random string followed by the day, random number and the hour
                $filename = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                // This is our upload main function, storing the image in the storage that named 'public'
                $upload_success = $image->storeAs($directory, $filename, 'public');
				$destinaton_path = public_path('images/logo/');
				$image->move($destinaton_path,$filename);
            }
		}
		else {
			$filename = "";
		}
		
		DB::table('banks')->where('bank_id','=', $bank_id)->update([
            'bank_name' => $request->bank_name,
			'sort' => $request->sort,
            'logo' => $request->logo,
			'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $message = Lang::get("labels.BankEditMessage");
        return redirect()->back()->with('message', $message);
    }

	public function deleteBank(Request $request){
        $myid = $request->users_id;
        DB::table('banks')->where('bank_id','=', $myid)->delete();
        return redirect()->back()->withErrors([Lang::get("labels.DeleteTemplateMessage")]);
    }

	public function filter(Request $request){
		$name = $request->FilterBy;
		$param = $request->parameter;
		$title = array('pageTitle' => Lang::get("labels.Bank"));
		$bank = $this->bank->filter($name,$param);
		return view("admin.bank.index",$title)->with('bank', $bank)->with('name',$name)->with('param',$param);
	}
}