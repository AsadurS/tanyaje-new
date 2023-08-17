<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\Models;
use App\Models\Core\Makes;
use App\Models\Core\Variant;
use App\Models\Core\Setting;
use App\Models\Core\Languages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Exception;
use Kyslik\ColumnSortable\Sortable;




class VariantController extends Controller
{

    //
    public function __construct(Models $model,Languages $language,Makes $makes,Variant $variant)
    {
      $this->model =$model;
      $this->language = $language;
      $this->makes = $makes;
      $this->variant = $variant;
    }

    public function display(){
      $title = array('pageTitle' => Lang::get("labels.Model"));
      $model = $this->variant->paginator(5);
      return view("admin.variant.index")->with('model',$model);
    }

    public function add(Request $request){
      $title = array('pageTitle' => Lang::get("labels.AddModel"));
      $result = array();
      $message = array();
      $make = $this->makes->getter();
      $model = $this->model->getter();
      $result['make'] = $make;
      $result['model'] = $model;
      $result['message'] = $message;
      return view("admin.variant.add",$title)->with('result', $result);
    }

      public function ajaxModel($id)
    {
        $ajaxmodel = DB::table("models")
                    ->select("model_name","model_id")
                    ->where("model_make_id",$id)
                    ->get();    
        return json_encode($ajaxmodel);
    }

    public function insert(Request $request){
      $title = array('pageTitle' => Lang::get("labels.AddModel"));
      $result = $this->variant->checkexistname($request);
      if(!empty($result[0]->variant_name)){
        return redirect()->back()->withErrors(Lang::get("Model name already insert, please fill in other."))->withInput();
      }else{
        $this->variant->insert($request);
      }
      return redirect()->back()->with('update', 'Content has been created successfully!');
    }

    public function edit(Request $request){
      $title = array('pageTitle' => Lang::get("labels.EditModel"));
      $model_id = $request->id;
      $result = array();
      $make = $this->makes->getter();
      $editModel = $this->variant->edit($model_id);
      $model = DB::table('models')
            ->select('*')
            ->where( 'models.model_make_id', $editModel[0]->make_id )
            ->get();
     

      $result['make'] = $make;

     
      $result['model'] = $model;

      return view("admin.variant.edit",$title)->with('editModel', $editModel)->with('result', $result);
    } 
    

 
    public function update(Request $request){
      $result = $this->variant->checkexistnameupdate($request);
     
      if(!empty($result[0]->variant_name)){
        return redirect()->back()->withErrors(Lang::get("Variant name is in used, please try another name."))->withInput();
      }else{
        $messages = 'update is not successfull' ;
        $title = array('pageTitle' => Lang::get("labels.EditModel"));
        $this->validate($request, [
          'models_id' => 'required',
          'model_make_id' => 'required',
          'name' => 'required',
          'id' => 'required',
        ]);
        $this->variant->updaterecord($request);
      }
      return redirect()->back()->with('update', 'Content has been updated successfully!');
    }

    //delete Model
    public function delete(Request $request){
      $this->variant->destroyrecord($request);
      return redirect()->back()->withErrors([Lang::get("labels.modelDeletedMessage")]);
    }

    public function filter(Request $request){
      $name = $request->FilterBy;
      $param = $request->parameter;
      $title = array('pageTitle' => Lang::get("labels.Model"));
      $model = $this->model->filter($name,$param);
      return view("admin.variant.index",$title)->with('model', $model)->with('name',$name)->with('param',$param);
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
