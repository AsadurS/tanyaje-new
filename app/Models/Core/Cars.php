<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use File;

class Cars extends Model
{
    //
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    public $primaryKey  = 'car_id';


    use Sortable;

    public $sortable = ['car_id','title','vim','stock_number','price','merchant_id','status','created_at','updated_at'];
    public $sortableAs = ['state_name'];
    protected $appends = array('pageview','whatsapp','call','waze');

    public function getter(){
        $car = Cars::sortable(['car_id'=>'ASC'])->get();
        return $car;
    }

    /*public function gettersearch($request){
        $car = Cars::sortable(['car_id'=>'ASC'])
        ->LeftJoin('states','cars.state_id','=','states.state_id')
        ->where('city_state_id', $request->c2)
        ->get();
        return $car;
    }*/

    public function paginator(){
        $car = Cars::sortable(['car_id'=>'ASC'])
        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
        ->LeftJoin('models','cars.model_id','=','models.model_id')
        //->LeftJoin('users','cars.merchant_id','=','users.id')
        ->LeftJoin('users','cars.user_id','=','users.id')
        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
        ->select('cars.*', 'makes.make_name','is_feature','models.model_name', 'users.company_name', 'item_type.name as item_type_name')
        // ->groupBy('cars.vim')
        ->paginate(30);
        return $car;
    }

    public function paginatorbyrole($role_id){
        // dd('test');
        $car = Cars::sortable(['car_id'=>'ASC'])
        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
        ->LeftJoin('models','cars.model_id','=','models.model_id')
        //->LeftJoin('users','cars.merchant_id','=','users.id')
        ->LeftJoin('users','cars.user_id','=','users.id')
        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name')
        ->where('users.id',$role_id)
        ->groupBy('cars.vim')
        ->paginate(30);
        return $car;
    }

