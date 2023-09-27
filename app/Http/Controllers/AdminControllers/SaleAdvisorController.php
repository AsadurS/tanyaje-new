<?php
namespace App\Http\Controllers\AdminControllers;
use App\Agent;
use App\Mail\ToAgentSend;
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
use App\Models\Core\SaleAdvisors;
use App\Models\Core\Template;
use App\Models\Core\Banks;
use App\Models\Core\Segments;
use App\Models\Core\Documents;
use App\SaleAdvisor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Models\Core\Images;
use Validator;
use Hash;
use ZipArchive;
use File;
use Image;
use Carbon\Carbon;

class SaleAdvisorController extends Controller
{
    //
    private $domain;
    public function __construct(Segments $segment,Admin $admin, Setting $setting, Order $order, Customers $customers, Cities $cities, States $States, Makes $makes, Cars $cars, User $user)
    {
		$this->segment =$segment;
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

    public function saleAdvisor(Request $request){
		//if role is merchant then only can edit himself branch info
        if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT && Auth()->user()->id != $request->id)
        {
            return  redirect('not_allowed');
        }

        $title = array('pageTitle' => Lang::get("labels.AddBranch"));

        $id = $request->id;

        $merchantData = array();
        $message = array();
        $errorMessage = array();

		if($id > 0){
			$branch = DB::table('merchant_branch')
            ->leftjoin('states', 'states.state_id','=','merchant_branch.state_id')
            ->leftJoin('cities', 'cities.city_id', '=', 'merchant_branch.city_id')
            ->where('merchant_branch.user_id', '=', $id);
			if (!empty($request->get('ID'))) {
			    $branch = $branch->where('merchant_branch.id', 'LIKE', $request->ID );
			}
			if (!empty($request->get('name'))) {
			    $branch = $branch->where('merchant_branch.merchant_name', 'LIKE', '%' . $request->name . '%');
			}
			if (!empty($request->get('organisation_id'))) {
			    $branch = $branch->where('merchant_branch.user_id', 'LIKE', '%' . $request->organisation_id . '%');
			}
			$branch = $branch->paginate(50);
		}
		else{
			$branch = DB::table('merchant_branch')
            ->leftjoin('states', 'states.state_id','=','merchant_branch.state_id')
            ->leftJoin('cities', 'cities.city_id', '=', 'merchant_branch.city_id');
			if (!empty($request->get('ID'))) {
			    $branch = $branch->where('merchant_branch.id', 'LIKE', $request->ID );
			}
			if (!empty($request->get('name'))) {
			    $branch = $branch->where('merchant_branch.merchant_name', 'LIKE', '%' . $request->name . '%');
			}
			if (!empty($request->get('organisation_id'))) {
			    $branch = $branch->where('merchant_branch.user_id', 'LIKE', '%' . $request->organisation_id . '%');
			}
			$branch = $branch->paginate(50);
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

        return view("admin.sale_advisors.index",$title)->with('data', $merchantData);
	}

	public function addSaleAdvisor(Request $request, $id){
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

		$admins = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
        $agents = Agent::where('status',1)->get();
		$result['admins'] = $admins;
		$result['agents'] = $agents;
		$result['user_id'] = $id;

		return view("admin.sale_advisors.add",$title)->with('result', $result)->with('allimage',$allimage);
	}

    public function insertSaleAdvisor(Request $request){

		$user_to_address = DB::table('user_to_address')->where('user_id','=', $request->organisation_id)->get();
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

		if ($request->hasFile('profile_img')) {
            $time = Carbon::now();
			$image = $request->file('profile_img');
            $extensions = Setting::imageType();
            if ($image and in_array($request->profile_img->extension(), $extensions)) {
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
				$destinaton_path = public_path('images/sale-advisor/');
				$image->move($destinaton_path,$filename);
            }
		}
		else{
			$filename = "";
		}

		$admins = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->where('users.id','=',$request->organisation_id)
			->get();
		$sa_profile_url = "/sale-advisor/".Str::slug($admins[0]->company_name,"-")."/".Str::slug($request->merchant_name,"-");
		// dd($sa_profile_url);

		$request->adminType= \App\Models\Core\SaleAdvisors::ROLE_SALESADVISOR;

        $saleadvisor_id = DB::table('merchant_branch')->insertGetId([
			'user_id' => $request->organisation_id,
			'merchant_emp_id' => $request->merchant_emp_id,
			'merchant_name' => $request->merchant_name,
			'slug' => Str::slug($request->merchant_name,"-"),
			'merchant_phone_no' => $request->merchant_phone_no,
			'state_id' => $request->state_id,
			'city_id' => $request->city_id,
			'merchant_email' => $request->merchant_email,
			'profile_img' => $request->profile_img,
			'sa_position' => $request->sa_position,
			'whatsapp_url' => $request->whatsapp_url,
			'whatsapp_default_message' => $request->whatsappDefaultMessage,
			'landingpage_version' => $request->landingpage_version,
			'landingpage_images' => $request->landingpage_images,
			'waze_url' => $waze_url,
			'sa_profile_url' => $sa_profile_url,
			'verified' => $request->verified,
			'verified_since' => $request->verified_since,
			'verified_until' => $request->verified_until,
			'address' => $request->address,
			'merchant_postcode' => $request->merchant_postcode,
			'generate_qr' => $request->generate_qr,
			'display_qr' => $request->display_qr,
			'contactMe' => $request->contactMe,
			'showMe' => $request->showMe,
			'askMe' => $request->askMe,
			'keepMe' => $request->keepMe,
			'campaign' => $request->campaign,
			'is_default' => $request->verified,
			'role_id' => $request->adminType,
			'password'	=>   Hash::make($request->password),
			'status' =>  '1',
			'created_at' => date('Y-m-d H:i:s'),
			'agent_id' => $request->agent_id,
			'package' => $request->package,
			'payslip' =>SaleAdvisor::paySlipUpload($request),
		]);

        $message = Lang::get("labels.SaleAdvisorAddedMessage");
		return  redirect('admin/saleAdvisor/*')->with('message', $message);
		// return redirect()->back()->with('message', $message);
    }

	public function editSaleAdvisor(Request $request, $id)
    {
		// dd($id);
		$sale_advisor_id = $id;
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

		$sale_advisor = DB::table('merchant_branch')
            ->leftjoin('states', 'states.state_id','=','merchant_branch.state_id')
            ->leftJoin('cities', 'cities.city_id', '=', 'merchant_branch.city_id')
			->where('merchant_branch.id','=',$sale_advisor_id)
            ->get();
		$result['sale_advisor'] = $sale_advisor;
		// dd($sale_advisor);
		$documents = Documents::all();
		$result['documents'] = $documents;

		$admins = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['admins'] = $admins;
        $agents = Agent::where('status',1)->get();
        $result['agents'] = $agents;
		// charts data
		$chart = DB::table('traffics_sa')
				->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
				->leftJoin('merchant_branch','merchant_branch.id','=','traffics_sa.sa_id')
				->select(
					'traffics.*','merchant_branch.merchant_name as merchant_name', 'merchant_branch.id as merchant_id',
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'visit' THEN 1 END ) ) AS pageview"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type != 'visit' THEN 1 END ) ) AS action"),
					DB::raw("DATE_FORMAT(traffics.created_at, '%M') months"),
					DB::raw("YEAR(traffics.created_at) year, MONTH(traffics.created_at) month, DAY(traffics.created_at) day")
				)
				->where('traffics_sa.sa_id','=',$sale_advisor_id);
				if (!empty($request->get('filterBy'))) {
					if($request->get('filterBy') == 'today'){
						$chartsSubTitle = "by today";
						$today = date('Y-m-d');
						$chart = $chart->whereDate('traffics.created_at', '=', $today);
					}
					if($request->get('filterBy') == 'yesterday'){
						$chartsSubTitle = "by yesterday";
						$yesterday = Carbon::yesterday();
						$chart = $chart->whereDate('traffics.created_at', '=', $yesterday);
					}
					if($request->get('filterBy') == 'thisweek'){
						$chartsSubTitle = "by this week";
						$chart = $chart->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
					}
					if($request->get('filterBy') == 'thismonth'){
						$chartsSubTitle = "by this month";
						$chart = $chart->whereMonth('traffics.created_at', date('m'));
					}
					if($request->get('filterBy') == '7day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 7;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == '30day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 30;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == 'customdate'){
						if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
							$startDate = $request->get('fromDate');
							$endDate = $request->get('toDate');
							$chartsSubTitle = "from ".$startDate." until ".$endDate;
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
				}
				if (empty($request->get('filterBy'))) {
					$chartsSubTitle = "by previous 30 days";
					$chart = $chart->whereBetween('traffics.created_at', [
						Carbon::now()->subdays(30)->format('Y-m-d'),
						Carbon::now()->subday()->format('Y-m-d')
					]);
				}
				$chart = $chart->groupby('year','month','day')->get();

		$chartsLabel = [];
		$chartsPageview = [];
		$chartsCall = [];
		foreach($chart as $charts){
			if($charts->merchant_id != null){
				$label = $charts->day."/".$charts->month."/".$charts->year;
				$chartsLabel[] = $label;

				$pageview = $charts->pageview;
				$chartsPageview[] = $pageview;

				// change to action
				$countCall = $charts->action;
				$chartsCall[] = $countCall;
			}
		}
		$result['chartsSubTitle'] = $chartsSubTitle;
		$result['chartsLabel'] = $chartsLabel;
		$result['chartsPageview'] = $chartsPageview;
		$result['chartsCall'] = $chartsCall;

		// charts table
		$admins_sa = DB::table('traffics_sa')
					->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
					->leftJoin('merchant_branch','merchant_branch.id','=','traffics_sa.sa_id')
					->select(
						'traffics.*','merchant_branch.merchant_name as merchant_name', 'merchant_branch.id as merchant_id',
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'visit' THEN 1 END ) ) AS pageview"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'call' THEN 1 END ) ) AS countCall"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'whatsapp' THEN 1 END ) ) AS whatsapp"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'waze' THEN 1 END ) ) AS waze"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'brochure' THEN 1 END ) ) AS brochure"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'pricelist' THEN 1 END ) ) AS pricelist"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'promotion' THEN 1 END ) ) AS promotion"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'redemption' THEN 1 END ) ) AS redemption"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type != 'visit' THEN 1 END ) ) AS action")
					)
					->where('traffics_sa.sa_id','=',$sale_advisor_id);

		if (!empty($request->get('ID'))) {
			$admins_sa = $admins_sa->where('merchant_branch.id', 'LIKE', $request->ID );
		}
		if (!empty($request->get('name'))) {
			$admins_sa = $admins_sa->where('merchant_branch.merchant_name', 'LIKE', '%' . $request->name . '%');
		}
		if (!empty($request->get('organisation_id'))) {
			$admins_sa = $admins_sa->where('merchant_branch.user_id', 'LIKE', '%' . $request->organisation_id . '%');
		}
		if (!empty($request->get('filterBy'))) {
            if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$admins_sa = $admins_sa->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$admins_sa = $admins_sa->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$admins_sa = $admins_sa->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$admins_sa = $admins_sa->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins_sa = $admins_sa->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins_sa = $admins_sa->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$admins_sa = $admins_sa->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
        }
		else{
			$admins_sa = $admins_sa->whereMonth('traffics.created_at', date('m'));
		}

		$admins_sa = $admins_sa->get();
		$result['admins_sa'] = $admins_sa;

		return view("admin.sale_advisors.edit",$title)->with('result', $result)->with('allimage',$allimage);
	}

    public function updateSaleAdvisor(Request $request){
		// dd($request->all());
		$saleAdvisor_id    =   $request->saleAdvisor_id;
		// dd($saleAdvisor_id);
		if ($request->hasFile('profile_img')) {
            $time = Carbon::now();
			$image = $request->file('profile_img');
            $extensions = Setting::imageType();
            if ($image and in_array($request->profile_img->extension(), $extensions)) {
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
				$destinaton_path = public_path('images/sale-advisor/');
				$image->move($destinaton_path,$filename);
            }
		}
		$request->adminType= \App\Models\Core\SaleAdvisors::ROLE_SALESADVISOR;
		DB::table('merchant_branch')->where('id','=', $saleAdvisor_id)->update([
            'user_id' => $request->organisation_id,
			'merchant_emp_id' => $request->merchant_emp_id,
			'merchant_name' => $request->merchant_name,
			'slug' => Str::slug($request->merchant_name,"-"),
			'merchant_phone_no' => $request->merchant_phone_no,
			'state_id' => $request->state_id,
			'city_id' => $request->city_id,
			'merchant_email' => $request->merchant_email,
			'profile_img' => $request->profile_img,
			'sa_position' => $request->sa_position,
			'whatsapp_url' => $request->whatsapp_url,
			'whatsapp_default_message' => $request->whatsappDefaultMessage,
			'landingpage_version' => $request->landingpage_version,
			'landingpage_images' => $request->landingpage_images,
			// 'waze_url' => $request->waze_url,
			'sa_profile_url' => $request->sa_profile_url,
			'verified' => $request->verified,
			'verified_since' => $request->verified_since,
			'verified_until' => $request->verified_until,
			'address' => $request->address,
			'merchant_postcode' => $request->merchant_postcode,
			'generate_qr' => $request->generate_qr,
			'display_qr' => $request->display_qr,
			'contactMe' => $request->contactMe,
			'showMe' => $request->showMe,
			'askMe' => $request->askMe,
			'keepMe' => $request->keepMe,
			'campaign' => $request->campaign,
			'created_at' => date('Y-m-d H:i:s'),
			'is_default' => $request->verified,
			'role_id' => $request->adminType,
			'updated_at'=> date('Y-m-d H:i:s'),
            'agent_id' => $request->agent_id,
            'package' => $request->package,
            'payslip' =>SaleAdvisor::paySlipUpload($request,$saleAdvisor_id),
        ]);

		if($request->changePassword == 'yes'){
			$admin_data['password'] = Hash::make($request->password);
			$customers_id = DB::table('merchant_branch')->where('id', '=', $saleAdvisor_id)->update($admin_data);
		}

        $message = Lang::get("labels.SaleAdvisorEditMessage");
        return redirect()->back()->with('message', $message);
    }

	// public function deleteSaleAdvisor(Request $request){
    //     $myid = $request->users_id;
    //     DB::table('segments')->where('segment_id','=', $myid)->delete();
    //     return redirect()->back()->withErrors([Lang::get("labels.DeleteSegmentMessage")]);
    // }

	public function filter(Request $request){
		$name = $request->FilterBy;
		$param = $request->parameter;
		$title = array('pageTitle' => Lang::get("labels.Segment"));
		$segment = $this->segment->filter($name,$param);
		return view("admin.segment.index",$title)->with('segment', $segment)->with('name',$name)->with('param',$param);
	}

	public function login(){

		if (Auth::guard('saleadvisor')->check()) {
		  return redirect('/admin/sale_advisors/dashboard');
		}else{
			$title = array('pageTitle' => Lang::get("labels.login_page_name"));
			return view("admin.sa_login",$title);
		}
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
			return redirect('sale_advisor/login')->withErrors($validator)->withInput();
		}else{
			//check authentication of email and password
			$adminInfo = array("merchant_email" => $request->email, "password" => $request->password);

			if(Auth::guard('saleadvisor')->attempt($adminInfo)) {
				$admin = Auth::guard('saleadvisor')->user();

				$roleType= \App\Models\Core\SaleAdvisors::ROLE_SALESADVISOR;
				if ($admin->role_id == $roleType && !$admin->status)
				{
					Auth::logout();
					return redirect('sale_advisor/login')->with('loginError',Lang::get("You need to confirm your account. We have sent you an activation code, please check your email."));
				}
				else
                {

                    if( $admin->role_id == \App\Models\Core\User::ROLE_CUSTOMER )
                    {
                        Auth::logout();
                        return redirect('sale_advisor/login')->with('loginError',Lang::get("labels.EmailPasswordIncorrectText"));
                    }

					$administrators = DB::table('merchant_branch')->where('id', $admin->id)->get();

					$categories_id = '';
					//admin category role
					if(Auth::guard('saleadvisor')->user()->adminType != '1'){
					    $userId = $admin->id;
				        $update_time_count = DB::table('merchant_branch')->where('id', $userId)->first();
						DB::table('merchant_branch')->where('id', $userId)->update([
							'login_counter'=>$update_time_count->login_counter+1,
							'last_login_at' => Carbon::now()->toDateTimeString()
							]);

						$categories_role = DB::table('categories_role')->where('admin_id', $userId)->get();
						if(!empty($categories_role) and count($categories_role)>0){
							$categories_id = $categories_role[0]->categories_ids;
						}else{
							$categories_id = '';
						}
					}
					// dd(redirect()->intended('/admin/sale_advisors/dashboard'));
					session(['categories_id' => $categories_id]);
					return redirect()->intended('/admin/sale_advisors/dashboard')->with('administrators', $administrators);
				}
			}else{
				return redirect('sale_advisor/login')->with('loginError',Lang::get("labels.EmailPasswordIncorrectText"));
			}
		}
	}

	//logout
	public function logout(){
		Auth::guard('saleadvisor')->logout();
		return redirect()->intended('sale_advisor/login');
	}

	public function dashboard(){
		// dd(Auth::guard('saleadvisor')->user());
		return view("admin.sale_advisors.dashboard");
	}

	// sa campaign start ---------------------------------------------------
	public function campaigns(Request $request){
		$title = array('pageTitle' => Lang::get("labels.ListingBanks"));
		$language_id = '1';

		$result = array();
		$message = array();
		$errorMessage = array();

        $segments = Segments::all();
		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;

		$campaign = DB::table('campaigns');

		if(Auth::guard('saleadvisor')) {
			$admin = Auth::guard('saleadvisor')->user();
			$roleType= \App\Models\Core\SaleAdvisors::ROLE_SALESADVISOR;
			if ($admin->role_id == $roleType){
				$sa_id = $admin->id;
				$campaign = $campaign->where('campaigns.sa_id','=', $sa_id);
			}
		}

		// $campaign = $campaign->select('*');
		if (!empty($request->get('filter_name'))) {
			$campaign = $campaign->where('campaigns.campaign_name', 'LIKE', '%' . $request->filter_name . '%');
		}
		if (!empty($request->get('start_date'))) {
			$campaign = $campaign->where('campaigns.period_start', '>=', $request->start_date);
		}
		if (!empty($request->get('end_date'))) {
			$campaign = $campaign->where('campaigns.period_end', '<=', $request->end_date);
		}
		if (!empty($request->get('organisation_id'))) {
			$campaign = $campaign->where('campaigns.org_id', '=',  $request->organisation_id );
		}
		if (!empty($request->get('status_id'))) {
			$campaign = $campaign->where('campaigns.status', '=',  $request->status_id );
		}
		else if($request->get('status_id') == '0'){
			$campaign = $campaign->where('campaigns.status', '=',  '0' );
		}
		else{

		}
		$campaign = $campaign->paginate(30);

		$result['organisation'] = $organisation;
		$result['admins'] = $campaign;
		$result['segments'] = $segments;

        return view("admin.sale_advisors.campaign.index",$title)->with('result', $result);
	}

	public function addcampaigns(Request $request){
		$title = array('pageTitle' => Lang::get("labels.add_campaign"));

		$result = array();
		$message = array();
		$errorMessage = array();

		$adminTypes = DB::table('user_types')->where('isActive', 1)->where('user_types_id','>','10')->get();
		$result['adminTypes'] = $adminTypes;

		$segments = Segments::all();
		$result['segments'] = $segments;

		$organisations = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['organisations'] = $organisations;

		return view("admin.sale_advisors.campaign.add",$title)->with('result', $result);
	}

	public function insertcampaigns(Request $request){
		$time = Carbon::now();
		if(Auth::guard('saleadvisor')) {
			$admin = Auth::guard('saleadvisor')->user();
			$roleType= \App\Models\Core\SaleAdvisors::ROLE_SALESADVISOR;
			if ($admin->role_id == $roleType){
				$sa_id = $admin->id;
			}
			else{
				$sa_id = null;
			}
		}
		else{
			$sa_id = null;
		}

		// insert new promotion
        $campaign_id = DB::table('campaigns')->insertGetId([
			'campaign_name' => $request->campaign_name,
			'campaign_image' => $request->campaign_image,
			'org_id' => $request->organization_id,
			'period_start' => $request->start_date . ' ' . $request->start_time,
			'period_end' => $request->end_date . ' ' . $request->end_time,
			'description' => $request->description,
			'status' => $request->status_id,
			'sa_id' => $sa_id,
			'created_at' => date('Y-m-d H:i:s')
		]);

        $message = Lang::get("labels.CampaignAddedMessage");
		return redirect()->back()->with('message', $message);
    }

	public function editcampaigns(Request $request){
		$title = array('pageTitle' => Lang::get("labels.add_campaign"));
		$myid = $request->id;

		$result = array();
		$message = array();
		$errorMessage = array();

		$adminTypes = DB::table('user_types')->where('isActive', 1)->where('user_types_id','>','10')->get();
		$result['adminTypes'] = $adminTypes;

		$result['myid'] = $myid;

		$admins = DB::table('campaigns')->where('campaign_id','=', $myid)->get();
		// dd($admins);
		$result['admins'] = $admins;

		$segments = Segments::all();
		$result['segments'] = $segments;

		$organisations = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['organisations'] = $organisations;

		return view("admin.sale_advisors.campaign.edit",$title)->with('result', $result);
	}

	public function updatecampaigns(Request $request){
		$currentUser = Auth()->user()->id;
		$campaign_id    =   $request->myid;
		$time = Carbon::now();

		DB::table('campaigns')->where('campaign_id','=', $campaign_id)->update([
			'campaign_name' => $request->campaign_name,
			'campaign_image' => $request->campaign_image,
			'period_start' => $request->period_start . ' ' . $request->start_time,
			'period_end' => $request->period_end . ' ' . $request->end_time,
			'org_id' => $request->organization_id,
			'description' => $request->description,
			'status' => $request->status_id,
			'updated_at' => date('Y-m-d H:i:s')
		]);

        $message = Lang::get("labels.CampaignEditMessage");
		return redirect()->back()->with('message', $message);
	}

	public function deletecampaigns(Request $request){
        $campaign_id  =  $request->users_id;

        DB::table('campaigns')->where('campaign_id','=', $campaign_id)->delete();
        return redirect()->back()->withErrors([Lang::get("labels.DeleteCampaignMessage")]);
    }
	// sa campaign end ---------------------------------------------------

	// sa campaign report
	public function campaignreport(Request $request){

		$title = array('pageTitle' => Lang::get("labels.titleCampaignReport"));
		$language_id = '1';
		$images = new Images;
		$allimage = $images->getimages();
		$result = array();
		$message = array();
		$errorMessage = array();

		// GET TOTAL ACTIVE CAMPAIGN
		$total_campaigns = DB::table('campaigns')->leftJoin('campaign_status_by_level','campaign_status_by_level.campaign_id','=','campaigns.campaign_id');
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$parentOrg = array();
			array_push($parentOrg, Auth()->user()->id);
			if(Auth()->user()->parent_id != null){
				$parent1 = DB::table('users')->where('users.id','=', Auth()->user()->parent_id)->first();
				if($parent1->id != null){
					array_push($parentOrg, $parent1->id);
					if($parent1->parent_id != null){
						$parent2 = DB::table('users')->where('users.id','=', $parent1->parent_id)->first();
						if($parent2->id != null){
							array_push($parentOrg, $parent2->id);
						}
					}
				}
			}
			if(count($parentOrg) > 0){
				$total_campaigns = $total_campaigns->whereIn('campaigns.org_id' ,$parentOrg);
				$total_campaigns = $total_campaigns->whereIn('campaign_status_by_level.organisation_id' ,$parentOrg)->where('campaign_status_by_level.status', '=', '1');
			}
		}
		$total_campaigns = DB::table('campaigns')->leftJoin('campaign_status_by_level','campaign_status_by_level.campaign_id','=','campaigns.campaign_id');
		if(Auth::guard('saleadvisor')){
			$parentOrg = array();
			// get sa id
			$saID = Auth::guard('saleadvisor')->user()->id;
			// get org id for this sa
			$orgID = DB::table('users')->where('users.id','=',Auth::guard('saleadvisor')->user()->user_id)->get();
			if(count($orgID) > 0){
				array_push($parentOrg, $orgID[0]->id);
				if($orgID[0]->parent_id != null){
					$parent1 = DB::table('users')->where('users.id','=', $orgID[0]->parent_id)->first();
					if($parent1->id != null){
						array_push($parentOrg, $parent1->id);
						if($parent1->parent_id != null){
							$parent2 = DB::table('users')->where('users.id','=', $parent1->parent_id)->first();
							if($parent2->id != null){
								array_push($parentOrg, $parent2->id);
							}
						}
					}
				}
			}
			if(count($parentOrg) > 0){
				$total_campaigns = $total_campaigns->whereIn('campaigns.org_id' ,$parentOrg);
				$total_campaigns = $total_campaigns->whereIn('campaign_status_by_level.organisation_id' ,$parentOrg)->where('campaign_status_by_level.status', '=', '1');
			}
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$total_campaigns = $total_campaigns->whereDate('campaigns.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$total_campaigns = $total_campaigns->whereDate('campaigns.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$total_campaigns = $total_campaigns->whereBetween('campaigns.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$total_campaigns = $total_campaigns->whereMonth('campaigns.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$total_campaigns = $total_campaigns->whereBetween('campaigns.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$total_campaigns = $total_campaigns->whereBetween('campaigns.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$total_campaigns = $total_campaigns->whereBetween('campaigns.created_at', [$startDate, $endDate]);
				}
			}
		}
		$total_campaigns = $total_campaigns->groupBy('campaigns.campaign_id');
		$total_campaigns = $total_campaigns->get();
		$result['total_campaigns'] = $total_campaigns;

		// campaign tracker
		$top_campaigns = DB::table('traffics_campaign')
					->leftJoin('traffics','traffics.id','=','traffics_campaign.traffic_id')
					->leftJoin('campaigns','campaigns.campaign_id','=','traffics_campaign.campaign_id')
					->leftJoin('users','users.id','=','traffics_campaign.org_id')
					->leftJoin('merchant_branch','merchant_branch.id','=','traffics_campaign.sa_id');

		// filter for all
		if(Auth::guard('saleadvisor')){
			$childOrg = array();
			$sa_id = Auth::guard('saleadvisor')->user()->id;
			$org_id = Auth::guard('saleadvisor')->user()->user_id;
			// main parent
			$mainOrg = DB::table('users')->where('users.id','=', $org_id)->first();
			if($mainOrg->id != null){
				array_push($childOrg, $mainOrg->id);
				// check main org got parent
				if($mainOrg->parent_id != null){
					$parentOrg = DB::table('users')->where('users.id','=', $mainOrg->parent_id)->first();
					if($parentOrg->id != null){
						array_push($childOrg, $parentOrg->id);
						if($parentOrg->parent_id != null){
							$parentOrg2 = DB::table('users')->where('users.id','=', $parentOrg->parent_id)->first();
							if($parentOrg2->id != null){
								array_push($childOrg, $parentOrg2->id);
							}
						}
					}
				}
			}
			// get id based on selected
			if(count($childOrg) > 0){
				// $top_campaigns = $top_campaigns->whereIn('traffics_campaign.org_id' ,$childOrg);
				$top_campaigns = $top_campaigns->where('traffics_campaign.sa_id' ,$sa_id);
			}
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$top_campaigns = $top_campaigns->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$top_campaigns = $top_campaigns->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$top_campaigns = $top_campaigns->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
		}

		// top 5 campaign
		$result['top_campaigns'] = (Clone $top_campaigns)
						->select(
							'traffics.*','campaigns.*','campaigns.campaign_id as campaign_ids', 'campaigns.campaign_name as campaign_name',
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response")
						)
						->where('campaigns.campaign_name','!=',null)
						->groupby('traffics_campaign.campaign_id')
						->orderBy('share','DESC')->orderBy('click', 'DESC')->limit(5)->get();

		// total share
		$result['total_share'] = (Clone $top_campaigns)
						->where('traffics.event_type','=','campaign_share')
						->where('campaigns.campaign_name','!=',null)
						->count();

		// total click/open
		$result['total_click'] = (Clone $top_campaigns)
						->where('traffics.event_type','=','campaign_click')
						->where('campaigns.campaign_name','!=',null)
						->count();

		// total interested
		$result['total_interest'] = (Clone $top_campaigns)
						->where('traffics.event_type','=','campaign_interest')
						->where('campaigns.campaign_name','!=',null)
						->count();

		// total response
		$result['total_response'] = (Clone $top_campaigns)
						->where('traffics.event_type','=','campaign_response')
						->where('campaigns.campaign_name','!=',null)
						->count();

		// determine login organisation role
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$check_role = Auth()->user()->id;
			// check hq
			$checkHq = DB::table('users')->where('users.id','=', $check_role)->select('users.parent_id')->first();
			if($checkHq->parent_id != null){
				$isHq = '0'; // not hq
			}
			else{
				$isHq = '1'; //is hq
			}
			// check region
			$checkRegion = DB::table('users')->where('users.id','=', $check_role)->select('users.parent_id')->first();
			$allUsersRegion = DB::table('users')->where('users.parent_id','=', $check_role)->get();
			if($checkRegion->parent_id != null && count($allUsersRegion) == 0){
				$isRegion = '0'; // not region
			}
			else{
				$isRegion = '1'; //is region
			}
			// check branch manager
			$checkBranchManager = DB::table('users')->where('users.id','=', $check_role)->select('users.parent_id')->first();
			$allUsersBranchManager = DB::table('users')->where('users.parent_id','=', $check_role)->get();
			if($checkBranchManager->parent_id != null && count($allUsersBranchManager) != 0){
				$isBranchManager = '0'; // not branch manager
			}
			else{
				$isBranchManager = '1'; //is branch manager
			}
		}
		else{
			$isHq = '';
			$isRegion = '';
			$isBranchManager = '';
		}
		$result['isHq'] = $isHq;
		$result['isRegion'] = $isRegion;
		$result['isBranchManager'] = $isBranchManager;

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
        $user = SaleAdvisor::where('id',Auth::guard('saleadvisor')->user()->id)->with('user')->first();

		return view("admin.sale_advisors.report.campaign",$title)->with('result', $result)->with('allimage',$allimage)
            ->with('user',$user);
	}

	public function campaignfullreport(Request $request){
		$title = array('pageTitle' => Lang::get("labels.titleCampaignReport"));
		$language_id = '1';
		$images = new Images;
		$allimage = $images->getimages();
		$result = array();
		$message = array();
		$errorMessage = array();
		$org_list = null;

		// get total campaign
		$total_campaigns = DB::table('campaigns')->count();
		$result['total_campaigns'] = $total_campaigns;
		// get total organisation
		$total_organisations = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->count();
		$result['total_organisations'] = $total_organisations;
		// get total sa
		$total_sa = DB::table('merchant_branch')->count();
		$result['total_sa'] = $total_sa;

		// campaign tracker
		$top_campaigns = DB::table('traffics_campaign')
					->leftJoin('traffics','traffics.id','=','traffics_campaign.traffic_id')
					->leftJoin('campaigns','campaigns.campaign_id','=','traffics_campaign.campaign_id')
					->leftJoin('users','users.id','=','traffics_campaign.org_id')
					->leftJoin('merchant_branch','merchant_branch.id','=','traffics_campaign.sa_id');

		// filter for all
		if(Auth::guard('saleadvisor')){
			$childOrg = array();
			$sa_id = Auth::guard('saleadvisor')->user()->id;
			$org_id = Auth::guard('saleadvisor')->user()->user_id;
			// main parent
			$mainOrg = DB::table('users')->where('users.id','=', $org_id)->first();
			if($mainOrg->id != null){
				array_push($childOrg, $mainOrg->id);
				// check main org got parent
				if($mainOrg->parent_id != null){
					$parentOrg = DB::table('users')->where('users.id','=', $mainOrg->parent_id)->first();
					if($parentOrg->id != null){
						array_push($childOrg, $parentOrg->id);
						if($parentOrg->parent_id != null){
							$parentOrg2 = DB::table('users')->where('users.id','=', $parentOrg->parent_id)->first();
							if($parentOrg2->id != null){
								array_push($childOrg, $parentOrg2->id);
							}
						}
					}
				}
			}
			// get id based on selected
			if(count($childOrg) > 0){
				// $top_campaigns = $top_campaigns->whereIn('traffics_campaign.org_id' ,$childOrg);
				$top_campaigns = $top_campaigns->where('traffics_campaign.sa_id' ,$sa_id);
			}
		}
		if (!empty($request->get('organisation_id'))) {
			$top_campaigns = $top_campaigns->where('traffics_campaign.org_id', '=',  $request->organisation_id );
		}
		if (!empty($request->get('sa_id'))) {
			$top_campaigns = $top_campaigns->where('traffics_campaign.sa_id', '=',  $request->sa_id );
		}
		if (!empty($request->get('campaign_id'))) {
			$top_campaigns = $top_campaigns->where('traffics_campaign.campaign_id', '=',  $request->campaign_id );
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$top_campaigns = $top_campaigns->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$top_campaigns = $top_campaigns->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$top_campaigns = $top_campaigns->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
		}

		// all campaign
		$result['top_campaigns'] = (Clone $top_campaigns)
						->select(
							'traffics.*','campaigns.*','campaigns.campaign_id as campaign_ids', 'campaigns.campaign_name as campaign_name','users.company_name', 'merchant_branch.merchant_name',
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response")
						)
						->where('campaigns.campaign_name','!=',null)
						->groupby('traffics_campaign.campaign_id')
						->orderBy('share','DESC')->paginate(15);
						// dd($result['top_campaigns']);

		// chart data for campaign full report ----------------------------------
		$chart = DB::table('traffics_campaign')
				->leftJoin('traffics','traffics.id','=','traffics_campaign.traffic_id')
				->leftJoin('campaigns','campaigns.campaign_id','=','traffics_campaign.campaign_id')
				->select(
					'traffics.*','campaigns.campaign_id as campaign_ids', 'campaigns.campaign_name as campaign_name',
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response"),
					DB::raw("DATE_FORMAT(traffics.created_at, '%M') months"),
					DB::raw("YEAR(traffics.created_at) year, MONTH(traffics.created_at) month, DAY(traffics.created_at) day")
				);
		// filter for all
		if(Auth::guard('saleadvisor')){
			$childOrg = array();
			$sa_id = Auth::guard('saleadvisor')->user()->id;
			$org_id = Auth::guard('saleadvisor')->user()->user_id;
			// main parent
			$mainOrg = DB::table('users')->where('users.id','=', $org_id)->first();
			if($mainOrg->id != null){
				array_push($childOrg, $mainOrg->id);
				// check main org got parent
				if($mainOrg->parent_id != null){
					$parentOrg = DB::table('users')->where('users.id','=', $mainOrg->parent_id)->first();
					if($parentOrg->id != null){
						array_push($childOrg, $parentOrg->id);
						if($parentOrg->parent_id != null){
							$parentOrg2 = DB::table('users')->where('users.id','=', $parentOrg->parent_id)->first();
							if($parentOrg2->id != null){
								array_push($childOrg, $parentOrg2->id);
							}
						}
					}
				}
			}
			// get id based on selected
			if(count($childOrg) > 0){
				// $top_campaigns = $top_campaigns->whereIn('traffics_campaign.org_id' ,$childOrg);
				$chart = $chart->where('traffics_campaign.sa_id' ,$sa_id);
			}
		}
		if (!empty($request->get('organisation_id'))) {
			$chart = $chart->where('traffics_campaign.org_id', '=',  $request->organisation_id );
		}
		if (!empty($request->get('campaign_id'))) {
			$chart = $chart->where('traffics_campaign.campaign_id', '=',  $request->campaign_id );
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$chart = $chart->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$chart = $chart->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$chart = $chart->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$chart = $chart->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
		}
		$chart = $chart->groupby('year','month','day')->get();
		// dd($chart);
		$chartsLabel = [];
		$chartsPageview = [];
		$chartsShare = [];
		$chartsInterest = [];
		$chartsResponse = [];
		foreach($chart as $charts){
			if($charts->campaign_ids != null){
				$label = $charts->day."/".$charts->month."/".$charts->year;
				$chartsLabel[] = $label;
				// click/open
				$pageview = $charts->click;
				$chartsPageview[] = $pageview;
				// share
				$countShare = $charts->share;
				$chartsShare[] = $countShare;
				// interest
				$countInterest = $charts->interest;
				$chartsInterest[] = $countInterest;
				// response
				$countResponse = $charts->response;
				$chartsResponse[] = $countResponse;
			}
		}
		$result['chartsSubTitle'] = 'subtitle';
		$result['chartsLabel'] = $chartsLabel;
		$result['chartsPageview'] = $chartsPageview;
		$result['chartsShare'] = $chartsShare;
		$result['chartsInterest'] = $chartsInterest;
		$result['chartsResponse'] = $chartsResponse;
		// end chart data

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;

		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['organisation'] = $organisation;

		$campaignn = DB::table('campaigns')->where('status','=','1')->get();
		$result['campaignn'] = $campaignn;
		$result['org_list'] = $org_list;

		return view("admin.sale_advisors.report.campaign_report",$title)->with('result', $result)->with('allimage',$allimage);
	}

	public function campaignsresponse(Request $request){
		$title = array('pageTitle' => Lang::get("labels.titleCampaignResponse"));
		$language_id = '1';
		$result = array();
		$message = array();
		$errorMessage = array();
		$org_list = null;

		$campaign_response = DB::table('campaigns_response')
							->leftJoin('campaigns','campaigns.campaign_id','=','campaigns_response.campaign_id')
							->leftJoin('users','users.id','=','campaigns_response.org_id')
							->leftJoin('merchant_branch','merchant_branch.id','=','campaigns_response.sa_id')
							->select('campaigns_response.*','campaigns.campaign_name as campaign_name','campaigns.status as status','users.company_name as org_name','merchant_branch.merchant_name as sa_name');

		if(Auth::guard('saleadvisor')){
			$childOrg = array();
			$sa_id = Auth::guard('saleadvisor')->user()->id;
			$org_id = Auth::guard('saleadvisor')->user()->user_id;
			// main parent
			$mainOrg = DB::table('users')->where('users.id','=', $org_id)->first();
			if($mainOrg->id != null){
				array_push($childOrg, $mainOrg->id);
				// check main org got parent
				if($mainOrg->parent_id != null){
					$parentOrg = DB::table('users')->where('users.id','=', $mainOrg->parent_id)->first();
					if($parentOrg->id != null){
						array_push($childOrg, $parentOrg->id);
						if($parentOrg->parent_id != null){
							$parentOrg2 = DB::table('users')->where('users.id','=', $parentOrg->parent_id)->first();
							if($parentOrg2->id != null){
								array_push($childOrg, $parentOrg2->id);
							}
						}
					}
				}
			}
			// get id based on selected
			if(count($childOrg) > 0){
				// $top_campaigns = $top_campaigns->whereIn('traffics_campaign.org_id' ,$childOrg);
				$campaign_response = $campaign_response->where('campaigns_response.sa_id' ,$sa_id);
			}
		}

		// filter for all
		if (!empty($request->get('organisation_id'))) {
			$campaign_response = $campaign_response->where('campaigns_response.org_id', '=',  $request->organisation_id );
		}
		if (!empty($request->get('campaign_id'))) {
			$campaign_response = $campaign_response->where('campaigns_response.campaign_id', '=',  $request->campaign_id );
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$campaign_response = $campaign_response->whereDate('campaigns_response.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$campaign_response = $campaign_response->whereDate('campaigns_response.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$campaign_response = $campaign_response->whereBetween('campaigns_response.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$campaign_response = $campaign_response->whereMonth('campaigns_response.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$campaign_response = $campaign_response->whereBetween('campaigns_response.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$campaign_response = $campaign_response->whereBetween('campaigns_response.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$campaign_response = $campaign_response->whereBetween('campaigns_response.created_at', [$startDate, $endDate]);
				}
			}
		}
		$campaign_response = $campaign_response->where('campaigns.campaign_name','!=',null);
		$campaign_response = $campaign_response->orderBy('campaigns_response.id', 'desc')->paginate(15);
		$result['campaign_response'] = $campaign_response;

		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['organisation'] = $organisation;

		$campaignn = DB::table('campaigns')->where('status','=','1')->get();
		$result['campaignn'] = $campaignn;
		$result['org_list'] = $org_list;

		return view("admin.sale_advisors.report.campaign_response",$title)->with('result', $result);
	}

	public function salesreport(Request $request){
		$title = array('pageTitle' => Lang::get("labels.titleOrgReport"));
		$language_id = '1';
		$images = new Images;
		$allimage = $images->getimages();
		$result = array();
		$message = array();
		$errorMessage = array();
		$org_list = null;

		$admins = DB::table('traffics_sa')
					->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
					// ->leftJoin('traffics_sa','traffics_sa.traffic_id','=','traffics.id')
					->leftJoin('merchant_branch','merchant_branch.id','=','traffics_sa.sa_id')
					->select(
						'traffics.*','merchant_branch.merchant_name as merchant_name', 'merchant_branch.id as merchant_id',
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'visit' THEN 1 END ) ) AS pageview"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'call' THEN 1 END ) ) AS countCall"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'whatsapp' THEN 1 END ) ) AS whatsapp"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'waze' THEN 1 END ) ) AS waze"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'brochure' THEN 1 END ) ) AS brochure"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'pricelist' THEN 1 END ) ) AS pricelist"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'promotion' THEN 1 END ) ) AS promotion"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'redemption' THEN 1 END ) ) AS redemption"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type != 'visit' THEN 1 END ) ) AS action")
					);

		if(Auth::guard('saleadvisor')){
			$childOrg = array();
			$sa_id = Auth::guard('saleadvisor')->user()->id;
			$org_id = Auth::guard('saleadvisor')->user()->user_id;
			array_push($childOrg, $sa_id);
			// get id based on selected
			if(count($childOrg) > 0){
				$org_list = $childOrg;
				$admins = $admins->whereIn('merchant_branch.id' ,$childOrg);
			}
		}
		if (!empty($request->get('ID'))) {
			$admins = $admins->where('merchant_branch.id', 'LIKE', $request->ID );
		}
		if (!empty($request->get('name'))) {
			$admins = $admins->where('merchant_branch.merchant_name', 'LIKE', '%' . $request->name . '%');
		}
		if (!empty($request->get('organisation_id'))) {
			$admins = $admins->where('merchant_branch.user_id', 'LIKE', '%' . $request->organisation_id . '%');
		}
		if (!empty($request->get('filterBy'))) {
            if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$admins = $admins->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$admins = $admins->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$admins = $admins->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$admins = $admins->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
        }
		else{
			$admins = $admins->whereBetween('traffics.created_at', [
				Carbon::now()->subdays(30)->format('Y-m-d'),
				Carbon::now()->format('Y-m-d')
			]);
		}

		$admins = $admins->groupby('traffics_sa.sa_id')->orderBy('pageview','DESC')->paginate(20);

		$total_pageview1 = DB::table('traffics_sa')
							->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
							->where('traffics.event_type', '=' , 'visit')
							->count();
		$total_pageview = $total_pageview1;

		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['organisation'] = $organisation;

		// charts data
		$chart = DB::table('traffics_sa')
				->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
				->leftJoin('merchant_branch','merchant_branch.id','=','traffics_sa.sa_id')
				->select(
					'traffics.*','merchant_branch.merchant_name as merchant_name', 'merchant_branch.id as merchant_id',
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'visit' THEN 1 END ) ) AS pageview"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type != 'visit' THEN 1 END ) ) AS action"),
					DB::raw("DATE_FORMAT(traffics.created_at, '%M') months"),
					DB::raw("YEAR(traffics.created_at) year, MONTH(traffics.created_at) month, DAY(traffics.created_at) day")
				);
				if(Auth::guard('saleadvisor')){
					$childOrg = array();
					$sa_id = Auth::guard('saleadvisor')->user()->id;
					$org_id = Auth::guard('saleadvisor')->user()->user_id;
					array_push($childOrg, $sa_id);
					// get id based on selected
					if(count($childOrg) > 0){
						$org_list = $childOrg;
						$chart = $chart->whereIn('merchant_branch.id' ,$childOrg);
					}
				}
				if (!empty($request->get('ID'))) {
					$chart = $chart->where('merchant_branch.id', 'LIKE', $request->ID );
				}
				if (!empty($request->get('name'))) {
					$chart = $chart->where('merchant_branch.merchant_name', 'LIKE', '%' . $request->name . '%');
				}
				if (!empty($request->get('organisation_id'))) {
					$chart = $chart->where('merchant_branch.user_id', 'LIKE', '%' . $request->organisation_id . '%');
				}
				if (!empty($request->get('filterBy'))) {
					if($request->get('filterBy') == 'today'){
						$chartsSubTitle = "by today";
						$today = date('Y-m-d');
						$chart = $chart->whereDate('traffics.created_at', '=', $today);
					}
					if($request->get('filterBy') == 'yesterday'){
						$chartsSubTitle = "by yesterday";
						$yesterday = Carbon::yesterday();
						$chart = $chart->whereDate('traffics.created_at', '=', $yesterday);
					}
					if($request->get('filterBy') == 'thisweek'){
						$chartsSubTitle = "by this week";
						$chart = $chart->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
					}
					if($request->get('filterBy') == 'thismonth'){
						$chartsSubTitle = "by this month";
						$chart = $chart->whereMonth('traffics.created_at', date('m'));
					}
					if($request->get('filterBy') == '7day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 7;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == '30day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 30;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == 'customdate'){
						if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
							$startDate = $request->get('fromDate');
							$endDate = $request->get('toDate');
							$chartsSubTitle = "from ".$startDate." until ".$endDate;
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
				}
				if (empty($request->get('filterBy'))) {
					$chartsSubTitle = "by previous 30 days";
					$chart = $chart->whereBetween('traffics.created_at', [
						Carbon::now()->subdays(30)->format('Y-m-d'),
						Carbon::now()->format('Y-m-d')
					]);
				}
				$chart = $chart->groupby('year','month','day')->get();

		$chartsLabel = [];
		$chartsPageview = [];
		$chartsCall = [];
		foreach($chart as $charts){
			if($charts->merchant_id != null){
				$label = $charts->day."/".$charts->month."/".$charts->year;
				$chartsLabel[] = $label;

				$pageview = $charts->pageview;
				$chartsPageview[] = $pageview;

				// change to action
				$countCall = $charts->action;
				$chartsCall[] = $countCall;
			}
		}
		$result['chartsSubTitle'] = $chartsSubTitle;
		$result['chartsLabel'] = $chartsLabel;
		$result['chartsPageview'] = $chartsPageview;
		$result['chartsCall'] = $chartsCall;

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		$result['admins'] = $admins;
		$result['total_pageview'] = $total_pageview;

		$result['org_list'] = $org_list;

		return view("admin.sale_advisors.report.sales",$title)->with('result', $result)->with('allimage',$allimage);
	}

	public function itemreport(Request $request){
	    // ini_set('memory_limit', '999M');
		$title = array('pageTitle' => Lang::get("labels.titleOrgReport"));
		$language_id = '1';
		$images = new Images;
		$allimage = $images->getimages();
		$result = array();
		$message = array();
		$errorMessage = array();
		$org_list = null;

		$admins = DB::table('traffics_item')
					->leftJoin('traffics','traffics.id','=','traffics_item.traffic_id')
					// ->leftJoin('traffics_item','traffics_item.traffic_id','=','traffics.id')
					->leftJoin('cars','cars.car_id','=','traffics_item.item_id')
					->select(
						'traffics.*','cars.car_id as item_id', 'cars.title as item_title',
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'visit' THEN 1 END ) ) AS pageview"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'call' THEN 1 END ) ) AS countCall"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'whatsapp' THEN 1 END ) ) AS whatsapp"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'waze' THEN 1 END ) ) AS waze")
					);

		if(Auth::guard('saleadvisor')){
			$childOrg = array();
			$sa_id = Auth::guard('saleadvisor')->user()->id;
			$org_id = Auth::guard('saleadvisor')->user()->user_id;
			array_push($childOrg, $org_id);
			// get id based on selected
			if(count($childOrg) > 0){
				$org_list = $childOrg;
				$admins = $admins->whereIn('cars.user_id' ,$childOrg);
			}
		}
		if (!empty($request->get('filter_name'))) {
			$admins = $admins->where('cars.title', 'LIKE', '%' . $request->filter_name . '%');
		}
		if (!empty($request->get('organisation_id'))) {
			$admins = $admins->where('cars.user_id', '=',  $request->organisation_id );
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$admins = $admins->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$admins = $admins->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$admins = $admins->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$admins = $admins->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
		}
		else{
			$admins = $admins->whereBetween('traffics.created_at', [
				Carbon::now()->subdays(30)->format('Y-m-d'),
				Carbon::now()->format('Y-m-d')
			]);
		}

		$admins = $admins->groupby('traffics_item.item_id')->orderBy('pageview','DESC')->paginate(20);

		// total pageview
		$total_pageview = DB::table('traffics_item')
							->leftJoin('traffics','traffics.id','=','traffics_item.traffic_id')
							->where('traffics.event_type', '=' , 'visit')
							->count();

		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();

		// charts data
		$chart = DB::table('traffics_item')
				->leftJoin('traffics','traffics.id','=','traffics_item.traffic_id')
				->leftJoin('cars','cars.car_id','=','traffics_item.item_id')
				->select(
					'traffics.*','cars.car_id as item_id', 'cars.title as item_title',
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'visit' THEN 1 END ) ) AS pageview"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type != 'visit' THEN 1 END ) ) AS action"),
					DB::raw("DATE_FORMAT(traffics.created_at, '%M') months"),
					DB::raw("YEAR(traffics.created_at) year, MONTH(traffics.created_at) month, DAY(traffics.created_at) day")
				);

				if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
					$childOrgChart = array();
					// main parent
					$hqOrgChart = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();

					if(count($hqOrgChart) > 0){
						array_push($childOrgChart, $hqOrgChart[0]->id);

						$checklvl2OrgChart = DB::table('users')->where('users.parent_id','=', $hqOrgChart[0]->id)->get();
						// dd($checklvl2OrgChart);
						if(count($checklvl2OrgChart) > 0){
							foreach($checklvl2OrgChart as $checklvl2OrgCharts){
								array_push($childOrgChart, $checklvl2OrgCharts->id);

								$checklvl3OrgChart = DB::table('users')->where('users.parent_id','=', $checklvl2OrgCharts->id)->get();
								if(count($checklvl3OrgChart) > 0){
									foreach($checklvl3OrgChart as $checklvl3OrgCharts){
										array_push($childOrgChart, $checklvl3OrgCharts->id);
									}
								}
							}
						}
					}

					// get id based on selected
					if(count($childOrgChart) > 0){
						$chart = $chart->whereIn('cars.user_id' ,$childOrgChart);
					}
				}
				if(Auth::guard('saleadvisor')){
					$childOrgChart = array();
					$sa_id = Auth::guard('saleadvisor')->user()->id;
					$org_id = Auth::guard('saleadvisor')->user()->user_id;
					array_push($childOrgChart, $org_id);
					// get id based on selected
					if(count($childOrgChart) > 0){
						$chart = $chart->whereIn('cars.user_id' ,$childOrgChart);
					}
				}
				if (!empty($request->get('filter_name'))) {
					$chart = $chart->where('cars.title', 'LIKE', '%' . $request->filter_name . '%');
				}
				if (!empty($request->get('organisation_id'))) {
					$chart = $chart->where('cars.user_id', '=',  $request->organisation_id );
				}
				if (!empty($request->get('filterBy'))) {
					if($request->get('filterBy') == 'today'){
						$chartsSubTitle = "by today";
						$today = date('Y-m-d');
						$chart = $chart->whereDate('traffics.created_at', '=', $today);
					}
					if($request->get('filterBy') == 'yesterday'){
						$chartsSubTitle = "by yesterday";
						$yesterday = Carbon::yesterday();
						$chart = $chart->whereDate('traffics.created_at', '=', $yesterday);
					}
					if($request->get('filterBy') == 'thisweek'){
						$chartsSubTitle = "by this week";
						$chart = $chart->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
					}
					if($request->get('filterBy') == 'thismonth'){
						$chartsSubTitle = "by this month";
						$chart = $chart->whereMonth('traffics.created_at', date('m'));
					}
					if($request->get('filterBy') == '7day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 7;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == '30day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 30;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == 'customdate'){
						if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
							$startDate = $request->get('fromDate');
							$endDate = $request->get('toDate');
							$chartsSubTitle = "from ".$startDate." until ".$endDate;
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
				}
				if (empty($request->get('filterBy'))) {
					$chartsSubTitle = "by previous 30 days";
					$chart = $chart->whereBetween('traffics.created_at', [
						Carbon::now()->subdays(30)->format('Y-m-d'),
						Carbon::now()->format('Y-m-d')
					]);
				}
				$chart = $chart->groupby('year','month','day')->get();

		$chartsLabel = [];
		$chartsPageview = [];
		$chartsCall = [];
		foreach($chart as $charts){
			if($charts->item_id != null){
				$label = $charts->day."/".$charts->month."/".$charts->year;
				$chartsLabel[] = $label;

				$pageview = $charts->pageview;
				$chartsPageview[] = $pageview;

				// change to action
				$countCall = $charts->action;
				$chartsCall[] = $countCall;
			}
		}
		$result['chartsSubTitle'] = $chartsSubTitle;
		$result['chartsLabel'] = $chartsLabel;
		$result['chartsPageview'] = $chartsPageview;
		$result['chartsCall'] = $chartsCall;

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		$result['admins'] = $admins;
		$result['total_pageview'] = $total_pageview;
		$result['admins'] = $admins;
		$result['organisation'] = $organisation;
		$result['org_list'] = $org_list;

		return view("admin.sale_advisors.report.item",$title)->with('result', $result)->with('allimage',$allimage);
	}

	public function promotionreport(Request $request){
		$title = array('pageTitle' => Lang::get("labels.titleOrgReport"));
		$language_id = '1';
		$images = new Images;
		$allimage = $images->getimages();
		$result = array();
		$message = array();
		$errorMessage = array();
		$org_list = null;

		$admins = DB::table('traffics_promotion')
					->leftJoin('traffics','traffics.id','=','traffics_promotion.traffic_id')
					// ->leftJoin('traffics_promotion','traffics_promotion.traffic_id','=','traffics.id')
					->leftJoin('promotion','promotion.promotion_id','=','traffics_promotion.promotion_id')
					->select(
						'traffics.*','promotion.promotion_id as promotion_ids', 'promotion.promotion_name as promotion_name',
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'promotion_click' THEN 1 END ) ) AS pageview")
					);

		if(Auth::guard('saleadvisor')){
			$childOrg = array();
			$sa_id = Auth::guard('saleadvisor')->user()->id;
			$org_id = Auth::guard('saleadvisor')->user()->user_id;
			array_push($childOrg, $org_id);
			// get id based on selected
			if(count($childOrg) > 0){
				$org_list = $childOrg;
				$admins = $admins->whereIn('promotion.organisation' ,$childOrg);
			}
		}
		if (!empty($request->get('filter_name'))) {
			$admins = $admins->where('promotion.promotion_name', 'LIKE', '%' . $request->filter_name . '%');
		}
		if (!empty($request->get('organisation_id'))) {
			$admins = $admins->where('promotion.organisation', '=',  $request->organisation_id );
		}
		if (!empty($request->get('segment_type'))) {
			$admins = $admins->where('promotion.segment_id', '=',  $request->segment_type);
		}
		if (!empty($request->get('status_id'))) {
			$admins = $admins->where('promotion.status', '=',  $request->status_id );
		}
		if (!empty($request->get('filterBy'))) {
            if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$admins = $admins->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$admins = $admins->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$admins = $admins->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$admins = $admins->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
        }
		else{
			$admins = $admins->whereBetween('traffics.created_at', [
				Carbon::now()->subdays(30)->format('Y-m-d'),
				Carbon::now()->format('Y-m-d')
			]);
		}

		$admins = $admins->groupby('traffics_promotion.promotion_id')->orderBy('pageview','DESC')->paginate(20);

		$total_pageview = DB::table('traffics_promotion')
							->leftJoin('traffics','traffics.id','=','traffics_promotion.traffic_id')
							->where('traffics.event_type', '=' , 'promotion_click')
							->count();

		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();

		$segments = Segments::all();

		// charts data
		$chart = DB::table('traffics_promotion')
				->leftJoin('traffics','traffics.id','=','traffics_promotion.traffic_id')
				->leftJoin('promotion','promotion.promotion_id','=','traffics_promotion.promotion_id')
				->select(
					'traffics.*','promotion.promotion_id as promotion_ids', 'promotion.promotion_name as promotion_name',
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'promotion_click' THEN 1 END ) ) AS pageview"),
					DB::raw("DATE_FORMAT(traffics.created_at, '%M') months"),
					DB::raw("YEAR(traffics.created_at) year, MONTH(traffics.created_at) month, DAY(traffics.created_at) day")
				);

				if(Auth::guard('saleadvisor')){
					$childOrgChart = array();
					$sa_id = Auth::guard('saleadvisor')->user()->id;
					$org_id = Auth::guard('saleadvisor')->user()->user_id;
					array_push($childOrgChart, $org_id);
					// get id based on selected
					if(count($childOrgChart) > 0){
						$admins = $admins->whereIn('promotion.organisation' ,$childOrgChart);
					}
				}
				if (!empty($request->get('filter_name'))) {
					$chart = $chart->where('promotion.promotion_name', 'LIKE', '%' . $request->filter_name . '%');
				}
				if (!empty($request->get('organisation_id'))) {
					$chart = $chart->where('promotion.organisation', '=',  $request->organisation_id );
				}
				if (!empty($request->get('segment_type'))) {
					$chart = $chart->where('promotion.segment_id', '=',  $request->segment_type);
				}
				if (!empty($request->get('status_id'))) {
					$chart = $chart->where('promotion.status', '=',  $request->status_id );
				}
				if (!empty($request->get('filterBy'))) {
					if($request->get('filterBy') == 'today'){
						$chartsSubTitle = "by today";
						$today = date('Y-m-d');
						$chart = $chart->whereDate('traffics.created_at', '=', $today);
					}
					if($request->get('filterBy') == 'yesterday'){
						$chartsSubTitle = "by yesterday";
						$yesterday = Carbon::yesterday();
						$chart = $chart->whereDate('traffics.created_at', '=', $yesterday);
					}
					if($request->get('filterBy') == 'thisweek'){
						$chartsSubTitle = "by this week";
						$chart = $chart->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
					}
					if($request->get('filterBy') == 'thismonth'){
						$chartsSubTitle = "by this month";
						$chart = $chart->whereMonth('traffics.created_at', date('m'));
					}
					if($request->get('filterBy') == '7day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 7;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == '30day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 30;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == 'customdate'){
						if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
							$startDate = $request->get('fromDate');
							$endDate = $request->get('toDate');
							$chartsSubTitle = "from ".$startDate." until ".$endDate;
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
				}
				if (empty($request->get('filterBy'))) {
					$chartsSubTitle = "by previous 30 days";
					$chart = $chart->whereBetween('traffics.created_at', [
						Carbon::now()->subdays(30)->format('Y-m-d'),
						Carbon::now()->format('Y-m-d')
					]);
				}
				$chart = $chart->groupby('year','month','day')->get();

		$chartsLabel = [];
		$chartsPageview = [];
		// $chartsCall = [];
		foreach($chart as $charts){
			if($charts->promotion_ids != null){
				$label = $charts->day."/".$charts->month."/".$charts->year;
				$chartsLabel[] = $label;

				$pageview = $charts->pageview;
				$chartsPageview[] = $pageview;

				// $countCall = $charts->countCall;
				// $chartsCall[] = $countCall;
			}
		}
		$result['chartsSubTitle'] = $chartsSubTitle;
		$result['chartsLabel'] = $chartsLabel;
		$result['chartsPageview'] = $chartsPageview;
		// $result['chartsCall'] = $chartsCall;

		$result['organisation'] = $organisation;
		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		$result['admins'] = $admins;
		$result['total_pageview'] = $total_pageview;
		$result['segments'] = $segments;
		$result['org_list'] = $org_list;

		return view("admin.sale_advisors.report.promotion",$title)->with('result', $result)->with('allimage',$allimage);
	}
	// sa campaign report end

    public function changeVerification(Request $request, $type, $id)
    {

        try{
           $success = false;
           if($type==='renew'){
               SaleAdvisor::where('id',$id)->where('verified',0)->update([
                   'verified'=> 3,
                   'payslip' =>SaleAdvisor::paySlipUpload($request,$id),
               ]);
               $success = true;
           }
            $saleAdvisor = SaleAdvisor::where('id',$id)->with('user')->first();
           if($saleAdvisor->user){
               try {
                   Mail::to($saleadvisor->merchant_email)->send(new ToAgentSend($data, true));
               }catch(\Exception $exception){
                   Log::error($exception);
               }

           }

       }catch(Exception $e){
           Log::error($e);
       }
       return \redirect()->back();
    }
}