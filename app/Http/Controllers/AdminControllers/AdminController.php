<?php
namespace App\Http\Controllers\AdminControllers;
use App\Agent;
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
use App\Models\Core\Documents;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Models\Core\Images;
use Validator;
use Hash;
use Auth;
use ZipArchive;
use File;
use Mail;
use Carbon\Carbon;

class AdminController extends Controller
{
	private $domain;
    public function __construct(Admin $admin, Setting $setting, Order $order, Customers $customers, Cities $cities, States $States, Makes $makes, Cars $cars, User $user)
    {
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

	public function dashboard(Request $request){
		$title 			  = 	array('pageTitle' => Lang::get("labels.title_dashboard"));
		$language_id      = 	'1';

		$result 		  =		array();

		$reportBase		  = 	$request->reportBase;

		//recently order placed
		$orders = DB::table('orders')
			->LeftJoin('currencies', 'currencies.code', '=', 'orders.currency')
			->where('customers_id','!=','')
			->orderBy('date_purchased','DESC')
			->get();


		$index = 0;
		$purchased_price = 0;
		$sold_cost = 0;
		foreach($orders as $orders_data){
			$orders_products = DB::table('orders_products')
				->select('final_price', DB::raw('SUM(final_price) as total_price') ,'products_id','products_quantity' )
				->where('orders_id', '=' ,$orders_data->orders_id)
				->groupBy('final_price')
				->get();


			if(count($orders_products)>0 and !empty($orders_products[0]->total_price)){
				$orders[$index]->total_price = $orders_products[0]->total_price;
			}else{
				$orders[$index]->total_price = 0;
			}


			foreach($orders_products as $orders_product){
				$sold_cost += $orders_product->total_price;
				$single_purchased_price = DB::table('inventory')->where('products_id',$orders_product->products_id)->sum('purchase_price');
				$single_stock = DB::table('inventory')->where('products_id',$orders_product->products_id)->where('stock_type','in')->sum('stock');
				if($single_stock>0){
					$single_product_purchase_price = $single_purchased_price/$single_stock;
				}else{
					$single_product_purchase_price = 0;
				}
				$purchased_price = $single_product_purchase_price*$orders_product->products_quantity;
				$purchased_price += $purchased_price;

			}

			$orders_status_history = DB::table('orders_status_history')
				->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
			->LeftJoin('orders_status_description', 'orders_status_description.orders_status_id', '=', 'orders_status.orders_status_id')
				->select('orders_status_description.orders_status_name', 'orders_status_description.orders_status_id')
				->where('orders_id', '=', $orders_data->orders_id)
		->where('orders_status_description.language_id', '=', $language_id)
		->orderby('orders_status_history.date_added', 'DESC')->limit(1)->get();

			$orders[$index]->orders_status_id = $orders_status_history[0]->orders_status_id;
			$orders[$index]->orders_status = $orders_status_history[0]->orders_status_name;

			$index++;

  		}

  		//products profit
  		if($purchased_price==0){
  			$profit = 0;
  		}else{
  			$profit = abs($purchased_price - $sold_cost);
  		}

  		$result['profit'] = number_format($profit,2);
  		$result['total_money'] = number_format($sold_cost,2);

  		$compeleted_orders = 0;
  		$pending_orders = 0;
  		foreach($orders as $orders_data){

  			if($orders_data->orders_status_id=='2')
  			{
  				$compeleted_orders++;
  			}
  			if($orders_data->orders_status_id=='1')
  			{
  				$pending_orders++;
  			}
  		}


  		$result['orders'] = $orders->chunk(10);
  		$result['pending_orders'] = $pending_orders;
  		$result['compeleted_orders'] = $compeleted_orders;
  		$result['total_orders'] = count($orders);

  		$result['inprocess'] = count($orders)-$pending_orders-$compeleted_orders;
  		//add to cart orders
  		$cart = DB::table('customers_basket')->get();

  		$result['cart'] = count($cart);

  		//Rencently added products
		  $recentProducts = DB::table('products')
			->LeftJoin('image_categories', function ($join) {
				$join->on('image_categories.image_id', '=', 'products.products_image')
					->where(function ($query) {
						$query->where('image_categories.image_type', '=', 'THUMBNAIL')
							->where('image_categories.image_type', '!=', 'THUMBNAIL')
							->orWhere('image_categories.image_type', '=', 'ACTUAL');
					});
			})
			->leftJoin('products_description','products_description.products_id','=','products.products_id')
			->select('products.*', 'products_description.*', 'image_categories.path as products_image')
			->where('products_description.language_id','=', $language_id)
  			->orderBy('products.products_id', 'DESC')
  			->paginate(8);

  		$result['recentProducts'] = $recentProducts;

  		//products
  		$products = DB::table('products')
  			->leftJoin('products_description','products_description.products_id','=','products.products_id')
  			->where('products_description.language_id','=', $language_id)
  			->orderBy('products.products_id', 'DESC')
  			->get();


  		//low products & out of stock
  		$lowLimit = 0;
  		$outOfStock = 0;
  		//$total_money = 0;
  		$products_ids = array();
  		$data = array();
  		foreach($products as $products_data){
  			//$total_money += $products_data->products_price;
  			$currentStocks = DB::table('inventory')->where('products_id',$products_data->products_id)->get();
  			//$total_money += DB::table('inventory')->where('products_id',$products_data->products_id)->sum('purchase_price');

  			if(count($currentStocks)>0){

  				if($products_data->products_type!=1){
  					$c_stock_in = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','in')->sum('stock');
  					$c_stock_out = DB::table('inventory')->where('products_id',$products_data->products_id)->where('stock_type','out')->sum('stock');

  					if(($c_stock_in-$c_stock_out)==0){
  						if(!in_array($products_data->products_id, $products_ids)){
  							$products_ids[] = $products_data->products_id;

  							array_push($data,$products_data);
  							$outOfStock++;
  						}
  					}
  				}

  			}else{
  				$outOfStock++;
  			}
  		}

  		$result['lowLimit'] = $lowLimit;
  		$result['outOfStock'] = $outOfStock;
		$result['totalProducts'] = count($products);

		$make = $this->Makes->allmake();
		$result['totalMake'] = count($make);

		$car = $this->Cars->allcar();
		$result['totalCar'] = count($car);

		$carbymerchant = $this->Cars->allcarbymerchant();
		$result['totalCarByMerchant'] = count($carbymerchant);

		$merchant = $this->User->allmerchant();
		$result['totalMerchant'] = count($merchant);

      	$customers = $this->Customers->getter();

  		$result['customers'] = $customers;//->chunk(21);
  		$result['totalCustomers'] = count($customers);
  		$result['reportBase'] = $reportBase;

  		$currency = $this->Setting->getSettings();
		$result['currency'] = $currency;

		//get dashboard view accessabilities

		$role =  DB::table('manage_role')
		->where('user_types_id',Auth()->user()->role_id)
        ->first();

        // $report_query = DB::table('cars_statistics')->leftjoin('cars','cars_statistics.car_id','cars.car_id');
        // if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
        //     $report_query->where('cars.merchant_id',Auth()->user()->id);
        // }

        // $result['popular_car'] = (Clone $report_query)->select('cars.car_id','cars.vim','cars.title', DB::raw("count('cars_statistics.car_id') AS visits"))
        //                 ->groupBy('cars_statistics.car_id')
        //                 ->orderBy('visits','desc')
        //                 ->limit(10)
        //                 ->get();
        // $result['popular_brand'] = (Clone $report_query)->leftJoin('makes','cars_statistics.make_id','makes.make_id')
        //                     ->select('makes.make_name as title', DB::raw("count('cars_statistics.car_id') AS visits"))
        //                     ->groupBy('makes.make_id')
        //                     ->orderBy('visits','desc')
        //                     ->limit(10)
        //                     ->get();
        // $result['popular_model'] = (Clone $report_query)->leftJoin('models','cars_statistics.model_id','models.model_id')
        //                             ->select('models.model_name as title', DB::raw("count('cars_statistics.car_id') AS visits"))
        //                             ->groupBy('models.model_id')
        //                             ->orderBy('visits','desc')
        //                             ->limit(10)
        //                             ->get();


  		return view("admin.dashboard",$title)->with('result', $result)->with('role', $role);
	}


	public function login(){

		if (Auth::check()) {
		  return redirect('/admin/dashboard/this_month');
		}else{
			$title = array('pageTitle' => Lang::get("labels.login_page_name"));
			return view("admin.login",$title);
		}
	}

	public function register(){
		$title = array('pageTitle' => Lang::get("labels.register_page_name"));
		return view("admin.register",$title);
	}

	public function admininfo(){
		$administor = administrators::all();
		return view("admin.login",$title);
	}

	//login function
    public function checkLogin(Request $request){


		$validator = Validator::make(
			array(
					'email'    => $request->email,
					'password' => $request->password
				),
			array(
					'email'    => 'required | email',
					'password' => 'required',
				)
		);
		//check validation
		if($validator->fails()){
			return redirect('admin/login')->withErrors($validator)->withInput();
		}else{
			//check authentication of email and password
			$adminInfo = array("email" => $request->email, "password" => $request->password);

			if(auth()->attempt($adminInfo)) {
				$admin = auth()->user();
				$roleType= \App\Models\Core\User::ROLE_MERCHANT;
				if ($admin->role_id == $roleType && !$admin->verified)
				{
					Auth::logout();
					return redirect('admin/login')->with('loginError',Lang::get("You need to confirm your account. We have sent you an activation code, please check your email."));
				}
				else
                {
                    if( $admin->role_id == \App\Models\Core\User::ROLE_CUSTOMER )
                    {
                        Auth::logout();
                        return redirect('admin/login')->with('loginError',Lang::get("labels.EmailPasswordIncorrectText"));
                    }

					$administrators = DB::table('users')->where('id', $admin->myid)->get();

					$categories_id = '';
					//admin category role
					if(auth()->user()->adminType != '1'){
					    $userId = Auth::id();
				        $update_time_count = DB::table('users')->where('id', $userId)->first();
                          DB::table('users')->where('id', $userId)->update([
                               'login_counter'=>$update_time_count->login_counter+1,
                               'last_login_at' => Carbon::now()->toDateTimeString()
                             ]);

					$categories_role = DB::table('categories_role')->where('admin_id', auth()->user()->myid)->get();
						if(!empty($categories_role) and count($categories_role)>0){
							$categories_id = $categories_role[0]->categories_ids;
						}else{
							$categories_id = '';
						}
					}
					// 	Create storage folder for logged in user
					if (!file_exists(public_path('images').'/users/'.auth()->user()->id)) {
					    File::makeDirectory(public_path('images').'/users/'.auth()->user()->id, $mode = 0755, true, true);
					}

					session(['categories_id' => $categories_id]);
					return redirect()->intended('admin/dashboard/this_month')->with('administrators', $administrators);
				}
			}else{
				return redirect('admin/login')->with('loginError',Lang::get("labels.EmailPasswordIncorrectText"));
			}

		}

	}

	//register function
    public function registerLogin(Request $request){
		$email = $request->email;
		$password = $request->password;
		$password_repeat = $request->password_repeat;
		if($password != $password_repeat)
	  	{
			return redirect('admin/register')->withInput($request->input())->with('registerError', Lang::get("Password and Password Repeat not same"));
		}
		else
		{
			$user_email = DB::table('users')->select('email')->where('email', $email)->get();
			if(count($user_email)>0)
			{
				return redirect('admin/register')->withInput($request->input())->with('registerError', Lang::get("Email already exist"));
			}
			else
            {
				$request->adminType= \App\Models\Core\User::ROLE_MERCHANT;
				if(DB::table('users')->insert([
					'user_name'		=>   $request->first_name.'_'.$request->last_name.time(),
					'first_name'	=>   $request->first_name,
					'last_name'		=>   $request->last_name,
					'phone'	 		=>	 $request->phone,
					'email'	 		=>   $request->email,
					'role_id'		=>	 $request->adminType,
					'password' 		=>   Hash::make($password),
					'status'		=>   "1",
                    'created_at' 	=> 	 date('Y-m-d H:i:s'),
					])
				)
				{
					$GetExistUser = DB::table('users')->where('role_id', $request->adminType)->where('email', $request->email)->first();
					DB::table('verify_users')->insert([
						'user_id' 		=> $GetExistUser->id,
						'token' 		=> str_random(40),
						'created_at' 	=> date('Y-m-d H:i:s')
					]);

					$get_customers = DB::table('users')->where('id', $GetExistUser->id)->first();
					$cuatomer_email = $request->email;
					$get_token = DB::table('verify_users')->where('user_id', $GetExistUser->id)->first();
					$customers = array(
						'first_name' 	=> $get_customers->first_name,
						'last_name' 	=> $get_customers->last_name,
						'email' 		=> $get_customers->email
					);
					$token = array(
						'token' 		=> $get_token->token
					);
					$data = array(
						'customers'		=> $customers,
						'token'			=> $token,
						'receiver'		=> $cuatomer_email
					);
					Mail::send('/emails/VerifyUser', ['data' => $data], function ($m) use ($data) {
						$m->to($data['receiver'])
							->subject("Verify Mail")
							->getSwiftMessage()
							->getHeaders()
							->addTextHeader('x-mailgun-native-send', 'true');
					});
					return redirect('admin/login')->with('loginSuccess', Lang::get("We sent you an activation code. Check your email and click on the link to verify."));
				}else{
					return redirect('admin/register')->with('registerError', Lang::get("Something is wrong"));
				}
			}
		}
	}

	public function verifyUser($token)
    {
		$verifyToken = DB::table('verify_users')->where('token', $token)->first();
        if(isset($verifyToken) ){
			$verifyUser = DB::table('users')->where('id', $verifyToken->user_id)->first();
            if(!$verifyUser->verified) {
				DB::table('users')->where('id', $verifyUser->id)->update([
					'verified'	=>	1
				]);
                $status = "Your e-mail is verified. You can now login.";
            }else{
                $status = "Your e-mail is already verified. You can now login.";
            }
        }else{
            return redirect('admin/login')->with('loginError', "Sorry your email cannot be identified.");
        }

		return redirect('admin/login')->with('loginSuccess', $status);
    }

	//logout
	public function logout(){
		Auth::guard('admin')->logout();
		return redirect()->intended('admin/login');
	}

	//admin profile
	public function adminProfile(Request $request){
		//check permission

		$title = array('pageTitle' => Lang::get("labels.Profile"));

		$result = array();

		$countries = DB::table('countries')->get();
		$zones = DB::table('zones')->where('zone_country_id', '=', auth()->user()->country)->get();

		$result['countries'] = $countries;
		$result['zones'] = $zones;

		return view("admin.profile",$title)->with('result', $result);

	}
  //updateProfile
	public function updateProfile(Request $request){


		$updated_at	= date('y-m-d h:i:s');

		$myVar = new SiteSettingController();
		$languages = $myVar->getLanguages();
		$extensions = $myVar->imageType();


		$uploadImage = $request->oldImage;


		$orders_status = DB::table('users')->where('id','=', Auth()->user()->id)->update([
				'user_name'		=>	$request->user_name,
				'first_name'	=>	$request->first_name,
				'last_name'		=>	$request->last_name,
				'country'		=>	$request->country,
				'phone'			=>	$request->phone,
				'avatar'		=>	$uploadImage,
				'updated_at'	=>	$updated_at
				]);

		$message = Lang::get("labels.ProfileUpdateMessage");
		return redirect()->back()->withErrors([$message]);

	}

  //updateProfile
	public function updateAdminPassword(Request $request){

		$orders_status = DB::table('users')->where('id','=', auth()->user()->myid)->update([
				'password'		=>	Hash::make($request->password)
				]);

		$message = Lang::get("labels.PasswordUpdateMessage");
		return redirect()->back()->withErrors([$message]);

	}

  //admins
	public function admins(Request $request){

		$title = array('pageTitle' => Lang::get("labels.ListingCustomers"));
		$language_id            				=   '1';

		$result = array();
		$message = array();
		$errorMessage = array();

		$admins = DB::table('users')
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=','12')
			->paginate(50);


		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		$result['admins'] = $admins;

		return view("admin.admins.index",$title)->with('result', $result);

	}

	//add admins
	public function addadmins(Request $request){

		$title = array('pageTitle' => Lang::get("labels.addadmin"));

		$result = array();
		$message = array();
		$errorMessage = array();

		//get function from ManufacturerController controller
		$myVar = new AddressController();
		$result['countries'] = $myVar->getAllCountries();

		$adminTypes = DB::table('user_types')->where('isActive', 1)->where('user_types_id','>','10')->get();
		$result['adminTypes'] = $adminTypes;

		return view("admin.admins.add",$title)->with('result', $result);

	}

  //addnewadmin
	public function addnewadmin(Request $request){

		//get function from other controller
		$myVar = new SiteSettingController();
		$extensions = $myVar->imageType();

		$result = array();
		$message = array();
		$errorMessage = array();

		//check email already exists
		$existEmail = DB::table('users')->where('email', '=', $request->email)->get();
		if(count($existEmail)>0){
			$errorMessage = Lang::get("labels.Email address already exist");
			return redirect()->back()->with('errorMessage', $errorMessage);
		}else{

			$uploadImage = '';

			$customers_id = DB::table('users')->insertGetId([
						'user_name'		 		    =>   $request->first_name.'_'.$request->last_name.time(),
						'first_name'		 		=>   $request->first_name,
						'last_name'			 		=>   $request->last_name,
						'phone'	 					=>	 $request->phone,
						'email'	 					=>   $request->email,
						'password'		 			=>   Hash::make($request->password),
						'status'		 	 		=>   $request->isActive,
						'avatar'	 				=>	 $uploadImage,
						'role_id'					=>	 $request->adminType
						]);


			$message = Lang::get("labels.New admin has been added successfully");
			return redirect()->back()->with('message', $message);

		}
	}
  //editadmin
	public function editadmin(Request $request){

		$title = array('pageTitle' => Lang::get("labels.EditAdmin"));
		$myid        	 =   $request->id;

		$result = array();
		$message = array();
		$errorMessage = array();

		//get function from other controller
		$myVar = new AddressController();
		$result['countries'] = $myVar->getAllCountries();

		$adminTypes = DB::table('user_types')->where('isActive', 1)->where('user_types_id','>','10')->get();

		$result['adminTypes'] = $adminTypes;

		$result['myid'] = $myid;

		$admins = DB::table('users')->where('id','=', $myid)->get();
		$zones = 0;

		if($zones>0){
			$result['zones'] = $zones;
		}else{
			$zones = new \stdClass;
			$zones->zone_id = "others";
			$zones->zone_name = "Others";
			$result['zones'][0] = $zones;
		}


		$result['admins'] = $admins;

		return view("admin.admins.edit",$title)->with('result', $result);
	}

  //update admin
	public function updateadmin(Request $request){

		//get function from other controller
		$myVar = new SiteSettingController();
		$extensions = $myVar->imageType();
		$myid = $request->myid;
		$result = array();
		$message = array();
		$errorMessage = array();

		//check email already exists
		$existEmail = DB::table('users')->where([['email','=',$request->email],['id','!=',$myid]])->get();
		if(count($existEmail)>0){
			$errorMessage = Lang::get("labels.Email address already exist");
			return redirect()->back()->with('errorMessage', $errorMessage);
		}else{

			$uploadImage = '';

			$admin_data = array(
				'first_name'		 		=>   $request->first_name,
				'last_name'			 		=>   $request->last_name,
				'phone'	 					=>	 $request->phone,
				'email'	 					=>   $request->email,
				'status'		 	 		=>   $request->isActive,
				'avatar'	 				=>	 $uploadImage,
				'role_id'	 				=>	 $request->adminType,
			);

			if($request->changePassword == 'yes'){
				$admin_data['password'] = Hash::make($request->password);
			}

			$customers_id = DB::table('users')->where('id', '=', $myid)->update($admin_data);


			$message = Lang::get("labels.Admin has been updated successfully");
			return redirect()->back()->with('message', $message);
		}

	}

	public function merchants(Request $request){
		$title = array('pageTitle' => Lang::get("labels.ListingMerchants"));
		$language_id = '1';

		$result = array();
		$message = array();
		$errorMessage = array();

		// get organisation from segment setting
		$segment_id = $request->segment_id;
		if( !empty($segment_id) ){
			$admins = User::sortable(['id'=>'DESC'])
				->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
				->select('users.*','user_types.*')
				->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
				->where('users.segment_id','=',$segment_id);
		}
		else{
			$admins = User::sortable(['id'=>'DESC'])
				->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
				->select('users.*','user_types.*')
				->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT);
		}
   
		// filter
        if (!empty($request->get('name'))) {
            // $admins = $admins->where(DB::raw("concat(users.first_name, ' ', users.last_name)"), 'LIKE', '%'.$request->name.'%');
			$admins = $admins->where('users.company_name', 'LIKE', '%' . $request->name . '%');
        }
		if (!empty($request->get('ID'))) {
            $admins = $admins->where('users.id', 'LIKE', $request->ID );
        }
		if (!empty($request->get('brn_no'))) {
            $admins = $admins->where('users.brn_no', 'LIKE', '%' . $request->brn_no . '%');
        }
		if (!empty($request->get('roc_no'))) {
            $admins = $admins->where('users.roc_scm_no', 'LIKE', '%' . $request->roc_no . '%');
        }
		if (!empty($request->get('segment_type'))) {
            $admins = $admins->where('users.segment_id', 'LIKE', '%' . $request->segment_type . '%');
        }
		$admins = $admins->paginate(50);

		$segments = Segments::all();
		$templates = Template::all();
        $merchant = $this->Cars->merchant();
       

      
		$result['merchant'] = $merchant;
		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		$result['admins'] = $admins;
		$result['segments'] = $segments;
		$result['templates'] = $templates;
	
		return view("admin.merchants.index",$title)->with('result', $result);

	}

