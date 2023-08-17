<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\Cars;
use App\Models\Core\Makes;
use App\Models\Core\Models;
use App\Models\Core\Images;
use App\Models\Core\Cities;
use App\Models\Core\States;
use App\Models\Core\Types;
use App\Models\Core\User;
use App\Models\Core\Segments;
use App\Models\Core\MerchantBranch;
use App\Models\Core\Setting;
use App\Zone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use App\Models\Core\Promotion;
use App\Models\Core\Redemption;
use Carbon\Carbon;
use File;
use Exception;

class PromotionController extends Controller
{
    //
    public function __construct( Cars $cars, Makes $makes, Models $models, Cities $cities, States $States, Types $types,  Promotion $promo, Redemption $redeem,  User $user){
        $this->Cars = $cars;
        $this->Makes = $makes;
        $this->Models = $models;
        $this->States = $States;
        $this->Cities = $cities;
        $this->Types = $types;
        $this->Promotion = $promo;
		$this->Redemption = $redeem;
		$this->User = $user;
		
    }

	public function promotions(Request $request){
		$title = array('pageTitle' => Lang::get("labels.ListingBanks"));
		$language_id = '1';

		$result = array();
		$message = array();
		$errorMessage = array();

		$admins = Promotion::all();
        $segments = Segments::all();
		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		
		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;

        
		//$name = $request->FilterBy;
       // $param = $request->parameter;
	

		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
		$orgid = Auth()->user()->id;
		$promos = Promotion::sortable(['promotion_id'=>'ASC'])
		->where('promotion.organisation','=', $orgid)
		->select('*');
			if (!empty($request->get('filter_name'))) {
				$promos = $promos->where('promotion.promotion_name', 'LIKE', '%' . $request->filter_name . '%');
			}
			if (!empty($request->get('start_date'))) {
				$promos = $promos->where('promotion.start_date', '>=', $request->start_date);
			}
			if (!empty($request->get('end_date'))) {
				$promos = $promos->where('promotion.end_date', '<=', $request->end_date);
			}
			if (!empty($request->get('organisation_id'))) {
				$promos = $promos->where('promotion.organisation', '=',  $request->organisation_id );
			}
			if (!empty($request->get('segment_type'))) {
				$promos = $promos->where('promotion.segment_id', '=',  $request->segment_type);
			}
			if (!empty($request->get('status_id'))) {
				$promos = $promos->where('promotion.status', '=',  $request->status_id );
			}
			$promos = $promos->paginate(30);
		} else{
			$promos = Promotion::sortable(['promotion_id'=>'ASC'])

		->select('*');
			if (!empty($request->get('filter_name'))) {
				$promos = $promos->where('promotion.promotion_name', 'LIKE', '%' . $request->filter_name . '%');
			}
			if (!empty($request->get('start_date'))) {
				$promos = $promos->where('promotion.period_start', '>=', $request->start_date);
			}
			if (!empty($request->get('end_date'))) {
				$promos = $promos->where('promotion.period_end', '<=', $request->end_date);
			}
			if (!empty($request->get('organisation_id'))) {
				$promos = $promos->where('promotion.organisation', '=',  $request->organisation_id );
			}
			if (!empty($request->get('segment_type'))) {
				$promos = $promos->where('promotion.segment_id', '=',  $request->segment_type);
			}
			if (!empty($request->get('status_id'))) {
				$promos = $promos->where('promotion.status', '=',  $request->status_id );
			}
			$promos = $promos->paginate(30);
			}

//		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
//			$orgid = Auth()->user()->id;
			//dd($orgid);
//			$promos = $this->Promotion->filtersa($name, $param, $orgid);
//        }else if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_NORMAL_ADMIN){
//			$promos = $this->Promotion->filter($name, $param);
//		}
//		else{
//			$promos = $this->Promotion->filter($name, $param);
//        }

		
		foreach($promos as $promo) {
			    $promo->promotion_redeem_history =  $this->Promotion->promotionclicks($promo['promotion_id']);
			}
		$result['organisation'] = $organisation;
		$result['admins'] = $promos;
		$result['segments'] = $segments;
    
