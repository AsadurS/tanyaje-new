<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\States;
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




class StateController extends Controller
{

    //
    public function __construct(States $state,Languages $language,Images $images)
    {
      $this->state =$state;
      $this->language = $language;
      $this->images = $images;
    }

    public function display(){
      $title = array('pageTitle' => Lang::get("labels.State"));
      $state = $this->state->paginator(5);
      return view("admin.state.index")->with('state',$state);
    }

    public function add(Request $request){
      $title = array('pageTitle' => Lang::get("labels.AddState"));
      return view("admin.state.add",$title);
    }

    public function insert(Request $request){
      $title = array('pageTitle' => Lang::get("labels.AddState"));
      $this->state->insert($request);
      return redirect()->back()->with('update', 'Content has been created successfully!');
    }

    public function edit(Request $request){
      $title = array('pageTitle' => Lang::get("labels.EditState"));
      $state_id = $request->id;
      $editState = $this->state->edit($state_id);
      return view("admin.state.edit",$title)->with('editState', $editState);
    }


    public function update(Request $request){
      $messages = 'update is not successfull' ;
      $title = array('pageTitle' => Lang::get("labels.EditState"));
      $this->validate($request, [
        'id' => 'required',
        'name' => 'required',
      ]);
      $this->state->updaterecord($request);
      return redirect()->back()->with('update', 'Content has been created successfully!');
    }

    //delete State
    public function delete(Request $request){
      $this->state->destroyrecord($request);
      return redirect()->back()->withErrors([Lang::get("labels.stateDeletedMessage")]);
    }

    public function filter(Request $request){
      $name = $request->FilterBy;
      $param = $request->parameter;
      $title = array('pageTitle' => Lang::get("labels.State"));
      $state = $this->state->filter($name,$param);
      return view("admin.state.index",$title)->with('state', $state)->with('name',$name)->with('param',$param);
    }
}
