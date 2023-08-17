<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\Types;
use App\Models\Core\Images;
use App\Models\Core\Setting;
use App\Models\Core\Languages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Exception;
use Kyslik\ColumnSortable\Sortable;




class TypeController extends Controller
{

    //
    public function __construct(Types $type,Languages $language,Images $images)
    {
      $this->type =$type;
      $this->language = $language;
      $this->images = $images;
    }

    public function display(){
      $title = array('pageTitle' => Lang::get("labels.Type"));
      $type = $this->type->paginator();
      return view("admin.type.index")->with('type',$type);
    }

    public function add(Request $request){
      $title = array('pageTitle' => Lang::get("labels.AddType"));
      $images = new Images;
      $allimage = $images->getimages();
      return view("admin.type.add",$title)->with('allimage', $allimage);
    }

    public function insert(Request $request){
      $title = array('pageTitle' => Lang::get("labels.AddType"));
      $this->type->insert($request);
      return redirect()->back()->with('update', 'Content has been created successfully!');
    }

    public function edit(Request $request){
      $title = array('pageTitle' => Lang::get("labels.EditType"));
      $images = new Images;
      $allimage = $images->getimages();
      $type_id = $request->id;
      $editType = $this->type->edit($type_id);
      return view("admin.type.edit",$title)->with('editType', $editType)->with('allimage', $allimage);
    }


    public function update(Request $request){
      $messages = 'update is not successfull' ;
      $title = array('pageTitle' => Lang::get("labels.EditType"));
      $this->validate($request, [
        'id' => 'required',
        'name' => 'required',
      ]);
      $this->type->updaterecord($request);
      return redirect()->back()->with('update', 'Content has been created successfully!');
    }

    //delete State
    public function delete(Request $request){
      $this->type->destroyrecord($request);
      return redirect()->back()->withErrors([Lang::get("labels.typeDeletedMessage")]);
    }

    public function filter(Request $request){
      $name = $request->FilterBy;
      $param = $request->parameter;
      $title = array('pageTitle' => Lang::get("labels.Type"));
      $type = $this->type->filter($name,$param);
      return view("admin.type.index",$title)->with('type', $type)->with('name',$name)->with('param',$param);
    }
}
