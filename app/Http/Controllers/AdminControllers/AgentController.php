<?php
namespace App\Http\Controllers\AdminControllers;
use App\Agent;
use App\Mail\ToAgentSend;
use App\Models\Core\Images;
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

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class AgentController extends Controller
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


    public function login(){

        if (Auth::guard('agent')->check()) {

            return redirect('/agent/dashboard');
        }else{
            $title = array('pageTitle' => Lang::get("labels.login_page_name"));
            return view("admin.agent.login",$title);
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
            return redirect('agent/login')->withErrors($validator)->withInput();
        }else{
            //check authentication of email and password
            $adminInfo = array("email" => $request->email, "password" => $request->password);

            if(Auth::guard('agent')->attempt($adminInfo)) {
                $agent = Auth::guard('agent')->user();

                $roleType= Agent::ROLE_ID;
                if($agent->role_id === $roleType &&$agent->status==1){

                return redirect()->intended('/agent/dashboard')->with('agent', $agent);
                }
            }else{
                return redirect('agent/login')->with('loginError',Lang::get("labels.EmailPasswordIncorrectText"));
            }
        }
    }

    //logout
    public function logout(){
        $agent = Auth::guard('agent')->user();
        return redirect()->intended('sale_advisor/login');
    }

    public function dashboard(){

        $agent = Auth::guard('agent')->user();
        return view("admin.agent.dashboard")->with('agent', $agent);
    }

    public function salesAdvisorList(Request $request){
        if(!Auth::guard('agent')->check())
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
                ->where('agent_id', Auth::guard('agent')->user()->id)
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
                ->where('agent_id', Auth::guard('agent')->user()->id)
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

        return view("admin.agent.sales-advisor-list",$title)->with('data', $merchantData);
    }

    protected function addSalesAdvisor(Request $request)
    {

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

        return view("admin.agent.sales-advisor-create",$title)->with('result', $result)->with('allimage',$allimage);
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
            'verified' => 4,
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
            'is_default' =>4,
            'role_id' => 18,
            'password'	=>   Hash::make($request->password),
            'status' =>  '1',
            'created_at' => date('Y-m-d H:i:s'),
            'agent_id' => Auth::guard('agent')->user()->id,
            'package' => $request->package,
            'payslip' =>SaleAdvisor::paySlipUpload($request),
        ]);
        $saleadvisor = SaleAdvisor::where('id',$saleadvisor_id)->first();

        $data=[
            'advisor' => $saleadvisor,
            'user' =>Auth::guard('agent')->user()
        ];

        try {
            Mail::to($saleadvisor->merchant_email)->send(new ToAgentSend($data, true));
        }catch(\Exception $exception){
            Log::error($exception);
        }
        $message = Lang::get("labels.SaleAdvisorAddedMessage");
        return  redirect('agent/sales-advisor')->with('message', $message);
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

        return view("admin.agent.sales-advisor-edit",$title)->with('result', $result)->with('allimage',$allimage);
    }

    public function updateSaleAdvisor(Request $request){
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
           // 'verified' => $request->verified,
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
            //'is_default' => $request->verified,
            'role_id' => $request->adminType,
            'updated_at'=> date('Y-m-d H:i:s'),
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

    public function deleteBranch(Request $request){
        //$customer_addresses = $this->Customers->deleteAddresses($request);
        $branch_id    =   $request->branch_id;
        DB::table('merchant_branch')->where('id','=', $branch_id)->delete();
        //DB::table('user_to_address')->where('address_book_id','=', $address_book_id)->delete();
        return redirect()->back()->withErrors([Lang::get("labels.DeleteSaleAdvisorSuccess")]);
    }

}