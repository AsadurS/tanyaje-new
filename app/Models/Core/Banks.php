<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;

class Banks extends Model
{
    //
    use Sortable;
    protected $table = "banks";
    protected $primaryKey = 'bank_id';
    public $sortable = ['bank_id', 'bank_name','created_at','updated_at'];

    public function paginator(){
        $bank =  Banks::sortable(['bank_id'=>'asc'])->select('banks.*')->paginate('30');
        return $bank;
    }

    public function filter($name,$param){
        switch ( $name )
        {
            case 'Name':
                $bank = Banks::sortable(['bank_id'=>'asc'])
                ->select('banks.*')
                ->where('banks.bank_name', 'LIKE', '%' . $param . '%')->paginate('30');
            break;

            default:
                $bank = Banks::sortable(['bank_id'=>'asc'])
                ->select('banks.*')
                ->paginate('30');
        }
        return $bank;
    }
}
