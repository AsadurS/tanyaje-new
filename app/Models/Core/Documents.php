<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;

class Documents extends Model
{
    //
    use Sortable;
    protected $table = "documents_manager";
    protected $primaryKey = 'id';
    public $sortable = [
                            'id', 
                            'user_id',
                            'name',
                            'attachment'
                        ];
}
