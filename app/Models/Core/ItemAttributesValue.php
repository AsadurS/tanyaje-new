<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;

class ItemAttributesValue extends Model
{
    //
    use Sortable;
    protected $table = "item_attributes_value";
    protected $primaryKey = 'id';
    public $sortable = ['id', 'name', 'item_attributes_id', 'created_at','updated_at'];

    public function paginator(){
        $item_attributes_value =  ItemAttributesValue::sortable(['id'=>'asc'])->select('item_attributes_value.*')->paginate(50);
        return $item_attributes_value;
    }

    public function filter($name,$param){
        switch ( $name )
        {
            case 'Name':
                $item_attributes_value = ItemAttributesValue::sortable(['id'=>'asc'])
                ->select('item_attributes_value.*')
                ->where('item_attributes_value.name', 'LIKE', '%' . $param . '%')->paginate('50');
            break;

            default:
                $item_attributes_value = ItemAttributesValue::sortable(['id'=>'asc'])
                ->select('item_attributes_value.*')
                ->paginate('50');
        }
        return $item_attributes_value;
    }
}
