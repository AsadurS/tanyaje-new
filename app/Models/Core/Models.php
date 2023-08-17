<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Core\Models;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;

class Models extends Model
{
    public function __construct()
    {
        $varsetting = new SiteSettingController();
        $this->varsetting = $varsetting;
    }
    
    use Sortable;

    public $sortable = ['model_id', 'model_name','created_at','updated_at'];
    public $sortableAs = ['make_name'];

    public function paginator(){
        $model =  Models::sortable(['model_id'=>'ASC'])->LeftJoin('makes','models.model_make_id','=','makes.make_id')->select('makes.make_name', 'models.model_id as id', 'models.model_name as name')->paginate(30);
        return $model;
    }

    public function insert($request){
        $date_added	= date('y-m-d h:i:s');
        
        $model_id = DB::table('models')->insertGetId([
            'model_make_id' =>   $request->model_make_id,
            'model_name' 	=>   $request->name,
            'image' => $request->image_label,
            'created_at'	=>   $date_added
        ]);
    }

    public function edit($model_id){
        $editModel= DB::table('models')
            ->select('models.model_make_id', 'models.model_id as id', 'models.model_name as name', 'models.image as image')
            ->where( 'models.model_id', $model_id )
            ->get();

        return $editModel;
    }

    public function filter($name,$param){
        switch ( $name )
        {
            case 'Model':
                $model = Models::sortable(['model_id'=>'ASC'])
                ->LeftJoin('makes','models.model_make_id','=','makes.make_id')
                ->select('makes.make_name', 'models.model_id as id', 'models.model_name as name')
                ->where('models.model_name', 'LIKE', '%' . $param . '%')->paginate(30);
            break;
            case 'Make':
                $model = Models::sortable(['model_id'=>'ASC'])
                    ->LeftJoin('makes','models.model_make_id','=','makes.make_id')
                    ->where('makes.make_name', 'LIKE', '%' . $param . '%')
                    ->select('makes.make_name', 'models.model_id as id', 'models.model_name as name')
                    ->paginate(30);
                break;
            default:
                $model = Models::sortable(['model_id'=>'ASC'])
                ->LeftJoin('makes','models.model_make_id','=','makes.make_id')
                ->select('makes.make_name', 'models.model_id as id', 'models.model_name as name')
                ->paginate(30);
        }
        return $model;
    }

    public function updaterecord($request){
        $last_modified 	=   date('y-m-d h:i:s');
                  
        DB::table('models')->where('model_id', $request->id)->update([
            'model_make_id' =>   $request->model_make_id,
            'model_name' 	=>   $request->name,
            'image' => $request->image_label,
            'updated_at'	=>   $last_modified                    
        ]);
    }

    //delete Model
    public function destroyrecord($request){
        DB::table('models')->where('model_id', $request->model_id)->delete();
    }

    public function getter(){
        $model = Models::sortable(['model_id'=>'ASC'])->get();
        return $model;
    }

    public function checkexistname($request){

        $checkModelName= DB::table('models')
            ->where( 'models.model_name', $request->name )
            ->get();
        return $checkModelName;
    }

    public function checkexistnameupdate($request){

        $checkModelName= DB::table('models')
            ->where( 'models.model_name', $request->name )
            ->where('models.model_id','!=', $request->id)
            ->get();
        return $checkModelName;
    }
}
