<?php

namespace App\Models\Core;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Core\Promotion;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;


class Promotion extends Model
{
    //
    protected $table = "promotion";
    protected $appends = array('pageview','redemption');


    public function __construct()
    {
        $varsetting = new SiteSettingController();
        $this->varsetting = $varsetting;
    }
    
    use Sortable;

    public $sortable = ['promotion_id','promotion_name', 'segment_id','period_start','promotion_clicks','status'];

    public function paginator(){
    $admin =  Promotion::sortable(['promotion_id'=>'ASC'])->paginate(30);
        return $admin;
    }

    public function insert($request){
        $date_added	= date('y-m-d h:i:s');
        
        $promotion_id = DB::table('promotion')->insertGetId([
            'promotion_id' =>   $request->promotion_id,
            'promotion_name' 	=>   $request->promotion_name,
            'created_at'	=>   $date_added
        ]);
    }

    public function edit($promotion_id){
        $editModel= DB::table('promotion')
            ->select('promotion.promotion_id as id', 'promotion.promotion_name as name')
            ->where( 'promotion.promotion_id', $promotion_id )
            ->get();

        return $editPromotion;
    }

//    public function filter($name,$param){
//        switch ( $name )
//        {
//            case 'Name':
//                $admin = Promotion::sortable(['promotion_id'=>'ASC'])
 //               ->select('*')
 //               ->where('promotion.promotion_name', 'LIKE', '%' . $param . '%')->paginate(30);
 //           break;
//            case 'Segment':
 //               $admin = Promotion::sortable(['promotion_id'=>'ASC'])
 //                   ->where('promotion.segment_id', 'LIKE', '%' . $param . '%')
 //                   ->select('*')
 //                   ->paginate(30);
 //               break;
 //               case 'Status':
 //                   $admin = Promotion::sortable(['promotion_id'=>'ASC'])
 //                       ->where('promotion.status', 'LIKE', '%' . $param . '%')
 //                       ->select('*')
 //                       ->paginate(30);
 //                   break;  
  //                  case 'Period':
 //                       $admin = Promotion::sortable(['promotion_id'=>'ASC'])
  //                          ->where('Promotion.period_start', 'LIKE', '%' . $param . '%')
 //                           ->select('*')
 //                           ->paginate(30);
 //                       break;      
 //                    case 'Organization':
  //                          $admin = Promotion::sortable(['promotion_id'=>'ASC'])
 //                               ->where('Promotion.promotion_name', 'LIKE', '%' . $param . '%')
  //                              ->select('*')
  //                              ->paginate(30);
 //                           break;  
  //          default:
  //              $admin = Promotion::sortable(['promotion_id'=>'ASC'])
  //              ->select('*')
  //              ->paginate(30);
  //      }
  //      return $admin;
  //  }

    
 //   public function filtersa($name,$param,$orgid){
 //       switch ( $name )
 //       {
 //           case 'Name':
 //               $admin = Promotion::sortable(['promotion_id'=>'ASC'])
  //              ->select('*')
  //              ->where('promotion.promotion_name', 'LIKE', '%' . $param . '%')
 //               ->where('promotion.organisation','=', $orgid)->paginate(30);
 //           break;
  //          case 'Segment':
  //              $admin = Promotion::sortable(['promotion_id'=>'ASC'])
  //                  ->where('promotion.segment_id', 'LIKE', '%' . $param . '%')
  //                  ->where('promotion.organisation','=', $orgid)
  //                  ->select('*')
  //                  ->paginate(30);
  //              break;
  //              case 'Status':
  //                  $admin = Promotion::sortable(['promotion_id'=>'ASC'])
  //                      ->where('promotion.status', 'LIKE', '%' . $param . '%')
  //                      ->where('promotion.organisation','=', $orgid)
  //                      ->select('*')
  //                      ->paginate(30);
   //                 break;  
  //                  case 'Period':
  //                      $admin = Promotion::sortable(['promotion_id'=>'ASC'])
  //                          ->where('Promotion.period_start', 'LIKE', '%' . $param . '%')
  //                          ->where('promotion.organisation','=', $orgid)
  //                          ->select('*')
  //                          ->paginate(30);
  //                      break;      
  //                      case 'Organization':
   //                         $admin = Promotion::sortable(['promotion_id'=>'ASC'])
   //                             ->where('Promotion.promotion_name', 'LIKE', '%' . $param . '%')
  //                              ->where('promotion.organisation','=', $orgid)
  //                              ->select('*')
  //                              ->paginate(30);
  //                          break;  
  //          default:
 //               $admin = Promotion::sortable(['promotion_id'=>'ASC'])
 //               ->where('promotion.organisation','=', $orgid)
 //               ->select('*')
 //               ->paginate(30);
 //       }
 //       return $admin;
 //   }

    public function promotionclicks($history){
        $promoclick =  DB::table('promotion_redemption')
        ->where('promotion_redemption.promotion_id', '=',  $history)
        ->count();

        return $promoclick;
    }

    public function getter(){
        $admin = Promotion::sortable(['promotion_id'=>'ASC'])->get();
        return $admin;
    }

    // count pageview
    public function getPageviewAttribute()
    {
        return $this->calculatePageview();  
    }
    public function calculatePageview(){
        $pageview =  DB::table('traffics_promotion')
                ->leftJoin('traffics','traffics.id','=','traffics_promotion.traffic_id')
                ->select(DB::raw('COUNT(traffics_promotion.id) as pageview'))
                ->where('traffics_promotion.promotion_id','=',$this->promotion_id)
                ->where('traffics.event_type','=','promotion_click')
                ->first();
        return $pageview->pageview;
    }

    // count redemption
    public function getRedemptionAttribute()
    {
        return $this->calculateRedemption();  
    }
    public function calculateRedemption(){
        $redemption =  DB::table('promotion_redemption')
                ->select(DB::raw('COUNT(promotion_redemption.id) as pageview'))
                ->where('promotion_redemption.promotion_id','=',$this->promotion_id)
                ->first();
        return $redemption->pageview;
    }

}


