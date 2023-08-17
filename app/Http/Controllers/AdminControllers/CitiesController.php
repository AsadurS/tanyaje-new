<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\Cities;
use App\Models\Core\States;
use App\Zone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;

class CitiesController extends Controller
{
    //
    public function __construct( Cities $cities, States $States){
        $this->States = $States;
        $this->Cities = $cities;
    }

    public function display(){
        $title = array('pageTitle' => Lang::get("labels.ListingCities"));
        $cities = $this->Cities->paginator(5);
        return view("admin.cities.index",$title)->with('cities',$cities);
    }

    public function add(Request $request){
        $title = array('pageTitle' => Lang::get("labels.AddCity"));
        $result = array();
        $message = array();
        $state = $this->States->getter();
        $result['state'] = $state;
        $result['message'] = $message;
        return view("admin.cities.add", $title)->with('result', $result);
    }

    public function insert(Request $request){
        $this->Cities->insert($request);
        $message = Lang::get("labels.CityAddedMessage");
        return Redirect::back()->with('message',$message);
    }

    public function edit(Request $request){
        $title = array('pageTitle' => Lang::get("labels.EditCity"));
        $result = array();
        $result['message'] = array();

        $cities = $this->Cities->edit($request);
        $state = $this->States->getter();
        $result['state'] = $state;
        $result['cities'] = $cities;
        return view("admin.cities.edit",$title)->with('result', $result);
    }

    public function update(Request $request){
        $this->Cities->updaterecord($request);
        $stateData['message'] = 'City has been updated successfully!';
        $message = Lang::get("labels.City has been updated successfully");
        return Redirect::back()->with('message',$message);
    }

    public function delete(Request $request){
        $this->Cities->deleterecord($request);
        return redirect()->back()->withErrors([Lang::get("labels.CityDeletedTax")]);
    }

    public function filter(Request $request){
        $name = $request->FilterBy;
        $param = $request->parameter;
        $title = array('pageTitle' => Lang::get("labels.ListingCities"));
        $cities = $this->Cities->filter($request);
        return view("admin.cities.index",$title)->with('cities', $cities)->with('name', $name)->with('param', $param);
    }

    public function getcity(Request $request){
        $getCity= array();
        $getCity = DB::table('cities')->where('city_state_id', $request->state_id)->get();
        if(count($getCity)>0){
            $responseData = array('success'=>'1', 'data'=>$getCity, 'message'=>"Returned all city.");
        }else{
            $responseData = array('success'=>'0', 'data'=>$getCity, 'message'=>"Returned all city.");
        }
        $cityResponse = json_encode($responseData);
        print $cityResponse;
    }
}
