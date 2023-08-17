<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\Cars;
use App\Models\Core\Makes;
use App\Models\Core\Models;
use App\Models\Core\Images;
use App\Models\Core\Cities;
use App\Models\Core\States;
use App\Models\Core\Types;
use App\Models\Core\User;
use App\Models\Core\CarExtra;
use App\Models\Core\CarExtraFile;
use App\Models\Core\CarExtraImage;
use App\Models\Core\MerchantBranch;
use App\Zone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use File;
use Exception;

class CarController extends Controller
{
    //
    public function __construct( Cars $cars, Makes $makes, Models $models, Cities $cities, States $States, Types $types, CarExtra $carsextra, CarExtraFile $carsextrafiles ,CarExtraImage $carsextraimages){
        $this->Cars = $cars;
        $this->CarExtra = $carsextra;
        $this->CarExtraFile = $carsextrafiles;
        $this->CarExtraImage = $carsextraimages;
        $this->Makes = $makes;
        $this->Models = $models;
        $this->States = $States;
        $this->Cities = $cities;
        $this->Types = $types;
    }

    public function display(Request $request){
        $title = array('pageTitle' => Lang::get("labels.ListingCars"));
        $result = array();

        if($request->item_type_id){
            $item_type_id = $request->item_type_id;
            $result['item_type_id'] = $item_type_id;
        }
        else{
            $result['item_type_id'] = array();
        }

        if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
            if($request->item_type_id){
                $car = $this->Cars->paginatorbytype($request->item_type_id);
            }
            else{
                $car = $this->Cars->paginatorbyrole(Auth()->user()->id);
            }
        }else{
            if($request->item_type_id){
                $car = $this->Cars->paginatorbytype($request->item_type_id);
            }
            else{
                $car = $this->Cars->paginator();
            }
        }

        $item_type = DB::table('item_type')->get();
        $result['item_type'] = $item_type;

        if($request->item_type_id){
            $item_header = DB::table('item_type_attributes')
                            ->LeftJoin('item_attributes','item_attributes.id','=','item_type_attributes.item_attribute_id')
                            // ->LeftJoin('car_extra','car_extra.item_attribute_id','=','item_type_attributes.item_attribute_id')
                            ->where('item_type_attributes.item_type_id','=',$request->item_type_id)
                            ->where('item_attributes.header','=','1')
                            ->get();
            $result['item_header'] = $item_header;
            // dd($item_header);
        }
        else{
            $result['item_header'] = array();
        }

