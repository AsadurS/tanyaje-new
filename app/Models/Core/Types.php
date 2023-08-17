<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Controllers\AdminControllers\SiteSettingController;
use Illuminate\Support\Facades\Validator;

class Types extends Model
{
    public function __construct()
    {
        $varsetting = new SiteSettingController();
        $this->varsetting = $varsetting;
    }

    use Sortable;

    public $sortable = ['type_id', 'type_name','created_at','updated_at'];

    public function paginator(){
        $type =  Types::sortable(['type_id'=>'desc'])
            ->leftJoin('image_categories as categoryTable','categoryTable.image_id', '=', 'types.image')
            ->select('types.type_id as id',  'types.type_name as name', 'categoryTable.path as imgpath')
            ->where('categoryTable.image_type','ACTUAL')->paginate(20);
        return $type;
    }

    public function insert($request){
        $date_added	= date('y-m-d h:i:s');

        $type_id = DB::table('types')->insertGetId([
            'type_name' 	=>   $request->name,
            'image'         =>   $request->image_id,
            'created_at'	=>   $date_added
        ]);
    }

    public function edit($type_id){
        $editType= DB::table('types')
            ->leftJoin('image_categories as categoryTable','categoryTable.image_id', '=', 'types.image')
            ->select('types.image','types.type_id as id', 'types.type_name as name', 'categoryTable.path as imgpath')
            ->where( 'types.type_id', $type_id )
            ->get();

        return $editType;
    }

    public function filter($name,$param){
        switch ( $name )
        {
            case 'Name':
                $type = Types::sortable(['type_id'=>'desc'])
                ->leftJoin('image_categories as categoryTable','categoryTable.image_id', '=', 'types.image')
                ->select('types.type_id as id', 'types.type_name as name', 'categoryTable.path as imgpath')
                ->where('types.type_name', 'LIKE', '%' . $param . '%')
                ->where('categoryTable.image_type','ACTUAL')->paginate('10');
            break;

            default:
                $type = Types::sortable(['type_id'=>'desc'])
                ->leftJoin('image_categories as categoryTable','categoryTable.image_id', '=', 'types.image')
                ->select('types.type_id as id', 'types.type_name as name', 'categoryTable.path as imgpath')
                ->where('categoryTable.image_type','ACTUAL')->paginate('10');
        }
        return $type;
    }

    public function updaterecord($request){
        $last_modified 	=   date('y-m-d h:i:s');
        if($request->image_id!==null){
            $uploadImage = $request->image_id;
        }else{
            $uploadImage = $request->oldImage;
        }
        DB::table('types')->where('type_id', $request->id)->update([
            'type_name' 	=>   $request->name,
            'image'         =>   $uploadImage,
            'updated_at'	=>   $last_modified
        ]);
    }

    //delete Type
    public function destroyrecord($request){
        DB::table('types')->where('type_id', $request->type_id)->delete();
    }

    public function getter(){
        $type = Types::sortable(['type_id'=>'ASC'])->get();
        return $type;
    }

    public function getterwithimage(){
        $type = Types::sortable(['type_id'=>'ASC'])
        ->leftJoin('image_categories as categoryTable','categoryTable.image_id', '=', 'types.image')
        ->select('types.*','categoryTable.path as imgpath')
        ->where('categoryTable.image_type','THUMBNAIL')->get();
        return $type;
    }
}
