<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\Models;
use App\Models\Core\Makes;
use App\Models\Core\Setting;
use App\Models\Core\Languages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Exception;
use Kyslik\ColumnSortable\Sortable;




class ModelController extends Controller
{

    //
    public function __construct(Models $model,Languages $language,Makes $makes)
    {
      $this->model =$model;
      $this->language = $language;
      $this->makes = $makes;
    }

    public function display(){
      $title = array('pageTitle' => Lang::get("labels.Model"));
      $model = $this->model->paginator(5);
      return view("admin.model.index")->with('model',$model);
    }

    public function add(Request $request){
      $title = array('pageTitle' => Lang::get("labels.AddModel"));
      $result = array();
      $message = array();
      $make = $this->makes->getter();
      $result['make'] = $make;
      $result['message'] = $message;
      return view("admin.model.add",$title)->with('result', $result);
    }

    public function insert(Request $request){
      $title = array('pageTitle' => Lang::get("labels.AddModel"));
      $result = $this->model->checkexistname($request);
      if(!empty($result[0]->model_name)){
        return redirect()->back()->withErrors(Lang::get("Model name already insert, please fill in other."))->withInput();
      }else{
        $this->model->insert($request);
      }
      return redirect()->back()->with('update', 'Content has been created successfully!');
    }

    public function edit(Request $request){
      $title = array('pageTitle' => Lang::get("labels.EditModel"));
      $model_id = $request->id;
      $result = array();
      $make = $this->makes->getter();
      $result['make'] = $make;
      $editModel = $this->model->edit($model_id);
      return view("admin.model.edit",$title)->with('editModel', $editModel)->with('result', $result);
    }


    public function update(Request $request){
      $result = $this->model->checkexistnameupdate($request);
      if(!empty($result[0]->model_name)){
        return redirect()->back()->withErrors(Lang::get("Make name already insert, please fill in other."))->withInput();
      }else{
        $messages = 'update is not successfull' ;
        $title = array('pageTitle' => Lang::get("labels.EditModel"));
        $this->validate($request, [
          'id' => 'required',
          'name' => 'required',
        ]);
        $this->model->updaterecord($request);
      }
      return redirect()->back()->with('update', 'Content has been created successfully!');
    }

    //delete Model
    public function delete(Request $request){
      $this->model->destroyrecord($request);
      return redirect()->back()->withErrors([Lang::get("labels.modelDeletedMessage")]);
    }

    public function filter(Request $request){
      $name = $request->FilterBy;
      $param = $request->parameter;
      $title = array('pageTitle' => Lang::get("labels.Model"));
      $model = $this->model->filter($name,$param);
      return view("admin.model.index",$title)->with('model', $model)->with('name',$name)->with('param',$param);
    }

    public function getmodel(Request $request){
      $getModel= array();
      $getModel = DB::table('models')->where('model_make_id', $request->make_id)->get();
      if(count($getModel)>0){
          $responseData = array('success'=>'1', 'data'=>$getModel, 'message'=>"Returned all model.");
      }else{
          $responseData = array('success'=>'0', 'data'=>$getModel, 'message'=>"Returned all model.");
      }
      $modelResponse = json_encode($responseData);
      print $modelResponse;
    }
}
