<?php

namespace App\Models\Core;
Use Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SaleAdvisors as Authenticatable;

class SaleAdvisors extends Model
{
    const ROLE_SALESADVISOR = 17;
    //
    use Sortable;
    protected $table = "merchant_branch";
    protected $primaryKey = 'id';
    protected $guard = 'saleadvisor';

    protected $hidden = [
        'password',
    ];
}
