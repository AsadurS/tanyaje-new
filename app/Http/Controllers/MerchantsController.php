<?php

namespace App\Http\Controllers;

use App\Models\Core\Cars;
use App\Models\Core\MerchantBranch;
use App\Models\Core\MerchantInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mail;

class MerchantsController extends Controller
{
    public function index(Request $request, $slug) {
        $merchant = MerchantBranch::leftjoin('users','merchant_branch.user_id','users.id')
                        ->where('merchant_branch.slug',$slug)
                        ->select('merchant_branch.id as branch_id','merchant_branch.merchant_name',
                            'merchant_branch.merchant_phone_no','users.id as merchant_id','users.verified',
                            'users.banner_id','users.banner_color','users.logo_id','users.title_color',
                            'users.roc_scm_no','users.description','users.opening_hours','users.created_at', 'merchant_branch.merchant_email as branch_email')
                        ->first();

        $user_to_address = DB::table('user_to_address')
            ->where('user_id', $merchant->merchant_id )
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
            $merchant->address = $address[0]->entry_street_address. ", " . $address[0]->entry_postcode . " " .
                $address[0]->entry_city . ", " . $address[0]->entry_state;
            $merchant->location = $address[0]->entry_state;
        }
        else
        {
            $merchant->address = "";
            $merchant->location = "";
        }

        if( isset($merchant->address) )
        {
            $address_result = str_replace("&", "%26",  str_replace(" ", "+", $merchant->address));
//            $merchant->google_map_url = "https://maps.google.it/maps?q=" . $address_result . "&output=svembed";
            $merchant->google_map_not_embed = "https://www.google.com/maps?daddr=" . $address_result;
            $merchant->google_map_url = "https://www.google.com/maps/embed/v1/place?key=" . env('GoogleMapAPI') . "&q=" . $address_result;
            $merchant->direction_google_map_url = "https://www.google.com/maps?daddr=" . $address_result;
        }
        else
        {
            $merchant->google_map_not_embed = "";
            $merchant->google_map_url = "";
            $merchant->direction_google_map_url = "";
        }
        $sliders = DB::table('sliders_images')
                    ->leftJoin('image_categories','sliders_images.sliders_image','image_categories.image_id')
                    ->where('sliders_images.status',1)
                    ->where('image_categories.image_type','ACTUAL')
                    ->where('sliders_images.merchant_id',$merchant->merchant_id)
                    ->select('sliders_images.sliders_title','sliders_images.sliders_url','image_categories.path')
                    ->inRandomOrder()
                    ->get();

        $cars = Cars::where('merchant_id',$merchant->branch_id);
        if($request->has('q') && !empty($request->get('q')))
        {
            $cars->where('title','like','%'.$request->get('q').'%');
        }
        if($request->has('sort') && !empty($request->get('sort')))
        {
            if($request->get('sort') != "mileage")
                $cars->orderBy('cars.'.$request->get('sort'),$request->get('direction'));
            else
                $cars->orderBy('mileageorder',$request->get('direction'));
        }
        $cars = $cars->paginate(10);

        if(Auth::guard('customer')->user())
            $cookies = DB::table('car_compares')->where('user_id',Auth::guard('customer')->user()->id)->pluck('car_id')->toArray();
        else
            $cookies = isset($_COOKIE['car_compare_list']) && $_COOKIE['car_compare_list'] != "" ? explode(',',$_COOKIE['car_compare_list']) : array();
        return view()->make('newtheme.modules.merchants.index',compact('merchant','sliders','cars','cookies'));
    }

    public function store_inquiry(Request $request, $merchant_id)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required'
        ];

        $messages = [
            'name.required' => 'Please enter link title.',
            'email.required' => 'Please enter email.',
            'email.email' => 'Please enter valid email.',
            'phone.required' => 'Please enter phone.'

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
        {
            return response()->json($validator->getMessageBag()->toArray(), 422);
        }
        try{
            $inquiry=New MerchantInquiry;
            $inquiry->user_id = $merchant_id;
            $inquiry->name=$request->get('name');
            $inquiry->email=$request->get('email');
            $inquiry->phone=$request->get('phone');
            $inquiry->message=$request->get('message');
            $inquiry->save();

            //send to branch instead of merchant
//            $merchant_email = $inquiry->merchant->email;
            $merchant_email = $request->get('branch_email');
            Mail::send('/newtheme/modules/email-template/merchant-inquiry-notification', [ 'inquiry' => $inquiry ], function ($m) use ($merchant_email) {
                $m->to($merchant_email)
                    ->subject('New Inquiry')
                    ->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true');
            });

            return response()->json(["status" => "success","message" => "Thank you to submit data."]);
        }
        catch(\Exception $e)
        {
          return response()->json(["status" => "error","message" => "Something went wrong, Please try after sometime."]);
        }

    }
}
