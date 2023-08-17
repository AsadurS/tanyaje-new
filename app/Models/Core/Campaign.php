<?php

namespace App\Models\Core;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Core\Campaign;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;


class Campaign extends Model
{
    //
    protected $table = "campaigns";


    public function __construct()
    {
        $varsetting = new SiteSettingController();
        $this->varsetting = $varsetting;
    }
    
    use Sortable;

    public $sortable = ['campaign_id','campaign_name', 'campaign_image','org_id','status'];

    public function paginator(){
    $admin =  Campaign::sortable(['campaign_id'=>'ASC'])->paginate(30);
        return $admin;
    }

    public function insert($request){
        $date_added	= date('y-m-d h:i:s');
        
        $campaign_id = DB::table('campaigns')->insertGetId([
            'campaign_id' =>   $request->campaign_id,
            'campaign_name' 	=>   $request->campaign_name,
            'created_at'	=>   $date_added
        ]);
    }

    public function edit($campaign_id){
        $editModel= DB::table('campaigns')
            ->select('campaigns.campaign_id as id', 'campaigns.campaign_name as name')
            ->where( 'campaigns.campaign_id', $campaign_id )
            ->get();

        return $editCampaign;
    }

    public function getter(){
        $admin = Campaign::sortable(['campaign_id'=>'ASC'])->get();
        return $admin;
    }


}


