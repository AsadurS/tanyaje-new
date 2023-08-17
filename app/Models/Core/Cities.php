<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\DB;

class Cities extends Model
{
    //

    use Sortable;
    protected $primaryKey = 'city_id';
    public function state(){
        return $this->belongsTo('App\State');
    }

    public $sortable = ['city_id','city_name','created_at','updated_at'];
    public $sortableAs = ['state_name'];

    public function getter(){
        $cities = Cities::sortable(['city_id'=>'ASC'])->get();
        return $cities;
    }
    
    public function gettersearch($request){
        $cities = Cities::sortable(['city_id'=>'ASC'])
        ->LeftJoin('states','cities.city_state_id','=','states.state_id')
        ->where('city_state_id', $request->c2)
        ->get();
        return $cities;
    }

    public function paginator(){
        $zones = Cities::sortable(['city_id'=>'ASC'])
        ->LeftJoin('states','cities.city_state_id','=','states.state_id')
        ->paginate(30);
        return $zones;
    }

    public function filter($data){
        $name = $data['FilterBy'];
        $param = $data['parameter'];

        $result = array();
        $message = array();
        $errorMessage = array();

        switch ( $name ) {
            case 'City':
                $cities = Cities::sortable(['city_id'=>'ASC'])->where('city_name', 'LIKE', '%' . $param . '%')
                    ->LeftJoin('states','cities.city_state_id','=','states.state_id')
                    ->paginate(30);
                break;
            case 'State':
                $cities = Cities::sortable(['city_id'=>'ASC'])
                    ->LeftJoin('states','cities.city_state_id','=','states.state_id')
                    ->where('state_name', 'LIKE', '%' . $param . '%')
                    ->paginate(30);
                break;
            default:
                $cities = Cities::sortable(['city_id'=>'ASC'])
                    ->LeftJoin('states','cities.city_state_id','=','states.state_id')
                    ->paginate(30);
                break;
        }

        return $cities;
    }

    public function getstates(){
        $states = DB::table('states')->get();
        return $states;
    }

    public function insert($request){
        $date_added	= date('y-m-d h:i:s');
        $city_id = DB::table('cities')->insertGetId([
            'city_state_id'  	=>   $request->city_state_id,
            'city_name'			=>   $request->city_name,
            'created_at'	    =>   $date_added
        ]);
        return $city_id;
    }

    public function edit($request){
        $cities =  DB::table('cities')->where('city_id', $request->id)->first();
        return $cities;
    }

    public function updaterecord($request){
        $last_modified 	=   date('y-m-d h:i:s');
        DB::table('cities')->where('city_id', $request->city_id)->update([
            'city_name'  		 =>   $request->city_name,
            'city_state_id'	     =>   $request->city_state_id,
            'updated_at'	     =>   $last_modified
        ]);
    }

    public function getstate($request){
        $state = DB::table('states')->where('state_id', $request->id)->get();
        return $state;
    }

    public function deleterecord($request){
      DB::table('cities')->where('city_id', $request->id)->delete();
    }
}
