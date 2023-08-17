<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MerchantBranch extends Model
{
    //
    protected $table = "merchant_branch";

    protected $appends = array('pageview','whatsapp','call','waze','brochure','pricelist','promotion','redemption','action');

    // count pageview
    public function getPageviewAttribute()
    {
        return $this->calculatePageview();  
    }
    public function calculatePageview(){
        $pageview =  DB::table('traffics_sa')
                ->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
                ->select(DB::raw('COUNT(traffics_sa.id) as pageview'))
                ->where('traffics_sa.sa_id','=',$this->id)
                ->where('traffics.event_type','=','visit')
                ->first();
        return $pageview->pageview  + $this->countPageviewFromTrafficTable() ;
    }
    // count from traffic table
    public function countPageviewFromTrafficTable(){
        $pageview =  DB::table('traffics')
                ->select(DB::raw('COUNT(traffics.id) as pageview'))
                ->where('traffics.sa_id','=',$this->id)
                ->whereDate('traffics.created_at', '<=', '2021-09-23')
                ->first();
        return $pageview->pageview;
    }

    // count whatsapp
    public function getWhatsappAttribute()
    {
        return $this->calculateWhatsapp();  
    }
    public function calculateWhatsapp(){
        $whatsapp =  DB::table('traffics_sa')
                ->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
                ->select(DB::raw('COUNT(traffics_sa.id) as pageview'))
                ->where('traffics_sa.sa_id','=',$this->id)
                ->where('traffics.event_type','=','whatsapp')
                ->first();
        return $whatsapp->pageview + $this->countWhatsappFromEventTable() ;
    }
    // count from event table
    public function countWhatsappFromEventTable(){
        $whatsapp =  DB::table('events')
                ->select(DB::raw('COUNT(events.id) as pageview'))
                ->where('events.sa_id','=',$this->id)
                ->whereDate('events.created_at', '<=', '2021-09-23')
                ->where('events.event','=','whatsapp')
                ->first();
        return $whatsapp->pageview;
    }

    // count call
    public function getCallAttribute()
    {
        return $this->calculateCall();  
    }
    public function calculateCall(){
        $call =  DB::table('traffics_sa')
                ->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
                ->select(DB::raw('COUNT(traffics_sa.id) as pageview'))
                ->where('traffics_sa.sa_id','=',$this->id)
                ->where('traffics.event_type','=','call')
                ->first();
        return $call->pageview + $this->countCallFromEventTable() ;
    }
    // count from event table
    public function countCallFromEventTable(){
        $whatsapp =  DB::table('events')
                ->select(DB::raw('COUNT(events.id) as pageview'))
                ->where('events.sa_id','=',$this->id)
                ->whereDate('events.created_at', '<=', '2021-09-23')
                ->where('events.event','=','call')
                ->first();
        return $whatsapp->pageview;
    }

    // count waze
    public function getWazeAttribute()
    {
        return $this->calculateWaze();  
    }
    public function calculateWaze(){
        $waze =  DB::table('traffics_sa')
                ->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
                ->select(DB::raw('COUNT(traffics_sa.id) as pageview'))
                ->where('traffics_sa.sa_id','=',$this->id)
                ->where('traffics.event_type','=','waze')
                ->first();
        return $waze->pageview + $this->countWazeFromEventTable() ;
    }
    // count from event table
    public function countWazeFromEventTable(){
        $whatsapp =  DB::table('events')
                ->select(DB::raw('COUNT(events.id) as pageview'))
                ->where('events.sa_id','=',$this->id)
                ->whereDate('events.created_at', '<=', '2021-09-23')
                ->where('events.event','=','waze')
                ->first();
        return $whatsapp->pageview;
    }

    // count brochure
    public function getBrochureAttribute()
    {
        return $this->calculateBrochure();  
    }
    public function calculateBrochure(){
        $brochure =  DB::table('traffics_sa')
                ->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
                ->select(DB::raw('COUNT(traffics_sa.id) as pageview'))
                ->where('traffics_sa.sa_id','=',$this->id)
                ->where('traffics.event_type','=','brochure')
                ->first();
        return $brochure->pageview + $this->countBrochureFromEventTable() ;
    }
    // count from event table
    public function countBrochureFromEventTable(){
        $brochure =  DB::table('events')
                ->select(DB::raw('COUNT(events.id) as pageview'))
                ->where('events.sa_id','=',$this->id)
                ->whereDate('events.created_at', '<=', '2021-09-23')
                ->where('events.event','=','brochure')
                ->first();
        return $brochure->pageview;
    }

    // count pricelist
    public function getPricelistAttribute()
    {
        return $this->calculatePricelist();  
    }
    public function calculatePricelist(){
        $pricelist =  DB::table('traffics_sa')
                ->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
                ->select(DB::raw('COUNT(traffics_sa.id) as pageview'))
                ->where('traffics_sa.sa_id','=',$this->id)
                ->where('traffics.event_type','=','pricelist')
                ->first();
        return $pricelist->pageview + $this->countPricelistFromEventTable() ;
    }
    // count from event table
    public function countPricelistFromEventTable(){
        $pricelist =  DB::table('events')
                ->select(DB::raw('COUNT(events.id) as pageview'))
                ->where('events.sa_id','=',$this->id)
                ->whereDate('events.created_at', '<=', '2021-09-23')
                ->where('events.event','=','pricelist')
                ->first();
        return $pricelist->pageview;
    }

    // count promotion
    public function getPromotionAttribute()
    {
        return $this->calculatePromotion();  
    }
    public function calculatePromotion(){
        $promotion =  DB::table('traffics_sa')
                ->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
                ->select(DB::raw('COUNT(traffics_sa.id) as pageview'))
                ->where('traffics_sa.sa_id','=',$this->id)
                ->where('traffics.event_type','=','promotion')
                ->first();
        return $promotion->pageview + $this->countPromotionFromEventTable() ;
    }
    // count from event table
    public function countPromotionFromEventTable(){
        $promotion =  DB::table('events')
                ->select(DB::raw('COUNT(events.id) as pageview'))
                ->where('events.sa_id','=',$this->id)
                ->whereDate('events.created_at', '<=', '2021-09-23')
                ->where('events.event','=','promotion')
                ->first();
        return $promotion->pageview;
    }

    // count redemption
    public function getRedemptionAttribute()
    {
        return $this->calculateRedemption();  
    }
    public function calculateRedemption(){
        $redemption =  DB::table('traffics_sa')
                ->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
                ->select(DB::raw('COUNT(traffics_sa.id) as pageview'))
                ->where('traffics_sa.sa_id','=',$this->id)
                ->where('traffics.event_type','=','redemption')
                ->first();
        return $redemption->pageview + $this->countRedemptionFromEventTable() ;
    }
    // count from event table
    public function countRedemptionFromEventTable(){
        $redemption =  DB::table('events')
                ->select(DB::raw('COUNT(events.id) as pageview'))
                ->where('events.sa_id','=',$this->id)
                ->whereDate('events.created_at', '<=', '2021-09-23')
                ->where('events.event','=','redemption')
                ->first();
        return $redemption->pageview;
    }

    // count action
    public function getActionAttribute()
    {
        return $this->calculateAction();  
    }
    public function calculateAction(){
        $action =  DB::table('traffics_sa')
                ->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
                ->select(DB::raw('COUNT(traffics_sa.id) as pageview'))
                ->where('traffics_sa.sa_id','=',$this->id)
                ->where('traffics.event_type','!=','visit')
                ->first();
        return $action->pageview + $this->countActionFromEventTable() ;
    }
    // count from event table
    public function countActionFromEventTable(){
        $action =  DB::table('events')
                ->select(DB::raw('COUNT(events.id) as pageview'))
                ->where('events.sa_id','=',$this->id)
                ->whereDate('events.created_at', '<=', '2021-09-23')
                ->where('events.event','!=','visit')
                ->first();
        return $action->pageview;
    }
}
