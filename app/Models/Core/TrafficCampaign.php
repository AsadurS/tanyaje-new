<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;

class TrafficCampaign extends Model
{
    protected $table = 'traffics_campaign';
    protected $fillable = ['id', 'campaign_name' ];
}
