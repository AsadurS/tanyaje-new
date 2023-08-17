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
use Mail;
use Symfony\Component\HttpKernel\Profiler\Profile;
use App\Models\Core\Countries;
use App\role;
use App\User;
use App\Models\Core\Customers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Braintree\Merchant;
use Carbon\Carbon;


class PromoController extends Controller
{	public function __construct(Customers $customers)
    {
        $this->Customers = $customers;
    }
    public function promotions()
    {
    	$countries = Countries::all();
	  	$states = States::all();
    	$customerData = array();
      	$customers = $this->Customers->edit(Auth::guard('customer')->user()->id);
        //dd(Auth::guard('customer')->user()->id);
         
        if(isset($_REQUEST['sid'])) {
            $salesagent = $_REQUEST['sid'];
        } else {
            $salesagent = false;
        }

       

        $customers_address = DB::table('address_book')
                                ->join('user_to_address','address_book.address_book_id','user_to_address.address_book_id')
                                ->where('user_to_address.user_id',Auth::guard('customer')->user()->id)
                                ->first();
    	$customerData['countries'] = $this->Customers->countries();
      	$customerData['customers'] = $customers;
      	$customerData['customers_address'] = $customers_address;


        $promos = DB::table('promotion')->where('status','=', '1')->where('period_start', '<=', Carbon::now())->where('period_end', '>=', Carbon::now())->paginate(15);
        foreach($promos as $promo) {   
           
            $coupon_total =  DB::table('promotion_redemption')->where('promotion_id','=', $promo->promotion_id)->where('customer_id','=', Auth::guard('customer')->user()->id)->count();
       
        if ($coupon_total) {         
                $promo->redeemed = true;      
            } else {
                $promo->redeemed = false;	
            }
		
                $promo->org_first =  DB::table('users')->where('id','=', $promo->organisation)->pluck('first_name')->first();
                $promo->org_last =  DB::table('users')->where('id','=', $promo->organisation)->pluck('last_name')->first();
            }
        $customerData['promo'] = $promos;
  
      // dd($customerData['promo']);
      		return view('newtheme.modules.promotions.index')->with(compact('customerData','countries','states','promos','salesagent'));
      	

    }

    public function logout()
    {
		$id = Auth::guard('customer')->user()->id;
        DB::table('car_user')->where('user_id', $id)->delete();
        if (request()->has('saved_cars')) {
            $saved_cars = json_decode(request()->saved_cars);
            if (!empty(request()->saved_cars)) {
                foreach ($saved_cars as $key => $value) {
                    DB::table('car_user')->insert(
                        ['user_id' => $id, 'car_id' => $value]
                    );
                }
            }
        }
        Auth::logout('customer');

       // return redirect(url('/'));
    }

    public function photo_upload(Request $request)
    {
        User::crop_image($request->get('photo'),$request->file('croppedImage')->getRealPath());
        $user = DB::table('users')->where('id',Auth::guard('customer')->user()->id)->update(['avatar' => $request->get('photo')]);
        return response()->json(['photo'=>asset("/uploads/users/".$request->get('photo'))], 200);
    }
     
