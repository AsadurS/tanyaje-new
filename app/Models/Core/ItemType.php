<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class ItemType extends Model {
    use Sortable;

    public $sortable = ['id', 'name'];

    private $table_name = 'item_type';

    public function delete($id = 0) {
        if (empty($id)) {
            return false;
        }

        DB::table($this->table_name)->where('id', $id)->delete();

        return true;
    }

    public function updateItemType($id = 0, $request) {
        if (empty($id) || empty($request)) return false;

        DB::table($this->table_name)->where('id', $id)->update([
            'name' => $request->name
        ]);

        return true;
    }

    public function getItemType($id = 0) {
        if (empty($id)) {
            return null;
        }

        $result = DB::table($this->table_name)->where('id', $id)->get();

        return !empty($result) ? $result : null;
    }

    public function insert($request) {
        $item_type_id = DB::table($this->table_name)->insertGetId([
            'name'       => $request->name,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $item_type_id;
    }

    public function paginator($item_per_page = 30) {
        $listing = DB::table($this->table_name)
            ->orderBy('id')
            ->paginate($item_per_page);

        return $listing;
    }
}
