<?php

namespace App\Http\Controllers\AdminControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CarsStatisticsController extends Controller
{
    public function index(Request $request)
    {
        $report = trans('labels.Statistics Report');
        $query = DB::table('cars_statistics')
                    ->leftjoin('cars','cars_statistics.car_id','cars.car_id');
        if($request->get('type') == "car")
        {
            $query->select('cars.car_id','cars.vim','cars.title', DB::raw("count('cars_statistics.car_id') AS visits"))->groupBy('cars_statistics.car_id');
            $report = trans('labels.Popular Cars');
        }
        elseif($request->get('type') == "brand")
        {
            $query->leftJoin('makes','cars_statistics.make_id','makes.make_id')
            ->select('makes.make_name as title', DB::raw("count('cars_statistics.car_id') AS visits"))->groupBy('cars_statistics.make_id');
            $report = trans('labels.Popular Brands');
        }
        elseif($request->get('type') == "model")
        {
            $query->leftJoin('models','cars_statistics.model_id','models.model_id')
            ->leftJoin('makes','models.model_make_id','makes.make_id')
            ->select('makes.make_name as brand','models.model_name as title', DB::raw("count('cars_statistics.car_id') AS visits"))->groupBy('cars_statistics.model_id');
            $report = trans('labels.Popular Models');
        }
        elseif($request->get('type') == "type")
        {
            $query->leftJoin('types','cars_statistics.type_id','types.type_id')
            ->select('types.type_name as title', DB::raw("count('cars_statistics.car_id') AS visits"))->groupBy('cars_statistics.type_id');
            $report = trans('labels.Popular Types');
        }
        elseif($request->get('type') == "state")
        {
            $query->leftJoin('states','cars_statistics.state_id','states.state_id')
            ->select('states.state_name as title', DB::raw("count('cars_statistics.car_id') AS visits"))->groupBy('cars_statistics.state_id');
            $report = trans('labels.Popular States');
        }
        elseif($request->get('type') == "city")
        {
            $query->leftJoin('cities','cars_statistics.city_id','cities.city_id')
            ->select('cities.city_name as title', DB::raw("count('cars_statistics.car_id') AS visits"))->groupBy('cars_statistics.city_id');
            $report = trans('labels.Popular Cities');
        }
        elseif($request->get('type') == "price")
        {
            $query->leftJoin('states','cars.state_id','states.state_id')
            ->select('states.state_name as title',DB::raw("MIN(cars.price) AS min_price, MAX(cars.price) AS max_price") , DB::raw("count('cars_statistics.car_id') AS visits"))->groupBy('cars.state_id');
            $report = trans('labels.Popular Price Range');
        }
        else
        {
            $query->select('cars.car_id','cars.vim','cars.title', DB::raw("count('cars_statistics.car_id') AS visits"))->groupBy('cars_statistics.car_id');
            $report = trans('labels.Popular Cars');
        }
        if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
            $query->where('cars.merchant_id',Auth()->user()->id);
        }

        $cars = $query->orderBy('visits','desc')
                ->get();

        return view()->make('admin.cars_statistics.index',compact('cars','request','report'));
    }
}
