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
class CustomerController extends Controller
{	public function __construct(Customers $customers)
    {
        $this->Customers = $customers;
    }
    public function profile()
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
      	if(count(request()->all()) > 0){
      		 $this->validate(request(), [
                'password' => ['nullable', 'string', 'min:6', 'same:confirm_password'],
                'confirm_password' => ['nullable', 'string', 'min:6'],
            ],
            [
                'password.same'=>'Password and Confirm Password Not Matched',
            ]);

      		$updated_at	= date('y-m-d h:i:s');
      		$customer = DB::table('users')->where('id',$customers->id)
		      		->update([
		                'first_name'    =>   request()->first_name,
		                'last_name'     =>   request()->last_name,
                        'dob'           =>   request()->dob,
                        'gender'        =>   request()->gender,
		                'state_id'      =>   request()->state_id,
						'updated_at'	=>	$updated_at/*,
		                'email'         =>   request()->email*/
		            ]);

            if(request()->password){
            	$customer = \DB::table('users')->where('id',$customers->id)
			      		->update([
			                'password'      =>   Hash::make(request()->password)
			            ]);
            }

            if(request()->phone){
            	$customer = \DB::table('users')->where('id',$customers->id)
			      		->update([
			                'phone'      =>   request()->phone
			            ]);
            }
            if ($customers_address) {
                $address_book = \DB::table('address_book')->where('address_book_id', $customers_address->address_book_id)
                        ->update([
                            /*'entry_country_id'          =>   request()->entry_country_id,
                            'entry_city'                =>   request()->entry_city,*/
                            'entry_state'               =>   request()->entry_state,
                            'entry_postcode'            =>   request()->entry_postcode,
                            /*'entry_street_address'      =>   request()->entry_street_address*/
                        ]);
            }
            else {
                $address_book = DB::table('address_book')->insert([
                            'user_id'          =>   Auth::guard('customer')->user()->id,
                            'entry_firstname'    =>   request()->first_name,
                            'entry_lastname'     =>   request()->last_name,
                            'entry_state'               =>   request()->entry_state,
                            'entry_postcode'            =>   request()->entry_postcode,
                        ]);
                $address_book = DB::table('address_book')->where('user_id', Auth::guard('customer')->user()->id)->first();
                $user_to_address = DB::table('user_to_address')->insert([
                    'user_id'          =>   Auth::guard('customer')->user()->id,
                    'address_book_id'  =>   $address_book->address_book_id
                ]);
            }
            return redirect()->back()->with('message','Profile Updated');
      	}else{
      		return view('newtheme.modules.profile.index')->with(compact('customerData','countries','states'));
      	}

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

    public function postChangePassword(Request $request)
    {
        $rules =[
            'old_password'  => 'required',
            'password'  => 'required|min:6|max:20|confirmed|different:old_password',
            'password_confirmation' =>  'required'
        ];

        $messages=[
            'old_password.required' => 'Please enter current password.',
            'password.required' => 'Please enter new password.',
            'password.min:6' => 'Please enter minimum 6 character.',
            'password.max:20' => 'Please enter maximum 20 character.',
            'password.confirmed' => 'Password and Confirmation Password are not same.',
            'password.different' => 'Old Password and New Password are same.',
            'password_confirmation.required' => 'Please enter new password again.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
        {
            return response()->json($validator->getMessageBag()->toArray(), 422);
        }
        
        if(Hash::check($request->get('old_password'),Auth::user()->password))
        {
            $user = User::find(Auth::id());
            $user->password = Hash::make($request->get('password'));
            $user->save();
            return response()->json(['success' => 'Your password changed successfully','password' => $request->get('password')], 200);
        }
        else
        {
            return response()->json(["old_password" => "Please enter correct current password"], 422);
        }
    }

    public function postChangeNumber(Request $request)
    {
        $rules =[
            'old_phone'  => 'required|min:10',
            'phone'  => 'required|min:10|different:old_phone',
        ];

        $messages=[
            'old_phone.required' => 'Please enter current phone number.',
            'old_phone.min:10' => 'Please enter minimum 10 character.',
            'phone.min:10' => 'Please enter minimum 10 character.',
            'phone.required' => 'Please enter new phone number.',
            'phone.different' => 'Old Phone number and New Phone number are same.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
        {
            return response()->json($validator->getMessageBag()->toArray(), 422);
        }
        
        if($request->get('old_phone') == Auth::user()->phone)
        {
            $user = User::find(Auth::id());
            $user->phone = $request->get('phone');
            $user->save();
            return response()->json(['success' => 'Your phone number changed successfully','phone' => $request->get('phone')], 200);
        }
        else
        {
            return response()->json(["old_phone" => "Please enter correct current phone number"], 422);
        }
    }
}