    public function paginatorbytype($item_type){
        if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
            $role_id = Auth()->user()->id;
            $car = Cars::sortable(['car_id'=>'ASC'])
                ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                ->LeftJoin('models','cars.model_id','=','models.model_id')
                //->LeftJoin('users','cars.merchant_id','=','users.id')
                ->LeftJoin('users','cars.user_id','=','users.id')
                ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                // ->LeftJoin('car_extra','cars.car_id','=','car_extra.cars_id')
                ->select('cars.*', 'makes.make_name','is_feature','models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                ->where('cars.item_type_id','=',$item_type)
                ->where('users.id',$role_id)
                // ->groupBy('cars.vim')
                ->paginate(30);
        }
        else{
            $car = Cars::sortable(['car_id'=>'ASC'])
                ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                ->LeftJoin('models','cars.model_id','=','models.model_id')
                //->LeftJoin('users','cars.merchant_id','=','users.id')
                ->LeftJoin('users','cars.user_id','=','users.id')
                ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                // ->LeftJoin('car_extra','cars.car_id','=','car_extra.cars_id')
                ->select('cars.*', 'makes.make_name','is_feature','models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                ->where('cars.item_type_id','=',$item_type)
                // ->groupBy('cars.vim')
                ->paginate(30);
        }

        return $car;
    }

    public function filter($data){
        // dd($data);
        $name = $data['FilterBy'];
        $param = $data['parameter'];
        $change_view = $data['change_view'];

        $result = array();
        $message = array();
        $errorMessage = array();
        if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
            $role_id = Auth()->user()->id;
            switch ( $name ) {
                case 'Merchant':
                    $car = Cars::sortable(['car_id'=>'ASC'])
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                        ->where('company_name', 'LIKE', '%' . $param . '%')
                        ->groupBy('cars.vim');
                        if($change_view){
                            $car = $car->where('cars.item_type_id', '=', $change_view);
                        }
                        $car = $car->paginate(30);
                    break;
                case 'VIN':
                    $car = Cars::sortable(['car_id'=>'ASC'])->where('vim', 'LIKE', '%' . $param . '%')
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                        ->where('merchant_branch.user_id',$role_id)
                        ->groupBy('cars.vim');
                        if($change_view){
                            $car = $car->where('cars.item_type_id', '=', $change_view);
                        }
                        $car = $car->paginate(30);
                    break;
                case 'Make':
                    $car = Cars::sortable(['car_id'=>'ASC'])
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                        ->where('make_name', 'LIKE', '%' . $param . '%')
                        ->where('merchant_branch.user_id',$role_id)
                        ->groupBy('cars.vim');
                        if($change_view){
                            $car = $car->where('cars.item_type_id', '=', $change_view);
                        }
                        $car = $car->paginate(30);
                    break;
                case 'Model':
                    $car = Cars::sortable(['car_id'=>'ASC'])
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                        ->where('model_name', 'LIKE', '%' . $param . '%')
                        ->where('merchant_branch.user_id',$role_id)
                        ->groupBy('cars.vim');
                        if($change_view){
                            $car = $car->where('cars.item_type_id', '=', $change_view);
                        }
                        $car = $car->paginate(30);
                    break;
                case 'Organisation':
                    $car = Cars::sortable(['car_id'=>'ASC'])
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                        ->where('company_name', 'LIKE', '%' . $param . '%')
                        ->groupBy('cars.vim');
                        if($change_view){
                            $car = $car->where('cars.item_type_id', '=', $change_view);
                        }
                        $car = $car->paginate(30);
                    // $car = Cars::sortable(['car_id'=>'ASC'])
                    //     ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                    //     ->LeftJoin('models','cars.model_id','=','models.model_id')
                    //     //->LeftJoin('users','cars.merchant_id','=','users.id')
                    //     ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
                    //     ->select('cars.*', 'makes.make_name', 'models.model_name', 'merchant_branch.merchant_name')
                    //     ->where('cars.merchant_id', '=', $param)
                    //     ->paginate(30);
                    break;
                case 'Type':
                    $car = Cars::sortable(['car_id'=>'ASC'])
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                        ->where('item_type.name', 'LIKE', '%' . $param . '%')
                        ->groupBy('cars.vim')
                        ->paginate(30);
                    break;
                case 'ItemName':
                    $car = Cars::sortable(['car_id'=>'ASC'])
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                        ->where('cars.title', 'LIKE', '%' . $param . '%')
                        ->groupBy('cars.vim');
                        if($change_view){
                            $car = $car->where('cars.item_type_id', '=', $change_view);
                        }
                        $car = $car->paginate(30);
                    break;
                default:
                    $car = Cars::sortable(['car_id'=>'ASC'])
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name')
                        ->groupBy('cars.vim')
                        ->paginate(30);
                    break;
            }
        }else{
            switch ( $name ) {
                case 'Merchant':
                    $car = Cars::sortable(['car_id'=>'ASC'])
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                        ->where('company_name', 'LIKE', '%' . $param . '%')
                        ->groupBy('cars.vim');
                        if($change_view){
                            $car = $car->where('cars.item_type_id', '=', $change_view);
                        }
                        $car = $car->paginate(30);
                    break;
                case 'VIN':
                    $car = Cars::sortable(['car_id'=>'ASC'])->where('vim', 'LIKE', '%' . $param . '%')
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                        ->groupBy('cars.vim');
                        if($change_view){
                            $car = $car->where('cars.item_type_id', '=', $change_view);
                        }
                        $car = $car->paginate(30);
                    break;
                case 'Make':
                    $car = Cars::sortable(['car_id'=>'ASC'])
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                        ->where('make_name', 'LIKE', '%' . $param . '%')
                        ->groupBy('cars.vim');
                        if($change_view){
                            $car = $car->where('cars.item_type_id', '=', $change_view);
                        }
                        $car = $car->paginate(30);
                    break;
                case 'Model':
                    $car = Cars::sortable(['car_id'=>'ASC'])
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                        ->where('model_name', 'LIKE', '%' . $param . '%')
                        ->groupBy('cars.vim');
                        if($change_view){
                            $car = $car->where('cars.item_type_id', '=', $change_view);
                        }
                        $car = $car->paginate(30);
                    break;
                case 'Organisation':
                    $car = Cars::sortable(['car_id'=>'ASC'])
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                        ->where('company_name', 'LIKE', '%' . $param . '%')
                        ->groupBy('cars.vim');
                        if($change_view){
                            $car = $car->where('cars.item_type_id', '=', $change_view);
                        }
                        $car = $car->paginate(30);
                    // $car = Cars::sortable(['car_id'=>'ASC'])
                    //     ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                    //     ->LeftJoin('models','cars.model_id','=','models.model_id')
                    //     //->LeftJoin('users','cars.merchant_id','=','users.id')
                    //     ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
                    //     ->select('cars.*', 'makes.make_name', 'models.model_name', 'merchant_branch.merchant_name')
                    //     ->where('cars.merchant_id', '=', $param)
                    //     ->paginate(30);
                    break;
                case 'Type':
                    $car = Cars::sortable(['car_id'=>'ASC'])
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name','item_type.name as item_type_name')
                        ->where('item_type.name', 'LIKE', '%' . $param . '%')
                        ->groupBy('cars.vim');
                        if($change_view){
                            $car = $car->where('cars.item_type_id', '=', $change_view);
                        }
                        $car = $car->paginate(30);
                    break;
                case 'ItemName':
                    $car = Cars::sortable(['car_id'=>'ASC'])
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->LeftJoin('item_type','cars.item_type_id','=','item_type.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name', 'item_type.name as item_type_name')
                        ->where('cars.title', 'LIKE', '%' . $param . '%')
                        ->groupBy('cars.vim');
                        if($change_view){
                            $car = $car->where('cars.item_type_id', '=', $change_view);
                        }
                        $car = $car->paginate(30);
                    break;
                default:
                    $car = Cars::sortable(['car_id'=>'ASC'])
                        ->LeftJoin('makes','cars.make_id','=','makes.make_id')
                        ->LeftJoin('models','cars.model_id','=','models.model_id')
                        //->LeftJoin('users','cars.merchant_id','=','users.id')
                        ->LeftJoin('users','cars.user_id','=','users.id')
                        ->select('cars.*', 'makes.make_name', 'models.model_name', 'users.company_name')
                        ->groupBy('cars.vim')
                        ->paginate(30);
                    break;
            }
        }
        return $car;
    }
    public function getstates(){
        $states = DB::table('states')->get();
        return $states;
    }

