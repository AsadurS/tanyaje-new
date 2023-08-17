<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\Makes;
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




class MakeController extends Controller
{

    //
    public function __construct(Makes $make,Languages $language,Images $images)
    {
      $this->make =$make;
      $this->language = $language;
      $this->images = $images;
    }

    public function display(){
      $title = array('pageTitle' => Lang::get("labels.Make"));
      $make = $this->make->paginator(5);
      return view("admin.make.index")->with('make',$make);
    }

    public function add(Request $request){
      $allimage = $this->images->getimages();
      $title = array('pageTitle' => Lang::get("labels.AddMake"));
      return view("admin.make.add",$title)->with('allimage', $allimage);
    }

    public function insert(Request $request){
      $title = array('pageTitle' => Lang::get("labels.AddMake"));
      $result = $this->make->checkexistname($request);
      if(!empty($result[0]->make_name)){
        return redirect()->back()->withErrors(Lang::get("Make name already insert, please fill in other."))->withInput();
      }else{
        $this->make->insert($request);
      }
      return redirect()->back()->with('update', 'Content has been created successfully!');
    }

    public function edit(Request $request){
      $title = array('pageTitle' => Lang::get("labels.EditMake"));
      $make_id = $request->id;
      $editMake = $this->make->edit($make_id);
      $allimage = $this->images->getimages();
      return view("admin.make.edit",$title)->with('editMake', $editMake)->with('allimage', $allimage);
    }


    public function update(Request $request){
      $result = $this->make->checkexistnameupdate($request);
      if(!empty($result[0]->make_name)){
        return redirect()->back()->withErrors(Lang::get("Make name already insert, please fill in other."))->withInput();
      }else{
        $messages = 'update is not successfull' ;
        $title = array('pageTitle' => Lang::get("labels.EditMake"));
        $this->validate($request, [
            'id' => 'required',
            //'oldImage' => 'required',
            //'old_slug' => 'required',
            //'slug' => 'required',
            'name' => 'required',
            //'manufacturers_url' => 'required',

        ]);
        $this->make->updaterecord($request);
      }
      return redirect()->back()->with('update', 'Content has been created successfully!');
    }

   //delete Manufacturers
    public function delete(Request $request){

        $this->make->destroyrecord($request);
        return redirect()->back()->withErrors([Lang::get("labels.makeDeletedMessage")]);
        }

    public function filter(Request $request){

          $name = $request->FilterBy;
          $param = $request->parameter;
          $title = array('pageTitle' => Lang::get("labels.Make"));
          $make = $this->make->filter($name,$param);
          return view("admin.make.index",$title)->with('make', $make)->with('name',$name)->with('param',$param);
    }



}
