<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;

class States extends Model
{
    public function __construct()
    {
        $varsetting = new SiteSettingController();
        $this->varsetting = $varsetting;
    }
    
    use Sortable;
    protected $primaryKey = 'state_id';
    public $sortable = ['state_id', 'state_name','created_at','updated_at'];

    public function paginator(){
        $state =  States::sortable(['state_id'=>'desc'])->select('states.state_id as id',  'states.state_name as name')->paginate(5);
        return $state;
    }

    public function insert($request){
        $date_added	= date('y-m-d h:i:s');
        
        $state_id = DB::table('states')->insertGetId([
            'state_name' 	    =>   $request->name,
            'created_at'	=>   $date_added
        ]);
    }

    public function edit($state_id){
        $editState= DB::table('states')
            ->select('states.state_id as id', 'states.state_name as name')
            ->where( 'states.state_id', $state_id )
            ->get();

        return $editState;
    }

    public function filter($name,$param){
        switch ( $name )
        {
            case 'Name':
                $state = States::sortable(['state_id'=>'desc'])
                ->select('states.state_id as id', 'states.state_name as name')
                ->where('states.state_name', 'LIKE', '%' . $param . '%')->paginate('10');
            break;

            default:
                $state = States::sortable(['state_id'=>'desc'])
                ->select('states.state_id as id', 'states.state_name as name')
                ->paginate('10');
        }
        return $state;
    }

    public function updaterecord($request){
        $last_modified 	=   date('y-m-d h:i:s');
                  
        DB::table('states')->where('state_id', $request->id)->update([
            'state_name' 	=>   $request->name,
            'updated_at'	=>   $last_modified                    
        ]);
    }

    //delete State
    public function destroyrecord($request){
        DB::table('states')->where('state_id', $request->state_id)->delete();
    }

    public function getter(){
        $states = States::sortable(['state_id'=>'ASC'])->get();
        return $states;
    }
}