	//add merchants
	public function addmerchants(Request $request){

		$title = array('pageTitle' => Lang::get("labels.addmerchant"));

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

		$segments = Segments::all();
		$result['segments'] = $segments;

		$banks = Banks::all();
		$result['banks'] = $banks;

		$templates = Template::all();
		$result['templates'] = $templates;

		$parent_org = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['parent_org'] = $parent_org;

		return view("admin.merchants.add",$title)->with('result', $result)->with('allimage',$allimage);

	}

	//addnewmerchant
	public function addnewmerchant(Request $request){
		// dd($request->all());
		//get function from other controller
		$myVar = new SiteSettingController();
		$extensions = $myVar->imageType();

		$result = array();
		$message = array();
		$errorMessage = array();

		//check email already exists
		$existEmail = DB::table('users')->where('email', '=', $request->email)->get();
		if(count($existEmail)>0){
			$errorMessage = Lang::get("labels.Email address already exist");
			return redirect()->back()->with('errorMessage', $errorMessage);
		}else{

			if ($request->hasFile('banner')) {
				$time = Carbon::now();
				$image = $request->file('banner');
				if ($image) {
					$extension = $image->getClientOriginalExtension();
					$filename = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
					$destinaton_path = public_path('images/banner/');
					$image->move($destinaton_path,$filename);
				}
			}
			else{
				$filename = "";
			}

			if ($request->hasFile('logo')) {
				$time = Carbon::now();
				$image2 = $request->file('logo');
				if ($image2) {
					$extension2 = $image2->getClientOriginalExtension();
					$filename2 = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension2;
					$destinaton_path2 = public_path('images/logo/');
					$image2->move($destinaton_path2,$filename2);
				}
			}
			else{
				$filename2 = "";
			}

			$uploadImage = '';
			//default_merchant_id
			$request->adminType= \App\Models\Core\User::ROLE_MERCHANT;
			//echo "<pre>";print_r($request);echo "</pre>";exit();
			$customers_id = DB::table('users')->insertGetId([
				'user_name'		 		    =>   $request->first_name.'_'.$request->last_name.time(),
				'first_name'		 		=>   $request->first_name,
				'last_name'			 		=>   $request->last_name,
				'phone'	 					=>	 $request->phone,
				'email'	 					=>   $request->email,
				'password'		 			=>   Hash::make($request->password),
				'status'		 	 		=>   $request->isActive,
				'avatar'	 				=>	 $uploadImage,
				'role_id'					=>	 $request->adminType,
				'company_name'	 			=>	 $request->company_name,
                'verified'					=>	 1,
                'banner_id'                 =>   $request->banner_id ? $request->banner_id : $request->old_banner_id,
                'banner_color'              =>   $request->banner_color,
                'logo_id'                   =>   $request->logo_id ? $request->logo_id : $request->old_logo_id,
                'title_color'               =>   $request->title_color,
                'roc_scm_no'                =>   $request->roc_scm_no,
				'segment_id'                =>   $request->segment_type,
				'template_id'               =>   $request->template_type,
				'corporate_email'           =>   $request->corporate_email,
				'corporate_phone'           =>   $request->corporate_phone,
				'brn_no'                    =>   $request->brn_no,
				'bank_id1'                  =>   $request->bank1,
				'bank_acc_name1'            =>   $request->bank_acc_name1,
				'bank_acc_no1'              =>   $request->bank_acc_no1,
				'bank_id2'                  =>   $request->bank2,
				'bank_acc_name2'            =>   $request->bank_acc_name2,
				'bank_acc_no2'              =>   $request->bank_acc_no2,
                'description'               =>   $request->description,
                'opening_hours'             =>   $request->opening_hours,
                'report_view'               =>   $request->report_view,
				//'merchant_name'		 	 	=>   $request->merchant_name,
				//'merchant_phone_no'		 	=>   $request->merchant_phone_no,
				//'state_id'		 	 		=>   $request->state_id,
				//'city_id'		 	 		=>   $request->city_id,
				'banner'                	=>   $request->banner,
				'logo'                		=>   $request->logo,
				'pricelist'                	=>   $request->pricelist,
				'promotion'                	=>   $request->promotion,
				'brochure'                	=>   $request->brochure,
				'pricelist_logo'            =>   $request->pricelist_logo,
				'promotion_logo'            =>   $request->promotion_logo,
				'brochure_logo'             =>   $request->brochure_logo,
				'extra_pricelist_logo'      =>   $request->extra_pricelist_logo,
				'extra_brochure_logo'      =>    $request->extra_brochure_logo,
				'extra_pricelist_label'     =>   $request->extra_pricelist_label,
				'extra_brochure_label'      =>   $request->extra_brochure_label,
				'parent_id'                	=>   $request->parent_id,
				'created_at'				=>   date('Y-m-d H:i:s')
			]);

			if($request->address){
				if(empty($request->zip)){
					$request->zip = "";
				}
				if(empty($request->city)){
					$request->city = "";
				}
				if(empty($request->country)){
					$request->country = 0;
				}
				if(empty($request->state)){
					$request->state = NULL;
				}
				//insert address book
				if(empty($request->latitude)){
					$request->latitude = NULL;
				}
				if(empty($request->longitude)){
					$request->longitude = NULL;
				}
				$address_book = DB::table('address_book')->insertGetId([
					'user_id'		 			=>   $customers_id,
					'entry_street_address'		=>   $request->address,
					'entry_postcode'		 	=>   $request->zip,
					'entry_city'			 	=>   $request->city,
					'entry_country_id'	 		=>	 $request->country,
					'entry_state'	 			=>   $request->state,
					'entry_latitude'	 		=>	 $request->latitude,
					'entry_longitude'	 		=>   $request->longitude
				]);

				$user_to_address = DB::table('user_to_address')->insertGetId([
					'user_id'		 			=>   $customers_id,
					'address_book_id'		 	=>   $address_book
				]);
			}

			$message = 'Organisation has been created successfully!';
			return  redirect('admin/merchants')->with('message', $message);
			// return redirect('admin/saleAdvisor/' . $customers_id)->with('update', 'Organisation has been created successfully!');
			//$message = Lang::get("labels.New merchant has been added successfully");
			//return redirect()->back()->with('message', $message);

		}
	}