        return view("admin.car.index",$title)->with('car',$car)->with('result', $result);
    }

    public function add(Request $request){
        $title = array('pageTitle' => Lang::get("labels.AddCar"));
        $images = new Images;
        if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
            $allimage = $images->getimagesbyrole(Auth()->user()->id);
        }else{
            $allimage = $images->getimages();
        }
        $result = array();
        $attribute_data = array();
        $message = array();
        $state = $this->States->getter();
        $result['state'] = $state;
        $city = $this->Cities->getter();
        $result['city'] = $city;
        $make = $this->Makes->getter();
        $result['make'] = $make;
        $model = $this->Models->getter();
        $result['model'] = $model;
        $type = $this->Types->getter();
        $result['type'] = $type;
        $merchant = $this->Cars->merchant();
        $result['merchant'] = $merchant;
        $result['message'] = $message;

        // get the current item type id
        if(isset($request->item_type_add)){
            $item_type_id = $request->item_type_add;
        }
        else{
            $item_type_id = '1';
        }
        $result['item_type_id'] = $item_type_id;

        // get all item type
        $item_type = DB::table('item_type')->get();
        $result['item_type'] = $item_type;

        return view("admin.car.add", $title)->with('result', $result)->with('allimage', $allimage);
    }

    
    public function ajaxModel($id)
    {
        $ajaxmodel = DB::table("variants")
                    ->select("variant_name","variant_id")
                    ->where("model_id",$id)
                    ->get();    
        return json_encode($ajaxmodel);
    }

    public function insert(Request $request){
        $time = Carbon::now();
        $extensions = array('pdf');
        $file = $request->file('pdf');
		if($request->hasFile('pdf')){
            if(in_array(strtolower($file->getClientOriginalExtension()), $extensions)){

            }else{
                return redirect()->back()->withErrors(Lang::get("Invalid Pdf. Accepted file format: pdf."))->withInput();
            }
        }
        $this->Cars->insert($request, $time);
        $message = Lang::get("labels.CarAddedMessage");
        return Redirect::back()->with('message',$message);
    }

    public function edit(Request $request){
        $title = array('pageTitle' => Lang::get("labels.EditCar"));
        $images = new Images;
        if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
            $allimage = $images->getimagesbyrole(Auth()->user()->id);
            $result = array();
            $result['message'] = array();

            $car = $this->Cars->edit($request);
            if($car->user_id == Auth()->user()->id){
                $state = $this->States->getter();
                $result['state'] = $state;
                $city = $this->Cities->getter();
                $result['city'] = $city;
                $make = $this->Makes->getter();
                $result['make'] = $make;
                $model = $this->Models->getter();
                $result['model'] = $model;
                $type = $this->Types->getter();
                $result['type'] = $type;
                $merchant = $this->Cars->merchant();
                $result['merchant'] = $merchant;
                $result['car'] = $car;
                $variants = DB::table("variants")
                ->select("variant_name","variant_id")
                ->where("model_id",$car->model_id)
                ->get();    
                $result['variants'] = $variants;

                // get all item type
                $item_type = DB::table('item_type')->get();
                $result['item_type'] = $item_type;

                // get car images for no spincar acc
                $car_images = DB::table('car_images')
                                ->where('car_images.cars_id', $request->id)
                                ->get();
                $result['car_images'] = $car_images;

                return view("admin.car.edit",$title)->with('result', $result)->with('allimage', $allimage);
            }else{
                return  redirect('not_allowed');
            }
        }else{
            $allimage = $images->getimages();
            $result = array();
            $result['message'] = array();

            $car = $this->Cars->edit($request);
            $state = $this->States->getter();
            $result['state'] = $state;
            $city = $this->Cities->getter();
            $result['city'] = $city;
            $make = $this->Makes->getter();
            $result['make'] = $make;
            $model = $this->Models->getter();
            $result['model'] = $model;
            $type = $this->Types->getter();
            $result['type'] = $type;
            $merchant = $this->Cars->merchant();
            $result['merchant'] = $merchant;
            $result['car'] = $car;
            $variants = DB::table("variants")
            ->select("variant_name","variant_id")
            ->where("model_id",$car->model_id)
            ->get();    
            $result['variants'] = $variants;

            // get all item type
            $item_type = DB::table('item_type')->get();
            $result['item_type'] = $item_type;

            // get car images for no spincar acc
            $car_images = DB::table('car_images')
                            ->where('car_images.cars_id', $request->id)
                            ->get();
            $result['car_images'] = $car_images;
      

            return view("admin.car.edit",$title)->with('result', $result)->with('allimage', $allimage);
        }
    }

    public function update(Request $request){
        $time = Carbon::now();
        $extensions = array('pdf');
        $file = $request->file('pdf');
		if($request->hasFile('pdf')){
            if(in_array(strtolower($file->getClientOriginalExtension()), $extensions)){

            }else{
                return redirect()->back()->withErrors(Lang::get("Invalid Pdf. Accepted file format: pdf."))->withInput();
            }
        }
        $this->Cars->updaterecord($request, $time);
        $stateData['message'] = 'Car has been updated successfully!';
        $message = Lang::get("labels.Car has been updated successfully");
        return Redirect::back()->with('message',$message);
    }

    public function delete(Request $request){
        $GetExistCar = DB::table('cars')->where('car_id', $request->id)->first();
        File::delete($GetExistCar->pdf);
        $this->Cars->deleterecord($request);
        return redirect()->back()->withErrors([Lang::get("labels.CarDeletedTax")]);
    }

    public function filter(Request $request){
        if($request->change_view){
            $change_view  = $request->change_view;
            $result['item_type_id'] = $request->change_view;
        }
        else{
            $change_view  = '';
            $result['item_type_id'] = array();
        }
        $name = $request->FilterBy;
        $param = $request->parameter;
        $title = array('pageTitle' => Lang::get("labels.ListingCars"));
        $car = $this->Cars->filter($request);

        $item_type = DB::table('item_type')->get();
        $result['item_type'] = $item_type;

        if($request->change_view){
            $item_header = DB::table('item_type_attributes')
                            ->LeftJoin('item_attributes','item_attributes.id','=','item_type_attributes.item_attribute_id')
                            // ->LeftJoin('car_extra','car_extra.item_attribute_id','=','item_type_attributes.item_attribute_id')
                            ->where('item_type_attributes.item_type_id','=',$request->change_view)
                            ->where('item_attributes.header','=','1')
                            ->get();
            $result['item_header'] = $item_header;
            // dd($item_header);
        }
        else{
            $result['item_header'] = array();
        }

        return view("admin.car.index",$title)->with('car', $car)->with('name', $name)->with('param', $param)->with('result', $result);
    }

    public function getCarsAirtime(Request $request){
        $cars = $this->Cars->where('merchant_id', $request->input('merchant_id'))->get()->toArray();

		return response()->json([
            'data' => $cars
        ]);
    }
    
    public function bulkEditAirtime(Request $request){
        $data = [
            'pageTitle' => Lang::get('labels.BulkEditAirtime'),
            'merchants' => MerchantBranch::orderBy('merchant_name', 'ASC')->get(),
        ];

		return view('admin.car.bulk-airtime.edit', $data);
    }
    
    public function toggleAirtime($id)
    {
        try {
            $car = $this->Cars->findOrFail($id);
            $car->update([
                'is_airtime_hide' => !$car->is_airtime_hide,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'is_success' => false,
                'message' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'is_success' => true,
            'message' => 'Update airtime successfully'
        ]);
    }

    public function getItemAttribute(Request $request){ 
        $result = array();

        // get the current item type id
        if(isset($request->id)){
            $item_type_id = $request->id;
        }
        else{
            $item_type_id = '1';
        }
        $result['item_type_id'] = $item_type_id;

        // get the item dynamic attribute
        $dynamic_attribute = DB::table('item_type_attributes')
                            ->LeftJoin('item_attributes','item_type_attributes.item_attribute_id','=','item_attributes.id')
                            ->select('item_type_attributes.*', 'item_attributes.name', 'item_attributes.html_type', 'item_attributes.input_prefix','item_attributes_value.attribute_value as option')
                            ->select('item_type_attributes.*', 'item_attributes.name', 'item_attributes.html_type', 'item_attributes.input_prefix')
                            ->where('item_type_attributes.item_type_id', '=', $item_type_id )
                            ->orderBy(DB::raw('ISNULL(item_attributes.sort_no), item_attributes.sort_no'), 'ASC')
                            ->distinct('item_type_attributes.id')
                            ->get();
        $result['dynamic_attribute'] = $dynamic_attribute;

        // get the attribute value for select/checbox/radio
        $dynamic_attribute_value = DB::table('item_attributes_value')
                                    ->LeftJoin('item_attributes','item_attributes_value.item_attributes_id','=','item_attributes.id')
                                    ->select('item_attributes.*','item_attributes_value.attribute_value as option','item_attributes_value.id as value_id')
                                    ->where('item_attributes_value.attribute_value','!=',null)
                                    ->orderBy(DB::raw('ISNULL(item_attributes.sort_no), item_attributes.sort_no'), 'ASC')
                                    ->get();
        $result['dynamic_attribute_value'] = $dynamic_attribute_value;

        if(isset($request->car_id)){
            $car_id = $request->car_id;
        }
        else{
            $car_id = '';
        }

        // get the car extra value if any
        if($car_id != null){
            $item_value = DB::table('car_extra')
                            ->LeftJoin('item_attributes','car_extra.item_attribute_id','=','item_attributes.id')
                            ->select('item_attributes.*','car_extra.item_attribute_value as user_value')
                            ->where('car_extra.cars_id','=',$car_id)
                            ->get();
            // dd($item_value);
            $result['item_value'] = $item_value;
        }
        else{
            $result['item_value'] = "";
        }
        

        return $result;
    }

    public function deletecarimage(Request $request){
        $car_image_id = $request->id;
        $car_image_id = DB::table('car_images')->where('id','=',$car_image_id)->delete();
        return redirect()->back()->withErrors([Lang::get("Car image has been removed.")]);
    }

    public function duplicateItem(Request $request){
        $organisation_id   = $request->branch_id;
        $duplicate_id = $request->user_id;
       
  
        $merchant_cars = Cars::where('user_id','=', $organisation_id)
                        ->get();
        foreach($merchant_cars as $merchant_car) {
            $car_extras = CarExtra::where('cars_id','=',$merchant_car->car_id)
            ->get();
            $car_extras_files = CarExtraFile::where('cars_id','=',$merchant_car->car_id)
            ->get();
            $car_extras_images = CarExtraImage::where('cars_id','=',$merchant_car->car_id)
            ->get();
            $car = $merchant_car->replicate();
            $car->user_id = $duplicate_id;
            $car->save();
            $new_car = $car->car_id;
            
            foreach($car_extras as $car_extra) {
                $table = $car_extra->replicate();
                $table->cars_id = $new_car;      
                $table->save();
            }
            foreach($car_extras_files as $car_extras_file) {
                $table2 = $car_extras_file->replicate();
                $table2->cars_id = $new_car;      
                $table2->save();
            }
            foreach($car_extras_images as $car_extras_image) {
                $table3 = $car_extras_image->replicate();
                $table3->cars_id = $new_car;      
                $table3->save();
            }
         
        
        }
		
    }
}
