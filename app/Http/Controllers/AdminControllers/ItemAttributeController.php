<?php

namespace App\Http\Controllers\AdminControllers;

use App\Models\Core\Languages;
use App\Models\Core\Setting;
use App\Models\Admin\Admin;
use App\Models\Core\Order;
use App\Models\Core\Customers;
use App\Models\Core\States;
use App\Models\Core\Cities;
use App\Models\Core\Makes;
use App\Models\Core\Cars;
use App\Models\Core\User;
use App\Models\Core\Template;
use App\Models\Core\Banks;
use App\Models\Core\Segments;
use App\Models\Core\ItemAttributes;
use App\Models\Core\ItemAttributesValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Exception;
use App\Models\Core\Images;
use Validator;
use Hash;
use Auth;
use ZipArchive;
use File;
use Mail;
use Carbon\Carbon;

class ItemAttributeController extends Controller
{
    private $data = [];
    private $page_title = [];

    public function __construct(ItemAttributes $item_attributes,Banks $bank,Admin $admin, Setting $setting, Order $order, Customers $customers, Cities $cities, States $States, Makes $makes, Cars $cars, User $user)
    {
        $this->item_attributes =$item_attributes;
		$this->bank =$bank;
        $this->Setting = $setting;
        $this->Admin = $admin;
        $this->Order = $order;
		$this->Customers = $customers;
		$this->States = $States;
		$this->Cities = $cities;
		$this->Makes = $makes;
		$this->Cars = $cars;
		$this->User = $user;
    }

    public function display() {
        $title = array('pageTitle' => Lang::get("labels.ListingItemAttributes"));
		$language_id = '1';

		$result = array();
		$message = array();
		$errorMessage = array();

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		$result['item_attributes'] = $this->item_attributes->paginator(50);
      	
        return view('admin.item_attribute.index',$title)->with('result', $result);
    }

    public function add(Request $request){
		$title = array('pageTitle' => Lang::get("labels.add_item_attribute"));

		$result = array();
		$message = array();
		$errorMessage = array();
		$myVar = new AddressController();
		$result['countries'] = $myVar->getAllCountries();
		$state = $this->States->getter();
		$result['state'] = $state;
		$city = $this->Cities->getter();
        $result['city'] = $city;
		$adminTypes = DB::table('user_types')->where('isActive', 1)->where('user_types_id','>','10')->get();
		$result['adminTypes'] = $adminTypes;

		return view("admin.item_attribute.add",$title)->with('result', $result);
	}

    public function insert(Request $request){
        // dd($request->all());
		$time = Carbon::now();
		$currentUser = Auth()->user()->id;
		
        // item attribute insert
        $item_attributes = DB::table('item_attributes')->insertGetId([
			'name' => $request->name,
			'html_type' => $request->html_type,
            'is_item_img' => $request->is_item_img,
            'sort_no' => $request->sort_no,
            'header' => $request->header,
			'created_at' => date('Y-m-d H:i:s')
		]);

        $update_item_attributes = DB::table('item_attributes')
                                    ->where('id','=',$item_attributes)
                                    ->update([
                                        'input_prefix' => Str::slug($request->name,"_")."_".$item_attributes
                                    ]);

        // item attribute value insert
        $attributeValue = $request->attributeValue;
        if(empty($attributeValue[0])){
            
        }
        else{
            for($count = 0; $count < count($attributeValue); $count++)
            {
                if($attributeValue[$count] != null){
                    $data = array(
                        'item_attributes_id'     => $item_attributes,
                        'attribute_value'        => $attributeValue[$count],
                        'created_at'             => date('Y-m-d H:i:s')
                    );
                    $insert_data[] = $data; 
                }
                else{
                    $insert_data[] = "";
                }
            }
            if(!empty($insert_data)){
                ItemAttributesValue::insert($insert_data);
            }
            else{
                
            }
        }
        

        $message = 'Item Attribute has been created successfully!';
        return  redirect('admin/itemattribute/display')->with('message', $message);
    }

	public function edit(Request $request)
    {
        $title = array('pageTitle' => Lang::get("labels.editItemAttribute"));
		$myid = $request->id;
		
		$result = array();
		$message = array();
		$errorMessage = array();
		
		$result['myid'] = $myid;

        $admins = DB::table('item_attributes')->where('id','=', $myid)->get();
        $result['admins'] = $admins;

        $attribute_value = DB::table('item_attributes_value')->where('item_attributes_id','=', $myid)->get();
        $result['attribute_value'] = $attribute_value;

		return view("admin/item_attribute/edit",$title)->with('result', $result);
	}

    public function update(Request $request){
        // dd($request->all());
		$time = Carbon::now();
		$currentUser = Auth()->user()->id;
		
        // item attribute update
        $item_attributes = DB::table('item_attributes')->where('id','=', $request->id)->update([
            'name' => $request->name,
			'html_type' => $request->html_type,
            'input_prefix' => Str::slug($request->name,"_")."_".$request->id,
            'sort_no' => $request->sort_no,
            'is_item_img' => $request->is_item_img,
            'header' => $request->header,
			'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // item attribute value insert
        $attribute_value = $request->attributeValue;
        $attribute_id = $request->attribute_id;
        for($count = 0; $count < count($attribute_value); $count++)
        {
            // dd($attribute_id[$count]);
            if(isset($attribute_id[$count])){
                if (DB::table('item_attributes_value')->where('id', '=', $attribute_id[$count])->exists()) {
                    $item_attributes_value = DB::table('item_attributes_value')->where('id','=', $attribute_id[$count])->update([
                        'attribute_value' => $attribute_value[$count],
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
            else{
                if($attribute_value[$count] != null){
                    $data = array(
                        'item_attributes_id'   => $request->id,
                        'attribute_value'      => $attribute_value[$count],
                        'created_at'           => date('Y-m-d H:i:s')
                    );
                    $insert_data[] = $data; 
                }
            } 
        }
        if(!empty($insert_data)){
            DB::table('item_attributes_value')->insert($insert_data);
        }
        else{
            
        }

        $message = Lang::get("labels.ItemAttributeValueEditMessage");
        return redirect()->back()->with('message', $message);
    }

	public function delete(Request $request){
        $myid = $request->id;
        DB::table('item_attributes')->where('id','=', $myid)->delete();
        return redirect()->back()->withErrors([Lang::get("labels.DeleteItemAttributeMessage")]);
    }

    public function deleteAttributeValue(Request $request){
        $attribute_value_id = $request->id;
        $attribute_value = DB::table('item_attributes_value')->where('id','=',$attribute_value_id)->delete();
        return redirect()->back()->withErrors([Lang::get("labels.DeleteAttributeValueMessage")]);
    }
}
