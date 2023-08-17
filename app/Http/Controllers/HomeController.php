<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Mail\CarQuotation;
use App\Mail\InsuranceQuotation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use App\Models\Core\Types;
use App\Models\Core\Makes;
use App\Models\Core\Models;
use App\Models\Core\Cities;
use App\Models\Core\States;
use App\Models\Core\Cars;
use Braintree\Merchant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Mail;
class HomeController extends Controller
{
    public function __construct( Makes $makes, Models $models, Cities $cities, States $states, Types $types, Cars $cars){
        $this->Makes = $makes;
        $this->Models = $models;
        $this->States = $states;
        $this->Cities = $cities;
        $this->Types = $types;
        $this->Cars = $cars;
    }

    public function index(Request $request){
        $result = array();

        $sliders = DB::table('sliders_images')
                    ->leftJoin('image_categories','sliders_images.sliders_image','image_categories.image_id')
                    ->where('sliders_images.status',1)
                    ->where('image_categories.image_type','ACTUAL')
                    ->whereNull('sliders_images.merchant_id')
                    ->select('sliders_images.sliders_title','sliders_images.sliders_url','image_categories.path')
                    ->get();
                    
        return view('newtheme.modules.home.index')->with('result', $result)->with('sliders',$sliders);
    }

    public function car_quotation(Request $request){
        return view('car_quotation');
    }

    public function insurance_quotation(Request $request){
        return view('insurance_quotation');
    }

