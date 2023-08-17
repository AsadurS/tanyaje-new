<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;

class Segments extends Model
{
    //
    use Sortable;
    protected $table = "segments";
    protected $primaryKey = 'segment_id';
    public $sortable = ['segment_id', 'segment_name','sort','created_at','updated_at'];

    public function paginator(){
        $segment =  Segments::sortable(['segment_id'=>'asc'])->select('segments.*')->paginate('30');
        return $segment;
    }

    public function filter($name,$param){
        switch ( $name )
        {
            case 'Name':
                $segment = Segments::sortable(['segment_id'=>'asc'])
                ->select('segments.*')
                ->where('segments.segment_name', 'LIKE', '%' . $param . '%')->paginate('30');
            break;

            default:
                $segment = Segments::sortable(['segment_id'=>'asc'])
                ->select('segments.*')
                ->paginate('30');
        }
        return $segment;
    }
}
