<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
class SaleAdvisor extends Authenticatable
{



	/**
     * The attributes that are mass assignable.
     *
     * @var string
     */
	//use Notifiable;

	protected $guard = "saleadvisor";

	protected $table = 'merchant_branch';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

	//use user id of admin
	protected $primaryKey = 'id';

	//public $table = true;
    public static  function paySlipUpload(Request $request,$id=null)
    {

            if($id){
                $sales= SaleAdvisor::find($id);
                if($request->file('payslip')){
                    $name = time().'-'.$request->file('payslip')->getClientOriginalName();
                    $destinationPath = 'payslip';
                    if($sales->payslip){

                        file_exists(public_path("/payslip/".$sales->payslip));
                        unlink(public_path("/payslip/".$sales->payslip));
                    }
                    $request->file('payslip')->move($destinationPath,$name);
                    return $name;
                }
                else{
                    return $sales->payslip;
                }
            }else {
                if ($request->file('payslip')) {

                    $name = time() . '-' . $request->file('payslip')->getClientOriginalName();
                    $destinationPath = 'payslip';
                     $request->file('payslip')->move($destinationPath, $name);
                    return $name;
                }
            }
//            }
//        }catch(\Exception $e){
//            Log::error($e);
//        }
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
}
;