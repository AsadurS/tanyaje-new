<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Core\Variant;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;

class Variant extends Model
{

    protected $table = "variants";
    public function __construct()
    {
        $varsetting = new SiteSettingController();
        $this->varsetting = $varsetting;
    }
    
    use Sortable;

    public $sortable = ['variant_id', 'variant_name','created_at','updated_at','model_name'];
    public $sortableAs = ['model_name'];

    public function paginator(){
        $admin =  Variant::sortable(['variant_id'=>'ASC'])->LeftJoin('models','variants.model_id','=','models.model_id')->select('variants.*' , 'models.model_name as model_name')->paginate(30);
        
        return $admin;   
    }


    public function insert($request){
        $date_added	= date('y-m-d h:i:s');
      
        $model_id = DB::table('variants')->insertGetId([
            'model_id' =>   $request->models_id,
            'make_id' =>   $request->model_make_id,
            'variant_name' 	=>   $request->name,
            'image' => $request->image_label,
            'created_at'	=>   $date_added
        ]);
    }

    public function edit($model_id){
        $editModel= DB::table('variants')
            ->select('variants.make_id as make_id', 'variants.model_id as model_id', 'variants.variant_name as name' , 'variants.variant_id as variant_id' , 'variants.image as image')
            ->where( 'variants.variant_id', $model_id )
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
       
        DB::table('variants')->where('variant_id', $request->id)->update([
            'model_id' =>   $request->models_id,
            'make_id' =>   $request->model_make_id,
            'variant_name' 	=>   $request->name,
            'image' => $request->image_label,
            'updated_at'	=>   $last_modified                    
        ]);
    }

    //delete Model
    public function destroyrecord($request){
    
        DB::table('variants')->where('variant_id', $request->model_id)->delete();
    }

    public function getter(){
        $model = Models::sortable(['model_id'=>'ASC'])->get();
        return $model;
    }

    public function checkexistname($request){

        $checkVariantName= DB::table('variants')
            ->where('variants.variant_name', $request->name )
            ->get();
        return $checkVariantName;
    }

    public function checkexistnameupdate($request){

        $checkVariantName= DB::table('variants')
            ->where( 'variants.variant_name', $request->name )
            ->where('variants.variant_id','!=', $request->id)
            ->get();
        return $checkVariantName;
    }
}
