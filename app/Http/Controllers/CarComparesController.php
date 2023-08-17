<?php

namespace App\Http\Controllers;

use App\Models\Core\Cars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarComparesController extends Controller
{

    public function index(Request $request){
        if(Auth::guard('customer')->user())
            $compared_cars = DB::table('car_compares')->where('user_id',Auth::guard('customer')->user()->id)->pluck('car_id')->toArray();
        else
            $compared_cars = isset($_COOKIE['car_compare_list']) && $_COOKIE['car_compare_list'] != "" ? explode(',',$_COOKIE['car_compare_list']) : array();
        $cars = Cars::whereIn('car_id',$compared_cars)->get();
        return view()->make('newtheme.modules.car_compares.index',compact('cars'));
    }

    public function add(Request $request)
    {
        try {
            $compared_cars = DB::table('car_compares')
                ->where('user_id',Auth::guard('customer')->user()->id)
                ->count();
            if ($compared_cars < 4) {
                DB::table('car_compares')->insert(
                    array(
                        'car_id' => $request->get('car_id'),
                        'user_id' => Auth::guard('customer')->user()->id
                    )
                );
                return response()->json('Car inserted to compare list.');
            }
            else
            {
                return response()->json("You can't campare more then 4 cars");
            }
        } catch (\Throwable $th) {
            return response()->json('Something went wrong, please try after sometimes.');
        }
    }

    public function remove(Request $request)
    {
        try {
            DB::table('car_compares')
                ->where('car_id',$request->get('car_id'))
                ->where('user_id',Auth::guard('customer')->user()->id)
                ->delete();
            return response()->json('Car removed to compare list.');
        } catch (\Throwable $th) {
            return response()->json('Something went wrong, please try after sometimes.');
        }
    }
}
