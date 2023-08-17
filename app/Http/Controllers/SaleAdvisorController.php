<?php

namespace App\Http\Controllers;

use App\Models\Core\Cars;
use App\Models\Core\Makes;
use App\Models\Core\Models;
use App\Models\Core\Variant;
use App\Models\Core\MerchantBranch;
use App\Models\Core\MerchantInquiry;
use App\Models\Core\Banks;
use App\Models\Core\States;
use App\Models\Core\Cities;
use App\Models\Core\Countries;
use App\Models\Core\User;
use App\Models\Core\Promotion;
use App\Models\Core\Campaigns;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mail;
use App\Http\Controllers\TrafficController;
use App\Models\Core\TrafficItem;
use App\Models\Core\TrafficOrganisation;
use App\Models\Core\TrafficSa;
use App\Models\Core\TrafficPromotion;
use App\Models\Core\TrafficCampaign;
use App\Models\Core\CampaignResponse;

class SaleAdvisorController extends Controller
{
    
    private $versioning;

    public function __construct( Cars $cars, Makes $makes, Models $models, Cities $cities, States $States, User $user, Countries $countries, Variant $variant)
    {
	
		$this->States = $States;
        $this->Cars = $cars;
        $this->Makes = $makes;
        $this->Models = $models;
        $this->Variant = $variant;
		$this->Cities = $cities;
		$this->User = $user;
        $this->Countries = $countries;

        // Just update this whenever salesapp.css or npm run dev
        $this->versioning ='1.16.3.11';
    }

    public function index(Request $request, $slug) {
        
        $data = [];
        
        $organisation_name = $request->organisation_name;
        // $organisation_data = str_replace("-", " ", $organisation_name);
        $organisation_data = str_replace("-","%",$organisation_name);
        // $organisation_data = str_replace(" ","%",$organisation_name);

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        // $organisation = DB::table('users')->where('company_name','=', $organisation_data)->first();
        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;
     //  dd($request->sa_slug);exit(); 
        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
        $sa_slug = $sa_slug_formatted;
        
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();
        // $car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('models','cars.model_id','=','models.model_id')
        //             ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        //             ->where('merchant_name', 'LIKE', '%' . $organisation_data . '%')
        //             ->get();

        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }

        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            // $sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        // $data['showme'] = $car;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['asset_version'] = $this->versioning;
        // dd($data);
        
