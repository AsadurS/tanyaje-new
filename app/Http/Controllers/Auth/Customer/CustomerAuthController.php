<?php

namespace App\Http\Controllers\Auth\Customer;

use Illuminate\Http\Request;
use App\role;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Symfony\Component\HttpKernel\Profiler\Profile;
use App\Models\Core\Countries;
use DB;
use Mail;
use Illuminate\View\View;
use App\Models\Core\States;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class CustomerAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(){
        $countries = Countries::all();
        $states = States::all();
        if(count(request()->all()) >0 ){
            $this->validate(request(), [
                'email' => ['required', 'string', 'email', 'max:255'],
                'phone' => ['required','string', 'unique:users'],
                'password' => ['required', 'string', 'min:6', 'same:confirm_password'],
                'confirm_password' => ['required', 'string', 'min:6'],
            ],
            [
                'phone.unique'=>'The mobile no already exist ! give new mobile no',
                'password.same'=>'Password and Confirm Password Not Matched',
            ]);

            $count = User::where('role_id', '2')->where('email', request()->email)->count();
            if( $count > 0 )
            {
                return redirect()->back()->withInput(request()->all())->withErrors(['error' => 'The Email Has Already Been Taken.',]);
            }

            $customer = \DB::table('users')->insertGetId([
                    'first_name'    =>   request()->first_name,
                    'last_name'     =>   request()->last_name,
                    'phone'         =>   request()->phone,
                    'email'         =>   request()->email,
                    'role_id'       =>   '2',
                    'password'      =>   Hash::make(request()->password),
                    'status'        =>   "1",
                    'verified'      =>   "1",
                    'created_at'    =>   date('Y-m-d H:i:s'),
                ]);

            $getCustomer = User::where('role_id', '2')->where('email', request()->email)->first();
            if($getCustomer){
                // $address_book = \DB::table('address_book')->insertGetId([
                //     /*'entry_country_id'          =>   request()->entry_country_id,
                //     'entry_city'                =>   request()->entry_city,
                //     'entry_state'               =>   request()->entry_state,*/
                //     'entry_postcode'            =>   request()->entry_postcode,
                //     'entry_street_address'      =>   request()->entry_street_address
                // ]);

                // $user_to_address = \DB::table('user_to_address')->insertGetId([
                //     'user_id'                   =>   $customer,
                //     'address_book_id'           =>   $address_book
                // ]);

                // if($address_book && $user_to_address){
                    //21-11-2020 : user not need verify. Auto verified by system
//                    DB::table('verify_users')->insert([
//                        'user_id'       => $getCustomer->id,
//                        'token'         => str_random(40),
//                        'created_at'    => date('Y-m-d H:i:s')
//                    ]);

//                    $get_token = DB::table('verify_users')->where('user_id', $getCustomer->id)->first();

//                    $verify_link = route('customer_registration_verify',['token'=>$get_token->token]);

//                    Mail::send('/newtheme/modules/email-template/new-registration', ['verify_link' => $verify_link], function ($m) use ($verify_link) {
//                        $m->to(request()->email)
//                            ->subject('Verify Your Email')
//                            ->getSwiftMessage()
//                            ->getHeaders()
//                            ->addTextHeader('x-mailgun-native-send', 'true');
//                    });
//                     return redirect()->back()->with('message','We sent you an email verification link. Check your email and click on the link to verify');

                    //send email to user for successful register
                    $loginPage = route('customer_login');
                    Mail::send('/newtheme/modules/email-template/successful-register', [ 'loginPage' => $loginPage ], function ($m) use ($loginPage) {
                        $m->to(request()->email)
                            ->subject('Register Successful')
                            ->getSwiftMessage()
                            ->getHeaders()
                            ->addTextHeader('x-mailgun-native-send', 'true');
                    });

                    $customer = array(
                        'first_name'    => $getCustomer->first_name,
                        'last_name'     => $getCustomer->last_name,
                        'email'         => $getCustomer->email,
                        'phone'         => $getCustomer->phone,
                    );

                    //send email to admin to notify new member register
                    Mail::send('/newtheme/modules/email-template/admin-new-member-join-notification', [ 'customer' => $customer ], function ($m) use ($customer) {
                        $m->to(env('MAIL_TO'))
                            ->subject('New Member Register Notification')
                            ->getSwiftMessage()
                            ->getHeaders()
                            ->addTextHeader('x-mailgun-native-send', 'true');
                    });

                    return redirect(route('customer_login'))->with('message','Account register successful.');
                //}
            }

        }
        else
        {
            $notice = "Register to be Tanyaje member now to enjoy some exclusive gift from us today";
            return view('newtheme.modules.profile.register')
                ->with(compact('countries','states', 'notice'));
        }
    }

    public function verifyCustomer($token)
    {
        $verifyToken = DB::table('verify_users')->where('token', $token)->first();
        if(isset($verifyToken) ){
            $verifyUser = DB::table('users')->where('id', $verifyToken->user_id)->first();
            if(!@$verifyUser->verified && @$verifyUser) {
                DB::table('users')->where('id', $verifyUser->id)->update([
                    'verified'  =>  1,
                    'status'  =>  1,
                ]);
                $message = "Your e-mail is verified.";
            }else{
                $message = "Your e-mail is already verified.";
            }

            return redirect(route('customer_login'))->with('message', $message);
        }else{
            return redirect(route('customer_login'))->withErrors([
                    'error' => 'Your email can not be identified',
                ])->withInput();
        }
    }

    public function login(){
       if(count(request()->all()) >0 ){
            $customer = User::where('email',request()->email)->first();
            if(@$customer && $customer->verified == 0){
                return redirect(route('customer_login'))->withErrors([
                    'error' => 'Your email not verified',
                ])->withInput();
            }elseif(@$customer && $customer->role_id != 2){
                return redirect(route('customer_login'))->withErrors([
                    'error' => 'Access Denied',
                ])->withInput();
            }elseif(@$customer && $customer->status == 0){
                return redirect(route('customer_login'))->withErrors([
                    'error' => 'Your account are deactivated',
                ])->withInput();
            }elseif(Auth::guard('customer')->attempt(['email' => request()->email, 'password'=> request()->password]))
            {
               $id = Auth::guard('customer')->user()->id;
               $saved_cars =  DB::table('car_user')->where('user_id', $id)->pluck('car_id');
                return redirect()->intended('profile');
            }else{
                return redirect(route('customer_login'))->withErrors([
                    'error' => 'email or password not matched.',
                ])->withInput();
            }


        }else{
            return view('newtheme.modules.profile.login');
        }

    }

    public function forgetPassword(){
        if(count(request()->all()) > 0){
            $this->validate(request(), [
                'email' => ['required', 'string', 'email', 'max:255', 'exists:users'],
            ]);

            $getCustomer = User::where('role_id', '2')->where('email', request()->email)->first();
            $verified_user = DB::table('verify_users')->where('user_id',$getCustomer->id)->exists();
            if($verified_user)
            {
                DB::table('verify_users')
                ->where('user_id',$getCustomer->id)
                ->update([
                        'token'         => str_random(40)
                    ]);
            }
            else{
                DB::table('verify_users')
                    ->where('user_id',$getCustomer->id)
                    ->insert([
                            'user_id' => $getCustomer->id,
                            'token'         => str_random(40),
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
            }
            $get_token = DB::table('verify_users')->where('user_id', $getCustomer->id)->first();
            $reset_password_link = route('customer_new_password',['token'=>$get_token->token]);

            Mail::send('/newtheme/modules/email-template/forget-password', ['reset_password_link' => $reset_password_link], function ($m) use ($reset_password_link,$getCustomer) {
                    $m->to($getCustomer->email)
                        ->subject('Reset Password')
                        ->getSwiftMessage()
                        ->getHeaders()
                        ->addTextHeader('x-mailgun-native-send', 'true');
            });

            return redirect()->back()->with('message',"We'll send a recovery link to")->withInput();
        }else{
            return view('newtheme.modules.profile.forget-password');
        }
    }

    public function newPassword($token)
    {   $getToken = DB::table('verify_users')->where('token', $token)->first();
        $getUser = DB::table('users')->where('id', $getToken->user_id)->first();

        if(count(request()->all()) > 0){
            $this->validate(request(), [
                'password' => ['required', 'string', 'min:6', 'same:confirm_password'],
                'confirm_password' => ['required', 'string', 'min:6'],
            ],
            [
                'password.same'=>'Password and Confirm Password Not Matched',
            ]);

            DB::table('verify_users')->where('token', $token)
                ->update([
                    'token'      =>   NULL,
                ]);

            DB::table('users')->where('id', $getToken->user_id)
                ->update([
                    'password'      =>   Hash::make(request()->password),
                ]);


            $message = "Your e-mail is verified. You can now login.";
            return redirect(route('customer_login'))->with('message','Your new password saved. You can now login');
        }else{
            if(@$getToken && @$getUser){
                return view('newtheme.modules.profile.new-password')->with(compact('token'));

            }else{
                return redirect(route('customer_login'))->withErrors([
                        'error' => 'Your email can not be identified',
                    ])->withInput();
            }
        }


    }

    public function redirectToProvider($provider)
    {
      switch ($provider) {
        case 'facebook':
            return Socialite::driver($provider)->redirect();
          break;
        case 'google':
            return Socialite::driver($provider)->redirect();
          break;
        default:
            return redirect()->route('home');
          break;
      }
    }

    public function handleProviderCallback(Request $request, $provider)
    {

      switch ($provider) {
        case 'facebook':
          $providerUser = Socialite::driver($provider)->user();
          $user = User::firstOrNew(['email' => $providerUser->email]);
          $user->fb_id = $providerUser->id;
          $user->role_id = 2;
          $user->first_name = $providerUser->name;
          $user->email = $providerUser->email;
          $user->password = bcrypt($providerUser->email);
          $user->status = true;
          $user->verified = true;
          $user->save();
          break;
        case 'google':
          $providerUser = Socialite::driver($provider)->user();
          $user = User::firstOrNew(['email' => $providerUser->email]);
          $user->google_id = $providerUser->id;
          $user->role_id = 2;
          $user->first_name = $providerUser->name;
          $user->email = $providerUser->email;
          $user->password = bcrypt($providerUser->email);
          $user->status = true;
          $user->verified = true;
          $user->save();
          break;
        default:
          return redirect()->route('home');
          break;
      }
      Auth::guard('customer')->login($user, true);
      return redirect()->route('profile');
    }

}