        return view("admin.promotion.index",$title)->with('result', $result);
	//return view("admin.promotion.index",$title)->with('result', $result)->with('name', $name)->with('param', $param);
	}

	public function redemptions(Request $request){

		$title = array('pageTitle' => Lang::get("labels.ListingBanks"));
		$language_id = '1';
		$myid = $request->id;
		$admins = Redemption::all();
		$promotion = DB::table('promotion')
		->select('*')
		->get();
        $customers = DB::table('users')
		->select('*')
		->where('role_id', '=', '2')
		->get();
		$salesagentname =  DB::table('merchant_branch')
		->select('*')
		->get();

		$result = array();
		$message = array();
		$errorMessage = array();


		if ($myid) {
		   $admins = $admins->where('promotion_id','=', $myid);
		} 
		if (!empty($request->get('promotion_id'))) {
			$admins = $admins->where('promotion_id', '=',  $request->promotion_id );
		}
		if (!empty($request->get('customer_id'))) {
			$admins = $admins->where('customer_id', '=',  $request->customer_id );
		}
		if (!empty($request->get('referal_id'))) {
			$admins = $admins->where('sales_agent', '=',  $request->referal_id );
		}
		if (!empty($request->get('start_date'))) {
			$admins = $admins->where('redeem_date', '>=', $request->start_date);
		}
		if (!empty($request->get('end_date'))) {
			$admins = $admins->where('redeem_date', '<=', $request->end_date);
		}
		
		
		foreach($admins as $admin) {
		//	$admin['customer_name'] = $this->Redemption->customername($admin['customer_id']);

			$admin->customer_name = $this->Redemption->customername($admin->customer_id);
			if(!empty($admin->sales_agent))  {
				$admin->referral_name = $this->Redemption->salesname($admin->sales_agent);
			} else {
				$admin->referral_name = 'N/A';
			}
			$admin->promotion_name = $this->Redemption->promotionname($admin->promotion_id);

        }
		//dd($admins);
		$result['oldid'] = $myid;
		$result['salesagentname'] = $salesagentname;
		$result['promotions'] = $promotion;
		$result['customer'] = $customers;
		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		$result['admins'] = $admins;

		return view("admin.promotion.detail",$title)->with('result', $result);
       
	}

	public function insertPromotions(Request $request){
		$currentUser = Auth()->user()->id;
		// dd($request->all());

		// images upload
		$time = Carbon::now();
		if ($request->hasFile('main_image')) {
			$image = $request->file('main_image');
            if ($image) {
                $extension = $image->getClientOriginalExtension();
                $filename = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
				$destinaton_path = public_path('images/promotion/');
				$image->move($destinaton_path,$filename);
            }
		}
		else {
			$filename = "";
		}

		if ($request->hasFile('additional_image1')) {
			$image1 = $request->file('additional_image1');
            if ($image1) {
                $extension1 = $image1->getClientOriginalExtension();
                $filename1 = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension1;
				$destinaton_path = public_path('images/promotion/');
				$image1->move($destinaton_path,$filename1);
            }
		}
		else {
			$filename1 = "";
		}

		if ($request->hasFile('additional_image2')) {
			$image2 = $request->file('additional_image2');
            if ($image2) {
                $extension2 = $image2->getClientOriginalExtension();
                $filename2 = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension2;
				$destinaton_path = public_path('images/promotion/');
				$image2->move($destinaton_path,$filename2);
            }
		}
		else {
			$filename2 = "";
		}

		if ($request->hasFile('additional_image3')) {
			$image3 = $request->file('additional_image3');
            if ($image3) {
                $extension3 = $image3->getClientOriginalExtension();
                $filename3 = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension3;
				$destinaton_path = public_path('images/promotion/');
				$image3->move($destinaton_path,$filename3);
            }
		}
		else {
			$filename3 = "";
		}

		// insert new promotion
        $promotion_id = DB::table('promotion')->insertGetId([
			'promotion_name' => $request->promo_name,
			'segment_id' => $request->segment_id,
			'period_start' => $request->start_date . ' ' . $request->start_time,
			'period_end' => $request->end_date . ' ' . $request->end_time,
			'status' => $request->status_id,
			'main_image' => $request->main_image,
			'additional_images1' => $request->additional_image1,
			'additional_images2' => $request->additional_image2,
			'additional_images3' => $request->additional_image3,
			'organisation' => $request->organization_id,
			'max_redeem' => $request->max_validation,
			'serial_number' => $request->serial,
			'prefix' => $request->prefix,
			'description' => $request->description,
			'fine_print' => $request->fine_print,
			'location' => $request->location,
			'created_at' => date('Y-m-d H:i:s')
		]);

        $message = Lang::get("labels.PromoAddedMessage");
		return redirect()->back()->with('message', $message);
    }


    public function addpromotions(Request $request){
		$title = array('pageTitle' => Lang::get("labels.add_promotion"));

		$result = array();
		$message = array();
		$errorMessage = array();
		$images = new Images;
		$allimage = $images->getimages();
		//get function from ManufacturerController controller
		$myVar = new AddressController();
		$result['countries'] = $myVar->getAllCountries();
		$state = $this->States->getter();
		$segments = Segments::all();
		$result['state'] = $state;
		$city = $this->Cities->getter();
        $result['city'] = $city;
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
		
		return view("admin.promotion.add",$title)->with('result', $result)->with('allimage',$allimage);
	}

    public function editpromotions(Request $request){
		$title = array('pageTitle' => Lang::get("labels.add_promotion"));
		$myid = $request->id;
			
		$result = array();
		$message = array();
		$errorMessage = array();
		$images = new Images;
		$allimage = $images->getimages();
		//get function from other controller
		$myVar = new AddressController();
		$adminTypes = DB::table('user_types')->where('isActive', 1)->where('user_types_id','>','10')->get();
		
		$state = $this->States->getter();
		$result['state'] = $state;
		$city = $this->Cities->getter();
		$result['city'] = $city;

		$result['adminTypes'] = $adminTypes;
		
		$result['myid'] = $myid;

		$admins = DB::table('promotion')->where('promotion_id','=', $myid)->get();
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

		return view("admin.promotion.edit",$title)->with('result', $result)->with('allimage',$allimage);
	}
	public function updatepromotions(Request $request){
		$currentUser = Auth()->user()->id;
		$promotion_id    =   $request->myid;
		// dd($request->all());
		// images upload
		$time = Carbon::now();
		if ($request->hasFile('main_image')) {
			$image = $request->file('main_image');
            if ($image) {
                $extension = $image->getClientOriginalExtension();
                $filename = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
				$destinaton_path = public_path('images/promotion/');
				$image->move($destinaton_path,$filename);
            }
		}
		else {
			$filename = $request->old_main_image;
		}

		if ($request->hasFile('additional_images1')) {
			$image1 = $request->file('additional_images1');
            if ($image1) {
                $extension1 = $image1->getClientOriginalExtension();
                $filename1 = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension1;
				$destinaton_path = public_path('images/promotion/');
				$image1->move($destinaton_path,$filename1);
            }
		}
		else {
			$filename1 = $request->old_additional_images1;
		}

		if ($request->hasFile('additional_images2')) {
			$image2 = $request->file('additional_images2');
            if ($image2) {
                $extension2 = $image2->getClientOriginalExtension();
                $filename2 = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension2;
				$destinaton_path = public_path('images/promotion/');
				$image2->move($destinaton_path,$filename2);
            }
		}
		else {
			$filename2 = $request->old_additional_images2;
		}

		if ($request->hasFile('additional_images3')) {
			$image3 = $request->file('additional_images3');
            if ($image3) {
                $extension3 = $image3->getClientOriginalExtension();
                $filename3 = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension3;
				$destinaton_path = public_path('images/promotion/');
				$image3->move($destinaton_path,$filename3);
            }
		}
		else {
			$filename3 = $request->old_additional_images3;
		}

		
		DB::table('promotion')->where('promotion_id','=', $promotion_id)->update([
			'promotion_name' => $request->promo_name,
			'segment_id' => $request->segment_id,
			'period_start' => $request->period_start . ' ' . $request->start_time,
			'period_end' => $request->period_end . ' ' . $request->end_time,
			'status' => $request->status_id,
			'main_image' => $request->main_image,
			'additional_images1' => $request->additional_images1,
			'additional_images2' => $request->additional_images2,
			'additional_images3' => $request->additional_images3,
			'organisation' => $request->organization_id,
			'max_redeem' => $request->max_validation,
			'serial_number' => $request->serial,
			'prefix' => $request->prefix,
			'description' => $request->description,
			'fine_print' => $request->fine_print,
			'location' => $request->location,
			'updated_at' => date('Y-m-d H:i:s')
		]);
		
        $message = Lang::get("labels.PromoEditMessage");
		return redirect()->back()->with('message', $message);
	}

	public function deletepromotions(Request $request){
        $promotion_id  =  $request->users_id;
		
        DB::table('promotion')->where('promotion_id','=', $promotion_id)->delete();
        return redirect()->back()->withErrors([Lang::get("labels.DeletePromoMessage")]);
    }

	public function editredeems(Request $request){
		$title = array('pageTitle' => Lang::get("labels.add_promotion"));
		$myid = $request->id;
			
		$result = array();
		$message = array();
		$errorMessage = array();
		$images = new Images;
		$allimage = $images->getimages();
		//get function from other controller
		$myVar = new AddressController();
		$adminTypes = DB::table('user_types')->where('isActive', 1)->where('user_types_id','>','10')->get();
		$state = $this->States->getter();
		$result['state'] = $state;
		$promotions = Promotion::all();
		$city = $this->Cities->getter();
		$result['city'] = $city;

		$result['adminTypes'] = $adminTypes;
		$result['promos'] = $promotions;
		$result['myid'] = $myid;

		$admins = DB::table('promotion_redemption')->where('id','=', $myid)->get();
		// dd($admins);
		$result['admins'] = $admins;


		return view("admin.promotion.editredeem",$title)->with('result', $result)->with('allimage',$allimage);
	}
	public function updateredeem(Request $request){
		$currentUser = Auth()->user()->id;
		$promotion_id    =   $request->myid;
		// dd($request);
		DB::table('promotion_redemption')->where('id','=', $promotion_id)->update([
			'promotion_id' => $request->promotion_id,
			'redeem_date' => $request->redeem_date,
			'serial_prefix' => $request->serial,
			'updated_at' => date('Y-m-d H:i:s')
		]);
        $message = Lang::get("labels.PromoEditMessage");
		return redirect()->back()->with('message', $message);
	}

	public function deleteredeems(Request $request){
        $history_id  =  $request->users_id;
		
        DB::table('promotion_redemption')->where('id','=', $history_id)->delete();
        return redirect()->back()->withErrors([Lang::get("labels.DeleteRedeemMessage")]);
    }

	public function emailtemplate(Request $request){
		$title = array('pageTitle' => Lang::get("labels.linkPromoEmail"));
		$language_id = '1';
	

		$result = array();
		$message = array();
		$errorMessage = array();

		$admins = DB::table('promotion_email')->get();
		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		

		$result['admins'] = $admins;
       // dd($admins);
        return view("admin.promotion.emailtemp",$title)->with('result', $result);
	}

	public function updateemailtemplate(Request $request){
		$currentUser = Auth()->user()->id;

		// dd($request);
		DB::table('promotion_email')->update([
			'customer_email' => $request->customer_email,
			'customer_subject' => $request->customer_subject,
			'organiser_subject' => $request->organiser_subject,
			'organiser_email' => $request->organiser_email,
			'admin_subject' => $request->admin_subject,
			'admin_email' => $request->admin_email,
			
	
		]);
        $message = Lang::get("labels.promoEmailEditMessage");
		return redirect()->back()->with('message', $message);
	}


}