        return view('sales/landing')->with('data', $data);
    }

    public function show(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;
        
        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }
        

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
        $sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();

        $car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
                ->LeftJoin('models','cars.model_id','=','models.model_id')
                ->LeftJoin('users','cars.user_id','=','users.id')
                ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                ->LeftJoin('car_images','cars.car_id','=','car_images.cars_id')
                ->select('makes.make_name','models.model_name','cars.*','item_type.name as item_type_name','car_images.filename as car_image')
                ->where('cars.user_id','=',$organisation->id)
                ->groupBy('cars.vim')
                ->get();
        $data['itemlist'] = $car->toArray();
        
        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        $data['showme'] = $car;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['asset_version'] = $this->versioning;
        // dd($data);

        return view('sales/show')->with('data', $data);
    }

    public function ask(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;
        
        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
        $sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();

        // $car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('models','cars.model_id','=','models.model_id')
        //             ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        //             ->where('merchant_name', 'LIKE', '%' . $organisation_data . '%')
        //             ->get();
        
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();
        
        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        // $data['showme'] = $car;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['asset_version'] = $this->versioning;
        // dd($data);

        return view('sales/ask')->with('data', $data);
    }

    public function keep(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;
        
        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }

        // $promos = DB::table('promotion')->where([
        //     ['status','=', '1'],
        //     ['organisation','=', $organisation->id]
        // ])->get();

        $promos = DB::table('promotion')
                ->where('status','=', '1')
                ->where(function($q) use ($organisation) {
                    $q->where('organisation', $organisation->id)
                    ->orWhere('organisation', '534');
                })->get();

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
        $sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();

        // $car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('models','cars.model_id','=','models.model_id')
        //             ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        //             ->where('merchant_name', 'LIKE', '%' . $organisation_data . '%')
        //             ->get();
        
        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }

    
        $data['promo'] = $promos;
  
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        // $data['showme'] = $car;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['asset_version'] = $this->versioning;
        // dd($data);

        return view('sales/keep')->with('data', $data);
    }

    public function verify(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);
        $banks = Banks::all();

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;

        $organisation->full_address = $organisation->default_address_id;
        
        $bank1Obj = "";
        $bank2Obj = "";
        if ($organisation->bank_id1 !== null && $organisation->bank_id1 !== 'Select') {
            $bank1Obj = $banks->filter(function($item) use ($organisation) {
                return $item->bank_id == $organisation->bank_id1;
            })->first();
            if ($bank1Obj) {
                $organisation->bank_1image = $bank1Obj->logo;
            } else {
                $organisation->bank_1image = '';
            };
        } else {
            $organisation->bank_1image = '';
        }
        if ($organisation->bank_id2 !== null && $organisation->bank_id2 !== 'Select') {
            $bank2Obj = $banks->filter(function($item) use ($organisation) {
                return $item->bank_id == $organisation->bank_id2;
            })->first();
            if ($bank2Obj) {
                $organisation->bank_2image = $bank2Obj->logo;
            } else {
                $organisation->bank_2image = '';
            };
        } else {
            $organisation->bank_2image = '';
        }

        // $organisation->bank_1 =  $banks->firstWhere('bank_id', 1)

        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
        $sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();

        // $car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('models','cars.model_id','=','models.model_id')
        //             ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        //             ->where('merchant_name', 'LIKE', '%' . $organisation_data . '%')
        //             ->get();
        
        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        // $data['showme'] = $car;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['banks'] = $banks;
        $data['asset_version'] = $this->versioning;
        // dd($data);

        return view('sales/verify')->with('data', $data);
    }

    public function make(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }
        
        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;
        
        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();

        //     $make = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('models','cars.model_id','=','models.model_id')
        //             ->LeftJoin('users','cars.user_id','=','users.id')
        //             ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
        //             ->select('makes.make_id','makes.make_name','makes.image')
        //             ->where('cars.user_id','=',$organisation->id)
        //             ->groupBy('makes.make_name')
        //             ->get();
                    
        //     $data['make'] = $make->toArray();
        //     // dd($data['make']);
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
        $sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();

        // $car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('models','cars.model_id','=','models.model_id')
        //             ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        //             ->where('merchant_name', 'LIKE', '%' . $organisation_data . '%')
        //             ->get();
        
        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
            $make = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
                    ->LeftJoin('models','cars.model_id','=','models.model_id')
                    ->LeftJoin('users','cars.user_id','=','users.id')
                    ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                    ->select('makes.make_id','makes.make_name','makes.image')
                    ->where('cars.user_id','=',$organisation->id)
                    ->groupBy('makes.make_name')
                    ->get();
                    
            $data['make'] = $make->toArray();
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['asset_version'] = $this->versioning;
        if(count($data['make']) == 1){
            // Goes to first ID found, TODO create a page for multiple make
            return redirect()->action( 'SaleAdvisorController@model', ['organisation_name' => $organisation_name, 'sa_slug' => $sa_slug, 'make_id' => $data['make'][0]['make_id']] );
        }

        return view('sales/show_make')->with('data', $data);
        // return view('sales/show')->with('data', $data);
    }

    public function model(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;
        
        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();

        //     if($request->make_id){
        //         $make_id = $request->make_id;
        //         $data['make_id'] = $make_id;
        //     }
        //     else{
        //         $make_id = '';
        //     };
            
        //     $model = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('models','cars.model_id','=','models.model_id')
        //             ->LeftJoin('users','cars.user_id','=','users.id')
        //             ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
        //             ->LeftJoin('car_images','cars.car_id','=','car_images.cars_id')
        //             ->select('models.model_id','models.model_name','models.image','cars.sp_account','cars.vim','item_type.name as item_type_name','car_images.filename as car_image')
        //             ->where('cars.user_id','=',$organisation->id)
        //             ->where('cars.make_id','=',$make_id)
        //             ->groupBy('models.model_name')
        //             ->get();
        //     $data['model'] = $model->toArray();
        //     // dd($data['model']);
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
        $sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();

        // $car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('models','cars.model_id','=','models.model_id')
        //             ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        //             ->where('merchant_name', 'LIKE', '%' . $organisation_data . '%')
        //             ->get();
        
        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
            if($request->make_id){
                $make_id = $request->make_id;
                $data['make_id'] = $make_id;
            }
            else{
                $make_id = '';
            };
            
            $model = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
                    ->LeftJoin('models','cars.model_id','=','models.model_id')
                    ->LeftJoin('users','cars.user_id','=','users.id')
                    ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                    ->LeftJoin('car_images','cars.car_id','=','car_images.cars_id')
                    ->select('models.model_id','models.model_name','models.image','cars.sp_account','cars.vim','item_type.name as item_type_name','car_images.filename as car_image','cars.image as newimage')
                    ->where('cars.user_id','=',$organisation->id)
                    ->where('cars.make_id','=',$make_id)
                    ->groupBy('models.model_name')
                    ->get();
            $data['model'] = $model->toArray();
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        // $data['showme'] = $car;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['asset_version'] = $this->versioning;
        // dd($data);

        return view('sales/show_model')->with('data', $data);
        // return view('sales/show')->with('data', $data);
    }

    public function variants(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;
        
        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();

        //     if($request->model_id){
        //         $model_id = $request->model_id;
        //         $data['model_id'] = $model_id;
        //     }
        //     else{
        //         $model_id = '';
        //         // $model_id = '523','520','1044';
        //     };
            
        //     $item = DB::table('variants')->where('model_id','=', $request->model_id)->get();
        //     $data['item'] = $item->toArray();
        //     foreach ($data['item'] as $p) {
        //         $p->firstcar = DB::table('cars')->where('variant_id','=', $p->variant_id)->where('user_id','=',$organisation->id)->first();
        //     }
        //     // dd($data['item']);
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }
        $modelInfo = DB::table('models')->where('model_id','=', $request->model_id)->first();
        $data['model_info'] = $modelInfo;

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
$sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();

        // $car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('models','cars.model_id','=','models.model_id')
        //             ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        //             ->where('merchant_name', 'LIKE', '%' . $organisation_data . '%')
        //             ->get();
        
        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
            if($request->model_id){
                $model_id = $request->model_id;
                $data['model_id'] = $model_id;
            }
            else{
                $model_id = '';
                // $model_id = '523','520','1044';
            };
            
            $item = DB::table('variants')->where('model_id','=', $request->model_id)->get();
            $data['item'] = $item->toArray();
            foreach ($data['item'] as $p) {
                $p->firstcar = DB::table('cars')->where('variant_id','=', $p->variant_id)->where('user_id','=',$organisation->id)->first();
            }
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        // $data['showme'] = $car;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['asset_version'] = $this->versioning;

        return view('sales/show_variant')->with('data', $data);
        // return view('sales/show')->with('data', $data);
    }

    public function items(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;
        
        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();

        //     if($request->model_id){
        //         $model_id = $request->model_id;
        //         $data['model_id'] = $model_id;
        //     }
        //     else{
        //         $model_id = '';
        //         // $model_id = '523','520','1044';
        //     };
            
        //     $item = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('users','cars.user_id','=','users.id')
        //             ->LeftJoin('models','cars.model_id','=','models.model_id')
        //             ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
        //             ->LeftJoin('car_images','cars.car_id','=','car_images.cars_id')
        //             ->select('makes.make_name','models.model_name','cars.*','item_type.name as item_type_name','car_images.filename as car_image')
        //             ->where('cars.user_id','=',$organisation->id)
        //             ->where('cars.model_id','=',$model_id)
        //             ->groupBy('cars.vim')
        //             ->get();
        //     $data['item'] = $item->toArray();
        //     // dd($data['item']);
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }
        $modelInfo = DB::table('models')->where('model_id','=', $request->model_id)->first();
        $data['model_info'] = $modelInfo;

        $data['breadcrumb_name'] = $modelInfo->model_name;
        $data['breadcrumb_url'] = '/show/model/'.$modelInfo->model_make_id ;

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
$sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();

        // $car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('models','cars.model_id','=','models.model_id')
        //             ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        //             ->where('merchant_name', 'LIKE', '%' . $organisation_data . '%')
        //             ->get();
        
        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
            if($request->model_id){
                $model_id = $request->model_id;
                $data['model_id'] = $model_id;
            }
            else{
                $model_id = '';
                // $model_id = '523','520','1044';
            };
            
            $item = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
                    ->LeftJoin('users','cars.user_id','=','users.id')
                    ->LeftJoin('models','cars.model_id','=','models.model_id')
                    ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                    ->LeftJoin('car_images','cars.car_id','=','car_images.cars_id')
                    ->select('makes.make_name','models.model_name','cars.*','item_type.name as item_type_name','car_images.filename as car_image')
                    ->where('cars.user_id','=',$organisation->id)
                    ->where('cars.model_id','=',$model_id)
                    ->groupBy('cars.vim')
                    ->get();
            $data['item'] = $item->toArray();
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        // $data['showme'] = $car;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['asset_version'] = $this->versioning;
        // dd($data);

        return view('sales/show_item_list')->with('data', $data);
        // return view('sales/show')->with('data', $data);
    }
    public function itemsByVariant(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;
        
        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();

        //     if($request->variant_id){
        //         $variant_id = $request->variant_id;
        //         $data['variant_id'] = $variant_id;
        //     }
        //     else{
        //         $variant_id = '';
        //         // $model_id = '523','520','1044';
        //     };
            
        //     $item = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('variants','cars.variant_id','=','variants.variant_id')
        //             ->LeftJoin('users','cars.user_id','=','users.id')
        //             ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
        //             ->LeftJoin('car_images','cars.car_id','=','car_images.cars_id')
        //             ->select('makes.make_name','variants.variant_name','cars.*','item_type.name as item_type_name','car_images.filename as car_image')
        //             ->where('cars.user_id','=',$organisation->id)
        //             ->where('cars.variant_id','=',$variant_id)
        //             ->groupBy('cars.vim')
        //             ->get();
        //     $data['item'] = $item->toArray();
        //     // dd($data['item']);
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }
        $variantInfo = DB::table('variants')->where('variant_id','=', $request->variant_id)->first();
        $data['variant_info'] = $variantInfo;

        $data['breadcrumb_name'] = $variantInfo->variant_name;
        $data['breadcrumb_url'] = '/show/variants/'.$variantInfo->model_id ;

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
$sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();

        // $car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('models','cars.model_id','=','models.model_id')
        //             ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        //             ->where('merchant_name', 'LIKE', '%' . $organisation_data . '%')
        //             ->get();
        
        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
            if($request->variant_id){
                $variant_id = $request->variant_id;
                $data['variant_id'] = $variant_id;
            }
            else{
                $variant_id = '';
                // $model_id = '523','520','1044';
            };
            
            $item = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
                    ->LeftJoin('variants','cars.variant_id','=','variants.variant_id')
                    ->LeftJoin('users','cars.user_id','=','users.id')
                    ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                    ->LeftJoin('car_images','cars.car_id','=','car_images.cars_id')
                    ->select('makes.make_name','variants.variant_name','cars.*','item_type.name as item_type_name','car_images.filename as car_image')
                    ->where('cars.user_id','=',$organisation->id)
                    ->where('cars.variant_id','=',$variant_id)
                    ->groupBy('cars.vim')
                    ->get();
            $data['item'] = $item->toArray();
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        // $data['showme'] = $car;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['asset_version'] = $this->versioning;
        // dd($data);

        return view('sales/show_item_list')->with('data', $data);
        // return view('sales/show')->with('data', $data);
    }

    public function item_detail(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
$sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();
        
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
            if($request->item_id){
                $item_id = $request->item_id;
            }
            else{
                $item_id = '';
                // $model_id = '523','520','1044';
            };
            
            $item_detail = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
                    ->LeftJoin('models','cars.model_id','=','models.model_id')
                    ->LeftJoin('users','cars.user_id','=','users.id')
                    ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                    ->LeftJoin('car_images','cars.car_id','=','car_images.cars_id')
                    ->LeftJoin('types','types.type_id','=','cars.type_id')
                    ->select('makes.make_name','models.model_name','cars.*','item_type.name as item_type_name','car_images.filename as car_image', 'types.type_name')
                    ->where('cars.user_id','=',$organisation->id)
                    ->where('cars.car_id','=',$item_id)
                    ->get();
            $data['item_detail'] = $item_detail->toArray();
            
            if($item_detail[0]->sp_account == 'no_sp_account'){
                $data['item_detail'] = '';
                $item_detail_nospincar = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
                    ->LeftJoin('models','cars.model_id','=','models.model_id')
                    ->LeftJoin('users','cars.user_id','=','users.id')
                    ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                    ->LeftJoin('car_images','cars.car_id','=','car_images.cars_id')
                    ->LeftJoin('car_extra','car_extra.cars_id','=','cars.car_id')
                    ->LeftJoin('item_attributes','item_attributes.id','=','car_extra.item_attribute_id')
                    ->select('car_extra.item_attribute_value')
                    ->where('cars.user_id','=',$organisation->id)
                    ->where('cars.car_id','=',$item_id)
                    ->where('item_attributes.is_item_img','=', '1')
                    ->where('item_attributes.html_type','=', 'file')
                    ->get();

                if(!empty($item_detail[0]->image)){
                    $nospincar_img = array($item_detail[0]->image);
                }
                else{
                    $nospincar_img = array();
                }

                foreach($item_detail_nospincar as $imgs){
                    if ($imgs->item_attribute_value !== '') {
                        array_push($nospincar_img, $imgs->item_attribute_value);
                    }
                }
                $data['no_spincar_img'] = $nospincar_img;
                $item_detail[0]->no_spincar_img = $nospincar_img;
                $data['item_detail'] = $item_detail->toArray();
            }
            else{
                $data['no_spincar_img'] = '';
            }
        }
        else{
            $template = DB::table('templates')->first();
        }
        
        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        // $data['showme'] = $car;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['asset_version'] = $this->versioning;
        // dd($data);

        return view('sales/show_item_detail')->with('data', $data);
        // return view('sales/show')->with('data', $data);
    }
    public function ask_pricelist(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;
        
        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
$sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();

        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['asset_version'] = $this->versioning;
        // dd($data);

        return view('sales/ask_pricelist')->with('data', $data);
    }
    
    public function ask_brochure(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;
        
        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
$sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();

        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['asset_version'] = $this->versioning;
        // dd($data);

        return view('sales/ask_brochure')->with('data', $data);
    }
    public function ask_extrapricelist(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;
        
        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
$sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();

        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['asset_version'] = $this->versioning;
        // dd($data);

        return view('sales/ask_extra_pricelist')->with('data', $data);
    }
    
    public function ask_extrabrochure(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;
        
        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
$sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();

        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['asset_version'] = $this->versioning;
        // dd($data);

        return view('sales/ask_extra_brochure')->with('data', $data);
    }

    public function campaign(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);
        $banks = Banks::all();

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;

        $organisation->full_address = $organisation->default_address_id;

        // $organisation->bank_1 =  $banks->firstWhere('bank_id', 1)

        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
$sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();
        

        // $campaigns = DB::table('campaign')
        // ->where('status','=', '1')
        // ->where(function($q) use ($organisation) {
        //     $q->where('organisation', $organisation->id)
        //     ->orWhere('organisation', '534');
        // })->get();
        // $campaigns = DB::table('campaigns')
        // ->where('status','=', '1')
        // ->orWhere('org_id','=',$organisation->id)
        // ->orWhere('sa_id','=',$organisation->id)
        // ->get();
        // dd($organisation->id);
        // $car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('models','cars.model_id','=','models.model_id')
        //             ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        //             ->where('merchant_name', 'LIKE', '%' . $organisation_data . '%')
        //             ->get();
        
        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        }
        else{
            $template = DB::table('templates')->first();
        }

        // campaign
        $campaigns = DB::table('campaigns'); //->where('status','=', '1')
        if($organisation->id != null){
			$childOrg = array();
            $campaignIds = array();
			// main parent
			$hqOrg = DB::table('users')->where('users.id','=', $organisation->id)->get();
            // dd($hqOrg);
			if(count($hqOrg) > 0){
				array_push($childOrg, $hqOrg[0]->id);

				$checklvl2Org = DB::table('users')->where('users.id','=', $hqOrg[0]->parent_id)->get();
                // dd($checklvl2Org);
				if(count($checklvl2Org) > 0){
					foreach($checklvl2Org as $checklvl2Orgs){
						array_push($childOrg, $checklvl2Orgs->id);
						
						$checklvl3Org = DB::table('users')->where('users.id','=', $checklvl2Orgs->parent_id)->get();
						if(count($checklvl3Org) > 0){
							foreach($checklvl3Org as $checklvl3Orgs){
								array_push($childOrg, $checklvl3Orgs->id);
                                $checklvl4Org = DB::table('users')->where('users.id','=', $checklvl3Orgs->parent_id)->get();
                                if(count($checklvl4Org) > 0){
                                    foreach($checklvl4Org as $checklvl4Orgs){
                                        array_push($childOrg, $checklvl4Orgs->id);
                                        $checklvl5Org = DB::table('users')->where('users.id','=', $checklvl4Orgs->parent_id)->get();
                                        if(count($checklvl5Org) > 0){
                                            foreach($checklvl5Org as $checklvl5Orgs){
                                                array_push($childOrg, $checklvl5Orgs->id);
                                            }
                                        }
                                    }
                                }
							}
						}
					}
				}
			}
			// get id based on selected 
			if(count($childOrg) > 0){
				$org_list = $childOrg;
                // $campaigns = $campaigns->whereIn('campaigns.org_id' ,$childOrg);
                $campaigns = $campaigns->whereIn('campaigns.org_id' ,$childOrg);
                $storeCampaignId = (Clone $campaigns)->get();
                if(count($storeCampaignId) > 0){
                    foreach($storeCampaignId as $storeCampaignIds){
                        array_push($campaignIds, $storeCampaignIds->campaign_id);
                    }
                }
			}
		}
        // role organisation
		if($organisation->id){
			$hq_auth = $organisation->id;
			// $checkHq = DB::table('users')->where('users.id','=', $hq_auth)->select('users.parent_id')->first();
			// only hq can edit/delete campaign
			// if($checkHq->parent_id != null){
				// if not hq then need to display their own status
				// if not hq, need to use status from campaign_status_by_level table
				$campaigns = $campaigns->leftJoin('campaign_status_by_level','campaign_status_by_level.campaign_id','campaigns.campaign_id')->select(
					'campaigns.campaign_id','campaigns.campaign_name','campaigns.period_start','campaigns.period_end','campaigns.campaign_image',
					'campaign_status_by_level.status','campaign_status_by_level.organisation_id','campaign_status_by_level.campaign_id as c_id'
				);
                // need to check this org id got data or not in campaign status by level, if not then take the parent or default status
                // dd($childOrg);
                if(count($campaignIds) > 0){
                    for($c=0; $c<count($campaignIds); $c++){
                        if(count($childOrg) > 0){
                            for($a=0; $a<count($childOrg); $a++){
                                $checkCampaignData = (Clone $campaigns)->where('campaign_status_by_level.organisation_id','=', $childOrg[$a])->where('campaign_status_by_level.campaign_id','=',$campaignIds[$c])->get();
                                // dd($checkCampaignData);
                                if(count($checkCampaignData) > 0){
                                    // dd($childOrg[$a]);
                                    $campaigns = $campaigns->where('campaign_status_by_level.organisation_id','=', $childOrg[$a]);
                                    $campaigns = $campaigns->where('campaign_status_by_level.status','=', '1');
                                    break;
                                }
                            }
                        }
                    }
                }
                
				// $campaigns = $campaigns->where('campaign_status_by_level.organisation_id','=', $hq_auth);
                // $campaigns = $campaigns->where('campaign_status_by_level.status','=', '1');
			// }
		}
        $campaigns = $campaigns->get();

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        // $data['showme'] = $car;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['banks'] = $banks;
        $data['campaigns'] = $campaigns;
        $data['asset_version'] = $this->versioning;
        // dd($data);

        return view('sales/campaign')->with('data', $data);
    }


    public function campaigndetail(Request $request){
        $data = [];

        $organisation_name = $request->organisation_name;
        $organisation_data = str_replace("-","%",$organisation_name);
        $banks = Banks::all();

        $state = $this->States->getter();
        $data['state'] = $state;
        $city = $this->Cities->getter();
        $data['city'] = $city;
        $country = $this->Countries->getter();
        $data['country'] = $country;

        $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
        if(empty($organisation)){
            return redirect('not_allowed');
        }

        $user_to_address = DB::table('user_to_address')->where('user_id','=', $organisation->id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->first();
            $country = $country->where('countries_id','=',$address->entry_country_id)->first();
            if($country){
                $countries_name = $country->countries_name;
            }else{
                $countries_name = 'Malaysia';
            }
            $fulladdress = $address->entry_street_address . ", " . $address->entry_postcode . ", " . $address->entry_city . " " . $address->entry_state . " " . $countries_name;
		}else{
			$address = array();
            $fulladdress = "";
		}
		$data['address'] = $fulladdress;

        $organisation->full_address = $organisation->default_address_id;

        // $organisation->bank_1 =  $banks->firstWhere('bank_id', 1)

        // if($organisation){
        //     $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        // }
        // else{
        //     $template = DB::table('templates')->first();
        // }

        $sa_slug_formatted = strtok(str_replace("%3F", "?", $request->sa_slug), '?');
$sa_slug = $sa_slug_formatted;
        $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();
        

        // $campaigns = DB::table('campaign')
        // ->where('status','=', '1')
        // ->where(function($q) use ($organisation) {
        //     $q->where('organisation', $organisation->id)
        //     ->orWhere('organisation', '534');
        // })->get();

        if($request->campaign_id){
            $campaign_id = $request->campaign_id;
            $data['campaign_id'] = $campaign_id;
        }
        $campaign = DB::table('campaigns')
        ->where('campaign_id','=', $campaign_id)
        ->where('status','=', '1')
        ->first();
        $campaign->striped_description = strip_tags($campaign->description);
        // $car = Cars::LeftJoin('makes','cars.make_id','=','makes.make_id')
        //             ->LeftJoin('models','cars.model_id','=','models.model_id')
        //             ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
        //             ->where('merchant_name', 'LIKE', '%' . $organisation_data . '%')
        //             ->get();
        
        // $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->get();
        $document = DB::table('documents_manager')->where('documents_manager.user_id','=',$organisation->id)->orderBy(DB::raw('ISNULL(sort_order), sort_order'), 'ASC')->get();

        if($sale_advisor->count() == '0'){
            $organisation_data2 = str_replace("-"," ",$organisation_name);
            $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
            if(empty($organisation)){
                return redirect('not_allowed');
            }
            if(!empty($organisation)){
                $sale_advisor = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                ->where('merchant_branch.user_id','=',$organisation->id)
                ->get();
            }
            if(count($sale_advisor) == null){
                return  redirect('not_allowed');
            }
        }

        if($organisation){
            $template = DB::table('templates')->where('id','=',$organisation->template_id)->get();
        }
        else{
            $template = DB::table('templates')->first();
        }

        if($sale_advisor[0]->sa_profile_url){
            $data['sa_base_url'] = $sale_advisor[0]->sa_profile_url;
        }
        else{
            $data['sa_base_url'] = '/';
        }
        $mainUrlSearch  = array('{base_url}');
        $mainUrlReplace = array($data['sa_base_url']);
        if ($template[0]->a360_redirect_url !== '') {
            $data['a360_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->a360_redirect_url);
        } else {
            $data['a360_url'] = $data['sa_base_url'] . '/show/make';
        }
        if ($template[0]->askme_redirect_url !== '') {
            $data['askme_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->askme_redirect_url);
        } else {
            $data['askme_redirect_url'] = $data['sa_base_url'] . '/ask';
        }
        if ($template[0]->promotion_redirect_url !== '') {
            $data['promotion_redirect_url'] = str_replace($mainUrlSearch, $mainUrlReplace, $template[0]->promotion_redirect_url);
        } else {
            $data['promotion_redirect_url'] = $data['sa_base_url'] . '/keep';
        }
        if($sale_advisor[0]->whatsapp_default_message){
            $currentURL = url()->full();
            $whatsapp_search  = array('{advisor name}', '{organisation name}');
            $whatsapp_replace = array($sale_advisor[0]->merchant_name, $organisation_name);
            $sale_advisor[0]->whatsapp_message = urlencode($currentURL).' %0a'.urlencode(str_replace($whatsapp_search, $whatsapp_replace, $sale_advisor[0]->whatsapp_default_message));
            //$sale_advisor[0]->whatsapp_message = urlencode($sale_advisor[0]->whatsapp_message);
        }
        $data['organisation'] = $organisation;
        $data['sale_advisor'] = $sale_advisor;
        // $data['showme'] = $car;
        $data['askme'] = $document;
        $data['template'] = $template;
        $data['banks'] = $banks;
        $data['campaign'] = $campaign;
        $data['asset_version'] = $this->versioning;
        // dd($data);

        return view('sales/campaigndetail')->with('data', $data);
    }

    public function EventTracker(Request $request){
        // TrafficController::traffic($request->event,$request->promoid);

        if($request->event == "visit"){
            $currentURL = url()->full();
        }
        elseif($request->event == "promotion_click"){
            $currentURL = url()->previous();
        }
        elseif($request->event == "campaign_click"){
            $currentURL = url()->full();
        }
        else{
            $currentURL = url()->previous();
        }

        // main data
        $host = $_SERVER['HTTP_HOST'];
        $ua = $_SERVER['HTTP_USER_AGENT']; 
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $visitor_ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $visitor_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $visitor_ip = $_SERVER['REMOTE_ADDR'];
        }

        // event name
        if($request->event == ''){
            $event = 'visit';
        }else{
            $event = $request->event;
        }

        // promo id
        if($request->promoid == ''){
            $promoid = '';
        }else{
            $promoid = $request->promoid;
        }

        $pieces = explode("/", $currentURL);

        if(count($pieces) >= 3){
            if($pieces[3] == "sale-advisor" ){
                // get org_id
                $org_name = $pieces[4];
                $organisation_data = str_replace("-","%",$org_name);
                $organisation = DB::table('users')->where('company_name','LIKE', $organisation_data)->latest()->first();
    
                // get sa_id
                $sa_slug = $pieces[5];
                if(!empty($organisation)){
                    $sa = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                        ->where('merchant_branch.user_id','=',$organisation->id)
                        ->get();
                    if(count($sa) == null){
                        $organisation_data2 = str_replace("-"," ",$org_name);
                        $organisation = DB::table('users')->where('company_name','=', $organisation_data2)->latest()->first();
                        if(!empty($organisation)){
                            $sa = MerchantBranch::where('merchant_branch.slug',$sa_slug)
                            ->where('merchant_branch.user_id','=',$organisation->id)
                            ->get();
                        }
                    }
                    $org_id = $organisation->id;
                    $sa_id = $sa[0]->id;
                }
                else{
                    $org_id = '';
                    $sa_id = '';
                }

                $data = array(
                    'url'               => $currentURL,
                    'visitor_ip'        => $visitor_ip,
                    'user_agent' 		=> $ua,
                    'host'              => $host,
                    'event_type'        => $event,
                    'created_at'        => date('Y-m-d H:i:s')
                );
                $insert_data[] = $data; 
                if(!empty($insert_data)){
                    $traffic_id = DB::table('traffics')->insert($insert_data);
                    $traffic_id = DB::getPdo()->lastInsertId();

                    // check got organisation id
                    if($org_id != null){
                        $traffic_org = new TrafficOrganisation;
                        $traffic_org->traffic_id = $traffic_id;
                        $traffic_org->organisation_id = $org_id;
                        $traffic_org->save();
                    }
                    // check got sa id
                    if($sa_id != null){
                        $traffic_sa = new TrafficSa;
                        $traffic_sa->traffic_id = $traffic_id;
                        $traffic_sa->sa_id = $sa_id;
                        $traffic_sa->save();
                    }
                    // check got item id
                    if (strpos($currentURL, '/show/item_detail/') !== false) {
                        $item_id = $pieces[8];
                        $traffic_item = new TrafficItem;
                        $traffic_item->traffic_id = $traffic_id;
                        $traffic_item->item_id = $item_id;
                        $traffic_item->save();
                    }
                    // check got promotion id
                    if($promoid != null){
                        $traffic_promo = new TrafficPromotion;
                        $traffic_promo->traffic_id = $traffic_id;
                        $traffic_promo->promotion_id = $promoid;
                        $traffic_promo->save();
                    }
                    // check got campaign id
                    if (strpos($currentURL, '/campaign/') !== false) {
                        $campaign_id = $pieces[7];
                        $traffic_campaign = new TrafficCampaign;
                        $traffic_campaign->traffic_id = $traffic_id;
                        $traffic_campaign->campaign_id = $campaign_id;
                        $traffic_campaign->org_id = $org_id;
                        $traffic_campaign->sa_id = $sa_id;
                        $traffic_campaign->save();
                    }
                } 
            }
        }
        
        if(count($pieces) >= 3){
            if (strpos($pieces[3], 'promotions') !== false) {
                $data = array(
                    'url'               => $currentURL,
                    'visitor_ip'        => $visitor_ip,
                    'user_agent' 		=> $ua,
                    'host'              => $host,
                    'event_type'        => $event,
                    'created_at'        => date('Y-m-d H:i:s')
                );
                $insert_data[] = $data; 
                if(!empty($insert_data)){
                    $traffic_id = DB::table('traffics')->insert($insert_data);
                    $traffic_id = DB::getPdo()->lastInsertId();

                    // check got promotion id
                    if($promoid != null){
                        $traffic_promo = new TrafficPromotion;
                        $traffic_promo->traffic_id = $traffic_id;
                        $traffic_promo->promotion_id = $promoid;
                        $traffic_promo->save();
                    }
                } 
            }
        }

        // store campaign response
        if($request->event == "campaign_response"){
            if (strpos($currentURL, '/campaign/') !== false) {
                $campaign_id = $pieces[7];

                $campaign_response = new CampaignResponse;
                $campaign_response->campaign_id = $campaign_id;
                $campaign_response->org_id = $org_id;
                $campaign_response->sa_id = $sa_id;
                $campaign_response->name = $request->form_response['name'];
                $campaign_response->email = $request->form_response['email'];
                $campaign_response->contact = '60'.$request->form_response['contact'];
                $campaign_response->save();

                $organiserdetails =  DB::table('merchant_branch')->where('merchant_branch.id', '=', $sa_id)->first();

                // setup email
                $emailtemplate =  DB::table('campaign_email')->first();
                $sasubject =  $emailtemplate->sa_subject;
                $satemplate =  $emailtemplate->sa_email;  

                $organiserdetails =  DB::table('merchant_branch')->where('merchant_branch.id', '=', $sa_id)->first();

                //organiser shortcode subject
                $organiser_sub_short = array();
                $organiser_sub_short[0] = '{customer_name}';
                $organiser_sub_short[1] = '{customer_email}';
                $organiser_sub_replace = array();
                $organiser_sub_replace[0] = $request->form_response['name'];
                $organiser_sub_replace[1] = $request->form_response['email'];
                $organiserSUB =  str_replace($organiser_sub_short, $organiser_sub_replace, $sasubject);

                //organiser shortcode html
                $organiser_short = array();
                $organiser_short[0] = '{organiser}';
                $organiser_short[1] = '{customer_name}';
                $organiser_short[2] = '{customer_email}';
                $organiser_short[3] = '{customer_contact}';
                $organiser_replace = array();
                $organiser_replace[0] = $organiserdetails->merchant_name;
                $organiser_replace[1] = $request->form_response['name'];
                $organiser_replace[2] = $request->form_response['email'];
                $organiser_replace[3] = '60'.$request->form_response['contact'];
                $organiserHTML =  str_replace($organiser_short, $organiser_replace, $satemplate);

                //send email to organiser
                $organisers = array(
                    'body'  => $organiserHTML,
                );
                Mail::send('/newtheme/modules/email-template/campaign-sa', [ 'organisers' => $organisers ], function ($m) use ($organisers,$organiserdetails,$organiserSUB) {       
                $m->to($organiserdetails->merchant_email)
                        ->subject($organiserSUB)
                        ->getSwiftMessage()
                        ->getHeaders()
                        ->addTextHeader('x-mailgun-native-send', 'true');
                });
            }
        }

        return;
    }
}