	//editmerchant
	public function editmerchant(Request $request)
    {
        //role merchant can not edit other merchant info
        if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT &&  $request->id != Auth()->user()->id )
        {
            return  redirect('not_allowed');
        }

		$title = array('pageTitle' => Lang::get("labels.EditMerchant"));
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

        $admins = DB::table('users')->where('id','=', $myid)->get();
		$zones = 0;

		$user_to_address = DB::table('user_to_address')->where('user_id','=', $admins[0]->id)->get();
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
		//$image_path = DB::table('image_categories')->where('image_id', $admins[0]->ssm)->where('image_type', 'ACTUAL')->get();
		//echo "a";echo "<pre>";print_r($image_path);echo "</pre>";echo $admins[0]->ssm;exit();
        $result['admins'] = $admins;
		//$result['image_path'] = $image_path;

		$segments = Segments::all();
		$result['segments'] = $segments;
		$banks = Banks::all();
		$result['banks'] = $banks;
		$templates = Template::all();
		$result['templates'] = $templates;
		$documents = Documents::where('user_id','=',$myid)->get();
		$result['documents'] = $documents;

		$parent_org = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['parent_org'] = $parent_org;

		return view("admin.merchants.edit",$title)->with('result', $result)->with('allimage', $allimage);
	}

	//update merchant
	public function updatemerchant(Request $request){
		// dd($request->all());
        //get function from other controller
		$myVar = new SiteSettingController();
		$extensions = $myVar->imageType();
		$myid = $request->myid;
		$result = array();
		$message = array();
		$errorMessage = array();
		if($request->image_avatar!==null){
			$members_picture = $request->image_avatar;
		}	else{
			$members_picture = $request->oldImage;
		}

		//check email already exists
		$existEmail = DB::table('users')->where([['email','=',$request->email],['id','!=',$myid]])->get();
		if(count($existEmail)>0){
			$errorMessage = Lang::get("labels.Email address already exist");
			return redirect()->back()->with('errorMessage', $errorMessage);
		}else{

			if ($request->hasFile('banner')) {
				$time = Carbon::now();
				$image = $request->file('banner');
				if ($image) {
					$extension = $image->getClientOriginalExtension();
					$filename = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
					$destinaton_path = public_path('images/banner/');
					$image->move($destinaton_path,$filename);
				}
			}
			else{
				$filename = "";
			}

			$uploadImage = '';
			//default_merchant_id
            $request->adminType=\App\Models\Core\User::ROLE_MERCHANT;
			$admin_data = array(
				'first_name'		 		=>   $request->first_name,
				'last_name'			 		=>   $request->last_name,
				'phone'	 					=>	 $request->phone,
				'email'	 					=>   $request->email,
				'status'		 	 		=>   $request->isActive,
				'avatar'	 				=>	 $uploadImage,
				//'ssm'	 					=>   $request->image_avatar,
				'company_name'	 			=>	 $request->company_name,
				//'company_email'	 			=>	 $request->company_email,
				//'company_phone_no'	 		=>	 $request->company_phone_no,
				//'registration_no'	 		=>	 $request->registration_no,
				//'merchant_name'		 	 	=>   $request->merchant_name,
				//'merchant_phone_no'		 	=>   $request->merchant_phone_no,
				//'state_id'		 	 		=>   $request->state_id,
                //'city_id'		 	 		=>   $request->city_id,
                'banner_id'                 =>   $request->banner_id ? $request->banner_id : $request->old_banner_id,
                'banner_color'              =>   $request->banner_color,
                'logo_id'                   =>   $request->logo_id ? $request->logo_id : $request->old_logo_id,
                'title_color'               =>   $request->title_color,
                'roc_scm_no'                =>   $request->roc_scm_no,
				'segment_id'                =>   $request->segment_type,
				'template_id'               =>   $request->template_type,
				'corporate_email'           =>   $request->corporate_email,
				'corporate_phone'           =>   $request->corporate_phone,
				'brn_no'                    =>   $request->brn_no,
				'bank_id1'                  =>   $request->bank1,
				'bank_acc_name1'            =>   $request->bank_acc_name1,
				'bank_acc_no1'              =>   $request->bank_acc_no1,
				'bank_id2'                  =>   $request->bank2,
				'bank_acc_name2'            =>   $request->bank_acc_name2,
				'bank_acc_no2'              =>   $request->bank_acc_no2,
                'description'               =>   $request->description,
                'opening_hours'             =>   $request->opening_hours,
                'report_view'               =>   $request->report_view,
				'role_id'	 				=>	 $request->adminType,
				'verified'					=>	 1,
				'banner'                	=>   $request->banner,
				'logo'                		=>   $request->logo,
				'pricelist'                	=>   $request->pricelist,
				'promotion'                	=>   $request->promotion,
				'brochure'                	=>   $request->brochure,
				'pricelist_logo'            =>   $request->pricelist_logo,
				'promotion_logo'            =>   $request->promotion_logo,
				'brochure_logo'             =>   $request->brochure_logo,
				'extra_pricelist_logo'      =>   $request->extra_pricelist_logo,
				'extra_brochure_logo'      	=>   $request->extra_brochure_logo,
				'extra_pricelist_label'     =>   $request->extra_pricelist_label,
				'extra_brochure_label'      =>   $request->extra_brochure_label,
				'parent_id'                	=>   $request->parent_id,
				'updated_at'				=>   date('Y-m-d H:i:s'),
			);

			if($request->changePassword == 'yes'){
				$admin_data['password'] = Hash::make($request->password);
			}

			$customers_id = DB::table('users')->where('id', '=', $myid)->update($admin_data);

			//if($request->address){
			$checkUserAddress = DB::table('user_to_address')->where('user_id', '=', $myid)->get();
			if(empty($request->latitude)){
				$request->latitude = NULL;
			}
			if(empty($request->longitude)){
				$request->longitude = NULL;
			}
			if(count($checkUserAddress)>0){
				$address_data = array(
					'entry_street_address'		=>   $request->address,
					'entry_postcode'		 	=>   $request->zip,
					'entry_city'			 	=>   $request->city,
					'entry_country_id'	 		=>	 $request->country,
					'entry_state'	 			=>   $request->state,
					'entry_latitude'	 		=>	 $request->latitude,
					'entry_longitude'	 		=>   $request->longitude
				);
				$address_id = DB::table('address_book')->where('address_book_id', '=', $checkUserAddress[0]->address_book_id)->update($address_data);
			}else{
				if($request->address){
					if(empty($request->zip)){
						$request->zip = "";
					}
					if(empty($request->city)){
						$request->city = "";
					}
					if(empty($request->country)){
						$request->country = 0;
					}
					if(empty($request->state)){
						$request->state = NULL;
					}
					//insert address book
					$address_book = DB::table('address_book')->insertGetId([
						'entry_street_address'		=>   $request->address,
						'entry_postcode'		 	=>   $request->zip,
						'entry_city'			 	=>   $request->city,
						'entry_country_id'	 		=>	 $request->country,
						'entry_state'	 			=>   $request->state
					]);

					$user_to_address = DB::table('user_to_address')->insertGetId([
						'user_id'		 			=>   $myid,
						'address_book_id'		 	=>   $address_book
					]);
				}
			}

			// update sa link sync with org name when org got updated
			$list_sa = DB::table('merchant_branch')->where('user_id','=',$myid)->get();
			$organisation_name = $request->company_name;
			$organisation_slug_name = str_replace(' ','-',$organisation_name);
			$base_url = url('');
			if(count($list_sa) > 0){
				foreach($list_sa as $each_sa){
					$sa_profile_url = $base_url."/sale-advisor/".$organisation_slug_name."/".$each_sa->slug;
					DB::table('merchant_branch')->where('id','=', $each_sa->id)->update([
						'sa_profile_url' => $sa_profile_url,
						'updated_at'=> date('Y-m-d H:i:s'),
					]);
				}
			}

			// update waze url for mechant branch / sale advisor
			$user_to_address = DB::table('user_to_address')->where('user_id','=', $myid)->get();
			if(count($user_to_address)>0){
				$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
				if( !empty($address->entry_latitude) && !empty($address->entry_longitude) ){
					$waze_url = "https://waze.com/ul?ll=".$address->entry_latitude.",".$address->entry_longitude;
				}
				else{
					$entry_street_address = str_replace(' ','%20',$address->entry_street_address);
					$entry_city = str_replace(' ','%20',$address->entry_city);
					$entry_state = str_replace(' ','%20',$address->entry_state);
					$waze_url = "https://waze.com/ul?q=".$entry_street_address."%20".$address->entry_postcode."%20".$entry_city."%20".$entry_state;
				}
			}
			else{
				$waze_url = "";
			}

			DB::table('merchant_branch')->where('user_id','=', $myid)->update([
				'waze_url' => $waze_url,
				'updated_at'=> date('Y-m-d H:i:s'),
			]);

			// document manager
			$time = Carbon::now();
			$insert_data = array();
			$document_name = $request->document_name;
			$document_attachment = $request->document_attachment;
			$doc_logos = $request->doc_logo;
			$is_askme = $request->is_askme;
			$link = $request->link;
			$sort_order = $request->sort_order;
			for($count = 0; $count < count($document_name); $count++)
			{
				if(isset($document_name[$count])){
					if(isset($document_attachment[$count])){
						$doc_name_save = $document_attachment[$count];
					}
					else{
						$doc_name_save = "";
					}

					if(isset($doc_logos[$count])){
						$doc_logo_name_save = $doc_logos[$count];
					}
					else{
						$doc_logo_name_save = "";
					}
	
					if(isset($link[$count])){
						$link_store = $link[$count];
					}
					else{
						$link_store = "";
					}
	
					if(isset($is_askme[$count])){
						$is_askme_store = $is_askme[$count];
					}
					else{
						$is_askme_store = "";
					}

					if(isset($sort_order[$count])){
						$sort_order_store = $sort_order[$count];
					}
					else{
						$sort_order_store = "";
					}
	
					$data = array(
						'user_id'     => $request->myid,
						'name'        => $document_name[$count],
						'link' 		  => $link_store,
						'attachment'  => $doc_name_save,
						'logo'		  => $doc_logo_name_save,
						'size'		  => '0',
						'type'		  => '0',
						'is_askme'	  => $is_askme_store,
						'sort_order'  => $sort_order_store,
						'created_at'  => date('Y-m-d H:i:s')
					);
					$insert_data[] = $data; 
				}
			}
			if(!empty($insert_data)){
				Documents::insert($insert_data);
			}
			else{
				
			}

			$message = Lang::get("labels.Merchant has been updated successfully");
			return redirect()->back()->with('message', $message);
		}

	}

	//delete merchant
	public function deletemerchant(Request $request){
		$myid = $request->users_id;
		DB::table('users')->where('id','=', $myid)->delete();
		$checkUserAddress = DB::table('user_to_address')->where('user_id', '=', $myid)->get();
		if(count($checkUserAddress)>0){
			DB::table('address_book')->where('address_book_id','=', $checkUserAddress[0]->address_book_id)->delete();
			DB::table('user_to_address')->where('id','=', $checkUserAddress[0]->id)->delete();
		}
		return redirect()->back()->withErrors([Lang::get("labels.DeleteMerchantMessage")]);
	}

	//delete document
	public function deleteDocument(Request $request){
		// dd($request->all());
		$myid = $request->branch_id;
		DB::table('documents_manager')->where('id','=', $myid)->delete();
		
		return redirect()->back()->withErrors([Lang::get("labels.DeleteDocumentMessage")]);
	}

	//delete file
	public function deleteFile(Request $request){
		// dd($request->all());
		if($request->org_id && $request->org_id){
			$myid = $request->org_id;
			$myfield = $request->field_name;
			DB::table('users')->where('id','=', $myid)->update([$myfield=>'']);

			return redirect()->back()->withErrors([Lang::get("labels.DeleteDocumentMessage")]);
		}
		else{
			return redirect()->back()->withErrors([Lang::get("Error.")]);
		}
	}

	// edit file
	public function updateDocManager(Request $request)
    {
		// dd($request->all());
		$time = Carbon::now();
		if($request->doc_id){
			$myId = $request->doc_id;

			DB::table('documents_manager')
				->where('id','=', $myId)
				->update([
					'name' => $request->doc_name,
					'attachment' => $request->doc_attach,
					'size' => '',
					'type' => '',
					'logo' => $request->logo_attach,
					'link' => $request->doc_link,
					'is_askme' => $request->selectEditDoc,
					'sort_order' => $request->doc_sort_order,
			]);
			
			$message = Lang::get("Document have been updated.");
			return redirect()->back()->with('message', $message);
		}
		else{
			$message = Lang::get("Failed to update the document.");
			return redirect()->back()->with('message', $message);
		}
    	
    }

    public function copyMerchantCars(Request $request){
        $branch_id   = $request->branch_id;
        $merchant_id = $request->user_id;
        $branch_cars = Cars::where('merchant_id',$branch_id)->pluck('vim')->toArray();
        $merchant_cars = Cars::where('user_id','=', $merchant_id)
                        ->whereNotIn('vim',$branch_cars)
                        ->groupby('vim')
                        ->get();
        foreach($merchant_cars as $merchant_car) {
            $car = $merchant_car->replicate();
            $car->merchant_id = $branch_id;
            $car->save();
        }
		return redirect()->back()->withErrors([Lang::get("labels.Branch Car Sync Text")]);
    }

    public function copyBranchCars(Request $request){
        $branch_id   = $request->branch_id;
        $other_branch_id = $request->user_id;
        $branch_cars = Cars::where('merchant_id',$branch_id)->pluck('vim')->toArray();
        $merchant_cars = Cars::where('merchant_id','=', $other_branch_id)
                        ->whereNotIn('vim',$branch_cars)
                        ->groupby('vim')
                        ->get();
        foreach($merchant_cars as $merchant_car) {
            $car = $merchant_car->replicate();
            $car->merchant_id = $branch_id;
            $car->save();
        }
		return redirect()->back()->withErrors([Lang::get("labels.Branch Car Sync Text")]);
    }

	public function displaybranch(Request $request)
    {
        //if role is merchant then only can edit himself branch info
        if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT && Auth()->user()->id != $request->id)
        {
            return  redirect('not_allowed');
        }

        $title = array('pageTitle' => Lang::get("labels.AddBranch"));

        //$language_id   				=   $request->language_id;
        $id            				=   $request->id;

        $merchantData = array();
        $message = array();
        $errorMessage = array();

		//$customer_addresses = $this->Customers->addresses($id);
		if($id > 0){
			$branch = DB::table('merchant_branch')
            ->leftjoin('states', 'states.state_id','=','merchant_branch.state_id')
            ->leftJoin('cities', 'cities.city_id', '=', 'merchant_branch.city_id')
            //->leftJoin('countries', 'countries.countries_id', '=', 'address_book.entry_country_id')
            ->where('merchant_branch.user_id', '=', $id)->get();
		}
		else{
			$branch = DB::table('merchant_branch')
            ->leftjoin('states', 'states.state_id','=','merchant_branch.state_id')
            ->leftJoin('cities', 'cities.city_id', '=', 'merchant_branch.city_id')
            //->leftJoin('countries', 'countries.countries_id', '=', 'address_book.entry_country_id')
            ->get();
		}

        //return $addresses;
        //$countries = $this->Customers->country();
		$state = $this->States->getter();
		$merchantData['state'] = $state;
		$city = $this->Cities->getter();
        $merchantData['city'] = $city;
        $merchantData['message'] = $message;
        $merchantData['errorMessage'] = $errorMessage;
        $merchantData['merchant_branch'] = $branch;
        //$merchantData['countries'] = $countries;
        $merchantData['user_id'] = $id;

		$admins = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$merchantData['admins'] = $admins;

		$segments = Segments::all();
		$merchantData['segments'] = $segments;


        return view("admin.merchants.branch.index",$title)->with('data', $merchantData);
	}

	public function editbranch(Request $request){

		$user_id           =   $request->user_id;
		$branch_id         =   $request->branch_id;

		//$customer_addresses = $this->Customers->addressBook($branch_id);
		$branch = DB::table('merchant_branch')
            //->leftJoin('zones', 'zones.zone_id', '=', 'address_book.entry_zone_id')
            //->leftJoin('countries', 'countries.countries_id', '=', 'address_book.entry_country_id')
            ->where('id', '=', $branch_id)->get();
		//$countries = $this->Customers->countries();;
		//$zones = $this->Customers->zones($customer_addresses);
		//$customers = $this->Customers->checkdefualtaddress($branch_id);

		$merchantData['user_id'] = $user_id;
		$merchantData['merchant_branch'] = $branch;
		$state = $this->States->getter();
		$merchantData['state'] = $state;
		$city = $this->Cities->getter();
        $merchantData['city'] = $city;
		//$merchantData['countries'] = $countries;
		//$merchantData['zones'] = $zones;
		//$merchantData['customers'] = $customers;

		return view("admin/merchants/branch/editbranch")->with('data', $merchantData);
	}

	public function deleteBranch(Request $request){
		//$customer_addresses = $this->Customers->deleteAddresses($request);
		$branch_id    =   $request->branch_id;
        DB::table('merchant_branch')->where('id','=', $branch_id)->delete();
        //DB::table('user_to_address')->where('address_book_id','=', $address_book_id)->delete();
		return redirect()->back()->withErrors([Lang::get("labels.DeleteSaleAdvisorSuccess")]);
    }

	// add merchant branch/sale advisor page
	public function addsaleadvisor(Request $request){
		$title = array('pageTitle' => Lang::get("labels.AddSaleAdvisor"));

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

		return view("admin.merchants.branch.add",$title)->with('result', $result)->with('allimage',$allimage);
	}

	// edit merchant branch/sale advisor page
	public function editsaleadvisor(Request $request){
		$title = array('pageTitle' => Lang::get("labels.EditSaleAdvisor"));

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

		return view("admin.merchants.branch.edit",$title)->with('result', $result)->with('allimage',$allimage);
	}


	//add Merchant branch
    public function addmerchantbranch(Request $request){
		//$customer_addresses = $this->Customers->addcustomeraddress($request);
		$branch_id = DB::table('merchant_branch')->insertGetId([
			'user_id'   		    =>   $request->user_id,
            'merchant_name'			=>   $request->merchant_name,
            'slug'			        =>   Str::slug($request->merchant_name,"-"),
			'merchant_email'		=>   $request->merchant_email,
            'merchant_phone_no'		=>   $request->merchant_phone_no,
            'merchant_payment'      =>   $request->merchant_payment,
			'state_id'				=>   $request->state_id,
			'city_id'				=>   $request->city_id,
			'is_default'		 	=>   $request->is_default,
			'created_at'			=>   date('Y-m-d H:i:s')
		]);
		return $branch_id;
	}

	//update Merchants branch
    public function updatebranch(Request $request){
		//$customer_addresses = $this->Customers->updateaddress($request);
		$branch_id    =   $request->branch_id;
		DB::table('merchant_branch')->where('id','=', $branch_id)->update([
            'user_id'   		    =>   $request->user_id,
            'merchant_name'			=>   $request->merchant_name,
            'slug'			        =>   $request->slug,
			'merchant_email'		=>   $request->merchant_email,
            'merchant_phone_no'		=>   $request->merchant_phone_no,
            'merchant_payment'      =>   $request->merchant_payment,
			'state_id'				=>   $request->state_id,
			'city_id'				=>   $request->city_id,
			'is_default'		 	=>   $request->is_default,
			'updated_at'			=>   date('Y-m-d H:i:s'),
        ]);
		//return ($customer_addresses);
		return 'success';
	}

   	public function profile(Request $request){

        $title = array('pageTitle' => Lang::get("labels.Profile"));
        $result = array();
        $images = new Images;
        $allimage = $images->getimages();
        $result['admin'] = $this->Admin->edit(auth()->user()->id);
        $countries = DB::table('countries')->get();
        $zones = DB::table('zones')->where('zone_country_id', '=', $result['admin']->entry_country_id)->get();
        $result['countries'] = $countries;
        $result['zones'] = $zones;
        return view("admin.admin.profile",$title)->with('result', $result)->with('allimage', $allimage);
    }

    public function update(Request $request)
    {
        $validator = Validator::make(
            array(
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'phone' => $request->phone,
                'city' => $request->city,
                'country' => $request->country,
                'zip' => $request->zip
            ),
            array(
                'first_name' => 'required',
                'last_name' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'city' => 'required',
                'country' => 'required',
                'zip' => 'required'
            )
        );
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $update = $this->Admin->updaterecord($request);
        $message = Lang::get("labels.ProfileUpdateMessage");
        return redirect()->back()->with(['success' => $message]);
    }

    public function updatepassword(Request $request)
    {
        $update = $this->Admin->updatepassword($request);
        $message = Lang::get("labels.PasswordUpdateMessage");
        return redirect()->back()->withErrors([$message]);
    }

    //deleteProduct
    public function deleteadmin(Request $request)
    {

        $myid = $request->myid;

        DB::table('users')->where('id','=', $myid)->delete();

        return redirect()->back()->withErrors([Lang::get("labels.DeleteAdminMessage")]);

    }

	//manageroles
	public function manageroles(Request $request)
    {


		$title = array('pageTitle' => Lang::get("labels.manageroles"));
		$language_id            				=   '1';

		$result = array();
		$message = array();
		$errorMessage = array();

		$adminTypes = DB::table('user_types')->where('user_types_id','>','10')->paginate(50);

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		$result['adminTypes'] = $adminTypes;

		return view("admin.admins.roles.manageroles",$title)->with('result', $result);

	}


	//add admins type
	public function addadmintype(Request $request){
		$title = array('pageTitle' => Lang::get("labels.addadmintype"));

		$result = array();
		$message = array();
		$errorMessage = array();

		//get function from ManufacturerController controller
		$myVar = new AddressController();
		$result['countries'] = $myVar->getAllCountries();

		$adminTypes = DB::table('user_types')->where('isActive', 1)->get();
		$result['adminTypes'] = $adminTypes;

		return view("admin.admins.roles.addadmintype",$title)->with('result', $result);
	}

	//addnewtype
	public function addnewtype(Request $request){

		$result = array();
		$message = array();
		$errorMessage = array();

		$customers_id = DB::table('user_types')->insertGetId([
						'user_types_name'	 		=>   $request->user_types_name,
						'created_at'			 	=>   time(),
						'isActive'		 	 		=>   $request->isActive,
						]);

		$message = Lang::get("labels.Admin type has been added successfully");
		return redirect()->back()->with('message', $message);

	}


	//editadmintype
	public function editadmintype(Request $request){
		$title = array('pageTitle' => Lang::get("labels.EditAdminType"));
		$user_types_id        	 =   $request->id;

		$result = array();

		$result['user_types_id'] = $user_types_id;

		$user_types = DB::table('user_types')->where('user_types_id','=', $user_types_id)->get();

		$result['user_types'] = $user_types;
		return view("admin.admins.roles.editadmintype",$title)->with('result', $result);
	}

	//updatetype
	public function updatetype(Request $request){

		$result = array();
		$message = array();
		$errorMessage = array();

		$customers_id = DB::table('user_types')->where('user_types_id',$request->user_types_id)->update([
						'user_types_name'	 		=>   $request->user_types_name,
						'updated_at'			 	=>   time(),
						'isActive'		 	 		=>   $request->isActive,
						]);


		$message = Lang::get("labels.Admin type has been updated successfully");
		return redirect()->back()->with('message', $message);

	}


	//deleteProduct
	public function deleteadmintype(Request $request){

		$user_types_id = $request->user_types_id;

		DB::table('user_types')->where('user_types_id','=', $user_types_id)->delete();

		return redirect()->back()->withErrors([Lang::get("labels.DeleteAdminTypeMessage")]);

	}

	//managerole
	public function addrole(Request $request){


		$title = array('pageTitle' => Lang::get("labels.EditAdminType"));
		$result = array();
		$user_types_id = $request->id;
		$result['user_types_id'] = $user_types_id;

		$adminType = DB::table('user_types')->where('user_types_id',$user_types_id)->get();
		$result['adminType'] = $adminType;

		$roles = DB::table('manage_role')->where('user_types_id','=', $user_types_id)->get();

		if(count($roles)>0){
			$dashboard_view = $roles[0]->dashboard_view;

			$manufacturer_view = $roles[0]->manufacturer_view;
			$manufacturer_create = $roles[0]->manufacturer_create;
			$manufacturer_update = $roles[0]->manufacturer_update;
			$manufacturer_delete = $roles[0]->manufacturer_delete;

			$categories_view   = $roles[0]->categories_view;
			$categories_create = $roles[0]->categories_create;
			$categories_update = $roles[0]->categories_update;
			$categories_delete = $roles[0]->categories_delete;

			$products_view = $roles[0]->products_view;
			$products_create = $roles[0]->products_create;
			$products_update = $roles[0]->products_update;
			$products_delete = $roles[0]->products_delete;

			$news_view   = $roles[0]->news_view;
			$news_create = $roles[0]->news_create;
			$news_update = $roles[0]->news_update;
			$news_delete = $roles[0]->news_delete;

			$media_view   = $roles[0]->view_media;
			$media_create = $roles[0]->add_media;
			$media_update = $roles[0]->edit_media;
			$media_delete = $roles[0]->delete_media;

			$customers_view = $roles[0]->customers_view;
			$customers_create = $roles[0]->customers_create;
			$customers_update = $roles[0]->customers_update;
			$customers_delete = $roles[0]->customers_delete;

			$tax_location_view = $roles[0]->tax_location_view;
			$tax_location_create = $roles[0]->tax_location_create;
			$tax_location_update = $roles[0]->tax_location_update;
			$tax_location_delete = $roles[0]->tax_location_delete;

			$coupons_view = $roles[0]->coupons_view;
			$coupons_create = $roles[0]->coupons_create;
			$coupons_update = $roles[0]->coupons_update;
			$coupons_delete = $roles[0]->coupons_delete;

			$notifications_view = $roles[0]->notifications_view;
			$notifications_send = $roles[0]->notifications_send;

			$orders_view = $roles[0]->orders_view;
			$orders_confirm = $roles[0]->orders_confirm;

			$shipping_methods_view = $roles[0]->shipping_methods_view;
			$shipping_methods_update = $roles[0]->shipping_methods_update;

			$payment_methods_view = $roles[0]->payment_methods_view;
			$payment_methods_update = $roles[0]->payment_methods_update;

			$reports_view = $roles[0]->reports_view;

			$website_setting_view = $roles[0]->website_setting_view;
			$website_setting_update = $roles[0]->website_setting_update;

			$application_setting_view = $roles[0]->application_setting_view;
			$application_setting_update = $roles[0]->application_setting_update;


			$general_setting_view = $roles[0]->general_setting_view;
			$general_setting_update = $roles[0]->general_setting_update;

			$manage_admins_view   = $roles[0]->manage_admins_view;
			$manage_admins_create = $roles[0]->manage_admins_create;
			$manage_admins_update = $roles[0]->manage_admins_update;
			$manage_admins_delete = $roles[0]->manage_admins_delete;


			$language_view = $roles[0]->language_view;
			$language_create = $roles[0]->language_create;
			$language_update = $roles[0]->language_update;
			$language_delete = $roles[0]->language_delete;

			$profile_view = $roles[0]->profile_view;
			$profile_update = $roles[0]->profile_update;

			$admintype_view = $roles[0]->admintype_view;
			$admintype_create = $roles[0]->admintype_create;
			$admintype_update = $roles[0]->admintype_update;
			$admintype_delete = $roles[0]->admintype_delete;
			$manage_admins_role = $roles[0]->manage_admins_role;

            $make_view   = $roles[0]->make_view;
            $make_create = $roles[0]->make_create;
            $make_update = $roles[0]->make_update;
            $make_delete = $roles[0]->make_delete;

            $model_view   = $roles[0]->model_view;
            $model_create = $roles[0]->model_create;
            $model_update = $roles[0]->model_update;
            $model_delete = $roles[0]->model_delete;

            $state_view   = $roles[0]->state_view;
            $state_create = $roles[0]->state_create;
            $state_update = $roles[0]->state_update;
            $state_delete = $roles[0]->state_delete;

            $cities_view   = $roles[0]->cities_view;
            $cities_create = $roles[0]->cities_create;
            $cities_update = $roles[0]->cities_update;
            $cities_delete = $roles[0]->cities_delete;

            $car_view   = $roles[0]->car_view;
            $car_create = $roles[0]->car_create;
            $car_update = $roles[0]->car_update;
            $car_delete = $roles[0]->car_delete;

			$manage_merchants_view   = $roles[0]->manage_merchants_view;
			$manage_merchants_create = $roles[0]->manage_merchants_create;
			$manage_merchants_update = $roles[0]->manage_merchants_update;
			$manage_merchants_delete = $roles[0]->manage_merchants_delete;

			$manage_managers_view   = $roles[0]->manage_managers_view;
			$manage_managers_create = $roles[0]->manage_managers_create;
			$manage_managers_update = $roles[0]->manage_managers_update;
			$manage_managers_delete = $roles[0]->manage_managers_delete;

			$view_report_organisation   = $roles[0]->view_report_organisation;
			$view_report_sa   = $roles[0]->view_report_sa;
			$view_report_item   = $roles[0]->view_report_item;
			$view_report_promotion   = $roles[0]->view_report_promotion;
			$view_report_campaign   = $roles[0]->view_report_campaign;
			$view_report_campaign_response   = $roles[0]->view_report_campaign_response;

			$type_create   = $roles[0]->type_create;
			$type_delete   = $roles[0]->type_delete;
			$type_update   = $roles[0]->type_update;
			$type_view   = $roles[0]->type_view;

			$view_promotion = $roles[0]->view_promotion;
			$view_campaign = $roles[0]->view_campaign;
		}
		else
        {
			$dashboard_view = '0';

			$manufacturer_view = '0';
			$manufacturer_create = '0';
			$manufacturer_update = '0';
			$manufacturer_delete = '0';

			$categories_view = '0';
			$categories_create = '0';
			$categories_update = '0';
			$categories_delete = '0';

			$products_view   = '0';
			$products_create = '0';
			$products_update = '0';
			$products_delete = '0';

			$media_view   = '0';
			$media_create = '0';
			$media_update = '0';
			$media_delete = '0';

			$news_view = '0';
			$news_create = '0';
			$news_update = '0';
			$news_delete = '0';

			$customers_view   = '0';
			$customers_create = '0';
			$customers_update = '0';
			$customers_delete = '0';

			$tax_location_view = '0';
			$tax_location_create = '0';
			$tax_location_update = '0';
			$tax_location_delete = '0';


			$coupons_view = '0';
			$coupons_create = '0';
			$coupons_update = '0';
			$coupons_delete = '0';

			$notifications_view = '0';
			$notifications_send = '0';

			$orders_view = '0';
			$orders_confirm = '0';

			$shipping_methods_view = '0';
			$shipping_methods_update = '0';

			$payment_methods_view = '0';
			$payment_methods_update = '0';

			$reports_view = '0';

			$website_setting_view = '0';
			$website_setting_update = '0';

			$application_setting_view = '0';
			$application_setting_update = '0';

			$general_setting_view = '0';
			$general_setting_update = '0';

			$manage_admins_view = '0';
			$manage_admins_create = '0';
			$manage_admins_update = '0';
			$manage_admins_delete = '0';

			$language_view = '0';
			$language_create = '0';
			$language_update = '0';
			$language_delete = '0';

			$profile_view = '0';
			$profile_update = '0';

			$admintype_view = '0';
			$admintype_create = '0';
			$admintype_update = '0';
			$admintype_delete = '0';
			$manage_admins_role = '0';

            $make_view   = '0';
            $make_create = '0';
            $make_update = '0';
            $make_delete = '0';

            $model_view   = '0';
            $model_create = '0';
            $model_update = '0';
            $model_delete = '0';

            $state_view   = '0';
            $state_create = '0';
            $state_update = '0';
            $state_delete = '0';

            $cities_view   = '0';
            $cities_create = '0';
            $cities_update = '0';
            $cities_delete = '0';

            $car_view   = '0';
            $car_create = '0';
            $car_update = '0';
            $car_delete = '0';

			$manage_merchants_view = '0';
			$manage_merchants_create = '0';
			$manage_merchants_update = '0';
			$manage_merchants_delete = '0';

			$manage_managers_view = '0';
			$manage_managers_create = '0';
			$manage_managers_update = '0';
			$manage_managers_delete = '0';

			$view_report_organisation   = '0';
			$view_report_sa   = '0';
			$view_report_item   = '0';
			$view_report_promotion   = '0';
			$view_report_campaign   = '0';
			$view_report_campaign_response   = '0';

			$type_create   = '0';
			$type_delete   = '0';
			$type_update   = '0';
			$type_view   = '0';

			$view_promotion = '0';
			$view_campaign = '0';
		}


		$result2[0]['link_name'] = 'dashboard';
		$result2[0]['permissions'] = array('0'=>array('name'=>'dashboard_view','value'=>$dashboard_view));

		$result2[1]['link_name'] = 'manufacturer';
		$result2[1]['permissions'] = array(
					'0'=>array('name'=>'manufacturer_view','value'=>$manufacturer_view),
					'1'=>array('name'=>'manufacturer_create','value'=>$manufacturer_create),
					'2'=>array('name'=>'manufacturer_update','value'=>$manufacturer_update),
					'3'=>array('name'=>'manufacturer_delete','value'=>$manufacturer_delete)
					);

		$result2[2]['link_name'] = 'categories';
		$result2[2]['permissions'] = array(
					'0'=>array('name'=>'categories_view','value'=>$categories_view),
					'1'=>array('name'=>'categories_create','value'=>$categories_create),
					'2'=>array('name'=>'categories_update','value'=>$categories_update),
					'3'=>array('name'=>'categories_delete','value'=>$categories_delete)
					);

		$result2[3]['link_name'] = 'products';
		$result2[3]['permissions'] = array(
					'0'=>array('name'=>'products_view','value'=>$products_view),
					'1'=>array('name'=>'products_create','value'=>$products_create),
					'2'=>array('name'=>'products_update','value'=>$products_update),
					'3'=>array('name'=>'products_delete','value'=>$products_delete)
					);

		$result2[4]['link_name'] = 'news';
		$result2[4]['permissions'] = array(
					'0'=>array('name'=>'news_view','value'=>$news_view),
					'1'=>array('name'=>'news_create','value'=>$news_create),
					'2'=>array('name'=>'news_update','value'=>$news_update),
					'3'=>array('name'=>'news_delete','value'=>$news_delete)
					);

		$result2[5]['link_name'] = 'customers';
		$result2[5]['permissions'] = array(
					'0'=>array('name'=>'customers_view','value'=>$customers_view),
					'1'=>array('name'=>'customers_create','value'=>$customers_create),
					'2'=>array('name'=>'customers_update','value'=>$customers_update),
					'3'=>array('name'=>'customers_delete','value'=>$customers_delete)
					);

		$result2[6]['link_name'] = 'tax_location';
		$result2[6]['permissions'] = array(
					'0'=>array('name'=>'tax_location_view','value'=>$tax_location_view),
					'1'=>array('name'=>'tax_location_create','value'=>$tax_location_create),
					'2'=>array('name'=>'tax_location_update','value'=>$tax_location_update),
					'3'=>array('name'=>'tax_location_delete','value'=>$tax_location_delete)
					);

		$result2[7]['link_name'] = 'coupons';
		$result2[7]['permissions'] = array(
					'0'=>array('name'=>'coupons_view','value'=>$coupons_view),
					'1'=>array('name'=>'coupons_create','value'=>$coupons_create),
					'2'=>array('name'=>'coupons_update','value'=>$coupons_update),
					'3'=>array('name'=>'coupons_delete','value'=>$coupons_delete)
					);

		$result2[8]['link_name'] = 'notifications';
		$result2[8]['permissions'] = array(
					'0'=>array('name'=>'notifications_view','value'=>$notifications_view),
					'1'=>array('name'=>'notifications_send','value'=>$notifications_send)
					);

		$result2[9]['link_name'] = 'orders';
		$result2[9]['permissions'] = array(
					'0'=>array('name'=>'orders_view','value'=>$orders_view),
					'1'=>array('name'=>'orders_confirm','value'=>$orders_confirm)
					);

		$result2[10]['link_name'] = 'shipping_methods';
		$result2[10]['permissions'] = array(
					'0'=>array('name'=>'shipping_methods_view','value'=>$shipping_methods_view),
					'1'=>array('name'=>'shipping_methods_update','value'=>$shipping_methods_update)
					);

		$result2[11]['link_name'] = 'payment_methods';
		$result2[11]['permissions'] = array(
					'0'=>array('name'=>'payment_methods_view','value'=>$payment_methods_view),
					'1'=>array('name'=>'payment_methods_update','value'=>$payment_methods_update)
					);

		$result2[12]['link_name'] = 'reports';
		$result2[12]['permissions'] = array('0'=>array('name'=>'reports_view','value'=>$reports_view));

		$result2[13]['link_name'] = 'application_setting';
		$result2[13]['permissions'] = array(
					'0'=>array('name'=>'application_setting_view','value'=>$application_setting_view),
					'1'=>array('name'=>'application_setting_update','value'=>$application_setting_update)
					);

		$result2[14]['link_name'] = 'general_setting';
		$result2[14]['permissions'] = array(
					'0'=>array('name'=>'general_setting_view','value'=>$general_setting_view),
					'1'=>array('name'=>'general_setting_update','value'=>$general_setting_update)
					);

		$result2[15]['link_name'] = 'manage_admins';
		$result2[15]['permissions'] = array(
					'0'=>array('name'=>'manage_admins_view','value'=>$manage_admins_view),
					'1'=>array('name'=>'manage_admins_create','value'=>$manage_admins_create),
					'2'=>array('name'=>'manage_admins_update','value'=>$manage_admins_update),
					'3'=>array('name'=>'manage_admins_delete','value'=>$manage_admins_delete)
					);

        $result2[16]['link_name'] = 'website_setting';
        $result2[16]['permissions'] = array(
            '0'=>array('name'=>'website_setting_view','value'=>$website_setting_view),
            '1'=>array('name'=>'website_setting_update','value'=>$website_setting_update)
        );

		$result2[17]['link_name'] = 'language';
		$result2[17]['permissions'] = array(
					'0'=>array('name'=>'language_view','value'=>$language_view),
					'1'=>array('name'=>'language_create','value'=>$language_create),
					'2'=>array('name'=>'language_update','value'=>$language_update),
					'3'=>array('name'=>'language_delete','value'=>$language_delete)
					);

		$result2[18]['link_name'] = 'profile';
		$result2[18]['permissions'] = array(
					'0'=>array('name'=>'profile_view','value'=>$profile_view),
					'1'=>array('name'=>'profile_update','value'=>$profile_update)
					);

		$result2[19]['link_name'] = 'Media';
		$result2[19]['permissions'] = array(
					'0'=>array('name'=>'media_view','value'=>$media_view),
					'1'=>array('name'=>'media_create','value'=>$media_create),
					'2'=>array('name'=>'media_update','value'=>$media_update),
					'3'=>array('name'=>'media_delete','value'=>$media_delete),
					);

        $result2[20]['link_name'] = 'Admin Types';
        $result2[20]['permissions'] = array(
            '0'=>array('name'=>'admintype_view','value'=>$admintype_view),
            '1'=>array('name'=>'admintype_create','value'=>$admintype_create),
            '2'=>array('name'=>'admintype_update','value'=>$admintype_update),
            '3'=>array('name'=>'admintype_delete','value'=>$admintype_delete),
            '4'=>array('name'=>'manage_admins_role','value'=>$manage_admins_role)
        );

        $result2[21]['link_name'] = 'Makes';
        $result2[21]['permissions'] = array(
            '0'=>array('name'=>'make_view','value'=>$make_view),
            '1'=>array('name'=>'make_create','value'=>$make_create),
            '2'=>array('name'=>'make_update','value'=>$make_update),
            '3'=>array('name'=>'make_delete','value'=>$make_delete)
        );

        $result2[22]['link_name'] = 'Models';
        $result2[22]['permissions'] = array(
            '0'=>array('name'=>'model_view','value'=>$model_view),
            '1'=>array('name'=>'model_create','value'=>$model_create),
            '2'=>array('name'=>'model_update','value'=>$model_update),
            '3'=>array('name'=>'model_delete','value'=>$model_delete)
        );

        $result2[23]['link_name'] = 'States';
        $result2[23]['permissions'] = array(
            '0'=>array('name'=>'state_view','value'=>$state_view),
            '1'=>array('name'=>'state_create','value'=>$state_create),
            '2'=>array('name'=>'state_update','value'=>$state_update),
            '3'=>array('name'=>'state_delete','value'=>$state_delete)
        );

        $result2[24]['link_name'] = 'Cities';
        $result2[24]['permissions'] = array(
            '0'=>array('name'=>'city_view','value'=>$cities_view),
            '1'=>array('name'=>'city_create','value'=>$cities_create),
            '2'=>array('name'=>'city_update','value'=>$cities_update),
            '3'=>array('name'=>'city_delete','value'=>$cities_delete)
        );

        $result2[25]['link_name'] = 'Cars';
        $result2[25]['permissions'] = array(
            '0'=>array('name'=>'car_view','value'=>$car_view),
            '1'=>array('name'=>'car_create','value'=>$car_create),
            '2'=>array('name'=>'car_update','value'=>$car_update),
            '3'=>array('name'=>'car_delete','value'=>$car_delete)
        );

		$result2[26]['link_name'] = 'manage_merchants';
		$result2[26]['permissions'] = array(
					'0'=>array('name'=>'manage_merchants_view','value'=>$manage_merchants_view),
					'1'=>array('name'=>'manage_merchants_create','value'=>$manage_merchants_create),
					'2'=>array('name'=>'manage_merchants_update','value'=>$manage_merchants_update),
					'3'=>array('name'=>'manage_merchants_delete','value'=>$manage_merchants_delete)
					);
		
		$result2[27]['link_name'] = 'manage_managers';
		$result2[27]['permissions'] = array(
					'0'=>array('name'=>'manage_managers_view','value'=>$manage_managers_view),
					'1'=>array('name'=>'manage_managers_create','value'=>$manage_managers_create),
					'2'=>array('name'=>'manage_managers_update','value'=>$manage_managers_update),
					'3'=>array('name'=>'manage_managers_delete','value'=>$manage_managers_delete)
					);
		
		$result2[28]['link_name'] = 'manage_reports';
		$result2[28]['permissions'] = array(
					'0'=>array('name'=>'view_report_organisation','value'=>$view_report_organisation),
					'1'=>array('name'=>'view_report_sa','value'=>$view_report_sa),
					'2'=>array('name'=>'view_report_item','value'=>$view_report_item),
					'3'=>array('name'=>'view_report_promotion','value'=>$view_report_promotion),
					'4'=>array('name'=>'view_report_campaign','value'=>$view_report_campaign),
					'5'=>array('name'=>'view_report_campaign_response','value'=>$view_report_campaign_response)
					);

		$result2[29]['link_name'] = 'manage_types';
		$result2[29]['permissions'] = array(
					'0'=>array('name'=>'type_create','value'=>$type_create),
					'1'=>array('name'=>'type_delete','value'=>$type_delete),
					'2'=>array('name'=>'type_update','value'=>$type_update),
					'3'=>array('name'=>'type_view','value'=>$type_view)
					);

		$result2[30]['link_name'] = 'promotion';
		$result2[30]['permissions'] = array('0'=>array('name'=>'view_promotion','value'=>$view_promotion));

		$result2[31]['link_name'] = 'campaign';
		$result2[31]['permissions'] = array('0'=>array('name'=>'view_campaign','value'=>$view_campaign));

        $result['data'] = $result2;
		return view("admin.admins.roles.addrole",$title)->with('result', $result);

	}

	//addnewroles
	public function addnewroles(Request $request){
		// dd($request->all());
		$user_types_id = $request->user_types_id;
		DB::table('manage_role')->where('user_types_id',$user_types_id)->delete();

		$roles = DB::table('manage_role')->where('user_types_id',$request->user_types_id)->insert([
					'user_types_id'			=>	$request->user_types_id,
					'dashboard_view'		=>	$request->dashboard_view,

					'manufacturer_view' 	=> $request->manufacturer_view,
					'manufacturer_create' 	=> $request->manufacturer_create,
					'manufacturer_update' 	=> $request->manufacturer_update,
					'manufacturer_delete' 	=> $request->manufacturer_delete,

					'categories_view' 		=> $request->categories_view,
					'categories_create' 	=> $request->categories_create,
					'categories_update' 	=> $request->categories_update,
					'categories_delete' 	=> $request->categories_delete,

					'products_view' 		=> $request->products_view,
					'products_create' 		=> $request->products_create,
					'products_update' 		=> $request->products_update,
					'products_delete' 		=> $request->products_delete,

					'news_view' 			=> $request->news_view,
					'news_create' 			=> $request->news_create,
					'news_update' 			=> $request->news_update,
					'news_delete' 			=> $request->news_delete,

					'customers_view' 		=> $request->customers_view,
					'customers_create' 		=> $request->customers_create,
					'customers_update' 		=> $request->customers_update,
					'customers_delete' 		=> $request->customers_delete,

					'tax_location_view' 	=> $request->tax_location_view,
					'tax_location_create' 	=> $request->tax_location_create,
					'tax_location_update' 	=> $request->tax_location_update,
					'tax_location_delete' 	=> $request->tax_location_delete,
					
					'coupons_view' 			=> $request->coupons_view,
					'coupons_create' 		=> $request->coupons_create,
					'coupons_update' 		=> $request->coupons_update,
					'coupons_delete' 		=> $request->coupons_delete,

					'notifications_view' 	=> $request->notifications_view,
					'notifications_send' 	=> $request->notifications_send,

					'orders_view' 			=> $request->orders_view,
					'orders_confirm' 		=> $request->orders_confirm,

					'shipping_methods_view' => $request->shipping_methods_view,
					'shipping_methods_update' => $request->shipping_methods_update,

					'payment_methods_view' => $request->payment_methods_view,
					'payment_methods_update' => $request->payment_methods_update,

					'reports_view' 			=> $request->reports_view,

					'website_setting_view' 	=> $request->website_setting_view,
					'website_setting_update' => $request->website_setting_update,

					'application_setting_view' => $request->application_setting_view,
					'application_setting_update' => $request->application_setting_update,

					'general_setting_view' => $request->general_setting_view,
					'general_setting_update' => $request->general_setting_update,

					'manage_admins_view' 	=> $request->manage_admins_view,
					'manage_admins_create'	=> $request->manage_admins_create,
					'manage_admins_update' 	=> $request->manage_admins_update,
					'manage_admins_delete' 	=> $request->manage_admins_delete,

					'language_view' 		=> $request->language_view,
					'language_create' 		=> $request->language_create,
					'language_update' 		=> $request->language_update,
					'language_delete' 		=> $request->language_delete,

					'profile_view' 			=> $request->profile_view,
					'profile_update' 		=> $request->profile_update,

					'admintype_view' 		=> $request->admintype_view,
					'admintype_create' 		=> $request->admintype_create,
					'admintype_update' 		=> $request->admintype_update,
					'admintype_delete' 		=> $request->admintype_delete,
					'manage_admins_role' 	=> $request->manage_admins_role,

					'view_media' 			=> $request->media_view,
					'add_media' 			=> $request->media_create,
					'edit_media' 			=> $request->media_update,
					'delete_media' 			=> $request->media_delete,

					'make_create'			=> $request->make_create,
					'make_delete'			=> $request->make_delete,
					'make_update'			=> $request->make_update,
					'make_view'				=> $request->make_view,

					'model_create'			=> $request->model_create,
					'model_delete'			=> $request->model_delete,
					'model_update'			=> $request->model_update,
					'model_view'			=> $request->model_view,

					'state_create'			=> $request->state_create,
					'state_delete'			=> $request->state_delete,
					'state_update'			=> $request->state_update,
					'state_view'			=> $request->state_view,

					'cities_create'			=> $request->city_create,
					'cities_delete'			=> $request->city_delete,
					'cities_update'			=> $request->city_update,
					'cities_view'			=> $request->city_view,

					'manage_merchants_create'	=> $request->manage_merchants_create,
					'manage_merchants_delete'	=> $request->manage_merchants_delete,
					'manage_merchants_update'	=> $request->manage_merchants_update,
					'manage_merchants_view'		=> $request->manage_merchants_view,

					'car_view' 		=> $request->car_view,
					'car_create' 	=> $request->car_create,
					'car_update' 	=> $request->car_update,
					'car_delete' 	=> $request->car_delete,

					'type_create'	=> $request->type_create,
					'type_delete'	=> $request->type_delete,
					'type_update'	=> $request->type_update,
					'type_view'		=> $request->type_view,

					'manage_managers_create'	=> $request->manage_managers_create,
					'manage_managers_delete'	=> $request->manage_managers_delete,
					'manage_managers_update'	=> $request->manage_managers_update,
					'manage_managers_view'		=> $request->manage_managers_view,

					'view_report_organisation'	=> $request->view_report_organisation,
					'view_report_sa'			=> $request->view_report_sa,
					'view_report_item'			=> $request->view_report_item,
					'view_report_promotion'		=> $request->view_report_promotion,
					'view_report_campaign'		=> $request->view_report_campaign,
					'view_report_campaign_response'		=> $request->view_report_campaign_response,

					'view_promotion'		=> $request->view_promotion,
					'view_campaign'			=> $request->view_campaign,


					]);

		$message = Lang::get("labels.Roles has been added successfully");
		return redirect()->back()->with('message', $message);

	}


   //managerole
	public function categoriesroles(Request $request)
    {


        $title = array('pageTitle' => Lang::get("labels.CategoriesRoles"));
        $result = array();
        $language_id = 1;

        $categories_role = DB::table('users')->join('categories_role','categories_role.admin_id','=','users.role_id')->where('users.role_id','!=','1')->get();

        $data = array();
        $index = 0;
        foreach($categories_role as $categories){
            array_push($data,$categories);
            $cat_array = explode(',',$categories->categories_ids);
            $categories_descrtiption = DB::table('categories_description')->whereIn('categories_id', $cat_array)->where('language_id',$language_id)->get();
            $data[$index++]->description = $categories_descrtiption;
        }

        $result['data'] = $data;
        return view("admin.admins.roles.category.index",$title)->with('result', $result);


	}

  	//addCategoriesRoles
  	public function addCategoriesRoles(Request $request)
    {

        $title = array('pageTitle' => Lang::get("labels.AddCategoriesRoles"));
        $result = array();
        $language_id = 1;
        $categories_role = DB::table('categories_role')->get();

        //get function from other controller
        $myVar = new AdminCategoriesController();
        $result['categories'] = $myVar->allCategories($language_id);

        $result['admins'] = DB::table('users')->where('role_id','!=','1')->get();

        $result['data'] = $categories_role;
        return view("admin.admins.roles.category.add",$title)->with('result', $result);

  	}

	//addCategoriesRoles
	public function addNewCategoriesRoles(Request $request)
    {


        $title = array('pageTitle' => Lang::get("labels.AddCategoriesRoles"));
        $result = array();

        $language_id = 1;

        $exist = DB::table('categories_role')->where('admin_id',$request->admin_id)->get();

        if(count($exist)>0){
            return redirect()->back()->with('error', Lang::get("labels.AlreadyCategoryAssignToadmin"));
        }else{

            $categories = array();
            foreach($request->categories as $category){
                $categories[] = $category;
            }

            $categories = implode(',',$categories);

            $roles = DB::table('categories_role')->insert([
                        'categories_ids'	=>	$categories,
                        'admin_id'			=>	$request->admin_id,
                        ]);

            return redirect()->back()->with('success', Lang::get("labels.CategoryRolesAddedSucceccfully"));
        }

	}

    //editCategoriesRoles
    public function editCategoriesRoles(Request $request){

		$title = array('pageTitle' => Lang::get("labels.AddCategoriesRoles"));
		$result = array();
		$language_id = 1;

		//get function from other controller
		$myVar = new AdminCategoriesController();
		$result['categories'] = $myVar->allCategories($language_id);

		$categories_role = DB::table('categories_role')->where('categories_role_id',$request->id)->get();

		$result['admins'] = DB::table('users')->where('role_id','!=','1')->get();

		$result['data'] = $categories_role;

		return view("admin.admins.roles.category.edit",$title)->with('result', $result);


	}

	//updatecategoriesroles
	public function updatecategoriesroles(Request $request)
    {

        $result = array();

        $categories = array();
        foreach($request->categories as $category){
            $categories[] = $category;
        }
        print_r($request->admin_id);
        $categories = implode(',',$categories);

        $roles = DB::table('categories_role')->where('categories_role_id',$request->categories_role_id)->update([
                    'categories_ids'	=>	$categories,
                    ]);

        return redirect()->back()->with('success', Lang::get("labels.CategoryRolesUpdatedSucceccfully"));

	}

	//deleteCountry
	public function deletecategoriesroles(Request $request)
    {

        DB::table('categories_role')->where('categories_role_id', $request->id)->delete();
        return redirect()->back()->withErrors([Lang::get("labels.AdminRemoveCategoryMessage")]);

	}

    //add new manager
    public function addnewmanager(Request $request){

        //get function from other controller
        $myVar = new SiteSettingController();
        $extensions = $myVar->imageType();

        $result = array();
        $message = array();
        $errorMessage = array();

        //check email already exists
        $existEmail = DB::table('users')->where('email', '=', $request->email)->get();
        if(count($existEmail)>0){
            $errorMessage = Lang::get("labels.Email address already exist");
            return redirect()->back()->with('errorMessage', $errorMessage);
        }else{

            $uploadImage = '';
            //default_manager_id
            $request->adminType= \App\Models\Core\User::ROLE_MANAGER;
            //echo "<pre>";print_r($request);echo "</pre>";exit();
            $customers_id = DB::table('users')->insertGetId([
                'user_name'		 		    =>   $request->first_name.'_'.$request->last_name.time(),
                'first_name'		 		=>   $request->first_name,
                'last_name'			 		=>   $request->last_name,
                'phone'	 					=>	 $request->phone,
                'email'	 					=>   $request->email,
                'password'		 			=>   Hash::make($request->password),
                'status'		 	 		=>   $request->isActive,
                'avatar'	 				=>	 $uploadImage,
                'role_id'					=>	 $request->adminType,
                'created_at'				=>   date('Y-m-d H:i:s')
            ]);

            if($request->address){
                if(empty($request->zip)){
                    $request->zip = "";
                }
                if(empty($request->city)){
                    $request->city = "";
                }
                if(empty($request->country)){
                    $request->country = 0;
                }
                if(empty($request->state)){
                    $request->state = NULL;
                }
                //insert address book
                $address_book = DB::table('address_book')->insertGetId([
                    'entry_street_address'		=>   $request->address,
                    'entry_postcode'		 	=>   $request->zip,
                    'entry_city'			 	=>   $request->city,
                    'entry_country_id'	 		=>	 $request->country,
                    'entry_state'	 			=>   $request->state
                ]);

                $user_to_address = DB::table('user_to_address')->insertGetId([
                    'user_id'		 			=>   $customers_id,
                    'address_book_id'		 	=>   $address_book
                ]);
            }

            $message = Lang::get("labels.New manager has been added successfully");
            return redirect()->back()->with('message', $message);

        }
    }

    public function managers(Request $request){

        $title = array('pageTitle' => Lang::get("labels.ListingManagers"));
        $language_id = '1';

        $result = array();
        $message = array();
        $errorMessage = array();

        $admins = DB::table('users')
            ->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
            ->select('users.*','user_types.*')
            ->where('users.role_id','=',\App\Models\Core\User::ROLE_MANAGER)
            ->paginate(50);


        $result['message'] = $message;
        $result['errorMessage'] = $errorMessage;
        $result['admins'] = $admins;

        return view("admin.managers.index",$title)->with('result', $result);

    }

    //add managers
    public function addmanagers(Request $request){

        $title = array('pageTitle' => Lang::get("labels.addmanager"));

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
        $adminTypes = DB::table('user_types')
            ->where('isActive', 1)
            ->where('user_types_id','=',User::ROLE_MANAGER)
            ->get();
        $result['adminTypes'] = $adminTypes;

        return view("admin.managers.add",$title)->with('result', $result)->with('allimage',$allimage);

    }

    //edit manager
    public function editmanager(Request $request)
    {
        //role manager can not edit other manager info
        if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT &&  $request->id != Auth()->user()->id )
        {
            return  redirect('not_allowed');
        }

        $title = array('pageTitle' => Lang::get("labels.EditManager"));
        $myid = $request->id;

        $result = array();
        $message = array();
        $errorMessage = array();
        $images = new Images;
        $allimage = $images->getimages();

        //get function from other controller
        $myVar = new AddressController();
        $result['countries'] = $myVar->getAllCountries();
        $adminTypes = DB::table('user_types')->where('isActive', 1)
            ->where('user_types_id','=','13')
            ->get();
        $state = $this->States->getter();
        $result['state'] = $state;
        $city = $this->Cities->getter();
        $result['city'] = $city;

        $result['adminTypes'] = $adminTypes;

        $result['myid'] = $myid;

        $admins = DB::table('users')->where('id','=', $myid)->get();
        $zones = 0;

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $admins[0]->id)->get();
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
        //$image_path = DB::table('image_categories')->where('image_id', $admins[0]->ssm)->where('image_type', 'ACTUAL')->get();
        //echo "a";echo "<pre>";print_r($image_path);echo "</pre>";echo $admins[0]->ssm;exit();
        $result['admins'] = $admins;
        //$result['image_path'] = $image_path;
        return view("admin.managers.edit",$title)->with('result', $result)->with('allimage', $allimage);
    }

    //update manager
    public function updatemanager(Request $request)
    {
        //get function from other controller
        $myVar = new SiteSettingController();
        $extensions = $myVar->imageType();
        $myid = $request->myid;
        $result = array();
        $message = array();
        $errorMessage = array();
        if($request->image_avatar!==null){
            $members_picture = $request->image_avatar;
        }	else{
            $members_picture = $request->oldImage;
        }

        //check email already exists
        $existEmail = DB::table('users')->where([['email','=',$request->email],['id','!=',$myid]])->get();
        if(count($existEmail)>0)
        {
            $errorMessage = Lang::get("labels.Email address already exist");
            return redirect()->back()->with('errorMessage', $errorMessage);
        }
        else
        {

            $uploadImage = '';
            //default_manager_id
            $request->adminType=\App\Models\Core\User::ROLE_MANAGER;
            $admin_data = array(
                'first_name'		 		=>   $request->first_name,
                'last_name'			 		=>   $request->last_name,
                'phone'	 					=>	 $request->phone,
                'email'	 					=>   $request->email,
                'status'		 	 		=>   $request->isActive,
                'avatar'	 				=>	 $uploadImage,
                'role_id'	 				=>	 $request->adminType,
                'updated_at'				=>   date('Y-m-d H:i:s'),
            );

            if($request->changePassword == 'yes'){
                $admin_data['password'] = Hash::make($request->password);
            }

            $customers_id = DB::table('users')->where('id', '=', $myid)->update($admin_data);

            //if($request->address){
            $checkUserAddress = DB::table('user_to_address')->where('user_id', '=', $myid)->get();
            if(count($checkUserAddress)>0){
                $address_data = array(
                    'entry_street_address'		=>   $request->address,
                    'entry_postcode'		 	=>   $request->zip,
                    'entry_city'			 	=>   $request->city,
                    'entry_country_id'	 		=>	 $request->country,
                    'entry_state'	 			=>   $request->state,
                );
                $address_id = DB::table('address_book')->where('address_book_id', '=', $checkUserAddress[0]->address_book_id)->update($address_data);
            }else{
                if($request->address){
                    if(empty($request->zip)){
                        $request->zip = "";
                    }
                    if(empty($request->city)){
                        $request->city = "";
                    }
                    if(empty($request->country)){
                        $request->country = 0;
                    }
                    if(empty($request->state)){
                        $request->state = NULL;
                    }
                    //insert address book
                    $address_book = DB::table('address_book')->insertGetId([
                        'entry_street_address'		=>   $request->address,
                        'entry_postcode'		 	=>   $request->zip,
                        'entry_city'			 	=>   $request->city,
                        'entry_country_id'	 		=>	 $request->country,
                        'entry_state'	 			=>   $request->state
                    ]);

                    $user_to_address = DB::table('user_to_address')->insertGetId([
                        'user_id'		 			=>   $myid,
                        'address_book_id'		 	=>   $address_book
                    ]);
                }
            }

            $message = Lang::get("labels.Manager has been updated successfully");
            return redirect()->back()->with('message', $message);
        }

    }

    //delete manager
    public function deletemanager(Request $request){
        $myid = $request->users_id;
        DB::table('users')->where('id','=', $myid)->delete();
        $checkUserAddress = DB::table('user_to_address')->where('user_id', '=', $myid)->get();
        if(count($checkUserAddress)>0){
            DB::table('address_book')->where('address_book_id','=', $checkUserAddress[0]->address_book_id)->delete();
            DB::table('user_to_address')->where('id','=', $checkUserAddress[0]->id)->delete();
        }
        return redirect()->back()->withErrors([Lang::get("labels.DeleteManagerMessage")]);
    }

	// template manager-----------------------------------------------------------------------------------------------------------
	public function templates(Request $request){
		$title = array('pageTitle' => Lang::get("labels.ListingTemplates"));
		$language_id = '1';

		$result = array();
		$message = array();
		$errorMessage = array();

		$admins = Template::orderBy('id','desc')->paginate('30');

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		$result['admins'] = $admins;

		return view("admin.templates.index",$title)->with('result', $result);
	}

	public function addtemplates(Request $request){
		$title = array('pageTitle' => Lang::get("labels.addtemplate"));

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

		return view("admin.templates.add",$title)->with('result', $result)->with('allimage',$allimage);
	}

	public function insertTemplate(Request $request){
		$time = Carbon::now();
		$currentUser = Auth()->user()->id;
		$uploadImage = '';

		// if ($request->hasFile('preview_image')) {
		// 	$image = $request->file('preview_image');
        //     if ($image) {
        //         $extension = $image->getClientOriginalExtension();
        //         $filename = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
		// 		$destinaton_path = public_path('images/template/');
		// 		$image->move($destinaton_path,$filename);
        //     }
		// }
		// else{
		// 	$filename = "";
		// }

		if($request->template_code){ $template_code = $request->template_code; } else{ $template_code = ""; }
		if($request->lite_template_code){ $lite_template_code = $request->lite_template_code; } else{ $lite_template_code = ""; }
		// if($request->landingpage_version){ $landingpage_version = $request->landingpage_version; } else{ $landingpage_version = ""; }
		if($request->askme_code){ $askme_code = $request->askme_code; } else{ $askme_code = ""; }
		if($request->showme_code){ $showme_code = $request->showme_code; } else{ $showme_code = ""; }
		if($request->keepme_code){ $keepme_code = $request->keepme_code; } else{ $keepme_code = ""; }

        $branch_id = DB::table('templates')->insertGetId([
			'name' => $request->name,
			'preview_image' => $request->preview_image,
			'template_code' => $template_code,
			'lite_template_code' => $lite_template_code,
			// 'landingpage_version' => $landingpage_version,
			'askme_code' => $askme_code,
			'showme_code' => $showme_code,
			'keepme_code' => $keepme_code,
			'call_icon' => $request->call_icon,
			'email_icon' => $request->email_icon,
			'whatsapp_icon' => $request->whatsapp_icon,
			'direction_icon' => $request->direction_icon,
			'A360_icon' => $request->A360_icon,
			'a360_title' => $request->a360_title,
			'a360_redirect_url' => $request->a360_redirect_url ? $request->a360_redirect_url : '{base_url}/show/make',
			'askme_icon' => $request->askme_icon,
			'askme_title' => $request->askme_title,
			'askme_redirect_url' => $request->askme_redirect_url ? $request->askme_redirect_url : '{base_url}/ask',
			'promotion_icon' => $request->promotion_icon,
			'promotion_title' => $request->promotion_title,
			'promotion_redirect_url' => $request->promotion_redirect_url ? $request->promotion_redirect_url : '{base_url}/keep',
			'css_code' => $request->css_code,
			'header_code' => $request->header_code,
			'footer_code' => $request->footer_code,
			'show_company_with' => $request->show_company_with,
			'colour1' => $request->color,
			'colour2' => $request->color2,
			'colour3' => $request->color3,
			'colour4' => $request->color4,
			'colour5' => $request->color5,
			'standard_lp_bg' => $request->standard_lp_bg,
			'lite_lp_bg' => $request->lite_lp_bg,
			'campaign_icon' => $request->campaign_icon,
			'campaign_title' => $request->campaign_title,
			'created_at' => date('Y-m-d H:i:s')
		]);
        $message = Lang::get("labels.TemplateAddedMessage");
		return  redirect('admin/templates')->with('message', $message);
    }

	public function edittemplate(Request $request)
    {
        $title = array('pageTitle' => Lang::get("labels.edittemplates"));
		$myid = $request->id;

		$currentUser = Auth()->user()->id;
		// dd($currentUser);
		
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

        $admins = DB::table('templates')->where('id','=', $myid)->get();
		$zones = 0;
		
		if($admins[0]->last_edit_by > 0){
			$allUser = DB::table('users')->where('id','=',$admins[0]->last_edit_by)->get();
			$flname = $allUser[0]->first_name . ' ' . $allUser[0]->last_name;
			$result['last_edit_by'] = $flname;
		}

		$user_to_address = DB::table('user_to_address')->where('user_id','=', $admins[0]->id)->get();
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
		//$image_path = DB::table('image_categories')->where('image_id', $admins[0]->ssm)->where('image_type', 'ACTUAL')->get();
		//echo "a";echo "<pre>";print_r($image_path);echo "</pre>";echo $admins[0]->ssm;exit();
        $result['admins'] = $admins;
		//$result['image_path'] = $image_path;
		return view("admin.templates.edit",$title)->with('result', $result)->with('allimage', $allimage);
	}

	public function updateTemplate(Request $request){
        $time = Carbon::now();
        $extensions = array('pdf');
		$currentUser = Auth()->user()->id;
		$template_id    =   $request->myid;
		
		// if ($request->hasFile('preview_image')) {
		// 	$image = $request->file('preview_image');
        //     if ($image) {
        //         $extension = $image->getClientOriginalExtension();
        //         $filename = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
		// 		$destinaton_path = public_path('images/template/');
		// 		$image->move($destinaton_path,$filename);
        //     }
		// }
		// else{
		// 	$filename = $request->old_preview_image;
		// }
		
		DB::table('templates')->where('id','=', $template_id)->update([
            'name' => $request->name,
			'preview_image' => $request->preview_image,
			'template_code' => $request->template_code,
			'lite_template_code' => $request->lite_template_code,
			// 'landingpage_version' => $request->landingpage_version,
			'askme_code' => $request->askme_code,
			'showme_code' => $request->showme_code,
			'keepme_code' => $request->keepme_code,
			'css_code' => $request->css_code,
			'header_code' => $request->header_code,
			'footer_code' => $request->footer_code,
			'show_company_with' => $request->show_company_with,
			'call_icon' => $request->call_icon,
			'email_icon' => $request->email_icon,
			'whatsapp_icon' => $request->whatsapp_icon,
			'direction_icon' => $request->direction_icon,
			'A360_icon' => $request->A360_icon,
			'a360_title' => $request->a360_title,
			'a360_redirect_url' => $request->a360_redirect_url ? $request->a360_redirect_url : '{base_url}/show/make',
			'askme_icon' => $request->askme_icon,
			'askme_title' => $request->askme_title,
			'askme_redirect_url' => $request->askme_redirect_url ? $request->askme_redirect_url : '{base_url}/ask',
			'promotion_icon' => $request->promotion_icon,
			'promotion_title' => $request->promotion_title,
			'promotion_redirect_url' => $request->promotion_redirect_url ? $request->promotion_redirect_url : '{base_url}/keep',
			'colour1' => $request->color,
			'colour2' => $request->color2,
			'colour3' => $request->color3,
			'colour4' => $request->color4,
			'colour5' => $request->color5,
			'last_edit_by' => $currentUser,
			'standard_lp_bg' => $request->standard_lp_bg,
			'lite_lp_bg' => $request->lite_lp_bg,
			'campaign_icon' => $request->campaign_icon,
			'campaign_title' => $request->campaign_title,
			'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // $this->Cars->updaterecord($request, $time);
        // $stateData['message'] = 'Car has been updated successfully!';
        $message = Lang::get("labels.TemplateEditMessage");
        return redirect()->back()->with('message', $message);
    }

	public function deleteTemplate(Request $request){
        $myid = $request->users_id;
        DB::table('templates')->where('id','=', $myid)->delete();
        return redirect()->back()->withErrors([Lang::get("labels.DeleteTemplateMessage")]);
    }

	// end of template manager----------------------------------------------------------------------------------------------------------
    //Agent
    public function agents(Request $request){

        $title = array('pageTitle' => Lang::get("labels.ListingManagers"));
        $language_id = '1';

        $result = array();
        $message = array();
        $errorMessage = array();

        $admins = DB::table('users')
            ->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
            ->select('users.*','user_types.*')
            ->where('users.role_id','=',Agent::ROLE_ID)
            ->paginate(50);


        $result['message'] = $message;
        $result['errorMessage'] = $errorMessage;
        $result['admins'] = $admins;

        return view("admin.agent.admin-agent-index",$title)->with('result', $result);

    }
    public function addAgents(Request $request){

        $title = array('pageTitle' => Lang::get("labels.addmanager"));

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
        $adminTypes = DB::table('user_types')
            ->where('isActive', 1)
            ->where('user_types_id','=',User::ROLE_MANAGER)
            ->get();
        $result['adminTypes'] = $adminTypes;

        return view("admin.managers.add",$title)->with('result', $result)->with('allimage',$allimage);

    }

    //edit manager
    public function editAgent(Request $request)
    {
        //role manager can not edit other manager info
        if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT &&  $request->id != Auth()->user()->id )
        {
            return  redirect('not_allowed');
        }

        $title = array('pageTitle' => Lang::get("labels.EditManager"));
        $myid = $request->id;

        $result = array();
        $message = array();
        $errorMessage = array();
        $images = new Images;
        $allimage = $images->getimages();

        //get function from other controller
        $myVar = new AddressController();
        $result['countries'] = $myVar->getAllCountries();
        $adminTypes = DB::table('user_types')->where('isActive', 1)
            ->where('user_types_id','=','13')
            ->get();
        $state = $this->States->getter();
        $result['state'] = $state;
        $city = $this->Cities->getter();
        $result['city'] = $city;

        $result['adminTypes'] = $adminTypes;

        $result['myid'] = $myid;

        $admins = DB::table('users')->where('id','=', $myid)->get();
        $zones = 0;

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $admins[0]->id)->get();
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
        //$image_path = DB::table('image_categories')->where('image_id', $admins[0]->ssm)->where('image_type', 'ACTUAL')->get();
        //echo "a";echo "<pre>";print_r($image_path);echo "</pre>";echo $admins[0]->ssm;exit();
        $result['admins'] = $admins;
        //$result['image_path'] = $image_path;
        return view("admin.managers.edit",$title)->with('result', $result)->with('allimage', $allimage);
    }

    //update manager
    public function updateAgent(Request $request)
    {
        //get function from other controller
        $myVar = new SiteSettingController();
        $extensions = $myVar->imageType();
        $myid = $request->myid;
        $result = array();
        $message = array();
        $errorMessage = array();
        if($request->image_avatar!==null){
            $members_picture = $request->image_avatar;
        }	else{
            $members_picture = $request->oldImage;
        }

        //check email already exists
        $existEmail = DB::table('users')->where([['email','=',$request->email],['id','!=',$myid]])->get();
        if(count($existEmail)>0)
        {
            $errorMessage = Lang::get("labels.Email address already exist");
            return redirect()->back()->with('errorMessage', $errorMessage);
        }
        else
        {

            $uploadImage = '';
            //default_manager_id
            $request->adminType=\App\Models\Core\User::ROLE_MANAGER;
            $admin_data = array(
                'first_name'		 		=>   $request->first_name,
                'last_name'			 		=>   $request->last_name,
                'phone'	 					=>	 $request->phone,
                'email'	 					=>   $request->email,
                'status'		 	 		=>   $request->isActive,
                'avatar'	 				=>	 $uploadImage,
                'role_id'	 				=>	 $request->adminType,
                'updated_at'				=>   date('Y-m-d H:i:s'),
            );

            if($request->changePassword == 'yes'){
                $admin_data['password'] = Hash::make($request->password);
            }

            $customers_id = DB::table('users')->where('id', '=', $myid)->update($admin_data);

            //if($request->address){
            $checkUserAddress = DB::table('user_to_address')->where('user_id', '=', $myid)->get();
            if(count($checkUserAddress)>0){
                $address_data = array(
                    'entry_street_address'		=>   $request->address,
                    'entry_postcode'		 	=>   $request->zip,
                    'entry_city'			 	=>   $request->city,
                    'entry_country_id'	 		=>	 $request->country,
                    'entry_state'	 			=>   $request->state,
                );
                $address_id = DB::table('address_book')->where('address_book_id', '=', $checkUserAddress[0]->address_book_id)->update($address_data);
            }else{
                if($request->address){
                    if(empty($request->zip)){
                        $request->zip = "";
                    }
                    if(empty($request->city)){
                        $request->city = "";
                    }
                    if(empty($request->country)){
                        $request->country = 0;
                    }
                    if(empty($request->state)){
                        $request->state = NULL;
                    }
                    //insert address book
                    $address_book = DB::table('address_book')->insertGetId([
                        'entry_street_address'		=>   $request->address,
                        'entry_postcode'		 	=>   $request->zip,
                        'entry_city'			 	=>   $request->city,
                        'entry_country_id'	 		=>	 $request->country,
                        'entry_state'	 			=>   $request->state
                    ]);

                    $user_to_address = DB::table('user_to_address')->insertGetId([
                        'user_id'		 			=>   $myid,
                        'address_book_id'		 	=>   $address_book
                    ]);
                }
            }

            $message = Lang::get("labels.Manager has been updated successfully");
            return redirect()->back()->with('message', $message);
        }

    }

    //delete manager
    public function deleteAgent(Request $request){
        $myid = $request->users_id;
        DB::table('users')->where('id','=', $myid)->delete();
        $checkUserAddress = DB::table('user_to_address')->where('user_id', '=', $myid)->get();
        if(count($checkUserAddress)>0){
            DB::table('address_book')->where('address_book_id','=', $checkUserAddress[0]->address_book_id)->delete();
            DB::table('user_to_address')->where('id','=', $checkUserAddress[0]->id)->delete();
        }
        return redirect()->back()->withErrors([Lang::get("labels.DeleteManagerMessage")]);
    }
    //end agent

}