    public function redeemcoupon(Request $request)
    {
        $id = Auth::guard('customer')->user()->id;
        $promotion_id  =  $request->redeem_id_coupon;
        $said = $request->salesagent_id;
        $organisation_id = $request->org_id;

        $customerdetails = DB::table('users')->where('users.id', '=',  $id)->first();  

        //REPLACE PLACEHOLDER ID > REQUEST
        $organiserdetails =  DB::table('users')->where('users.id', '=', $organisation_id)->first();
        $coupon_query = DB::table('promotion')->where('status','=', '1')->where('promotion_id','=', $promotion_id)->where('period_start', '<=', Carbon::now())->where('period_end', '>=', Carbon::now())->first();

        
        $emailtemplate =  DB::table('promotion_email')->first();

            
        $customersubject =  $emailtemplate->customer_subject;
        $customertemplate =  $emailtemplate->customer_email;  
        $organisersubject =  $emailtemplate->organiser_subject;
        $organisertemplate =  $emailtemplate->organiser_email;
        $adminsubject =  $emailtemplate->admin_subject; 
        $admintemplate =  $emailtemplate->admin_email; 

       

        if ($coupon_query) {
        //customer shortcode subject
        $customer_sub_short = array();
        $customer_sub_short[0] = '{prefix}';
        $customer_sub_short[1] = '{serial_number}';
        $customer_sub_replace = array();
        $customer_sub_replace[0] = $coupon_query->prefix;
        $customer_sub_replace[1] = $coupon_query->serial_number;
        $customerSUB =  str_replace($customer_sub_short, $customer_sub_replace, $customersubject);

        
        //organiser shortcode subject
        $organiser_sub_short = array();
        $organiser_sub_short[0] = '{customer_name}';
        $organiser_sub_short[1] = '{customer_email}';
        $organiser_sub_replace = array();
        $organiser_sub_replace[0] = $customerdetails->first_name . ' ' . $customerdetails->last_name;
        $organiser_sub_replace[1] = $customerdetails->email;
        $organiserSUB =  str_replace($organiser_sub_short, $organiser_sub_replace, $organisersubject);

        
        //admin shortcode subject
        $admin_sub_short = array();
        $admin_sub_short[0] = '{customer_name}';
        $admin_sub_short[1] = '{customer_email}';
        $admin_sub_short[2] = '{organiser}';
        $admin_sub_replace = array();
        $admin_sub_replace[0] = $customerdetails->first_name . ' ' . $customerdetails->last_name;
        $admin_sub_replace[1] = $customerdetails->email;
        $admin_sub_replace[2] = $organiserdetails->first_name . ' ' . $organiserdetails->last_name;
        $adminSUB =  str_replace($admin_sub_short, $admin_sub_replace, $adminsubject);


        //customer shortcode html
        $customer_short = array();
        $customer_short[0] = '{customer}';
        $customer_short[1] = '{main_image}';
        $customer_short[2] = '{prefix}';
        $customer_short[3] = '{serial_number}';
        $customer_short[4] = '{start_date}';
        $customer_short[5] = '{end_date}';
        $customer_replace = array();
        $customer_replace[0] = $customerdetails->first_name . ' ' . $customerdetails->last_name;
        $customer_replace[1] = $coupon_query->main_image;
        $customer_replace[2] = $coupon_query->prefix;
        $customer_replace[3] = $coupon_query->serial_number;
        $customer_replace[4] = $coupon_query->period_start;
        $customer_replace[5] = $coupon_query->period_end;
        $customerHTML =  str_replace($customer_short, $customer_replace, $customertemplate);

        //organiser shortcode html
        $organiser_short = array();
        $organiser_short[0] = '{organiser}';
        $organiser_short[1] = '{customer_name}';
        $organiser_short[2] = '{customer_email}';
        $organiser_short[3] = '{prefix}';
        $organiser_short[4] = '{serial_number}';
        $organiser_short[5] = '{start_date}';
        $organiser_short[6] = '{end_date}';
        $organiser_short[7] = '{main_image}';
        $organiser_replace = array();
        $organiser_replace[0] = $organiserdetails->first_name . ' ' . $organiserdetails->last_name;
        $organiser_replace[1] = $customerdetails->first_name . ' ' . $customerdetails->last_name;
        $organiser_replace[2] = $customerdetails->email;
        $organiser_replace[3] = $coupon_query->prefix;
        $organiser_replace[4] = $coupon_query->serial_number;
        $organiser_replace[5] = $coupon_query->period_start;
        $organiser_replace[6] = $coupon_query->period_end;
        $organiser_replace[7] = $coupon_query->main_image;
        $organiserHTML =  str_replace($organiser_short, $organiser_replace, $organisertemplate);
        

        //admin shortcode html
        $admin_short = array();
        $admin_short[0] = '{organiser}';
        $admin_short[1] = '{customer_name}';
        $admin_short[2] = '{customer_email}';
        $admin_short[3] = '{prefix}';
        $admin_short[4] = '{serial_number}';
        $admin_short[5] = '{start_date}';
        $admin_short[6] = '{end_date}';
        $admin_short[7] = '{main_image}';
        $admin_replace = array();
        $admin_replace[0] = $organiserdetails->first_name . ' ' . $organiserdetails->last_name;
        $admin_replace[1] = $customerdetails->first_name . ' ' . $customerdetails->last_name;
        $admin_replace[2] = $customerdetails->email;
        $admin_replace[3] = $coupon_query->prefix;
        $admin_replace[4] = $coupon_query->serial_number;
        $admin_replace[5] = $coupon_query->period_start;
        $admin_replace[6] = $coupon_query->period_end;
        $admin_replace[7] = $coupon_query->main_image;
        $adminHTML =  str_replace($admin_short, $admin_replace, $admintemplate);

       
   
       
        $coupon_total =  DB::table('promotion_redemption')->where('promotion_id','=', $promotion_id)->count();
        $coupon_total_customer =  DB::table('promotion_redemption')->where('promotion_id','=', $promotion_id)->where('customer_id','=', Auth::guard('customer')->user()->id)->count();
        $status = true;
     
            if ($coupon_query->max_redeem > '0' && $coupon_total >= $coupon_query->max_redeem ) {
                $error = "Promotion Redemption Amount Has Been Fully Redeemed";
                $status = false;
            }
        
            if ($coupon_total_customer >= '1' ) {
                $error = "Promotion Redemption Per Account Has Been Exceeded";
                $status = false;
            }
        } else {
            $error = "Promotion is Unavailable";
            $status = false;	
        }

        if ($status) {

            $increments = $coupon_query->serial_number + $coupon_total;	
         
            $promotion_id = DB::table('promotion_redemption')->insertGetId([
                'promotion_id' => $request->redeem_id_coupon,
                'customer_id' => $id,
                'serial_prefix' => $coupon_query->prefix . '' . $increments,
                'redeem_date' => date('Y-m-d H:i:s'),
                'sales_agent' => $said,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $message = "Promotion successfully redeemed";

            //send email to customer
            $customer = array(
                'email' => $customerdetails->email,
                'body'  => $customerHTML,
            );
            Mail::send('/newtheme/modules/email-template/promotion-customer', [ 'customer' => $customer ], function ($m) use ($customer,$customerdetails,$customerSUB) {
                $m->to($customerdetails->email)
                    ->subject($customerSUB)
                    ->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true');
            });

            //send email to organiser
            $organisers = array(
                'body'  => $organiserHTML,
            );
            Mail::send('/newtheme/modules/email-template/promotion-organiser', [ 'organisers' => $organisers ], function ($m) use ($organisers,$customerdetails,$organiserdetails,$organiserSUB) {       
               $m->to($organiserdetails->email)
              //  $m->to($customerdetails->email)
                    ->subject($organiserSUB)
                    ->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true');
            });

            //send email to admin
            $admins = array(
                'body'  => $adminHTML,
            );
            Mail::send('/newtheme/modules/email-template/promotion-admin', [ 'admins' => $admins ], function ($m) use ($admins,$customerdetails,$adminSUB) {
                $m->to(env('MAIL_TO'))
           //   $m->to($customerdetails->email)
                    ->subject($adminSUB)
                    ->getSwiftMessage()
                    ->getHeaders()
                    ->addTextHeader('x-mailgun-native-send', 'true');
            });

            return redirect()->back()->with('message', $message);



             } else {
                
                return redirect()->back()->with('error', $error);
        }
     
    }

    
    public function myrewards()
    {
    	$countries = Countries::all();
	  	$states = States::all();
    	$customerData = array();
      	$customers = $this->Customers->edit(Auth::guard('customer')->user()->id);
   
        $customers_address = DB::table('address_book')
                                ->join('user_to_address','address_book.address_book_id','user_to_address.address_book_id')
                                ->where('user_to_address.user_id',Auth::guard('customer')->user()->id)
                                ->first();
    	$customerData['countries'] = $this->Customers->countries();
      	$customerData['customers'] = $customers;
      	$customerData['customers_address'] = $customers_address;
        $promos = DB::table('promotion_redemption')->LeftJoin('promotion','promotion_redemption.promotion_id','=','promotion.promotion_id')->where('customer_id','=', $customers->id)->paginate(15);
       
        //dd($promos);
        foreach($promos as $promo) {   
                $promo->org_first =  DB::table('users')->where('id','=', $promo->organisation)->pluck('first_name')->first();
                $promo->org_last =  DB::table('users')->where('id','=', $promo->organisation)->pluck('last_name')->first();
            }
        $customerData['promo'] = $promos;
        
     
      		return view('newtheme.modules.promotions.redeem')->with(compact('customerData','countries','states','promos'));
      	

    }
    
}



