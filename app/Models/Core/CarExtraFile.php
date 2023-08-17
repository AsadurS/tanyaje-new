<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;

class CarExtraFile extends Model
{
    //
    protected $table = "car_extra_file";
    protected $primaryKey = 'id';
  

    public function __construct()
    {
        $varsetting = new SiteSettingController();
        $this->varsetting = $varsetting;
    }

}
