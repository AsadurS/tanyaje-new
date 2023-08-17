<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;

class ItemAttributes extends Model
{
    //
    use Sortable;
    protected $table = "item_attributes";
    protected $primaryKey = 'id';
    public $sortable = ['id', 'name', 'html_type', 'created_at','updated_at'];

    public function paginator(){
        $item_attributes =  ItemAttributes::sortable(['id'=>'asc'])->select('item_attributes.*')->paginate(50);
        return $item_attributes;
    }

    public function filter($name,$param){
        switch ( $name )
        {
            case 'Name':
                $item_attributes = ItemAttributes::sortable(['id'=>'asc'])
                ->select('item_attributes.*')
                ->where('item_attributes.name', 'LIKE', '%' . $param . '%')->paginate('50');
            break;

            default:
                $item_attributes = ItemAttributes::sortable(['id'=>'asc'])
                ->select('item_attributes.*')
                ->paginate('50');
        }
        return $item_attributes;
    }
}
