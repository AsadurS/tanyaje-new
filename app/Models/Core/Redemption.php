<?php

namespace App\Models\Core;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Core\Redemption;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;


class Redemption extends Model
{
    //
    protected $table = "promotion_redemption";
   
    public function __construct()
    {
        $varsetting = new SiteSettingController();
        $this->varsetting = $varsetting;
    }


    
    public function customername($name){
        $customername = DB::table('users')
        ->select('first_name')
        ->where('users.id', '=',  $name)
        ->first();  
 
        return $customername->first_name;

    }
    public function salesname($name){
        $salesagentname =  DB::table('merchant_branch')
        ->select('merchant_name')
        ->where('merchant_branch.id', '=',  $name)
        ->first();

        return $salesagentname->merchant_name;
    }
    public function promotionname($name){
        $promotionname =  DB::table('promotion')
        ->select('promotion_name')
        ->where('promotion.promotion_id', '=',  $name)
        ->first();

        return $promotionname->promotion_name;
    }





}