    public function insert($request, $time){
        // dd($request->all());
        $date_added	= date('y-m-d h:i:s');
        $features = "";
        $mileage = "";

        if( !empty($request->features) )
        {
            foreach($request->features as $features_data){
                if(!empty($features_data)){
                    if(empty($features)){
                        $features = $features_data;
                    }else if(!empty($features)){
                        $features .= ",".$features_data;
                    }
                }
            }
        }

        if( !empty($request->mileage) )
        {
            foreach($request->mileage as $mileage_data){
//                if(!empty($mileage_data)){
                    if(empty($mileage)){
                        $mileage = $mileage_data;
                    }else if(!empty($mileage)){
                        $mileage .= ",".$mileage_data;
                    }
//                }
            }
        }

        if( !empty($request->merchant_id) )
        {
            // $user_id = MerchantBranch::find($request->merchant_id)->user_id;
            $user_id = $request->merchant_id;
        }

        $car_id = DB::table('cars')->insertGetId([
            'title'			    =>   $request->title,
            'sp_account'	    =>   $request->sp_account,
            'vim'			    =>   $request->vim,
            'stock_number'	    =>   $request->stock_number,
            'make_id'  	        =>   $request->make_id,
            'year_make'  	    =>   $request->year_make,
            'model_id'  	    =>   $request->model_id,
            'status'  	        =>   $request->status,
            'variant_id'        =>   $request->variant_id,
//            'image'             =>   $request->image_id,
            'extra_link'        => $request->extra_link,
            'extra_link_label'  => $request->extra_link_label,
            'html_editor'  	    =>   $request->html_editor,
            'price'  	        =>   $request->price,
            'state_id'  	    =>   $request->state_id,
            'city_id'	        =>   $request->city_id,
            'type_id'  	        =>   $request->type_id,
            'merchant_id'  	    =>   $request->merchant_id,
            'user_id'  	        =>   $user_id,
            'fuel_type'  	    =>   $request->fuel_type,
            'features'  	    =>   $features,
            'seats'  	        =>   $request->seats,
            'transmission'  	=>   $request->transmission,
            'mileage'  	        =>   $mileage,
            'color'  	        =>   $request->color,
            'engine_capacity'  	=>   $request->engine_capacity,
            'is_sold'  	        =>   $request->is_sold,
            'is_airtime_hide'  	=>   $request->is_airtime_hide,
            'is_publish'  	    =>   $request->is_publish,
            'item_type_id'      =>   $request->item_type_id,
            'pdf'  	            =>   $request->pdf,
            'created_at'	    =>   $date_added,
            'image'             =>   $request->car_image,
            
        ]);

        if($request->hasFile('pdf')){
            $image = $request->file('pdf');
            // getting size
            $size = getimagesize($image);
            list($width, $height, $type, $attr) = $size;
            // Getting the extension of the file
            $extension = $image->getClientOriginalExtension();
            // Creating the directory, for example, if the date = 18/10/2017, the directory will be 2017/10/
            $directory = date_format($time, 'Y') . '/' . date_format($time, 'm');
            // Creating the file name: random string followed by the day, random number and the hour
            $filename = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            // This is our upload main function, storing the image in the storage that named 'public'
            $upload_success = $image->storeAs($directory, $filename, 'public');

            //store DB
            $Path = 'images/media/' . $directory . '/' . $filename;
            DB::table('cars')->where('car_id', $car_id)->update([
                'pdf'  	=>   $Path
            ]);
        }

        // store no_spin_car images
        $no_spin_car = $request->file('car_image');
        if($no_spin_car)
        {
            foreach($request->file('car_image') as $file)
            {
                $extension = $file->getClientOriginalExtension();
                $size = ($file->getSize()) / 1000;
                $file_name = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $destinaton_path = public_path('images/items/');
                $file->move($destinaton_path,$file_name);
                $file_name_save = $file_name;
                $file_size = $size;

                DB::table('car_images')->insertGetId(
                    ['cars_id' => $car_id, 'filename' => $file_name_save, 'created_at' => date('Y-m-d H:i:s'), 'size' => $file_size, 'type' => $extension]
                );
            }
        }

        // car_extra insertion
        $dynamic_attribute = DB::table('item_type_attributes')
                            ->LeftJoin('item_attributes','item_type_attributes.item_attribute_id','=','item_attributes.id')
                            ->select('item_type_attributes.*', 'item_attributes.name', 'item_attributes.html_type', 'item_attributes.input_prefix','item_attributes_value.attribute_value as option')
                            ->select('item_type_attributes.*', 'item_attributes.name', 'item_attributes.html_type', 'item_attributes.input_prefix')
                            ->where('item_type_attributes.item_type_id', '=', $request->item_type_id )
                            ->orderBy('item_type_attributes.sort_no','ASC')
                            ->distinct('item_type_attributes.id')
                            ->get();

        foreach($dynamic_attribute as $da){
            $dynamic_data = array();
            if( $da->html_type == 'text' || $da->html_type == 'date' || $da->html_type == 'time' || $da->html_type == 'number' || $da->html_type == 'textarea' || $da->html_type == 'select' || $da->html_type == 'radio' ){
                if($request->input($da->input_prefix)){
                    $user_keyin = $request->input($da->input_prefix);
                    DB::table('car_extra')->insertGetId(
                        [
                            'cars_id' => $car_id,
                            'item_type_id' => $request->item_type_id,
                            'item_attribute_id' => $da->item_attribute_id,
                            'item_attribute_value' => $user_keyin,
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    );
                }
                else{
                    $user_keyin = "";
                    DB::table('car_extra')->insertGetId(
                        [
                            'cars_id' => $car_id,
                            'item_type_id' => $request->item_type_id,
                            'item_attribute_id' => $da->item_attribute_id,
                            'item_attribute_value' => $user_keyin,
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    );
                }
            }
            else if( $da->html_type == 'checkbox' ){
                if($request->input($da->input_prefix)){
                    
                    $user_keyin = is_array($request->input($da->input_prefix))?implode(',',$request->input($da->input_prefix)):$request->input($da->input_prefix);
                    DB::table('car_extra')->insertGetId(
                        [
                            'cars_id' => $car_id,
                            'item_type_id' => $request->item_type_id,
                            'item_attribute_id' => $da->item_attribute_id,
                            'item_attribute_value' => $user_keyin,
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    );
                }
                else{
                    $user_keyin = "";
                    DB::table('car_extra')->insertGetId(
                        [
                            'cars_id' => $car_id,
                            'item_type_id' => $request->item_type_id,
                            'item_attribute_id' => $da->item_attribute_id,
                            'item_attribute_value' => $user_keyin,
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    );
                }
            }
            else if( $da->html_type == 'file' ){
                if($request->input($da->input_prefix)){
                    $user_keyin = $request->input($da->input_prefix);
                    DB::table('car_extra')->insertGetId(
                        [
                            'cars_id' => $car_id,
                            'item_type_id' => $request->item_type_id,
                            'item_attribute_id' => $da->item_attribute_id,
                            'item_attribute_value' => $user_keyin,
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    );
                }
                else{
                    $user_keyin = "";
                    DB::table('car_extra')->insertGetId(
                        [
                            'cars_id' => $car_id,
                            'item_type_id' => $request->item_type_id,
                            'item_attribute_id' => $da->item_attribute_id,
                            'item_attribute_value' => $user_keyin,
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    );
                }
                
                // if($request->file($da->input_prefix)){

                //     $input_file = $request->file($da->input_prefix);

                //     $extension = $input_file->getClientOriginalExtension();
                //     $size = ($input_file->getSize()) / 1000;
                //     $file_name = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                //     $destinaton_path = public_path('images/items/');
                //     $input_file->move($destinaton_path,$file_name);
                //     $file_name_save = $file_name;
                //     $file_size = $size;

                //     DB::table('car_extra_file')->insertGetId(
                //         [
                //             'cars_id' => $car_id, 
                //             'item_attribute_id' => $da->item_attribute_id,
                //             'filename' => $file_name_save, 
                //             'created_at' => date('Y-m-d H:i:s'), 
                //             'size' => $file_size, 
                //             'type' => $extension
                //         ]
                //     );

                //     DB::table('car_extra')->insertGetId(
                //         [
                //             'cars_id' => $car_id,
                //             'item_type_id' => $request->item_type_id,
                //             'item_attribute_id' => $da->item_attribute_id,
                //             'item_attribute_value' => $file_name_save,
                //             'created_at' => date('Y-m-d H:i:s')
                //         ]
                //     );
                // }
            }
            else{
                // 
            }
        }
        
        return $car_id;
    }

    public function edit($request){
        $car =  DB::table('cars')
            ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
            ->leftJoin('image_categories as categoryTable','categoryTable.image_id', '=', 'cars.image')
            ->select('cars.*', 'categoryTable.path as imgpath','merchant_branch.user_id as merchant_user_id')
            ->where('cars.car_id', $request->id)->first();
        return $car;
    }

    public function updaterecord($request, $time){
        // dd($request->all());
        $last_modified 	=   date('y-m-d h:i:s');
        if($request->image_id!==null){
            $uploadImage = $request->image_id;
        }else{
            $uploadImage = $request->oldImage;
        }
        $features = "";
        $mileage = "";

        if( !empty($request->features) )
        {
            foreach ($request->features as $features_data) {
                if (!empty($features_data)) {
                    if (empty($features)) {
                        $features = $features_data;
                    } else if (!empty($features)) {
                        $features .= "," . $features_data;
                    }
                }
            }
        }

        if( !empty($request->mileage) )
        {
            foreach($request->mileage as $mileage_data){
//                if($mileage_data){
                    if(empty($mileage)){
                        $mileage = $mileage_data;
                    }else if(!empty($mileage)){
                        $mileage .= ",".$mileage_data;
                    }
//                }
            }
        }

        if( !empty($request->merchant_id) )
        {
            $user_id =  $request->merchant_id;
        }

        DB::table('cars')->where('car_id', $request->car_id)->update([
            'title'			    =>   $request->title,
            'sp_account'  	    =>   $request->sp_account,
            'vim'  		        =>   $request->vim,
            'stock_number'	    =>   $request->stock_number,
            'extra_link'        => $request->extra_link,
            'extra_link_label'  => $request->extra_link_label,
            'make_id'  	        =>   $request->make_id,
            'year_make'  	    =>   $request->year_make,
            'model_id'  	    =>   $request->model_id,
            'variant_id'        =>   $request->variant_id,
            'status'  	        =>   $request->status,
            'image'             =>   $request->car_image,
            'html_editor'  	    =>   $request->html_editor,
            'price'  	        =>   $request->price,
            'state_id'	        =>   $request->state_id,
            'city_id'	        =>   $request->city_id,
            'type_id'  	        =>   $request->type_id,
            'merchant_id'  	    =>   $request->merchant_id,
            'user_id'  	        =>   $user_id,
            'fuel_type'  	    =>   $request->fuel_type,
            'features'  	    =>   $features,
            'seats'  	        =>   $request->seats,
            'transmission'  	=>   $request->transmission,
            'mileage'  	        =>   $mileage,
            'color'  	        =>   $request->color,
            'engine_capacity'  	=>   $request->engine_capacity,
            'is_sold'  	        =>   $request->is_sold,
            'is_airtime_hide'  	=>   $request->is_airtime_hide,
            'is_publish'  	    =>   $request->is_publish,
            'updated_at'	    =>   $last_modified,
            'pdf'	            =>   $request->pdf,
        ]);

        if($request->hasFile('pdf')){
            $image = $request->file('pdf');
            // getting size
            $size = getimagesize($image);
            list($width, $height, $type, $attr) = $size;
            // Getting the extension of the file
            $extension = $image->getClientOriginalExtension();
            // Creating the directory, for example, if the date = 18/10/2017, the directory will be 2017/10/
            $directory = date_format($time, 'Y') . '/' . date_format($time, 'm');
            // Creating the file name: random string followed by the day, random number and the hour
            $filename = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            // This is our upload main function, storing the image in the storage that named 'public'
            $upload_success = $image->storeAs($directory, $filename, 'public');

            $GetExistCar = DB::table('cars')->where('car_id', $request->car_id)->first();
            File::delete($GetExistCar->pdf);
            //store DB
            $Path = 'images/media/' . $directory . '/' . $filename;
            DB::table('cars')->where('car_id', $request->car_id)->update([
                'pdf'  	=>   $Path
            ]);
        }

        $no_spin_car = $request->file('car_image');
        if($no_spin_car)
        {
            foreach($request->file('car_image') as $file)
            {
                $extension = $file->getClientOriginalExtension();
                $size = ($file->getSize()) / 1000;
                $file_name = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $destinaton_path = public_path('images/items/');
                $file->move($destinaton_path,$file_name);
                $file_name_save = $file_name;
                $file_size = $size;

                DB::table('car_images')->insertGetId(
                    ['cars_id' => $request->car_id, 'filename' => $file_name_save, 'created_at' => date('Y-m-d H:i:s'), 'size' => $file_size, 'type' => $extension]
                );
            }
        }

        // car_extra update
        $dynamic_attribute = DB::table('item_type_attributes')
                            ->LeftJoin('item_attributes','item_type_attributes.item_attribute_id','=','item_attributes.id')
                            ->select('item_type_attributes.*', 'item_attributes.name', 'item_attributes.html_type', 'item_attributes.input_prefix','item_attributes_value.attribute_value as option')
                            ->select('item_type_attributes.*', 'item_attributes.name', 'item_attributes.html_type', 'item_attributes.input_prefix')
                            ->where('item_type_attributes.item_type_id', '=', $request->item_type_id )
                            ->orderBy('item_type_attributes.sort_no','ASC')
                            ->distinct('item_type_attributes.id')
                            ->get();

        foreach($dynamic_attribute as $da){
            $dynamic_data = array();
            if( $da->html_type == 'text' || $da->html_type == 'date' || $da->html_type == 'time' || $da->html_type == 'number' || $da->html_type == 'textarea' || $da->html_type == 'select' || $da->html_type == 'radio' ){
                if($request->input($da->input_prefix)){
                    $user_keyin = $request->input($da->input_prefix);
                    if (DB::table('car_extra')->where('cars_id','=', $request->car_id)->where('item_attribute_id', '=', $da->item_attribute_id)->exists()) {
                        DB::table('car_extra')->where('cars_id','=', $request->car_id)->where('item_attribute_id','=',$da->item_attribute_id)->update([
                            'item_attribute_value' => $user_keyin,
                        ]);
                    }
                    else{
                        DB::table('car_extra')->insertGetId(
                            [
                                'cars_id' => $request->car_id,
                                'item_type_id' => $request->item_type_id,
                                'item_attribute_id' => $da->item_attribute_id,
                                'item_attribute_value' => $user_keyin,
                                'created_at' => date('Y-m-d H:i:s')
                            ]
                        );
                    }
                }
            }
            else if( $da->html_type == 'checkbox' ){
                if($request->input($da->input_prefix)){
                    
                    $user_keyin = is_array($request->input($da->input_prefix))?implode(',',$request->input($da->input_prefix)):$request->input($da->input_prefix);
                    if (DB::table('car_extra')->where('cars_id','=', $request->car_id)->where('item_attribute_id', '=', $da->item_attribute_id)->exists()) {
                        DB::table('car_extra')->where('cars_id','=', $request->car_id)->where('item_attribute_id','=',$da->item_attribute_id)->update([
                            'item_attribute_value' => $user_keyin,
                        ]);
                    }
                    else{
                        DB::table('car_extra')->insertGetId(
                            [
                                'cars_id' => $request->car_id,
                                'item_type_id' => $request->item_type_id,
                                'item_attribute_id' => $da->item_attribute_id,
                                'item_attribute_value' => $user_keyin,
                                'created_at' => date('Y-m-d H:i:s')
                            ]
                        );
                    }
                }
            }
            else if( $da->html_type == 'file' ){
                if($request->input($da->input_prefix)){
                    $user_keyin = $request->input($da->input_prefix);
                    if (DB::table('car_extra')->where('cars_id','=', $request->car_id)->where('item_attribute_id', '=', $da->item_attribute_id)->exists()) {
                        DB::table('car_extra')->where('cars_id','=', $request->car_id)->where('item_attribute_id','=',$da->item_attribute_id)->update([
                            'item_attribute_value' => $user_keyin,
                        ]);
                    }
                    else{
                        DB::table('car_extra')->insertGetId(
                            [
                                'cars_id' => $request->car_id,
                                'item_type_id' => $request->item_type_id,
                                'item_attribute_id' => $da->item_attribute_id,
                                'item_attribute_value' => $user_keyin,
                                'created_at' => date('Y-m-d H:i:s')
                            ]
                        );
                    }
                }
                // if($request->file($da->input_prefix)){

                //     $input_file = $request->file($da->input_prefix);

                //     $extension = $input_file->getClientOriginalExtension();
                //     $size = ($input_file->getSize()) / 1000;
                //     $file_name = str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                //     $destinaton_path = public_path('images/items/');
                //     $input_file->move($destinaton_path,$file_name);
                //     $file_name_save = $file_name;
                //     $file_size = $size;

                //     if (DB::table('car_extra_file')->where('cars_id','=', $request->car_id)->where('item_attribute_id', '=', $da->item_attribute_id)->exists()) {
                //         DB::table('car_extra_file')->where('cars_id','=', $request->car_id)->where('item_attribute_id','=',$da->item_attribute_id)->update([
                //             'filename' => $file_name_save,
                //         ]);
                //     }
                //     else{
                //         DB::table('car_extra_file')->insertGetId(
                //             [
                //                 'cars_id' => $request->car_id, 
                //                 'item_attribute_id' => $da->item_attribute_id,
                //                 'filename' => $file_name_save, 
                //                 'created_at' => date('Y-m-d H:i:s'), 
                //                 'size' => $file_size, 
                //                 'type' => $extension
                //             ]
                //         );
                //     }

                //     if (DB::table('car_extra')->where('cars_id','=', $request->car_id)->where('item_attribute_id', '=', $da->item_attribute_id)->exists()) {
                //         DB::table('car_extra')->where('cars_id','=', $request->car_id)->where('item_attribute_id','=',$da->item_attribute_id)->update([
                //             'item_attribute_value' => $file_name_save,
                //         ]);
                //     }
                //     else{
                //         DB::table('car_extra')->insertGetId(
                //             [
                //                 'cars_id' => $request->car_id, 
                //                 'item_attribute_id' => $da->item_attribute_id,
                //                 'item_attribute_value' => $file_name_save, 
                //                 'created_at' => date('Y-m-d H:i:s'), 
                //                 'size' => $file_size, 
                //                 'type' => $extension
                //             ]
                //         );
                //     }
                // }
            }
            else{
                // do nothing
            }
        }
    }

    public function getstate($request){
        $state = DB::table('states')->where('state_id', $request->id)->get();
        return $state;
    }

    public function deleterecord($request){
      DB::table('cars')->where('car_id', $request->id)->delete();
    }

    public function merchant()
    {
        // $merchant = DB::table('users')
        //     ->LeftJoin('merchant_branch','users.id','=','merchant_branch.user_id')
        //     ->select('merchant_branch.id','merchant_branch.user_id','merchant_branch.merchant_name')
        //     ->where('users.role_id',11)
        //     ->where('users.status',1)
        //     ->where('merchant_branch.is_default',1)
        //     ->orderBy('merchant_branch.id','ASC')->get();
        $merchant = DB::table('users')
            ->select('users.id','users.company_name','users.first_name','users.last_name')
            ->where('users.role_id',11)
            ->where('users.status',1)
            ->orderBy('users.id','ASC')->get();
        return $merchant;
    }

    public function merchant_data()
    {
        return $this->belongsTo('App\Models\Core\User', 'merchant_id');
    }

    public function car_merchant()
    {
        return $this->belongsTo('App\Models\Core\MerchantBranch', 'merchant_id');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\Core\Cities', 'city_id');
    }

    public function state()
    {
        return $this->belongsTo('App\Models\Core\States', 'state_id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Core\Makes', 'make_id','make_id');
    }

    public function model()
    {
        return $this->belongsTo('App\Models\Core\Models', 'model_id','model_id');
    }

    public function types()
    {
        return $this->belongsTo('App\Models\Core\Types', 'type_id','type_id');
    }

    public  function allcar()
    {
        $carInfo = DB::table('cars')->get();
        return $carInfo;
    }

    public  function allcarbymerchant()
    {
        $carInfo = DB::table('cars')
            ->LeftJoin('merchant_branch','cars.merchant_id','=','merchant_branch.id')
            ->where('merchant_branch.user_id', Auth()->user()->id)->get();
        return $carInfo;
    }

    public function getMerchantCompanyName()
    {
        $merchant = DB::table('users')
            ->select('users.id','users.company_name')
            ->where('users.role_id',11)
            ->where('users.status',1)
            ->where('users.company_name',"!=",null)
            ->orderBy('users.company_name','ASC')->get();
        return $merchant;
    }

    public function getCarUrl()
    {
        $params = array();

        $condition = 'unknown';
        if( $this->status == 1 )
        {
            $condition = "New";
        }
        else if( $this->status == 2 )
        {
            $condition = "Used";
        }
        else if( $this->status == 3 )
        {
            $condition = "Recond";
        }

        $param['condition'] =  Str::slug($condition);
        $param['city'] = Str::slug($this->city ? $this->city->city_name : "");
        $param['title'] = Str::slug($this->title, '-');
        $param['id'] = Str::slug($this->car_id, '-');;
        return $param;
    }

    // count pageview
    public function getPageviewAttribute()
    {
        return $this->calculatePageview();  
    }
    public function calculatePageview(){
        $pageview =  DB::table('traffics_item')
                ->leftJoin('traffics','traffics.id','=','traffics_item.traffic_id')
                ->select(DB::raw('COUNT(traffics_item.id) as pageview'))
                ->where('traffics_item.item_id','=',$this->car_id)
                ->where('traffics.event_type','=','visit')
                ->first();
        return $pageview->pageview;
    }

    // count whatsapp
    public function getWhatsappAttribute()
    {
        return $this->calculateWhatsapp();  
    }
    public function calculateWhatsapp(){
        $whatsapp =  DB::table('traffics_item')
                ->leftJoin('traffics','traffics.id','=','traffics_item.traffic_id')
                ->select(DB::raw('COUNT(traffics_item.id) as pageview'))
                ->where('traffics_item.item_id','=',$this->car_id)
                ->where('traffics.event_type','=','whatsapp')
                ->first();
        return $whatsapp->pageview;
    }

    // count call
    public function getCallAttribute()
    {
        return $this->calculateCall();  
    }
    public function calculateCall(){
        $call =  DB::table('traffics_item')
                ->leftJoin('traffics','traffics.id','=','traffics_item.traffic_id')
                ->select(DB::raw('COUNT(traffics_item.id) as pageview'))
                ->where('traffics_item.item_id','=',$this->car_id)
                ->where('traffics.event_type','=','call')
                ->distinct()
                ->first();
        return $call->pageview;
    }

    // count waze
    public function getWazeAttribute()
    {
        return $this->calculateWaze();  
    }
    public function calculateWaze(){
        $waze =  DB::table('traffics_item')
                ->leftJoin('traffics','traffics.id','=','traffics_item.traffic_id')
                ->select(DB::raw('COUNT(traffics_item.id) as pageview'))
                ->where('traffics_item.item_id','=',$this->car_id)
                ->where('traffics.event_type','=','waze')
                ->first();
        return $waze->pageview;
    }
}