    public function post_car_quotation(Request $request){
        // print_r($request->all());exit;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'contact' => 'required|max:12',
            'email' => 'required|email',
            'model' => 'required',
            'year' => 'required|numeric|min:4',
        ]);



        if ($validator->passes()) {
            Mail::to(env('MAIL_TO'))->send(new CarQuotation(['title' => 'Car Quotation Mail','name' => $request->name, 'email' => $request->email, 'contact' => $request->contact, 'model' => $request->model, 'year' => $request->year]));

		    return response()->json(['success'=>'Quotation is send.']);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function post_insurance_quotation(Request $request){
        // print_r($request->all());exit;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'contact' => 'required|max:12',
            'email' => 'required|email',
            'car_ic' => 'required',
            'car_plate' => 'required',
        ]);



        if ($validator->passes()) {
            Mail::to(env('MAIL_TO'))->send(new InsuranceQuotation(['title' => 'Car Insurance Quotation Mail','name' => $request->name, 'email' => $request->email, 'contact' => $request->contact, 'car_ic' => $request->car_ic, 'car_plate' => $request->car_plate]));

		    return response()->json(['success'=>'Insurance Quotation is send.']);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function testSpinCar()
    {
        return view('test_spin_car');
    }

    public function car_search(Request $request){
        $result = array();
        $query = DB::table('cars')
        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
        ->LeftJoin('models','cars.model_id','=','models.model_id')
        ->LeftJoin('types','cars.type_id','=','types.type_id')
        ->LeftJoin('states','cars.state_id','=','states.state_id')
        ->LeftJoin('cities','cars.city_id','=','cities.city_id')
        ->LeftJoin('users','cars.merchant_id','=','users.id')
        ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.first_name', 'users.last_name')
        ->orderBy('cars.car_id','DESC');
        if(!empty($request->q) && $request->q)
        {
                $query->where(function ($query1) use ($request) {
                    $query1->where('cars.title', 'like', "%$request->q%")
                    ->orWhere('types.type_name', 'like', "%$request->q%")
                    ->orWhere('makes.make_name', 'like', "%$request->q%")
                    ->orWhere('models.model_name', 'like', "%$request->q%")
                    ->orWhere('states.state_name', 'like', "%$request->q%")
                    ->orWhere('cities.city_name', 'like', "%$request->q%")
                    ->orWhere('merchant_branch.merchant_name', 'like', "%$request->q%");
                });
        }

        if(!empty($request->c0))
        {
            if($request->c0 != 0){
                $query->where('cars.status',$request->c0);
            }
        }

       /* if(!empty($request->year) && $request->year)
        {
                $query->where('cars.year_make',$request->year);
        }*/

        if(!empty($request->price_from) && !empty($request->price_to) && $request->price_from <= $request->price_to)
        {
            $query->whereBetween('cars.price',[$request->price_from, $request->price_to]);
        }

        if(!empty($request->c2))
        {
            $query->where('cars.type_id',$request->c2);
        }
        if(!empty($request->c3))
        {
            $query->where('cars.make_id',$request->c3);
        }
        if(!empty($request->c4))
        {
            $query->where('cars.model_id',$request->c4);
        }
        if(!empty($request->year_from) && !empty($request->year_to) && $request->year_from <= $request->year_to)
        {
            $query->whereBetween('cars.year_make',[$request->year_from, $request->year_to]);
        }
        if(!empty($request->c5))
        {
            $query->where('cars.state_id',$request->c5);
        }
        if(!empty($request->c6))
        {
            $query->where('cars.city_id',$request->c6);
        }
        if(!empty($request->c7))
        {
            $query->where('merchant_branch.user_id',$request->c7);
        }
        $car = $query->paginate(15);
        $type = $this->Types->getterwithimage();
        $result['type'] = $type;
        $make = $this->Makes->getter();
        $result['make'] = $make;
        $model = $this->Models->getter();
        $result['model'] = $model;
        $state = $this->States->getter();
        $result['state'] = $state;
        $city = $this->Cities->getter();
        $result['city'] = $city;
        $company_name = $this->Cars->getMerchantCompanyName();
        $result['company_name'] = $company_name;
        return view('car_search')->with('result', $result)->with('car',$car);
    }

    public function getmodel(Request $request){
        $getModel= array();
        if($request->make_id == 0){
            $getModel = DB::table('models')->orderBy('model_name','ASC')->get();
            $responseData = array('success'=>'0', 'data'=>$getModel, 'message'=>"Returned all model.");
        }else{
            $getModel = DB::table('models')->where('model_make_id', $request->make_id)->orderBy('model_name','ASC')->get();
            if(count($getModel)>0){
                $responseData = array('success'=>'1', 'data'=>$getModel, 'message'=>"Returned all model.");
            }else{
                $responseData = array('success'=>'0', 'data'=>$getModel, 'message'=>"Returned all model.");
            }
        }
        $modelResponse = json_encode($responseData);
        print $modelResponse;
    }

    public function getcity(Request $request){
        $getCity= array();
        if($request->state_id == 0){
            $getCity = DB::table('cities')->get();
            $responseData = array('success'=>'0', 'data'=>$getCity, 'message'=>"Returned all city.");
        }else{
            $getCity = DB::table('cities')->where('city_state_id', $request->state_id)->get();
            if(count($getCity)>0){
                $responseData = array('success'=>'1', 'data'=>$getCity, 'message'=>"Returned all city.");
            }else{
                $responseData = array('success'=>'0', 'data'=>$getCity, 'message'=>"Returned all city.");
            }
        }
        $cityResponse = json_encode($responseData);
        print $cityResponse;
    }

    public function car_page(Request $request){
        $message = array();
        $car =  DB::table('cars')
            ->leftJoin('image_categories as categoryTable','categoryTable.image_id', '=', 'cars.image')
            ->LeftJoin('makes','cars.make_id','=','makes.make_id')
            ->LeftJoin('models','cars.model_id','=','models.model_id')
            ->LeftJoin('types','cars.type_id','=','types.type_id')
            //->LeftJoin('users','cars.merchant_id','=','users.id')
            ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
            ->LeftJoin('states','merchant_branch.state_id','=','states.state_id')
            ->LeftJoin('cities','merchant_branch.city_id','=','cities.city_id')
            ->select('cars.*', 'makes.make_name', 'models.model_name', 'types.type_name', 'states.state_name', 'cities.city_name', 'categoryTable.path as imgpath', 'merchant_branch.merchant_name', 'merchant_branch.merchant_phone_no', 'merchant_branch.merchant_email')
            ->where('cars.car_id', $request->id)->first();

        $url="https://manager.spincar.com/web-preview/walkaround-thumb/" . $car->sp_account. "/" . strtolower($car->vim) . "/md";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $a = curl_exec($ch);
        $car_image_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $car_image_url = str_replace("thumb-md.jpg","closeups/cu-0.jpg",$car_image_url);

        return view('car_page')->with('car',$car)->with('message',$message)->with('car_image_url', $car_image_url);
    }

    public function enquired(Request $request){
        $merchantEmail = $request->merchant_email;

        $car =  DB::table('cars')
            ->leftJoin('image_categories as categoryTable','categoryTable.image_id', '=', 'cars.image')
            ->LeftJoin('makes','cars.make_id','=','makes.make_id')
            ->LeftJoin('models','cars.model_id','=','models.model_id')
            ->LeftJoin('types','cars.type_id','=','types.type_id')
            ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
            ->LeftJoin('states','merchant_branch.state_id','=','states.state_id')
            ->LeftJoin('cities','merchant_branch.city_id','=','cities.city_id')
            ->select('cars.*', 'makes.make_name', 'models.model_name', 'types.type_name', 'states.state_name', 'cities.city_name', 'categoryTable.path as imgpath', 'merchant_branch.merchant_name', 'merchant_branch.merchant_phone_no', 'merchant_branch.merchant_email')
            ->where('cars.car_id', $request->car_id)->first();

        $data = array(
            'member_name'=>$request->name,
            'member_email'=>$request->email,
            'member_contact'=>$request->contact,
            'car'=>$car,
            'receiver'=>$merchantEmail
        );

        Mail::send('/emails/EnquireCarInfo', ['data' => $data], function ($m) use ($data) {
            $m->to($data['receiver'])
                ->subject("Enquire Car Info")
                ->getSwiftMessage()
                ->getHeaders()
                ->addTextHeader('x-mailgun-native-send', 'true');
		});

        return Redirect::back()->withErrors([Lang::get("Enquired successfully!")]);
    }

    public function aboutUs(Request $request)
    {
        return view('newtheme.modules.aboutus.index');
    }

    public function contactUs(Request $request)
    {
        return view('newtheme.modules.contactus.index');
    }

    public function contactUsPost(Request $request)
    {
        $receiver = env("CONTACT_US_RECEIVER", "info@tanyaje.com.my");
        $data = array(
            'full_name'=>$request->full_name,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'message'=>$request->message,
            'receiver'=>$receiver
        );

        Mail::send('/emails/contactUs', ['data' => $data], function ($m) use ($data) {
            $m->to($data['receiver'])
                ->subject("Contact Us Form Submission")
                ->getSwiftMessage()
                ->getHeaders()
                ->addTextHeader('x-mailgun-native-send', 'true');
        });

        return Redirect::back()->withErrors([Lang::get("Send successfully!")]);
    }

    public function contactUsForVoucher(Request $request)
    {
        //sample url : https://{domain-name}/contactusforvoucher?voucher_name=voucher1
        $states = States::orderBy('state_name', 'ASC')->get();
        $voucher_name = $request->get('voucher_name');

        return view('newtheme.modules.contactusforvoucher.index')->with(compact('states', 'voucher_name'));
    }

    public function contactUsForVoucherPost(Request $request)
    {
        $receiver = env("CONTACT_US_RECEIVER", "info@tanyaje.com.my");
        $data = array(
            'voucher_name' => $request->voucher_name,
            'full_name'=>$request->full_name,
            'ic'=>$request->ic,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'entry_street_address' => $request->entry_street_address,
            'state' => $request->state,
            'entry_postcode' => $request->entry_postcode,
            'remark'=>$request->remark,
            'receiver'=>$receiver
        );

        Mail::send('/emails/contactUsForVoucher', ['data' => $data], function ($m) use ($data) {
            $m->to($data['receiver'])
                ->subject("Contact Us For Voucher Form Submission")
                ->getSwiftMessage()
                ->getHeaders()
                ->addTextHeader('x-mailgun-native-send', 'true');
        });

        return Redirect::back()->withErrors([Lang::get("Send successfully!")]);
    }

    public function profile(Request $request)
    {
        return view('newtheme.modules.profile.index');
    }

    public function savedCars(Request $request)
    {
        return view('newtheme.modules.savedcars.index');
    }

        //    public function car_list(Request $request){
        //
        //        $car = DB::table('cars')
        //            ->LeftJoin('makes','cars.make_id','=','makes.make_id')
        //            ->LeftJoin('models','cars.model_id','=','models.model_id')
        //            ->LeftJoin('types','cars.type_id','=','types.type_id')
        //            ->LeftJoin('states','cars.state_id','=','states.state_id')
        //            ->LeftJoin('cities','cars.city_id','=','cities.city_id')
        //            ->LeftJoin('users','cars.merchant_id','=','users.id')
        //            ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        //            ->LeftJoin('image_categories','cars.image','=','image_categories.id','&&','image_type','=','MEDIUM')
        //            ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.first_name', 'users.last_name', 'states.state_name', 'cities.city_name', 'image_categories.path','height','width','merchant_branch.merchant_name')
        //            ->orderBy('created_at','DESC')
        //            ->paginate(20);
        //
        //        $this->data['car'] = $car;
        //
        //		$this->data['start_make_year'] = DB::table('cars')->min('year_make');
        //		$this->data['last_make_year'] = DB::table('cars')->max('year_make');
        //
        ////		$start_mileage = DB::table('cars')->min('mileage');
        ////		$start_mileage = explode("-",$start_mileage);
        //		$this->data['start_mileage'] = 0;
        //
        ////		$last_mileage = DB::table('cars')->max('mileage');
        ////		$last_mileage = explode("-",$last_mileage);
        //		$this->data['last_mileage'] = 400000;
        //
        //		$this->data['start_price'] = DB::table('cars')->min('price');
        //		$this->data['last_price'] = DB::table('cars')->max('price');
        //
        //		$this->data['start_engine_capacity'] = DB::table('cars')->min('engine_capacity');
        //		$this->data['last_engine_capacity'] = DB::table('cars')->max('engine_capacity');
        //
        //		$this->data['start_seats'] = DB::table('cars')->min('seats');
        //	 	$this->data['last_seats'] = DB::table('cars')->max('seats');
        //
        //        $this->data['state'] = $this->States->getter();
        //
        //        $this->data['color'] = DB::table('cars')->select('car_id','color')
        //            ->groupBy('color')
        //            ->orderBy('color','ASC')
        //            ->where('color', '!=','5')
        //            ->get();
        //
        //        $merchant = DB::table('cars')
        //            ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        //            ->select('merchant_id','merchant_name')
        //            ->groupBy('merchant_id')
        //            ->orderBy('car_id','ASC')
        //            ->get() ;
        //
        //
        //
        //
        //        $this->data['merchant'] =  $merchant;
        //        $this->data['state'] = $this->States->getter();
        //
        //        $this->data['city'] = $this->Cities->getter();
        //        $this->data['make'] = $this->Makes->getter();
        //        $this->data['model'] = $this->Models->getter();
        //        $this->data['type'] = $this->Types->getter();
        //
        //        //search message
        //        $search_message = "Advance Search";
        //        $this->data['search_message'] = $search_message;
        //
        //        return view('newtheme.modules.car_list.index',$this->data);
        //    }

    public function car_details(Request $request)
    {
        $car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
            ->LeftJoin('models','cars.model_id','=','models.model_id')
            ->LeftJoin('types','cars.type_id','=','types.type_id')
            ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
            ->LeftJoin('users','merchant_branch.user_id','=','users.id')
            ->LeftJoin('states','merchant_branch.state_id','=','states.state_id')
            ->LeftJoin('cities','merchant_branch.city_id','=','cities.city_id')
            ->select('cars.*', 'makes.make_name', 'models.model_name', 'types.type_name', 'states.state_name',
                'cities.city_name', 'merchant_branch.merchant_name','merchant_branch.slug', 'merchant_branch.merchant_phone_no',
                'merchant_branch.merchant_email', 'merchant_branch.user_id as merchnat_branch_user_id',
                'users.description as merchant_info','users.created_at', 'users.logo_id as merchant_logo_id')
            ->where('cars.car_id', $request->id)
            ->get();

        foreach($car as $rs){
            $this->data['car_id']=$rs->car_id;
            $this->data['vim']=$rs->vim;
            $this->data['stock_number']=$rs->stock_number;
            $this->data['make_id']=$rs->make_id;
            $this->data['model_id']=$rs->model_id;
            $this->data['status']=$rs->status;
            $this->data['type_id']=$rs->type_id;
            $this->data['state_id']=$rs->state_id;
            $this->data['image']=$rs->image;
            $this->data['pdf']=$rs->pdf;
            $this->data['price']=$rs->price;
            $this->data['created_at']=$rs->created_at;
            $this->data['year_make']=$rs->year_make;
            $this->data['title']=$rs->title;
            $this->data['fuel_type']=$rs->fuel_type;
            $this->data['features']=$rs->features;
            $this->data['seats']=$rs->seats;
            $this->data['transmission']=$rs->transmission;
            $this->data['mileage']=$rs->mileage;
            $this->data['color']=$rs->color;
            $this->data['engine_capacity']=$rs->engine_capacity;
            $this->data['make_name']=$rs->make_name;
            $this->data['model_name']=$rs->model_name;
            $this->data['type_name']=$rs->type_name;
            $this->data['state_name']=$rs->state_name;
            $this->data['city_name']=$rs->city_name;
            $this->data['merchant_id']=$rs->merchant_id;
            $this->data['merchant_name']=$rs->merchant_name;
            $this->data['merchant_slug']=$rs->slug;
            $this->data['merchant_phone_no']=$rs->merchant_phone_no;
            $this->data['merchant_email']=$rs->merchant_email;
            $this->data['features']=explode(",",$rs->features);
            $this->data['title']=$rs->title;
            $this->data['description']=$rs->html_editor;
            $this->data['merchant_info']=$rs->merchant_info;
            $this->data['merchant_logo_id']=$rs->merchant_logo_id;
            $this->data['created_at']=$rs->created_at;

            $user_to_address = DB::table('user_to_address')
                ->where('user_id', $rs->merchnat_branch_user_id )
                ->get();
            if(count($user_to_address)>0)
            {
                $address = DB::table('address_book')
                    ->where('address_book_id','=', $user_to_address[0]->address_book_id)
                    ->get();
            }
            else
            {
                $address = array();
            }
 //            $result['address'] = $address;
            if( isset($address[0]) )
            {
                $this->data['address'] = $address[0]->entry_street_address. ", " . $address[0]->entry_postcode . " " .
                    $address[0]->entry_city . ", " . $address[0]->entry_state;
            }
            else
            {
                $this->data['address'] = "";
            }

            //get the thumbnail image to share
            $url="https://manager.spincar.com/web-preview/walkaround-thumb/" . $rs->sp_account . "/" . strtolower($rs->vim) . "/md";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $a = curl_exec($ch);
            $car_image_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            $car_image_url = str_replace("thumb-md.jpg","closeups/cu-0.jpg",$car_image_url);
            $this->data['car_image_url']= $car_image_url;

            //get total car count for merchant
            $ttl_car_count = DB::table('cars')
                ->where('merchant_id', $rs->merchant_id)
                ->count();
            $this->data['ttl_car_count']= $ttl_car_count;

            $this->data['current_url'] = route("car_details",$rs->getCarUrl());
        }

        $random_car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
            ->LeftJoin('models','cars.model_id','=','models.model_id')
            ->LeftJoin('types','cars.type_id','=','types.type_id')
            ->LeftJoin('states','cars.state_id','=','states.state_id')
            ->LeftJoin('cities','cars.city_id','=','cities.city_id')
            ->LeftJoin('users','cars.merchant_id','=','users.id')
            ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
            ->select('cars.car_id', 'cars.year_make','cars.price','cars.mileage', 'cars.color','cars.vim', 'cars.sp_account',
                'cars.fuel_type','cars.city_id','makes.make_name', 'models.model_name', 'users.first_name', 'users.last_name',
                'states.state_name', 'cities.city_name','merchant_branch.merchant_name', 'cars.title', 'merchant_branch.slug as merchant_slug')
            ->inRandomOrder()
            ->where('merchant_id', $rs->merchant_id)
            ->get()
            ->take(4);

        $this->data['random_car'] = $random_car;

        if( isset($this->data['address']) )
        {
            $address_result = str_replace("&", "%26",  str_replace(" ", "+", $this->data['address']));
//            $this->data['google_map_url'] = "https://maps.google.it/maps?q=" . $address_result . "&output=svembed";
            $this->data['google_map_url'] = "https://www.google.com/maps/embed/v1/place?key=" . env('GoogleMapAPI') . "&q=" . $address_result;
            $this->data['direction_google_map_url'] = "https://www.google.com/maps?daddr=" . $address_result;
        }
        else
        {
            $this->data['google_map_url'] = "";
            $this->data['direction_google_map_url'] = "";
        }
        if(Auth::guard('customer')->user())
            $this->data['cookies'] = DB::table('car_compares')->where('user_id',Auth::guard('customer')->user()->id)->pluck('car_id')->toArray();
        else
            $this->data['cookies'] = isset($_COOKIE['car_compare_list']) && $_COOKIE['car_compare_list'] != "" ? explode(',',$_COOKIE['car_compare_list']) : array();

        #region share car title and description
        $share_car_title = "";
        if( !empty( $this->data['title'] ) )
        {
            $share_car_title = $this->data['title'];

            //state name
            if( isset($this->data['state_name']) && $this->data['state_name'] != "" )
            {
                $share_car_title = $share_car_title . " in " .  ucwords(strtolower($this->data['state_name']));
            }

            //transmission
            if( isset($this->data['transmission']) && $this->data['transmission'] != "" )
            {
                $share_car_title = $share_car_title . " " .  ucwords(strtolower($this->data['transmission']));
            }

            //category
            if( isset($this->data['type_name']) && $this->data['type_name'] != "" )
            {
                $share_car_title = $share_car_title . " " .  $this->data['type_name'];
            }

            //colour
            if( isset($this->data['color']) && $this->data['color'] != "" )
            {
                $share_car_title = $share_car_title . " " .  ucwords(strtolower($this->data['color']));
            }

            //car price
            if( isset($this->data['price']) && $this->data['price'] != "" )
            {
                $share_car_title = $share_car_title . " for RM" .  number_format($this->data['price'], 2, '.', ',');
            }

            //vin
            if( isset($this->data['vim']) && $this->data['vim'] != "" )
            {
                $share_car_title = $share_car_title . " - " .  $this->data['vim'];
            }

            $share_car_title = $share_car_title . " - Tanyaje.com.my";
        }
        else
        {
            $share_car_title = trans('labels.app_name') . "|" . $this->data['vim'];
        }
        $this->data['share_car_title']=$share_car_title;

        $share_car_description = "";
        if( isset($this->data['share_car_title']) && $this->data['share_car_title'] != "" )
        {
            $share_car_description = $this->data['share_car_title'];

            if( isset($this->data['description']) && $this->data['description'] != "" )
            {
                $share_car_description = $share_car_description . " - " .  str_replace(array("\r", "\n"), ' ', $this->data['description']); ;
            }
        }
        else
        {
            $share_car_description = "Tanya-Je translated as “Just Ask” is the first most advanced online digital automotive classified in South East Asia.";
        }
        $this->data['share_car_description']= substrwords($share_car_description, 250);

        #endregion

        #region whatsapp predefine message
        $whatsapp_message = "Hi, I saw your car on TanyaJe.com.my and I would like to know more about " . $this->data['title'];

        if( isset($this->data['price']) && $this->data['price'] != "" )
        {
            $whatsapp_message = $whatsapp_message . " (RM " . number_format($this->data['price'], 2, '.', ',') . ")";
        }

        $current_url = $this->data['current_url'];
        $whatsapp_message =  $whatsapp_message . ". Thanks. " . $current_url ;

        $this->data['whatsapp_message']= "https://api.whatsapp.com/send?phone=" . str_replace(' ', '', $this->data['merchant_phone_no']) . "&text=" . $whatsapp_message;
        #endregion

        return view('newtheme.modules.car_details.index',$this->data);
    }

	public function carfilters(Request $request)
	{

		$filter_data = array();

		$condition = '';
        $condition_value = '';
		if(!empty($request->condition)){
			$condition = $request->condition;
			if($condition == 1){
				$condition_value = 'New';
			}else if($condition == 2){
				$condition_value = 'Used';
			}else if($condition == 3){
				$condition_value = 'Recond';
			}
			$filter_data[] = array(
				'name'=>$condition_value,
                'n'  =>1
			);
		}

		$state = '';
		if(!empty($request->state)){
			$state = $request->state;

			$stateNameData = DB::table('states')
			->select('state_name')
			->where('state_id',$state)
			->get();
			if(count($stateNameData)>0){
				foreach($stateNameData as $rs){
					$state_name = $rs->state_name;
				}
				$filter_data[] = array(
					'name'=>$state_name,
                     'n' =>2
				);
			}
		}

		$brand = '';
		if(!empty($request->brand)){
			$brand = $request->brand;
			$brandNameData = DB::table('makes')
			->select('make_name')
			->where('make_id',$brand)
			->get();

			if(count($brandNameData)>0){
				foreach($brandNameData as $rs){
					$brand_name = $rs->make_name;
				}
			}
			$filter_data[] = array(
				'name'=>$brand_name,
                'n'  =>3
			);
		}

		$model = '';
		if(!empty($request->model)){
			$model = $request->model;
			$modelNameData = DB::table('models')
				->select('model_name')
				->where('model_id',$model)
				->get();
				if(count($modelNameData)>0){
					foreach($modelNameData as $rs){
						$model_name = $rs->model_name;
					}
				}
				$filter_data[] = array(
					'name'=>$model_name,
                    'n' =>4
				);
		}

		$categories = '';
		if(!empty($request->categories)){
			$categories = $request->categories;
			$categoryNameData = DB::table('types')
			->select('type_name')
			->where('type_id',$categories)
			->get();
			if(count($categoryNameData)>0){
				foreach($categoryNameData as $rs){
					$category_name = $rs->type_name;
				}
			}
			$filter_data[] = array(
				'name'=>$category_name,
                'n'=>5
			);
		}

		$start_make_year = '';
		if(!empty($request->start_make_year)){
			$start_make_year = $request->start_make_year;
			$filter_data[] = array(
				'name'=>'Year Make Start : '.$start_make_year,
                'n' =>6
			);
		}

		$last_make_year = '';
		if(!empty($request->last_make_year)){
			$last_make_year = $request->last_make_year;
			$filter_data[] = array(
				'name'=>'Year Make End : '.$last_make_year,
                'n'=>7
			);
		}

//		$start_mileage = '';
//		if(!empty($request->start_mileage)){
//			$start_mileage = $request->start_mileage;
//			$filter_data[] = array(
//				'name'=>'Min Mileage : '.$start_mileage
//			);
//		}
//
//		$last_mileage = '';
//		if(!empty($request->last_mileage)){
//			$last_mileage = $request->last_mileage;
//			$filter_data[] = array(
//				'name'=>'Max Mileage : '.$last_mileage
//			);
//		}

        $mileage_range = '';
        if(!empty($request->mileage_range)){
            $mileage_range = $request->mileage_range;
            $filter_data[] = array(
                'name'=>'Mileage : '.$mileage_range,
                'n'=>9
            );
        }

		$start_price = '';
		if(!empty($request->start_price)){
			$start_price = $request->start_price;
			$filter_data[] = array(
				'name'=>'Min Price : '.$start_price,
                'n'=>10
			);
		}

		$last_price = '';
		if(!empty($request->last_price)){
			$last_price = $request->last_price;
			$filter_data[] = array(
				'name'=>'Max Price : '.$last_price,
                'n'=>11
			);
		}

		$fuel_type = array();
		if(!empty($request->fuel_type)){
			$fuel_type = $request->fuel_type;
			$filter_data[] = array(
				'fuel_type'=>$fuel_type,
                'n'=>12
			);
		}

		$transmission = array();
		if(!empty($request->transmission)){
			$transmission = $request->transmission;
			$filter_data[] = array(
				'transmission'=>$transmission,
                'n'=>13
			);
		}

		$start_engine_capacity = '';
		if(!empty($request->start_engine_capacity)){
			$start_engine_capacity = $request->start_engine_capacity;
			$filter_data[] = array(
                'name'=>'Min Engine Capacity : ' . $start_engine_capacity,
                'n'=>14
			);
		}

		$last_engine_capacity = '';
		if(!empty($request->last_engine_capacity)){
			$last_engine_capacity = $request->last_engine_capacity;
			$filter_data[] = array(
                'name'=>'Max Engine Capacity : ' . $last_engine_capacity,
                'n'=>15
			);
		}

		$start_seats = '';
		if(!empty($request->start_seats)){
			$start_seats = $request->start_seats;
			$filter_data[] = array(
				'name'=> 'Min Seat : ' . $start_seats,
                'n'=>16
			);
		}

        $last_seats = '';
        if(!empty($request->last_seats)){
            $last_seats = $request->last_seats;
            $filter_data[] = array(
                'name'=> 'Max Seat : ' . $last_seats,
                'n'=>17
            );
        }

        $color = '';
        if(!empty($request->color)){
            $color = $request->color;
            $filter_data[] = array(
                'name'=>$color,
                'n'=>18
            );
        }


        $merchant = '';
        $merchant_name='';
        if(!empty($request->merchant))
        {
            $merchantNameData = DB::table('users')
                ->select('company_name')
                ->find($request->merchant);

            $merchant= $request->merchant;

            $merchant_name = "";
            if(isset($merchantNameData))
            {
                $merchant_name= $merchantNameData->company_name;
            }

            $filter_data[] = array(
                'name'=>$merchant_name,
                'n'=>20
            );
        }

		$features = array();
		if(!empty($request->features)){
			$features = $request->features;
			$filter_data[] = array(
				'features'=>$features,
                'n'=>19
			);
        }

        $titleCar = '';
        if(!empty($request->title)){
            $titleCar = $request->title;
            $filter_data[] = array(
                'name'=>$titleCar,
                'n'=>20
            );
        }

		//echo "<pre>";print_r($filter_data);die;

		$car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
            ->LeftJoin('models','cars.model_id','=','models.model_id')
            ->LeftJoin('types','cars.type_id','=','types.type_id')
            ->LeftJoin('states','cars.state_id','=','states.state_id')
            ->LeftJoin('cities','cars.city_id','=','cities.city_id')
            ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
            ->LeftJoin('users','merchant_branch.user_id','=','users.id')
            ->select('cars.year_make','cars.title','cars.car_id','cars.price','cars.mileage', 'cars.color','cars.vim','cars.sp_account',
                'cars.fuel_type','cars.city_id', 'cars.status', 'makes.make_name', 'models.model_name', 'users.first_name',
                'users.last_name', 'states.state_name', 'cities.city_name','merchant_branch.merchant_name',
                'cars.updated_at',DB::raw('CAST(cars.mileage AS SIGNED) AS mileageorder'), 'merchant_branch.slug as merchant_slug' );

            if($request->has('sort') && !empty($request->get('sort')))
            {
                if($request->get('sort') != "mileage")
                    $car->orderBy('cars.'.$request->get('sort'),$request->get('direction'));
                else
                    $car->orderBy('mileageorder',$request->get('direction'));
            }

        if( isset($titleCar) && $titleCar != "" )
        {
            $car = $car->where('cars.title',$titleCar);
        }

		if( isset($condition) && $condition != "" )
        {
            $car = $car->where('cars.status',$condition);
        }

		if( isset($state) && $state != "" )
        {
            $car = $car->where('cars.state_id',$state);
        }

		if( isset($brand) && $brand != "" )
        {
            $car = $car->where('cars.make_id',$brand);
        }

        if( isset($model) && $model != "" )
        {
            $car = $car->where('cars.model_id',$model);
        }

        if( isset($categories) && $categories != "" )
        {
            $car = $car->where('cars.type_id',$categories);
        }

        if( isset($mileage_range) && $mileage_range != "" )
        {
            $strMileageRange = str_replace('-', ' - ', $mileage_range);
            $car = $car->where('cars.mileage', $strMileageRange);
        }

        $min_year_make = DB::table('cars')->min('year_make');
        $max_year_make = DB::table('cars')->max('year_make');
        if( $start_make_year != "" || $last_make_year != "" )
        {
            if( $start_make_year == "" )
            {
                $start_make_year = $min_year_make;
            }

            if( $last_make_year == "" )
            {
                $last_make_year = $max_year_make;
            }
            $car = $car->whereBetween('cars.year_make',[$start_make_year, $last_make_year]);
        }

        if( isset($fuel_type) && $fuel_type != "" && count($fuel_type) > 0)
        {
            $car = $car->whereIn('cars.fuel_type',$fuel_type);
        }

        if( isset($transmission) && $transmission != "" && count($transmission) > 0)
        {
            $car = $car->whereIn('cars.transmission',$transmission);
        }

        $min_price = DB::table('cars')->min('price');
        $max_price = DB::table('cars')->max('price');
        if( $start_price != "" || $last_price != "" )
        {
            if( $start_price == "" )
            {
                $start_price = $min_price;
            }

            if( $last_price == "" )
            {
                $last_price = $max_price;
            }
            $car = $car->whereBetween('cars.price',[$start_price, $last_price]);
        }
        else
        {
            $start_price = $min_price;
            $last_price = $max_price;
            $car = $car->whereBetween('cars.price',[$start_price, $last_price]);
        }

        $min_engine_capacity = DB::table('cars')->min('engine_capacity');
        $max_engine_capacity = DB::table('cars')->max('engine_capacity');
        if( $start_engine_capacity != "" || $last_engine_capacity != "" )
        {
            if( $start_engine_capacity == "" )
            {
                $start_engine_capacity = $min_engine_capacity;
            }

            if( $last_engine_capacity == "" )
            {
                $last_engine_capacity = $max_engine_capacity;
            }
            $car = $car->whereBetween('cars.engine_capacity',[$start_engine_capacity, $last_engine_capacity]);
        }

        $min_seats = DB::table('cars')->min('seats');
        $max_seats = DB::table('cars')->max('seats');
        if( $start_seats != "" || $last_seats != "" )
        {
            if( $start_seats == "" )
            {
                $start_seats = $min_seats;
            }

            if( $last_seats == "" )
            {
                $last_seats = $max_seats;
            }
            $car = $car->whereBetween('cars.seats',[$start_seats, $last_seats]);
        }

        if( isset($color) && $color != "" )
        {
            $car = $car->where('cars.color',$color);
        }

        if( isset($features) && $features != "" && count($features) > 0)
        {
            $car = $car->whereIn('cars.features',$features);
        }

        if( isset($merchant) && $merchant != "" )
        {
            $car = $car->where('merchant_branch.user_id',$merchant);
        }

//		    ->where('cars.state_id',$state)
//		    ->where('cars.state_id','!=','')
//            ->where('cars.color','!=','')
//            ->where('cars.color',$color)
//            ->where('cars.merchant_id','!=','')
//            ->orWhere('cars.merchant_id',$merchant)
//		    ->Where('cars.status',$condition)
//		    ->where('cars.status','!=','')
//		    ->orWhere('cars.make_id',$brand)
//		    ->where('cars.make_id','!=','')
//		    ->orWhere('cars.model_id',$model)
//		    ->where('cars.model_id','!=','')
//		    ->orWhere('cars.type_id',$categories)
//		    ->where('cars.type_id','!=','')
//		    ->orWhereBetween('cars.year_make',[$start_make_year, $last_make_year])
//		    ->where('cars.year_make','!=','')
//		    ->orWhereBetween('cars.mileage',[$start_mileage, $last_mileage])
//		    ->where('cars.mileage','!=','')
//		    ->orWhereBetween('cars.mileage',[$start_mileage, $last_mileage])
//		    ->where('cars.mileage','!=','')
//		    ->orWhereBetween('cars.price',[$start_price, $last_price])
//		    ->where('cars.price','!=','')
//		    ->orWhereIn('cars.fuel_type',$fuel_type)
//		    ->where('cars.fuel_type','!=','')
//		    ->orWhereIn('cars.transmission',$transmission)
//		    ->where('cars.transmission','!=','')
//		    ->orWhereBetween('cars.engine_capacity',[$start_engine_capacity, $last_engine_capacity])
//		    ->where('cars.engine_capacity','!=','')
//		    ->orWhereBetween('cars.seats',[$start_seats, $last_seats])
//		    ->where('cars.seats','!=','')
//		    ->orWhereIn('cars.features',$features)
//		    ->where('cars.features','!=','')
        $airtime_cars = (clone $car)->where('is_airtime_hide','0')->orderBy('random_code','desc')->get()->toArray();
        $airtime_merchant = array_unique(array_column($airtime_cars, 'merchant_name'));
        $airtime_car_ids = array_unique(array_column($airtime_cars, 'car_id'));
        //dd($airtime_car_ids);
        if(count($airtime_merchant) > 1 && !in_array($request->get('sort'),['updated_at','price']))
        {
            $airtime_perpage = 25;
            $this->data['airtime'] = true;
            $car = collect();
            $airtime_merchants = DB::table('merchant_branch')
                            ->whereIn('merchant_name',$airtime_merchant)
                            ->join('cars','merchant_branch.id','=','cars.merchant_id')
                            ->select('merchant_branch.id','merchant_branch.merchant_name','merchant_branch.merchant_payment',
                                DB::raw("(merchant_branch.merchant_payment / count(cars.car_id)) as airtime"))
                            ->groupBy('merchant_branch.id')
//                            ->orderBy('airtime','desc')
                            ->inRandomOrder()
                            ->take(50)
                            ->get();

            if(count($airtime_cars) < 25)
            {
                $airtime_perpage = count($airtime_cars);
            }
            $car = $this->rearrange_cars($airtime_merchants,$airtime_car_ids,$car,$airtime_perpage);
            $this->data['airtime_per_page'] = $airtime_perpage;
            $this->data['airtime_total_pages'] = round($car->count() / $airtime_perpage);
            $this->data['current_page'] = isset($_COOKIE['airtime_page']) && $_COOKIE['airtime_page'] < $this->data['airtime_total_pages'] ? $_COOKIE['airtime_page'] + 1 : 1;
            setcookie('airtime_page',$this->data['current_page']);
            $skip = ($this->data['current_page'] - 1) * $airtime_perpage;
            $car = $car->slice($skip)->take($airtime_perpage);
            if($request->has('sort') && !empty($request->get('sort')))
            {
                if($request->get('sort') != "mileage")
                    $car = $car->sortBy($request->get('sort'));
                else
                    $car = $car->sortBy('mileageorder');
            }
            if(count($airtime_cars) < 25)
            {
                $car = $car->shuffle();
            }
        }
        else
        {
            $car = $car->paginate(20);
            $this->data['airtime'] = false;

        }

        $this->data['car'] = $car;

        $this->data['min_year_make'] = $min_year_make;
        $this->data['max_year_make'] = $max_year_make;
        $this->data['start_make_year'] = $start_make_year;
        $this->data['last_make_year'] = $last_make_year;

//		$this->data['start_mileage'] = $start_mileage;
//		$this->data['last_mileage'] = $last_mileage;
        $this->data['mileage_range'] = $mileage_range;

        $this->data['start_price'] = $start_price;
        $this->data['last_price'] = $last_price;
        $this->data['min_price'] = $min_price;
        $this->data['max_price'] = $max_price;

        $this->data['min_engine_capacity'] = $min_engine_capacity;
        $this->data['max_engine_capacity'] = $max_engine_capacity;
        $this->data['start_engine_capacity'] = $start_engine_capacity;
        $this->data['last_engine_capacity'] = $last_engine_capacity;

        $this->data['min_seats'] = $min_seats;
        $this->data['max_seats'] = $max_seats;
        $this->data['start_seats'] = $start_seats;
        $this->data['last_seats'] = $last_seats;

        $this->data['filter_condition'] = $condition;
        $this->data['filter_state_id'] = $state;
        $this->data['color'] = DB::table('cars')->select('car_id','color')
            ->groupBy('color')
            ->orderBy('color','ASC')
            ->where('color', '!=','5')
            ->get();

        $this->data['merchant'] = $this->Cars->getMerchantCompanyName();

        $this->data['filter_color'] = $color;
        $this->data['selected_merchant'] = $merchant;
        $this->date['filter_merchant']  =$merchant_name;
		$this->data['filter_brand'] = $brand;
		$this->data['filter_model'] = $model;
		$this->data['filter_categories'] = $categories;
		$this->data['filter_fuel_type'] = $fuel_type;
		$this->data['filter_transmission'] = $transmission;
		$this->data['filter_features'] = $features;
		$this->data['state'] = States::all();
        $this->data['city'] = Cities::all();
        $this->data['make'] = Makes::all();
//        $this->data['model'] = $this->Models->getter();
        $this->data['type'] = Types::all();

        $stateNameData = DB::table('states')
            ->select('state_name')
            ->where('state_id',$state)
            ->get();
        if(count($stateNameData)>0){
            foreach($stateNameData as $rs){
                $this->data['state_name'] = $rs->state_name;
            }
        }else{
            $this->data['state_name'] = '';
        }

        $brandNameData = DB::table('makes')
            ->select('make_name')
            ->where('make_id',$brand)
            ->get();
        if(count($brandNameData)>0){
            foreach($brandNameData as $rs){
                $this->data['brand_name'] = $rs->make_name;
            }

            //if have brand then show model
            $this->data['model'] = Models::sortable(['model_id'=>'ASC'])
                ->where('model_make_id', $brand)
                ->get();
        }else{
            $this->data['brand_name'] = '';

            //if no have brand then not need show any model
            $this->data['model'] = array();
        }

        $modelNameData = DB::table('models')
            ->select('model_name')
            ->where('model_id',$model)
            ->get();
        if(count($modelNameData)>0){
            foreach($modelNameData as $rs){
                $this->data['model_name'] = $rs->model_name;
            }
        }else{
            $this->data['model_name'] = '';
        }

        $categoryNameData = DB::table('types')
            ->select('type_name')
            ->where('type_id',$categories)
            ->get();
        if(count($categoryNameData)>0){
            foreach($categoryNameData as $rs){
                $this->data['category_name'] = $rs->type_name;
            }
        }else{
            $this->data['category_name'] = '';
        }

        //search message
        $search_message = "";
        if(isset($condition_value) && $condition_value != "")
        {
            $search_message = $condition_value . " ";
            if( $this->data['brand_name'] == "" && $this->data['model_name']== "" && $this->data['category_name'] == "")
            {
                $search_message .= "Car ";
            }
        }

        if(isset($this->data['brand_name']) && $this->data['brand_name'] != "")
        {
            $search_message .= ucwords(strtolower($this->data['brand_name'])) . " ";
        }

        if(isset($this->data['model_name']) && $this->data['model_name'] != "")
        {
            $search_message .= ucwords(strtolower($this->data['model_name'])) . " ";
        }

        if(isset($this->data['category_name']) && $this->data['category_name'] != "")
        {
            $search_message .= ucwords(strtolower($this->data['category_name'])) . " ";
        }

        $search_message .= " for Sale ";

        if( isset($this->data['state_name']) && $this->data['state_name'] != "" )
        {
            $search_message .= " in " . ucwords(strtolower($this->data['state_name']));
        }

        if( $condition_value == "" && $this->data['brand_name'] == "" &&
            $this->data['model_name'] == "" &&  $this->data['category_name'] == "" )
        {
            $search_message = "Advance Search";
        }

        $this->data['search_message'] = $search_message;

        //mileage range
        $mileage_ranges = array();
        array_push($mileage_ranges,
            ['0','5000'],
            ['5001', '10000'],
            ['10001', '20000'],
            ['20001', '30000'],
            ['30001', '40000'],
            ['40001', '50000'],
            ['50001', '60000'],
            ['60001', '70000'],
            ['70001', '80000'],
            ['80001', '90000'],
            ['90001', '100000'],
            ['100001', '110000'],
            ['110001', '120000'],
            ['120001', '130000'],
            ['130001', '140000'],
            ['140001', '150000'],
            ['150001', '160000'],
            ['160001', '170000'],
            ['170001', '180000'],
            ['180001', '190000'],
            ['190001', '200000'],
            ['200001', '210000'],
            ['210001', '220000'],
            ['220001', '230000'],
            ['230001', '240000'],
            ['240001', '250000'],
            ['250001', '260000'],
            ['260001', '270000'],
            ['270001', '280000'],
            ['280001', '290000'],
            ['290001', '300000'],
            ['300001', '310000'],
            ['310001', '320000'],
            ['320001', '330000'],
            ['330001', '340000'],
            ['340001', '350000'],
            ['350001', '360000'],
            ['360001', '370000'],
            ['370001', '380000'],
            ['380001', '390000'],
            ['390001', '400000']
        );

        $this->data['mileage_ranges'] = $mileage_ranges;

        $this->data['filter_data'] = $filter_data;
        if(Auth::guard('customer')->user())
            $this->data['cookies'] = DB::table('car_compares')->where('user_id',Auth::guard('customer')->user()->id)->pluck('car_id')->toArray();
        else
            $this->data['cookies'] = isset($_COOKIE['car_compare_list']) && $_COOKIE['car_compare_list'] != "" ? explode(',',$_COOKIE['car_compare_list']) : array();
        // echo "<pre>";print_r($filter_data);die;
        return view('newtheme.modules.car_list.index',$this->data);
    }

    public function runscript()
    {
        //get the car list
        $cars =  DB::table('cars')->get();

        //loop all
        $info = array();
        foreach( $cars as $car )
        {
            $yearMake = $car->year_make;
            $title = $car->title;

            $titleMakeYear = substr($title, 0, 4);
            if( $yearMake == $titleMakeYear )
            {
                $result = true;

                $newTitle = str_replace($yearMake . " ", "", $title);

                $info[$car->car_id]['title'] =  $newTitle;
                $info[$car->car_id]['car_id'] =  $car->car_id;
            }
            else
            {
                $info[$car->car_id]['title'] =  $car->title;
                $info[$car->car_id]['car_id'] =  $car->car_id;

            }
        }

        //update db
        foreach( array_chunk($info,200) as $chunk_info )
        {
            //reset array
            $id_array = array();

            $car_title_string = 'case ';
            foreach ($chunk_info as $car_id => $item) {
                $car_title_string .= " when car_id = " . $item['car_id'] . " then '" . $item['title'] . "'";
                $id_array[] = $item['car_id'];
            }

            $car_title_string .= ' END';

            $query = "UPDATE cars "
                . " SET title = (" . $car_title_string . ")"
                . " WHERE car_id in (" . implode(',', $id_array) . ")";

            //update db
            if (!empty($chunk_info)) {
//                print_r($query);
                DB::update($query);
            }
        }

        return response()->json([
            'status' => 'OK',
            'message' => "Script Run Success",
            'function' => 'Remove year make from title'
        ]);
    }

//    public function sendRegisterEmail(Request $request)
//    {
//        $name = "";
//        $email = "";
//        $contact_number = "";
//
//        if(!empty($request->name))
//        {
//            $name = $request->name;
//        }
//        else
//        {
//            return Redirect::back()
//                ->with('error', 'Please input name')
//                ->withInput();
//        }
//
//        if(!empty($request->email))
//        {
//            $email = $request->email;
//        }
//        else
//        {
//            return Redirect::back()
//                ->with('error', 'Please input email')
//                ->withInput();
//        }
//
//        if(!empty($request->contact_number))
//        {
//            $contact_number = $request->contact_number;
//        }
//        else
//        {
//            return Redirect::back()
//                ->with('error', 'Please fill contact number')
//                ->withInput();
//        }
//
//        if( !empty($request->name) && !empty($request->email) && !empty($request->contact_number) ){
//            Mail::send('/emails/userRegistration', ['name' => $name, 'email' => $email, 'contact_number' => $contact_number], function($m) use ($name, $email, $contact_number){
//                $m->to(env('MAIL_TO'))->subject("User Registration Info")->getSwiftMessage()
//                    ->getHeaders()
//                    ->addTextHeader('x-mailgun-native-send', 'true');
//            });
//        }
//
//        return Redirect::back()
//            ->with('success', 'Registration info send successful.')
//            ->withInput();
//
//    }

    public function getMerchantByCondition(Request $request){
        $getMerchant= array();
        if($request->condition_id == "")
        {
            $getMerchant = DB::table('cars')
                ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
                ->LeftJoin('users','merchant_branch.user_id','=','users.id')
                ->select('users.id as merchant_id','users.company_name as merchant_name')
                ->groupBy('users.id')
                ->orderBy('users.company_name','ASC')
                ->get();

            $responseData = array('success'=>'0', 'data'=>$getMerchant, 'message'=>"Returned all model.");
        }
        else
        {
            $getMerchant = DB::table('cars')
                ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
                ->LeftJoin('users','merchant_branch.user_id','=','users.id')
                ->select('users.id as merchant_id','users.company_name as merchant_name')
                ->where('cars.status', $request->condition_id)
                ->groupBy('users.id')
                ->orderBy('users.company_name','ASC')
                ->get();

            if(count($getMerchant)>0)
            {
                $responseData = array('success'=>'1', 'data'=>$getMerchant, 'message'=>"Returned all model.");
            }
            else
            {
                $responseData = array('success'=>'0', 'data'=>$getMerchant, 'message'=>"No records found.");
            }
        }
        $merchantResponse = json_encode($responseData);
        print $merchantResponse;
    }

    public function wishList()
    {

        return view('newtheme.modules.wishList.index');
    }

    public function wishListData(Request $request)
    {
        $car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        ->LeftJoin('models','cars.model_id','=','models.model_id')
        ->LeftJoin('types','cars.type_id','=','types.type_id')
        ->LeftJoin('states','cars.state_id','=','states.state_id')
        ->LeftJoin('cities','cars.city_id','=','cities.city_id')
        ->LeftJoin('users','cars.merchant_id','=','users.id')
        ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        ->select('cars.status','cars.city_id','cars.year_make','cars.title','cars.car_id','cars.price','cars.mileage',
            'cars.color','cars.vim','cars.sp_account','cars.fuel_type','makes.make_name', 'models.model_name', 'users.first_name',
            'users.last_name', 'states.state_name', 'cities.city_name','merchant_branch.merchant_name', 'merchant_branch.slug as merchant_slug')
        ->orderBy('cars.car_id','DESC')
        ->whereIn('cars.car_id',$request->List);

        $car = $car->paginate(20);
        foreach($car as $c)
        {
            $c->car_url = route('car_details',$c->getCarUrl());
            $c->merchant_branch_url = $c->merchant_slug? route('site.merchant', $c->merchant_slug): '#';
        }
        $this->data['car'] = $car;
        return $this->data;
        die($request->List);
    }


    public function car_viewed(Request $request)
    {
        try
        {
            $car = Cars::where('car_id',$request->get('car_id'))->first();
            DB::table('cars_statistics')->insert(
                array(
                    'car_id' => $car->car_id,
                    'merchant_id' => $car->merchant_id,
                    'make_id' => $car->make_id,
                    'model_id' => $car->model_id,
                    'type_id' => $car->type_id,
                    'state_id' => $car->state_id,
                    'city_id' => $car->city_id,
                    'price' => $car->price,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                )
            );
            return response()->json('Car inserted to viewed list.');
        } catch (\Throwable $th) {
            return response()->json('Something went wrong, please try after sometimes.');
        }

    }

    //Get Title Cars from Ajax
	public function getTitleCar(Request $request)
	{
		$result = Cars::where('title', 'LIKE', '%'. $request->input('param.term') .'%')->get()->map(function ($car) {
            return [
                'id' => $car->title,
                'text' => $car->title,
            ];
        });

		return response()->json([
			'results' => $result,
		]);
	}

    private function rearrange_cars($airtime_merchants,$airtime_cars,$car,$airtime_perpage,$selected_cars = array()){
        foreach($airtime_merchants as $m)
        {
            $merchant_car = DB::table('cars')
            ->LeftJoin('makes','cars.make_id','=','makes.make_id')
            ->LeftJoin('models','cars.model_id','=','models.model_id')
            ->LeftJoin('types','cars.type_id','=','types.type_id')
            ->LeftJoin('states','cars.state_id','=','states.state_id')
            ->LeftJoin('cities','cars.city_id','=','cities.city_id')
            ->LeftJoin('users','cars.merchant_id','=','users.id')
            ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
            ->whereIn('cars.car_id',$airtime_cars);

            if( count($selected_cars) < 100 )
            {
                //cause timeout problem so need to reduce use it
                //this statement cause the loading longer when selected car become longer list
                //to prevent duplicate car when the list is not many cars
                $merchant_car = $merchant_car->whereNotIn('cars.car_id',$selected_cars);
            }

            $merchant_car = $merchant_car->where('merchant_branch.id',$m->id)
            ->select('cars.year_make','cars.title','cars.car_id','cars.price','cars.mileage', 'cars.color','cars.vim', 'cars.sp_account',
                'cars.fuel_type','makes.make_name', 'models.model_name', 'users.first_name', 'users.last_name', 'states.state_name',
                'cities.city_name','merchant_branch.merchant_name','cars.updated_at',DB::raw('CAST(cars.mileage AS SIGNED) AS mileageorder'),
                'merchant_branch.slug as merchant_slug')
            ->inRandomOrder()->first();
            if ($airtime_perpage < 25) {
                if ($merchant_car) {
                    $selected_cars[] = $merchant_car->car_id;
                    $car->push($merchant_car);
                }
            }
            else
            {
                $car->push($merchant_car);
            }
        }
        $remain_amount = $airtime_merchants->count() % $airtime_perpage;

        //only recursive if airtime_merchants less than airtime_perpage
        if($remain_amount > 0  && $remain_amount < 1)
        {
            $this->rearrange_cars($airtime_merchants,$airtime_cars,$car,($airtime_perpage - $remain_amount),$selected_cars);
        }
        return $car;
    }

    public function privacyPolicy()
    {

        return view('newtheme.modules.privacypolicy.index');
    }

    public function termOfService()
    {

        return view('newtheme.modules.termofservice.index');
    }
}



