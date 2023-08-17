<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Core\ItemType;
use Doctrine\DBAL\Schema\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;

class ItemTypeController extends Controller {
    private $data = [];
    private $item_per_page = 30;

    private $item_type = null;

    public function __construct(ItemType $itemType) {
        $this->data['pageTitle'] = Lang::get('labels.item_type');

        $this->item_type = $itemType;
    }

    public function add(Request $request) {
        $result = array();
        $item_attribute = DB::table('item_attributes')->get();
        $result['item_attribute'] = $item_attribute;

        return view('admin.item_type.add', $this->data)->with('result', $result);
    }

    public function edit(Request $request) {
        $result = array();

        $item_attribute = DB::table('item_attributes')->get();
        $result['item_attribute'] = $item_attribute;

        if (!empty($request->id)) {
            $item_type = $this->item_type->getItemType($request->id);
            $this->data['item_type'] = !empty($item_type) ? $item_type{0} : null;

            $item_type_attributes = DB::table('item_type_attributes')->where('item_type_id','=',$request->id)->get();
            $result['item_type_attributes'] = $item_type_attributes;
        }

        return view('admin.item_type.edit', $this->data)->with('data', $this->data)->with('result', $result);
    }

    public function display() {
        $listing = $this->item_type->paginator($this->item_per_page);

        $this->data['item_types'] = $listing;
        return view('admin.item_type.index', $this->data)->with('data', $this->data);
    }

    public function delete(Request $request) {

        if($request->id == '1'){
            $result = '';
        }
        else{
            $result = $this->item_type->delete($request->id);
        }

        if (!empty($result)) {
            $message_prefix = 'message';
            $message = Lang::get("labels.item_type_deleted_successfully");
        } else {
            $message_prefix = 'error_warning';
            $message = Lang::get("labels.item_type_failed_delete");
        }

        return redirect('admin/itemtype/display')->with($message_prefix, $message);
    }

    public function update(Request $request) {
        // dd($request->all());
        $result = $this->item_type->updateItemType($request->id, $request);

        // bind item
        $attributes_bind = DB::table('item_type_attributes')->where('item_type_id','=',$request->id)->get();
        if(count($attributes_bind) > 0 ){
            DB::table('item_type_attributes')->where('item_type_id','=',$request->id)->delete();
        }

        // item attribute
        $checkAttribute = $request->checkAttribute;
        if($checkAttribute){
            for($count = 0; $count < count($checkAttribute); $count++)
            {
                if(isset($checkAttribute[$count])){
                    if (DB::table('item_type_attributes')->where('item_type_id', '=', $request->id)->where('item_attribute_id','=',$checkAttribute[$count])->exists()) {

                    }
                    else{
                        $data = array(
                            'item_type_id'          => $request->id,
                            'item_attribute_id'     => $checkAttribute[$count],
                            'created_at'             => date('Y-m-d H:i:s')
                        );
                        $insert_data[] = $data;
                    }
                }
            }
            if(!empty($insert_data)){
                DB::table('item_type_attributes')->insert($insert_data);
            }
        }
        

        if (!empty($result)) {
            $message_prefix = 'message';
            $message = Lang::get("labels.item_type_edited_successfully");
        } else {
            $message_prefix = 'error_warning';
            $message = Lang::get("labels.item_type_failed_edit");
        }

        return redirect('admin/itemtype/display')->with($message_prefix, $message);
    }

    public function insert(Request $request) {
        $item_type_id = $this->item_type->insert($request);

        // item attribute
        $checkAttribute = $request->checkAttribute;
        if(isset($checkAttribute)){
            for($count = 0; $count < count($checkAttribute); $count++)
            {
                $data = array(
                    'item_type_id'          => $item_type_id,
                    'item_attribute_id'     => $checkAttribute[$count],
                    'created_at'             => date('Y-m-d H:i:s')
                );
                $insert_data[] = $data;
            }
            if(!empty($insert_data)){
                DB::table('item_type_attributes')->insert($insert_data);
            }
            else{
                
            }
        }

        if (!empty($item_type_id)) {
            $message = Lang::get("labels.item_type_added_successfully");
        } else {
            $message = Lang::get("labels.item_type_failed_add");
        }

        return redirect('admin/itemtype/display')->with('message', $message);
    }
}
