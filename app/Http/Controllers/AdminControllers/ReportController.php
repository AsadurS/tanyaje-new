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
use App\Models\Core\MerchantBranch;
use App\Models\Core\Promotion;
use App\Models\Core\TrafficOrganisation;
use App\Models\Core\Segments;
use App\Zone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

use Carbon\Carbon;
use File;
use Exception;

class ReportController extends Controller
{
    //
    public function __construct( Cars $cars, Makes $makes, Models $models, Cities $cities, States $States, Types $types){
        $this->Cars = $cars;
        $this->Makes = $makes;
        $this->Models = $models;
        $this->States = $States;
        $this->Cities = $cities;
        $this->Types = $types;
    }

	public function organisationreport(Request $request){
		$title = array('pageTitle' => Lang::get("labels.titleOrgReport"));
		$language_id = '1';
		$images = new Images;
		$allimage = $images->getimages();
		$result = array();
		$message = array();
		$errorMessage = array();

		$admins = DB::table('traffics_organisation')
				->leftJoin('traffics','traffics.id','=','traffics_organisation.traffic_id')
				->leftJoin('users','users.id','=','traffics_organisation.organisation_id')
				->select(
					'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','users.id as company_id','users.company_name','users.brn_no','users.roc_scm_no','users.segment_id',
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'visit' THEN 1 END ) ) AS pageview"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'call' THEN 1 END ) ) AS countCall"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'whatsapp' THEN 1 END ) ) AS whatsapp"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'waze' THEN 1 END ) ) AS waze")
				);
		
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$childOrg = array();
			// main parent
			$hqOrg = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
			if(count($hqOrg) > 0){
				array_push($childOrg, $hqOrg[0]->id);

				$checklvl2Org = DB::table('users')->where('users.parent_id','=', $hqOrg[0]->id)->get();
				if(count($checklvl2Org) > 0){
					foreach($checklvl2Org as $checklvl2Orgs){
						array_push($childOrg, $checklvl2Orgs->id);
						
						$checklvl3Org = DB::table('users')->where('users.parent_id','=', $checklvl2Orgs->id)->get();
						if(count($checklvl3Org) > 0){
							foreach($checklvl3Org as $checklvl3Orgs){
								array_push($childOrg, $checklvl3Orgs->id);
							}
						}
					}
				}
			}
			
			// get id based on selected 
			if(count($childOrg) > 0){
				$admins = $admins->whereIn('users.id' ,$childOrg);
			}
		}
		
		$segment_id = $request->segment_id;
		if( !empty($segment_id) ){
			$admins = $admins->where('users.segment_id','=',$segment_id);
		}
		
		// filter
        if (!empty($request->get('name'))) {
			$admins = $admins->where('users.company_name', 'LIKE', '%' . $request->name . '%');
        }
		if (!empty($request->get('ID'))) {
            $admins = $admins->where('users.id', 'LIKE', $request->ID );
        }
		if (!empty($request->get('brn_no'))) {
            $admins = $admins->where('users.brn_no', 'LIKE', '%' . $request->brn_no . '%');
        }
		if (!empty($request->get('roc_no'))) {
            $admins = $admins->where('users.roc_scm_no', 'LIKE', '%' . $request->roc_no . '%');
        }
		if (!empty($request->get('segment_type'))) {
            $admins = $admins->where('users.segment_id', 'LIKE', '%' . $request->segment_type . '%');
        }
		if (!empty($request->get('filterBy'))) {
            if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$admins = $admins->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$admins = $admins->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$admins = $admins->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$admins = $admins->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
        }else{
			// $admins = $admins->whereMonth('traffics.created_at', date('m'));
			$admins = $admins->whereBetween('traffics.created_at', [
				Carbon::now()->subdays(30)->format('Y-m-d'),
				Carbon::now()->format('Y-m-d')
			]);
		}

		$admins = $admins->groupby('traffics_organisation.organisation_id')->orderBy('pageview','DESC')->simplePaginate(20);

		// total pageview
		$total_pageview1 = DB::table('traffics_organisation')
						->leftJoin('traffics','traffics.id','=','traffics_organisation.traffic_id')
						->where('traffics.event_type', '=' , 'visit')
						->count();

		$total_pageview = $total_pageview1;

		$segments = Segments::all();

		// charts data
		$chart = DB::table('traffics_organisation')
				->leftJoin('traffics','traffics.id','=','traffics_organisation.traffic_id')
				->leftJoin('users','users.id','=','traffics_organisation.organisation_id')
				->select(
					'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','users.id as company_id','users.company_name','users.brn_no','users.roc_scm_no','users.segment_id',
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'visit' THEN 1 END ) ) AS pageview"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'call' THEN 1 END ) ) AS countCall"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type != 'visit' THEN 1 END ) ) AS action"),
					DB::raw("DATE_FORMAT(traffics.created_at, '%M') months"), 
					DB::raw("YEAR(traffics.created_at) year, MONTH(traffics.created_at) month, DAY(traffics.created_at) day")
				);
				if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
					$childOrgChart = array();
					// main parent
					$hqOrgChart = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
					
					if(count($hqOrgChart) > 0){
						array_push($childOrgChart, $hqOrgChart[0]->id);
						
						$checklvl2OrgChart = DB::table('users')->where('users.parent_id','=', $hqOrgChart[0]->id)->get();
						// dd($checklvl2OrgChart);
						if(count($checklvl2OrgChart) > 0){
							foreach($checklvl2OrgChart as $checklvl2OrgCharts){
								array_push($childOrgChart, $checklvl2OrgCharts->id);
								
								$checklvl3OrgChart = DB::table('users')->where('users.parent_id','=', $checklvl2OrgCharts->id)->get();
								if(count($checklvl3OrgChart) > 0){
									foreach($checklvl3OrgChart as $checklvl3OrgCharts){
										array_push($childOrgChart, $checklvl3OrgCharts->id);
									}
								}
							}
						}
					}
					
					// get id based on selected 
					if(count($childOrgChart) > 0){
						$chart = $chart->whereIn('users.id' ,$childOrgChart);
					}
				}
				if (!empty($request->get('name'))) {
					$chart = $chart->where('users.company_name', 'LIKE', '%' . $request->name . '%');
				}
				if (!empty($request->get('ID'))) {
					$chart = $chart->where('users.id', 'LIKE', $request->ID );
				}
				if (!empty($request->get('brn_no'))) {
					$chart = $chart->where('users.brn_no', 'LIKE', '%' . $request->brn_no . '%');
				}
				if (!empty($request->get('roc_no'))) {
					$chart = $chart->where('users.roc_scm_no', 'LIKE', '%' . $request->roc_no . '%');
				}
				if (!empty($request->get('segment_type'))) {
					$chart = $chart->where('users.segment_id', 'LIKE', '%' . $request->segment_type . '%');
				}
				if (!empty($request->get('filterBy'))) {
					if($request->get('filterBy') == 'today'){
						$chartsSubTitle = "by today";
						$today = date('Y-m-d');
						$chart = $chart->whereDate('traffics.created_at', '=', $today);
					}
					if($request->get('filterBy') == 'yesterday'){
						$chartsSubTitle = "by yesterday";
						$yesterday = Carbon::yesterday();
						$chart = $chart->whereDate('traffics.created_at', '=', $yesterday);
					}
					if($request->get('filterBy') == 'thisweek'){
						$chartsSubTitle = "by this week";
						$chart = $chart->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
					}
					if($request->get('filterBy') == 'thismonth'){
						$chartsSubTitle = "by this month";
						$chart = $chart->whereMonth('traffics.created_at', date('m'));
					}
					if($request->get('filterBy') == '7day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 7;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == '30day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 30;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == 'customdate'){
						if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
							$startDate = $request->get('fromDate');
							$endDate = $request->get('toDate');
							$chartsSubTitle = "from ".$startDate." until ".$endDate;
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
				}
				if (empty($request->get('filterBy'))) {
					$chartsSubTitle = "by previous 30 days";
					$chart = $chart->whereBetween('traffics.created_at', [
						Carbon::now()->subdays(30)->format('Y-m-d'),
						Carbon::now()->format('Y-m-d')
					]);
				}
				$chart = $chart->groupby('year','month','day')->get();
		
		$chartsLabel = [];
		$chartsPageview = [];
		$chartsCall = [];
		foreach($chart as $charts){
			if($charts->company_id != null){
				$label = $charts->day."/".$charts->month."/".$charts->year;
				$chartsLabel[] = $label;

				$pageview = $charts->pageview;
				$chartsPageview[] = $pageview;

				// change to action
				$countCall = $charts->action;
				$chartsCall[] = $countCall;
			}
		}
		$result['chartsSubTitle'] = $chartsSubTitle;
		$result['chartsLabel'] = $chartsLabel;
		$result['chartsPageview'] = $chartsPageview;
		$result['chartsCall'] = $chartsCall;

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		$result['admins'] = $admins;
		$result['total_pageview'] = $total_pageview;
		$result['segments'] = $segments;

		return view("admin.report.organisation",$title)->with('result', $result)->with('allimage',$allimage);
	}

	public function salesreport(Request $request){
		$title = array('pageTitle' => Lang::get("labels.titleOrgReport"));
		$language_id = '1';
		$images = new Images;
		$allimage = $images->getimages();
		$result = array();
		$message = array();
		$errorMessage = array();
		$org_list = null;

		$admins = DB::table('traffics_sa')
					->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
					// ->leftJoin('traffics_sa','traffics_sa.traffic_id','=','traffics.id')
					->leftJoin('merchant_branch','merchant_branch.id','=','traffics_sa.sa_id')
					->select(
						'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','merchant_branch.merchant_name as merchant_name', 'merchant_branch.id as merchant_id',
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'visit' THEN 1 END ) ) AS pageview"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'call' THEN 1 END ) ) AS countCall"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'whatsapp' THEN 1 END ) ) AS whatsapp"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'waze' THEN 1 END ) ) AS waze"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'brochure' THEN 1 END ) ) AS brochure"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'pricelist' THEN 1 END ) ) AS pricelist"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'promotion' THEN 1 END ) ) AS promotion"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'redemption' THEN 1 END ) ) AS redemption"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type != 'visit' THEN 1 END ) ) AS action"),
						DB::raw("DATE_FORMAT(traffics.created_at, '%M') months"), 
						DB::raw("YEAR(traffics.created_at) year, MONTH(traffics.created_at) month, DAY(traffics.created_at) day")
					);
		
		$chart = (Clone $admins);

		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$childOrg = array();
			// main parent
			$hqOrg = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
			if(count($hqOrg) > 0){
				array_push($childOrg, $hqOrg[0]->id);

				$checklvl2Org = DB::table('users')->where('users.parent_id','=', $hqOrg[0]->id)->get();
				if(count($checklvl2Org) > 0){
					foreach($checklvl2Org as $checklvl2Orgs){
						array_push($childOrg, $checklvl2Orgs->id);
						
						$checklvl3Org = DB::table('users')->where('users.parent_id','=', $checklvl2Orgs->id)->get();
						if(count($checklvl3Org) > 0){
							foreach($checklvl3Org as $checklvl3Orgs){
								array_push($childOrg, $checklvl3Orgs->id);
							}
						}
					}
				}
			}
			
			// get id based on selected 
			if(count($childOrg) > 0){
				$org_list = $childOrg;
				$admins = $admins->whereIn('merchant_branch.user_id' ,$childOrg);
			}
		}	
		if (!empty($request->get('ID'))) {
			$admins = $admins->where('merchant_branch.id', 'LIKE', $request->ID );
		}
		if (!empty($request->get('name'))) {
			$admins = $admins->where('merchant_branch.merchant_name', 'LIKE', '%' . $request->name . '%');
		}
		if (!empty($request->get('organisation_id'))) {
			$admins = $admins->where('merchant_branch.user_id', 'LIKE', '%' . $request->organisation_id . '%');
		}
		if (!empty($request->get('filterBy'))) {
            if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$admins = $admins->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$admins = $admins->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$admins = $admins->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$admins = $admins->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
        }
		else{
			$admins = $admins->whereBetween('traffics.created_at', [
				Carbon::now()->subdays(30)->format('Y-m-d'),
				Carbon::now()->format('Y-m-d')
			]);
		}

		$admins = $admins->groupby('traffics_sa.sa_id')->orderBy('pageview','DESC')->simplePaginate(20);

		$total_pageview1 = DB::table('traffics_sa')
							->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
							->where('traffics.event_type', '=' , 'visit')
							->count();
		$total_pageview = $total_pageview1;

		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['organisation'] = $organisation;

		// charts data
		// $chart = DB::table('traffics_sa')
		// 		->leftJoin('traffics','traffics.id','=','traffics_sa.traffic_id')
		// 		->leftJoin('merchant_branch','merchant_branch.id','=','traffics_sa.sa_id')
		// 		->select(
		// 			'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','merchant_branch.merchant_name as merchant_name', 'merchant_branch.id as merchant_id',
		// 			DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'visit' THEN 1 END ) ) AS pageview"),
		// 			DB::raw("COUNT( ( CASE WHEN traffics.event_type != 'visit' THEN 1 END ) ) AS action"),
		// 			DB::raw("DATE_FORMAT(traffics.created_at, '%M') months"), 
		// 			DB::raw("YEAR(traffics.created_at) year, MONTH(traffics.created_at) month, DAY(traffics.created_at) day")
		// 		);
				if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
					$childOrgChart = array();
					// main parent
					$hqOrgChart = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
					
					if(count($hqOrgChart) > 0){
						array_push($childOrgChart, $hqOrgChart[0]->id);
						
						$checklvl2OrgChart = DB::table('users')->where('users.parent_id','=', $hqOrgChart[0]->id)->get();
						// dd($checklvl2OrgChart);
						if(count($checklvl2OrgChart) > 0){
							foreach($checklvl2OrgChart as $checklvl2OrgCharts){
								array_push($childOrgChart, $checklvl2OrgCharts->id);
								
								$checklvl3OrgChart = DB::table('users')->where('users.parent_id','=', $checklvl2OrgCharts->id)->get();
								if(count($checklvl3OrgChart) > 0){
									foreach($checklvl3OrgChart as $checklvl3OrgCharts){
										array_push($childOrgChart, $checklvl3OrgCharts->id);
									}
								}
							}
						}
					}
					
					// get id based on selected 
					if(count($childOrgChart) > 0){
						$chart = $chart->whereIn('merchant_branch.user_id' ,$childOrgChart);
					}
				}
				if (!empty($request->get('ID'))) {
					$chart = $chart->where('merchant_branch.id', 'LIKE', $request->ID );
				}
				if (!empty($request->get('name'))) {
					$chart = $chart->where('merchant_branch.merchant_name', 'LIKE', '%' . $request->name . '%');
				}
				if (!empty($request->get('organisation_id'))) {
					$chart = $chart->where('merchant_branch.user_id', 'LIKE', '%' . $request->organisation_id . '%');
				}
				if (!empty($request->get('filterBy'))) {
					if($request->get('filterBy') == 'today'){
						$chartsSubTitle = "by today";
						$today = date('Y-m-d');
						$chart = $chart->whereDate('traffics.created_at', '=', $today);
					}
					if($request->get('filterBy') == 'yesterday'){
						$chartsSubTitle = "by yesterday";
						$yesterday = Carbon::yesterday();
						$chart = $chart->whereDate('traffics.created_at', '=', $yesterday);
					}
					if($request->get('filterBy') == 'thisweek'){
						$chartsSubTitle = "by this week";
						$chart = $chart->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
					}
					if($request->get('filterBy') == 'thismonth'){
						$chartsSubTitle = "by this month";
						$chart = $chart->whereMonth('traffics.created_at', date('m'));
					}
					if($request->get('filterBy') == '7day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 7;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == '30day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 30;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == 'customdate'){
						if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
							$startDate = $request->get('fromDate');
							$endDate = $request->get('toDate');
							$chartsSubTitle = "from ".$startDate." until ".$endDate;
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
				}
				if (empty($request->get('filterBy'))) {
					$chartsSubTitle = "by previous 30 days";
					$chart = $chart->whereBetween('traffics.created_at', [
						Carbon::now()->subdays(30)->format('Y-m-d'),
						Carbon::now()->format('Y-m-d')
					]);
				}
				$chart = $chart->groupby('year','month','day')->get();
		
		$chartsLabel = [];
		$chartsPageview = [];
		$chartsCall = [];
		foreach($chart as $charts){
			if($charts->merchant_id != null){
				$label = $charts->day."/".$charts->month."/".$charts->year;
				$chartsLabel[] = $label;

				$pageview = $charts->pageview;
				$chartsPageview[] = $pageview;

				// change to action
				$countCall = $charts->action;
				$chartsCall[] = $countCall;
			}
		}
		$result['chartsSubTitle'] = $chartsSubTitle;
		$result['chartsLabel'] = $chartsLabel;
		$result['chartsPageview'] = $chartsPageview;
		$result['chartsCall'] = $chartsCall;

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		$result['admins'] = $admins;
		$result['total_pageview'] = $total_pageview;

		$result['org_list'] = $org_list;

		return view("admin.report.sales",$title)->with('result', $result)->with('allimage',$allimage);
	}

	public function itemreport(Request $request){
	    // ini_set('memory_limit', '999M');
		$title = array('pageTitle' => Lang::get("labels.titleOrgReport"));
		$language_id = '1';
		$images = new Images;
		$allimage = $images->getimages();
		$result = array();
		$message = array();
		$errorMessage = array();
		$org_list = null;

		$admins = DB::table('traffics_item')
					->leftJoin('traffics','traffics.id','=','traffics_item.traffic_id')
					// ->leftJoin('traffics_item','traffics_item.traffic_id','=','traffics.id')
					->leftJoin('cars','cars.car_id','=','traffics_item.item_id')
					->select(
						'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','cars.car_id as item_id', 'cars.title as item_title',
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'visit' THEN 1 END ) ) AS pageview"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'call' THEN 1 END ) ) AS countCall"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'whatsapp' THEN 1 END ) ) AS whatsapp"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'waze' THEN 1 END ) ) AS waze")
					);
			
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$childOrg = array();
			// main parent
			$hqOrg = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
			if(count($hqOrg) > 0){
				array_push($childOrg, $hqOrg[0]->id);

				$checklvl2Org = DB::table('users')->where('users.parent_id','=', $hqOrg[0]->id)->get();
				if(count($checklvl2Org) > 0){
					foreach($checklvl2Org as $checklvl2Orgs){
						array_push($childOrg, $checklvl2Orgs->id);
						
						$checklvl3Org = DB::table('users')->where('users.parent_id','=', $checklvl2Orgs->id)->get();
						if(count($checklvl3Org) > 0){
							foreach($checklvl3Org as $checklvl3Orgs){
								array_push($childOrg, $checklvl3Orgs->id);
							}
						}
					}
				}
			}
			
			// get id based on selected 
			if(count($childOrg) > 0){
				$org_list = $childOrg;
				$admins = $admins->whereIn('cars.user_id' ,$childOrg);
			}
		}	
		if (!empty($request->get('filter_name'))) {
			$admins = $admins->where('cars.title', 'LIKE', '%' . $request->filter_name . '%');
		}
		if (!empty($request->get('organisation_id'))) {
			$admins = $admins->where('cars.user_id', '=',  $request->organisation_id );
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$admins = $admins->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$admins = $admins->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$admins = $admins->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$admins = $admins->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
		}
		else{
			$admins = $admins->whereBetween('traffics.created_at', [
				Carbon::now()->subdays(30)->format('Y-m-d'),
				Carbon::now()->format('Y-m-d')
			]);
		}

		$admins = $admins->groupby('traffics_item.item_id')->orderBy('pageview','DESC')->simplePaginate(20);
		
		// total pageview
		$total_pageview = DB::table('traffics_item')
							->leftJoin('traffics','traffics.id','=','traffics_item.traffic_id')
							->where('traffics.event_type', '=' , 'visit')
							->count();
		
		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		
		// charts data
		$chart = DB::table('traffics_item')
				->leftJoin('traffics','traffics.id','=','traffics_item.traffic_id')
				->leftJoin('cars','cars.car_id','=','traffics_item.item_id')
				->select(
					'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','cars.car_id as item_id', 'cars.title as item_title',
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'visit' THEN 1 END ) ) AS pageview"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type != 'visit' THEN 1 END ) ) AS action"),
					DB::raw("DATE_FORMAT(traffics.created_at, '%M') months"), 
					DB::raw("YEAR(traffics.created_at) year, MONTH(traffics.created_at) month, DAY(traffics.created_at) day")
				);
				
				if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
					$childOrgChart = array();
					// main parent
					$hqOrgChart = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
					
					if(count($hqOrgChart) > 0){
						array_push($childOrgChart, $hqOrgChart[0]->id);
						
						$checklvl2OrgChart = DB::table('users')->where('users.parent_id','=', $hqOrgChart[0]->id)->get();
						// dd($checklvl2OrgChart);
						if(count($checklvl2OrgChart) > 0){
							foreach($checklvl2OrgChart as $checklvl2OrgCharts){
								array_push($childOrgChart, $checklvl2OrgCharts->id);
								
								$checklvl3OrgChart = DB::table('users')->where('users.parent_id','=', $checklvl2OrgCharts->id)->get();
								if(count($checklvl3OrgChart) > 0){
									foreach($checklvl3OrgChart as $checklvl3OrgCharts){
										array_push($childOrgChart, $checklvl3OrgCharts->id);
									}
								}
							}
						}
					}
					
					// get id based on selected 
					if(count($childOrgChart) > 0){
						$chart = $chart->whereIn('cars.user_id' ,$childOrgChart);
					}
				}
				if (!empty($request->get('filter_name'))) {
					$chart = $chart->where('cars.title', 'LIKE', '%' . $request->filter_name . '%');
				}
				if (!empty($request->get('organisation_id'))) {
					$chart = $chart->where('cars.user_id', '=',  $request->organisation_id );
				}
				if (!empty($request->get('filterBy'))) {
					if($request->get('filterBy') == 'today'){
						$chartsSubTitle = "by today";
						$today = date('Y-m-d');
						$chart = $chart->whereDate('traffics.created_at', '=', $today);
					}
					if($request->get('filterBy') == 'yesterday'){
						$chartsSubTitle = "by yesterday";
						$yesterday = Carbon::yesterday();
						$chart = $chart->whereDate('traffics.created_at', '=', $yesterday);
					}
					if($request->get('filterBy') == 'thisweek'){
						$chartsSubTitle = "by this week";
						$chart = $chart->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
					}
					if($request->get('filterBy') == 'thismonth'){
						$chartsSubTitle = "by this month";
						$chart = $chart->whereMonth('traffics.created_at', date('m'));
					}
					if($request->get('filterBy') == '7day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 7;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == '30day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 30;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == 'customdate'){
						if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
							$startDate = $request->get('fromDate');
							$endDate = $request->get('toDate');
							$chartsSubTitle = "from ".$startDate." until ".$endDate;
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
				}
				if (empty($request->get('filterBy'))) {
					$chartsSubTitle = "by previous 30 days";
					$chart = $chart->whereBetween('traffics.created_at', [
						Carbon::now()->subdays(30)->format('Y-m-d'),
						Carbon::now()->format('Y-m-d')
					]);
				}
				$chart = $chart->groupby('year','month','day')->get();
		
		$chartsLabel = [];
		$chartsPageview = [];
		$chartsCall = [];
		foreach($chart as $charts){
			if($charts->item_id != null){
				$label = $charts->day."/".$charts->month."/".$charts->year;
				$chartsLabel[] = $label;

				$pageview = $charts->pageview;
				$chartsPageview[] = $pageview;

				// change to action
				$countCall = $charts->action;
				$chartsCall[] = $countCall;
			}
		}
		$result['chartsSubTitle'] = $chartsSubTitle;
		$result['chartsLabel'] = $chartsLabel;
		$result['chartsPageview'] = $chartsPageview;
		$result['chartsCall'] = $chartsCall;

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		$result['admins'] = $admins;
		$result['total_pageview'] = $total_pageview;
		$result['admins'] = $admins;
		$result['organisation'] = $organisation;
		$result['org_list'] = $org_list;
      
		return view("admin.report.item",$title)->with('result', $result)->with('allimage',$allimage);
	}

	public function promotionreport(Request $request){
		$title = array('pageTitle' => Lang::get("labels.titleOrgReport"));
		$language_id = '1';
		$images = new Images;
		$allimage = $images->getimages();
		$result = array();
		$message = array();
		$errorMessage = array();
		$org_list = null;

		$admins = DB::table('traffics_promotion')
					->leftJoin('traffics','traffics.id','=','traffics_promotion.traffic_id')
					// ->leftJoin('traffics_promotion','traffics_promotion.traffic_id','=','traffics.id')
					->leftJoin('promotion','promotion.promotion_id','=','traffics_promotion.promotion_id')
					->select(
						'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','promotion.promotion_id as promotion_ids', 'promotion.promotion_name as promotion_name',
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'promotion_click' THEN 1 END ) ) AS pageview")
					);
		
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$childOrg = array();
			// main parent
			$hqOrg = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
			if(count($hqOrg) > 0){
				array_push($childOrg, $hqOrg[0]->id);

				$checklvl2Org = DB::table('users')->where('users.parent_id','=', $hqOrg[0]->id)->get();
				if(count($checklvl2Org) > 0){
					foreach($checklvl2Org as $checklvl2Orgs){
						array_push($childOrg, $checklvl2Orgs->id);
						
						$checklvl3Org = DB::table('users')->where('users.parent_id','=', $checklvl2Orgs->id)->get();
						if(count($checklvl3Org) > 0){
							foreach($checklvl3Org as $checklvl3Orgs){
								array_push($childOrg, $checklvl3Orgs->id);
							}
						}
					}
				}
			}
			
			// get id based on selected 
			if(count($childOrg) > 0){
				$org_list = $childOrg;
				$admins = $admins->whereIn('promotion.organisation' ,$childOrg);
			}
		}	
		if (!empty($request->get('filter_name'))) {
			$admins = $admins->where('promotion.promotion_name', 'LIKE', '%' . $request->filter_name . '%');
		}
		if (!empty($request->get('organisation_id'))) {
			$admins = $admins->where('promotion.organisation', '=',  $request->organisation_id );
		}
		if (!empty($request->get('segment_type'))) {
			$admins = $admins->where('promotion.segment_id', '=',  $request->segment_type);
		}
		if (!empty($request->get('status_id'))) {
			$admins = $admins->where('promotion.status', '=',  $request->status_id );
		}
		if (!empty($request->get('filterBy'))) {
            if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$admins = $admins->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$admins = $admins->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$admins = $admins->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$admins = $admins->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$admins = $admins->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
        }
		else{
			$admins = $admins->whereBetween('traffics.created_at', [
				Carbon::now()->subdays(30)->format('Y-m-d'),
				Carbon::now()->format('Y-m-d')
			]);
		}

		$admins = $admins->groupby('traffics_promotion.promotion_id')->orderBy('pageview','DESC')->simplePaginate(20);

		$total_pageview = DB::table('traffics_promotion')
							->leftJoin('traffics','traffics.id','=','traffics_promotion.traffic_id')
							->where('traffics.event_type', '=' , 'promotion_click')
							->count();

		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		
		$segments = Segments::all();

		// charts data
		$chart = DB::table('traffics_promotion')
				->leftJoin('traffics','traffics.id','=','traffics_promotion.traffic_id')
				->leftJoin('promotion','promotion.promotion_id','=','traffics_promotion.promotion_id')
				->select(
					'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','promotion.promotion_id as promotion_ids', 'promotion.promotion_name as promotion_name',
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'promotion_click' THEN 1 END ) ) AS pageview"),
					DB::raw("DATE_FORMAT(traffics.created_at, '%M') months"), 
					DB::raw("YEAR(traffics.created_at) year, MONTH(traffics.created_at) month, DAY(traffics.created_at) day")
				);
				
				if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
					$childOrgChart = array();
					// main parent
					$hqOrgChart = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
					
					if(count($hqOrgChart) > 0){
						array_push($childOrgChart, $hqOrgChart[0]->id);
						
						$checklvl2OrgChart = DB::table('users')->where('users.parent_id','=', $hqOrgChart[0]->id)->get();
						// dd($checklvl2OrgChart);
						if(count($checklvl2OrgChart) > 0){
							foreach($checklvl2OrgChart as $checklvl2OrgCharts){
								array_push($childOrgChart, $checklvl2OrgCharts->id);
								
								$checklvl3OrgChart = DB::table('users')->where('users.parent_id','=', $checklvl2OrgCharts->id)->get();
								if(count($checklvl3OrgChart) > 0){
									foreach($checklvl3OrgChart as $checklvl3OrgCharts){
										array_push($childOrgChart, $checklvl3OrgCharts->id);
									}
								}
							}
						}
					}
					
					// get id based on selected 
					if(count($childOrgChart) > 0){
						$chart = $chart->whereIn('promotion.organisation' ,$childOrgChart);
					}
				}
				if (!empty($request->get('filter_name'))) {
					$chart = $chart->where('promotion.promotion_name', 'LIKE', '%' . $request->filter_name . '%');
				}
				if (!empty($request->get('organisation_id'))) {
					$chart = $chart->where('promotion.organisation', '=',  $request->organisation_id );
				}
				if (!empty($request->get('segment_type'))) {
					$chart = $chart->where('promotion.segment_id', '=',  $request->segment_type);
				}
				if (!empty($request->get('status_id'))) {
					$chart = $chart->where('promotion.status', '=',  $request->status_id );
				}
				if (!empty($request->get('filterBy'))) {
					if($request->get('filterBy') == 'today'){
						$chartsSubTitle = "by today";
						$today = date('Y-m-d');
						$chart = $chart->whereDate('traffics.created_at', '=', $today);
					}
					if($request->get('filterBy') == 'yesterday'){
						$chartsSubTitle = "by yesterday";
						$yesterday = Carbon::yesterday();
						$chart = $chart->whereDate('traffics.created_at', '=', $yesterday);
					}
					if($request->get('filterBy') == 'thisweek'){
						$chartsSubTitle = "by this week";
						$chart = $chart->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
					}
					if($request->get('filterBy') == 'thismonth'){
						$chartsSubTitle = "by this month";
						$chart = $chart->whereMonth('traffics.created_at', date('m'));
					}
					if($request->get('filterBy') == '7day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 7;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == '30day'){
						if(!empty($request->get('fromDate'))){
							$startDate = $request->get('fromDate');
							$date = Carbon::createFromFormat('Y-m-d', $startDate);
							$daysToAdd = 30;
							$date = $date->addDays($daysToAdd);
							$endDate = $date;
							$chartsSubTitle = "from ".$startDate." until ".$endDate->toDateString();
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
					if($request->get('filterBy') == 'customdate'){
						if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
							$startDate = $request->get('fromDate');
							$endDate = $request->get('toDate');
							$chartsSubTitle = "from ".$startDate." until ".$endDate;
							$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
						}
					}
				}
				if (empty($request->get('filterBy'))) {
					$chartsSubTitle = "by previous 30 days";
					$chart = $chart->whereBetween('traffics.created_at', [
						Carbon::now()->subdays(30)->format('Y-m-d'),
						Carbon::now()->format('Y-m-d')
					]);
				}
				$chart = $chart->groupby('year','month','day')->get();
		
		$chartsLabel = [];
		$chartsPageview = [];
		// $chartsCall = [];
		foreach($chart as $charts){
			if($charts->promotion_ids != null){
				$label = $charts->day."/".$charts->month."/".$charts->year;
				$chartsLabel[] = $label;

				$pageview = $charts->pageview;
				$chartsPageview[] = $pageview;

				// $countCall = $charts->countCall;
				// $chartsCall[] = $countCall;
			}
		}
		$result['chartsSubTitle'] = $chartsSubTitle;
		$result['chartsLabel'] = $chartsLabel;
		$result['chartsPageview'] = $chartsPageview;
		// $result['chartsCall'] = $chartsCall;

		$result['organisation'] = $organisation;
		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		$result['admins'] = $admins;
		$result['total_pageview'] = $total_pageview;
		$result['segments'] = $segments;
		$result['org_list'] = $org_list;
    
		return view("admin.report.promotion",$title)->with('result', $result)->with('allimage',$allimage);
	}

	public function campaignreport(Request $request){
		$title = array('pageTitle' => Lang::get("labels.titleCampaignReport"));
		$language_id = '1';
		$images = new Images;
		$allimage = $images->getimages();
		$result = array();
		$message = array();
		$errorMessage = array();

		// GET TOTAL ACTIVE CAMPAIGN
		$total_campaigns = DB::table('campaigns')->leftJoin('campaign_status_by_level','campaign_status_by_level.campaign_id','=','campaigns.campaign_id');
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$parentOrg = array();
			array_push($parentOrg, Auth()->user()->id);
			if(Auth()->user()->parent_id != null){
				$parent1 = DB::table('users')->where('users.id','=', Auth()->user()->parent_id)->first();
				if($parent1->id != null){
					array_push($parentOrg, $parent1->id);
					if($parent1->parent_id != null){
						$parent2 = DB::table('users')->where('users.id','=', $parent1->parent_id)->first();
						if($parent2->id != null){
							array_push($parentOrg, $parent2->id);
						}
					}
				}
			}
			if(count($parentOrg) > 0){
				$total_campaigns = $total_campaigns->whereIn('campaigns.org_id' ,$parentOrg);
				$total_campaigns = $total_campaigns->whereIn('campaign_status_by_level.organisation_id' ,$parentOrg)->where('campaign_status_by_level.status', '=', '1');
			}
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$total_campaigns = $total_campaigns->whereDate('campaigns.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$total_campaigns = $total_campaigns->whereDate('campaigns.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$total_campaigns = $total_campaigns->whereBetween('campaigns.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$total_campaigns = $total_campaigns->whereMonth('campaigns.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$total_campaigns = $total_campaigns->whereBetween('campaigns.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$total_campaigns = $total_campaigns->whereBetween('campaigns.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$total_campaigns = $total_campaigns->whereBetween('campaigns.created_at', [$startDate, $endDate]);
				}
			}
		}
		$total_campaigns = $total_campaigns->groupBy('campaigns.campaign_id');
		$total_campaigns = $total_campaigns->get();
		$result['total_campaigns'] = $total_campaigns;

		// campaign tracker
		$top_campaigns = DB::table('traffics_campaign')
					->leftJoin('traffics','traffics.id','=','traffics_campaign.traffic_id')
					->leftJoin('campaigns','campaigns.campaign_id','=','traffics_campaign.campaign_id')
					->leftJoin('users','users.id','=','traffics_campaign.org_id')
					->leftJoin('merchant_branch','merchant_branch.id','=','traffics_campaign.sa_id');

		// filter for all
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$childOrg = array();
			// main parent
			$hqOrg = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
			if(count($hqOrg) > 0){
				array_push($childOrg, $hqOrg[0]->id);

				$checklvl2Org = DB::table('users')->where('users.parent_id','=', $hqOrg[0]->id)->get();
				if(count($checklvl2Org) > 0){
					foreach($checklvl2Org as $checklvl2Orgs){
						array_push($childOrg, $checklvl2Orgs->id);
						
						$checklvl3Org = DB::table('users')->where('users.parent_id','=', $checklvl2Orgs->id)->get();
						if(count($checklvl3Org) > 0){
							foreach($checklvl3Org as $checklvl3Orgs){
								array_push($childOrg, $checklvl3Orgs->id);
							}
						}
					}
				}
			}
			
			// get id based on selected 
			if(count($childOrg) > 0){
				$top_campaigns = $top_campaigns->whereIn('users.id' ,$childOrg);
			}
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$top_campaigns = $top_campaigns->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$top_campaigns = $top_campaigns->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$top_campaigns = $top_campaigns->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
		}

		// top 5 campaign
		$result['top_campaigns'] = (Clone $top_campaigns)
						->select(
							'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','campaigns.*','campaigns.campaign_id as campaign_ids', 'campaigns.campaign_name as campaign_name',
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response")
						)
						->where('campaigns.campaign_name','!=',null)
						->groupby('traffics_campaign.campaign_id')
						->orderBy('share','DESC')->orderBy('click', 'DESC')->limit(5)->get();

		// top 5 organisation
		$result['top_orgs'] = (Clone $top_campaigns)
						->select(
							'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','campaigns.*','campaigns.campaign_id as campaign_ids', 'campaigns.campaign_name as campaign_name','users.company_name','users.id as company_id',
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response")
						)
						->where('users.company_name','!=', null)
						->where('campaigns.campaign_name','!=',null)
						->groupby('traffics_campaign.org_id')
						->orderBy('share','DESC')->orderBy('click', 'DESC')->get();
						// dd($result['top_orgs']);

		// top 5 sa
		$result['top_sas'] = (Clone $top_campaigns)
						->select(
							'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','campaigns.*','campaigns.campaign_id as campaign_ids', 'campaigns.campaign_name as campaign_name','merchant_branch.merchant_name','merchant_branch.id as merchant_id',
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response")
						)
						->where('users.company_name','!=', null)
						->where('campaigns.campaign_name','!=',null)
						->groupby('traffics_campaign.sa_id')
						->orderBy('share','DESC')->orderBy('click', 'DESC')->get();

		// total share
		$result['total_share'] = (Clone $top_campaigns)
						->where('traffics.event_type','=','campaign_share')
						->where('campaigns.campaign_name','!=',null)
						->count();

		// total click/open
		$result['total_click'] = (Clone $top_campaigns)
						->where('traffics.event_type','=','campaign_click')
						->where('campaigns.campaign_name','!=',null)
						->count();
		
		// total interested
		$result['total_interest'] = (Clone $top_campaigns)
						->where('traffics.event_type','=','campaign_interest')
						->where('campaigns.campaign_name','!=',null)
						->count();
				
		// total response
		$result['total_response'] = (Clone $top_campaigns)
						->where('traffics.event_type','=','campaign_response')
						->where('campaigns.campaign_name','!=',null)
						->count();

		// determine login organisation role
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$check_role = Auth()->user()->id;
			// check hq
			$checkHq = DB::table('users')->where('users.id','=', $check_role)->select('users.parent_id')->first();
			if($checkHq->parent_id != null){
				$isHq = '0'; // not hq
			}
			else{
				$isHq = '1'; //is hq
			}
			// check region
			$checkRegion = DB::table('users')->where('users.id','=', $check_role)->select('users.parent_id')->first();
			$allUsersRegion = DB::table('users')->where('users.parent_id','=', $check_role)->get();
			if($checkRegion->parent_id != null && count($allUsersRegion) == 0){
				$isRegion = '0'; // not region
			}
			else{
				$isRegion = '1'; //is region
			}
			// check branch manager
			$checkBranchManager = DB::table('users')->where('users.id','=', $check_role)->select('users.parent_id')->first();
			$allUsersBranchManager = DB::table('users')->where('users.parent_id','=', $check_role)->get();
			if($checkBranchManager->parent_id != null && count($allUsersBranchManager) != 0){
				$isBranchManager = '0'; // not branch manager
			}
			else{
				$isBranchManager = '1'; //is branch manager
			}
		}
		else{
			$isHq = '';
			$isRegion = '';
			$isBranchManager = '';
		}
		$result['isHq'] = $isHq;
		$result['isRegion'] = $isRegion;
		$result['isBranchManager'] = $isBranchManager;

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;

		return view("admin.report.campaign",$title)->with('result', $result)->with('allimage',$allimage);
	}

	public function campaignfullreport(Request $request){
		$title = array('pageTitle' => Lang::get("labels.titleCampaignReport"));
		$language_id = '1';
		$images = new Images;
		$allimage = $images->getimages();
		$result = array();
		$message = array();
		$errorMessage = array();
		$org_list = null;

		// campaign tracker
		$top_campaigns = DB::table('traffics_campaign')
					->leftJoin('traffics','traffics.id','=','traffics_campaign.traffic_id')
					->leftJoin('campaigns','campaigns.campaign_id','=','traffics_campaign.campaign_id')
					->leftJoin('users','users.id','=','traffics_campaign.org_id')
					->leftJoin('merchant_branch','merchant_branch.id','=','traffics_campaign.sa_id');

		// filter for all
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$childOrg = array();
			// main parent
			$hqOrg = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
			if(count($hqOrg) > 0){
				array_push($childOrg, $hqOrg[0]->id);

				// child
				$checklvl2Org = DB::table('users')->where('users.parent_id','=', $hqOrg[0]->id)->get();
				if(count($checklvl2Org) > 0){
					foreach($checklvl2Org as $checklvl2Orgs){
						array_push($childOrg, $checklvl2Orgs->id);
						
						$checklvl3Org = DB::table('users')->where('users.parent_id','=', $checklvl2Orgs->id)->get();
						if(count($checklvl3Org) > 0){
							foreach($checklvl3Org as $checklvl3Orgs){
								array_push($childOrg, $checklvl3Orgs->id);
							}
						}
					}
				}

				// parent
				$parent_id = $hqOrg[0]->parent_id;
				$checkParent1 = DB::table('users')->where('users.id','=', $parent_id)->get();
				if(count($checkParent1) > 0){
					foreach($checkParent1 as $checkParent1s){
						array_push($childOrg, $checkParent1s->id);
						if($checkParent1s->parent_id != null){
							$checkParent2 = DB::table('users')->where('users.id','=', $checkParent1s->parent_id)->get();
							if(count($checkParent2) > 0){
								foreach($checkParent2 as $checkParent2s){
									array_push($childOrg, $checkParent2s->id);
								}
							}
						}
					}
				}

				if(count($checklvl2Org) > 0){
					foreach($checklvl2Org as $checklvl2Orgs){
						array_push($childOrg, $checklvl2Orgs->id);
						
						$checklvl3Org = DB::table('users')->where('users.parent_id','=', $checklvl2Orgs->id)->get();
						if(count($checklvl3Org) > 0){
							foreach($checklvl3Org as $checklvl3Orgs){
								array_push($childOrg, $checklvl3Orgs->id);
							}
						}
					}
				}
			}
			
			// get id based on selected 
			if(count($childOrg) > 0){
				$org_list = $childOrg;
				$top_campaigns = $top_campaigns->whereIn('traffics_campaign.org_id' ,$childOrg);
			}
		}
		if (!empty($request->get('organisation_id'))) {
			$top_campaigns = $top_campaigns->where('traffics_campaign.org_id', '=',  $request->organisation_id );
		}
		if (!empty($request->get('sa_id'))) {
			$top_campaigns = $top_campaigns->where('traffics_campaign.sa_id', '=',  $request->sa_id );
		}
		if (!empty($request->get('campaign_id'))) {
			$top_campaigns = $top_campaigns->where('traffics_campaign.campaign_id', '=',  $request->campaign_id );
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$top_campaigns = $top_campaigns->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$top_campaigns = $top_campaigns->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$top_campaigns = $top_campaigns->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
		}

		// all campaign
		$result['top_campaigns'] = (Clone $top_campaigns)
						->select(
							'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','campaigns.*','campaigns.campaign_id as campaign_ids', 'campaigns.campaign_name as campaign_name','users.company_name', 'merchant_branch.merchant_name',
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response")
						)
						->where('campaigns.campaign_name','!=',null)
						->groupby('traffics_campaign.campaign_id')
						->orderBy('share','DESC')->simplePaginate(15);
						// dd($result['top_campaigns']);
		
		// chart data for campaign full report ----------------------------------
		$chart = DB::table('traffics_campaign')
				->leftJoin('traffics','traffics.id','=','traffics_campaign.traffic_id')
				->leftJoin('campaigns','campaigns.campaign_id','=','traffics_campaign.campaign_id')
				->select(
					'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','campaigns.campaign_id as campaign_ids', 'campaigns.campaign_name as campaign_name',
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response"),
					DB::raw("DATE_FORMAT(traffics.created_at, '%M') months"), 
					DB::raw("YEAR(traffics.created_at) year, MONTH(traffics.created_at) month, DAY(traffics.created_at) day")
				);
		// filter for all
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$childOrg = array();
			// main parent
			$hqOrg = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
			if(count($hqOrg) > 0){
				array_push($childOrg, $hqOrg[0]->id);

				$checklvl2Org = DB::table('users')->where('users.parent_id','=', $hqOrg[0]->id)->get();
				if(count($checklvl2Org) > 0){
					foreach($checklvl2Org as $checklvl2Orgs){
						array_push($childOrg, $checklvl2Orgs->id);
						
						$checklvl3Org = DB::table('users')->where('users.parent_id','=', $checklvl2Orgs->id)->get();
						if(count($checklvl3Org) > 0){
							foreach($checklvl3Org as $checklvl3Orgs){
								array_push($childOrg, $checklvl3Orgs->id);
							}
						}
					}
				}
			}
			
			// get id based on selected 
			if(count($childOrg) > 0){
				$chart = $chart->whereIn('traffics_campaign.org_id' ,$childOrg);
			}
		}
		if (!empty($request->get('organisation_id'))) {
			$chart = $chart->where('traffics_campaign.org_id', '=',  $request->organisation_id );
		}
		if (!empty($request->get('campaign_id'))) {
			$chart = $chart->where('traffics_campaign.campaign_id', '=',  $request->campaign_id );
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$chart = $chart->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$chart = $chart->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$chart = $chart->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$chart = $chart->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
		}
		$chart = $chart->groupby('year','month','day')->get();
		// dd($chart);
		$chartsLabel = [];
		$chartsPageview = [];
		$chartsShare = [];
		$chartsInterest = [];
		$chartsResponse = [];
		foreach($chart as $charts){
			if($charts->campaign_ids != null){
				$label = $charts->day."/".$charts->month."/".$charts->year;
				$chartsLabel[] = $label;
				// click/open
				$pageview = $charts->click;
				$chartsPageview[] = $pageview;
				// share
				$countShare = $charts->share;
				$chartsShare[] = $countShare;
				// interest
				$countInterest = $charts->interest;
				$chartsInterest[] = $countInterest;
				// response
				$countResponse = $charts->response;
				$chartsResponse[] = $countResponse;
			}
		}
		$result['chartsSubTitle'] = 'subtitle';
		$result['chartsLabel'] = $chartsLabel;
		$result['chartsPageview'] = $chartsPageview;
		$result['chartsShare'] = $chartsShare;
		$result['chartsInterest'] = $chartsInterest;
		$result['chartsResponse'] = $chartsResponse;
		// end chart data

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;

		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['organisation'] = $organisation;

		$campaignn = DB::table('campaigns')->get();
		$result['campaignn'] = $campaignn;
		$result['org_list'] = $org_list;
		// dd($result['org_list']);

		return view("admin.report.campaign_report",$title)->with('result', $result)->with('allimage',$allimage);
	}

	public function campaignfullreportorg(Request $request){
		$title = array('pageTitle' => Lang::get("labels.titleCampaignReport"));
		$language_id = '1';
		$images = new Images;
		$allimage = $images->getimages();
		$result = array();
		$message = array();
		$errorMessage = array();
		$org_list = null;

		// campaign tracker
		$top_campaigns = DB::table('traffics_campaign')
					->leftJoin('traffics','traffics.id','=','traffics_campaign.traffic_id')
					->leftJoin('campaigns','campaigns.campaign_id','=','traffics_campaign.campaign_id')
					->leftJoin('users','users.id','=','traffics_campaign.org_id')
					->leftJoin('merchant_branch','merchant_branch.id','=','traffics_campaign.sa_id');

		// filter for all
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$childOrg = array();
			// main parent
			$hqOrg = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
			if(count($hqOrg) > 0){
				array_push($childOrg, $hqOrg[0]->id);

				$checklvl2Org = DB::table('users')->where('users.parent_id','=', $hqOrg[0]->id)->get();
				if(count($checklvl2Org) > 0){
					foreach($checklvl2Org as $checklvl2Orgs){
						array_push($childOrg, $checklvl2Orgs->id);
						
						$checklvl3Org = DB::table('users')->where('users.parent_id','=', $checklvl2Orgs->id)->get();
						if(count($checklvl3Org) > 0){
							foreach($checklvl3Org as $checklvl3Orgs){
								array_push($childOrg, $checklvl3Orgs->id);
							}
						}
					}
				}
			}
			
			// get id based on selected 
			if(count($childOrg) > 0){
				$top_campaigns = $top_campaigns->whereIn('users.id' ,$childOrg);
			}
		}
		if (!empty($request->get('organisation_id'))) {
			$top_campaigns = $top_campaigns->where('traffics_campaign.org_id', '=',  $request->organisation_id );
		}
		if (!empty($request->get('campaign_id'))) {
			$top_campaigns = $top_campaigns->where('traffics_campaign.campaign_id', '=',  $request->campaign_id );
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$top_campaigns = $top_campaigns->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$top_campaigns = $top_campaigns->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$top_campaigns = $top_campaigns->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
		}

		// top 5 organisation
		$result['top_orgs'] = (Clone $top_campaigns)
						->select(
							'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','campaigns.*','campaigns.campaign_id as campaign_ids', 'campaigns.campaign_name as campaign_name','users.company_name','users.id as company_id',
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response")
						)
						->where('users.company_name','!=', null)
						->where('campaigns.campaign_name','!=',null)
						->groupby('traffics_campaign.org_id')
						->orderBy('share','DESC')->orderBy('click', 'DESC')->simplePaginate(10);
						// dd($result['top_orgs']);
		
		// chart data for campaign full report ----------------------------------
		$chart = DB::table('traffics_campaign')
				->leftJoin('traffics','traffics.id','=','traffics_campaign.traffic_id')
				->leftJoin('users','users.id','=','traffics_campaign.org_id')
				->leftJoin('campaigns','campaigns.campaign_id','=','traffics_campaign.campaign_id')
				->leftJoin('merchant_branch','merchant_branch.id','=','traffics_campaign.sa_id')
				->select(
					// 'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','campaigns.campaign_id as campaign_ids', 'campaigns.campaign_name as campaign_name',
					'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','campaigns.*','campaigns.campaign_id as campaign_ids', 'campaigns.campaign_name as campaign_name','users.company_name','users.id as company_id',
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response"),
					DB::raw("DATE_FORMAT(traffics.created_at, '%M') months"), 
					DB::raw("YEAR(traffics.created_at) year, MONTH(traffics.created_at) month, DAY(traffics.created_at) day")
				);
		// filter for all
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$childOrg = array();
			// main parent
			$hqOrg = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
			if(count($hqOrg) > 0){
				array_push($childOrg, $hqOrg[0]->id);

				$checklvl2Org = DB::table('users')->where('users.parent_id','=', $hqOrg[0]->id)->get();
				if(count($checklvl2Org) > 0){
					foreach($checklvl2Org as $checklvl2Orgs){
						array_push($childOrg, $checklvl2Orgs->id);
						
						$checklvl3Org = DB::table('users')->where('users.parent_id','=', $checklvl2Orgs->id)->get();
						if(count($checklvl3Org) > 0){
							foreach($checklvl3Org as $checklvl3Orgs){
								array_push($childOrg, $checklvl3Orgs->id);
							}
						}
					}
				}
			}
			
			// get id based on selected 
			if(count($childOrg) > 0){
				$org_list = $childOrg;
				$chart = $chart->whereIn('users.id' ,$childOrg);
			}
		}
		if (!empty($request->get('organisation_id'))) {
			$chart = $chart->where('traffics_campaign.org_id', '=',  $request->organisation_id );
		}
		if (!empty($request->get('campaign_id'))) {
			$chart = $chart->where('traffics_campaign.campaign_id', '=',  $request->campaign_id );
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$chart = $chart->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$chart = $chart->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$chart = $chart->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$chart = $chart->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
		}
		$chart = $chart->where('campaigns.campaign_name','!=',null)->where('users.company_name','!=', null);
		$chart = $chart->groupby('traffics_campaign.org_id','year','month','day')->get();
		// dd($chart);
		$chartsLabel = [];
		$chartsPageview = [];
		$chartsShare = [];
		$chartsInterest = [];
		$chartsResponse = [];
		foreach($chart as $charts){
			if($charts->campaign_ids != null){
				$label = $charts->day."/".$charts->month."/".$charts->year;
				$chartsLabel[] = $label;
				// click/open
				$pageview = $charts->click;
				$chartsPageview[] = $pageview;
				// share
				$countShare = $charts->share;
				$chartsShare[] = $countShare;
				// interest
				$countInterest = $charts->interest;
				$chartsInterest[] = $countInterest;
				// response
				$countResponse = $charts->response;
				$chartsResponse[] = $countResponse;
			}
		}
		$result['chartsSubTitle'] = 'subtitle';
		$result['chartsLabel'] = $chartsLabel;
		$result['chartsPageview'] = $chartsPageview;
		$result['chartsShare'] = $chartsShare;
		$result['chartsInterest'] = $chartsInterest;
		$result['chartsResponse'] = $chartsResponse;
		// end chart data

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;

		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['organisation'] = $organisation;

		$campaignn = DB::table('campaigns')->where('status','=','1')->get();
		$result['campaignn'] = $campaignn;
		$result['org_list'] = $org_list;

		return view("admin.report.campaign_report_org",$title)->with('result', $result)->with('allimage',$allimage);
	}

	public function campaignfullreportsa(Request $request){
		$title = array('pageTitle' => Lang::get("labels.titleCampaignReport"));
		$language_id = '1';
		$images = new Images;
		$allimage = $images->getimages();
		$result = array();
		$message = array();
		$errorMessage = array();
		$org_list = null;

		// campaign tracker
		$top_campaigns = DB::table('traffics_campaign')
					->leftJoin('traffics','traffics.id','=','traffics_campaign.traffic_id')
					->leftJoin('campaigns','campaigns.campaign_id','=','traffics_campaign.campaign_id')
					->leftJoin('users','users.id','=','traffics_campaign.org_id')
					->leftJoin('merchant_branch','merchant_branch.id','=','traffics_campaign.sa_id');

		// filter for all
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$childOrg = array();
			// main parent
			$hqOrg = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
			if(count($hqOrg) > 0){
				array_push($childOrg, $hqOrg[0]->id);

				$checklvl2Org = DB::table('users')->where('users.parent_id','=', $hqOrg[0]->id)->get();
				if(count($checklvl2Org) > 0){
					foreach($checklvl2Org as $checklvl2Orgs){
						array_push($childOrg, $checklvl2Orgs->id);
						
						$checklvl3Org = DB::table('users')->where('users.parent_id','=', $checklvl2Orgs->id)->get();
						if(count($checklvl3Org) > 0){
							foreach($checklvl3Org as $checklvl3Orgs){
								array_push($childOrg, $checklvl3Orgs->id);
							}
						}
					}
				}
			}
			
			// get id based on selected 
			if(count($childOrg) > 0){
				$top_campaigns = $top_campaigns->whereIn('traffics_campaign.org_id' ,$childOrg);
			}
		}
		if (!empty($request->get('organisation_id'))) {
			$top_campaigns = $top_campaigns->where('traffics_campaign.org_id', '=',  $request->organisation_id );
		}
		if (!empty($request->get('sa_id'))) {
			$top_campaigns = $top_campaigns->where('traffics_campaign.sa_id', '=',  $request->sa_id );
		}
		if (!empty($request->get('campaign_id'))) {
			$top_campaigns = $top_campaigns->where('traffics_campaign.campaign_id', '=',  $request->campaign_id );
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$top_campaigns = $top_campaigns->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$top_campaigns = $top_campaigns->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$top_campaigns = $top_campaigns->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$top_campaigns = $top_campaigns->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
		}

		// top 5 sa
		$result['top_sas'] = (Clone $top_campaigns)
						->select(
							'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','campaigns.*','campaigns.campaign_id as campaign_ids', 'campaigns.campaign_name as campaign_name','merchant_branch.merchant_name','merchant_branch.id as merchant_id',
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
							DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response")
						)
						->where('users.company_name','!=', null)
						->where('campaigns.campaign_name','!=',null)
						// ->groupby('traffics_campaign.org_id')
						->groupby('traffics_campaign.sa_id')
						->orderBy('share','DESC')->orderBy('click', 'DESC')->simplePaginate(10);
		
		// chart data for campaign full report ----------------------------------
		$chart = DB::table('traffics_campaign')
				->leftJoin('traffics','traffics.id','=','traffics_campaign.traffic_id')
				->leftJoin('users','users.id','=','traffics_campaign.org_id')
				->leftJoin('campaigns','campaigns.campaign_id','=','traffics_campaign.campaign_id')
				->leftJoin('merchant_branch','merchant_branch.id','=','traffics_campaign.sa_id')
				->select(
					// 'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','campaigns.campaign_id as campaign_ids', 'campaigns.campaign_name as campaign_name',
					'traffics.id','traffics.organisation_id','traffics.sa_id','traffics.event_type','campaigns.*','campaigns.campaign_id as campaign_ids', 'campaigns.campaign_name as campaign_name','users.company_name','users.id as company_id',
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response"),
					DB::raw("DATE_FORMAT(traffics.created_at, '%M') months"), 
					DB::raw("YEAR(traffics.created_at) year, MONTH(traffics.created_at) month, DAY(traffics.created_at) day")
				);
		// filter for all
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$childOrg = array();
			// main parent
			$hqOrg = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
			if(count($hqOrg) > 0){
				array_push($childOrg, $hqOrg[0]->id);

				$checklvl2Org = DB::table('users')->where('users.parent_id','=', $hqOrg[0]->id)->get();
				if(count($checklvl2Org) > 0){
					foreach($checklvl2Org as $checklvl2Orgs){
						array_push($childOrg, $checklvl2Orgs->id);
						
						$checklvl3Org = DB::table('users')->where('users.parent_id','=', $checklvl2Orgs->id)->get();
						if(count($checklvl3Org) > 0){
							foreach($checklvl3Org as $checklvl3Orgs){
								array_push($childOrg, $checklvl3Orgs->id);
							}
						}
					}
				}
			}
			
			// get id based on selected 
			if(count($childOrg) > 0){
				$org_list = $childOrg;
				$chart = $chart->whereIn('traffics_campaign.org_id' ,$childOrg);
			}
		}
		if (!empty($request->get('organisation_id'))) {
			$chart = $chart->where('traffics_campaign.org_id', '=',  $request->organisation_id );
		}
		if (!empty($request->get('campaign_id'))) {
			$chart = $chart->where('traffics_campaign.campaign_id', '=',  $request->campaign_id );
		}
		if (!empty($request->get('sa_id'))) {
			$chart = $chart->where('traffics_campaign.sa_id', '=',  $request->sa_id );
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$chart = $chart->whereDate('traffics.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$chart = $chart->whereDate('traffics.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$chart = $chart->whereBetween('traffics.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$chart = $chart->whereMonth('traffics.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$chart = $chart->whereBetween('traffics.created_at', [$startDate, $endDate]);
				}
			}
		}
		$chart = $chart->where('users.company_name','!=', null)->where('campaigns.campaign_name','!=',null);
		$chart = $chart->groupby('traffics_campaign.sa_id','year','month','day')->get();
		// dd($chart);
		$chartsLabel = [];
		$chartsPageview = [];
		$chartsShare = [];
		$chartsInterest = [];
		$chartsResponse = [];
		foreach($chart as $charts){
			if($charts->campaign_ids != null){
				$label = $charts->day."/".$charts->month."/".$charts->year;
				$chartsLabel[] = $label;
				// click/open
				$pageview = $charts->click;
				$chartsPageview[] = $pageview;
				// share
				$countShare = $charts->share;
				$chartsShare[] = $countShare;
				// interest
				$countInterest = $charts->interest;
				$chartsInterest[] = $countInterest;
				// response
				$countResponse = $charts->response;
				$chartsResponse[] = $countResponse;
			}
		}
		$result['chartsSubTitle'] = 'subtitle';
		$result['chartsLabel'] = $chartsLabel;
		$result['chartsPageview'] = $chartsPageview;
		$result['chartsShare'] = $chartsShare;
		$result['chartsInterest'] = $chartsInterest;
		$result['chartsResponse'] = $chartsResponse;
		// end chart data

		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;

		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['organisation'] = $organisation;

		$sale_advisors = DB::table('merchant_branch')
			->leftJoin('users','users.id','=','merchant_branch.user_id')
			->select('merchant_branch.*')
			->where('merchant_branch.user_id','!=',null)
			->get();
		$result['sale_advisors'] = $sale_advisors;

		$campaignn = DB::table('campaigns')->where('status','=','1')->get();
		$result['campaignn'] = $campaignn;
		$result['org_list'] = $org_list;

		// get sa list based on org login
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			// get id based on selected 
			if(count($childOrg) > 0){
				$sa_list = DB::table('merchant_branch')->whereIn('merchant_branch.user_id' ,$childOrg)->get();
			}
		}
		else{
			$sa_list = DB::table('merchant_branch')->get();
		}
		$result['sa_list'] = $sa_list;

		return view("admin.report.campaign_report_sa",$title)->with('result', $result)->with('allimage',$allimage);
	}

	public function campaignsresponse(Request $request){
		$title = array('pageTitle' => Lang::get("labels.titleCampaignResponse"));
		$language_id = '1';
		$result = array();
		$message = array();
		$errorMessage = array();
		$org_list = null;

		$campaign_response = DB::table('campaigns_response')
							->leftJoin('campaigns','campaigns.campaign_id','=','campaigns_response.campaign_id')
							->leftJoin('users','users.id','=','campaigns_response.org_id')
							->leftJoin('merchant_branch','merchant_branch.id','=','campaigns_response.sa_id')
							->select('campaigns_response.*','campaigns.campaign_name as campaign_name','campaigns.status as status','users.company_name as org_name','merchant_branch.merchant_name as sa_name');

		
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$childOrg = array();
			// main parent
			$hqOrg = DB::table('users')->where('users.id','=', Auth()->user()->id)->get();
			if(count($hqOrg) > 0){
				array_push($childOrg, $hqOrg[0]->id);

				$checklvl2Org = DB::table('users')->where('users.parent_id','=', $hqOrg[0]->id)->get();
				if(count($checklvl2Org) > 0){
					foreach($checklvl2Org as $checklvl2Orgs){
						array_push($childOrg, $checklvl2Orgs->id);
						
						$checklvl3Org = DB::table('users')->where('users.parent_id','=', $checklvl2Orgs->id)->get();
						if(count($checklvl3Org) > 0){
							foreach($checklvl3Org as $checklvl3Orgs){
								array_push($childOrg, $checklvl3Orgs->id);
							}
						}
					}
				}
			}
			
			// get id based on selected 
			if(count($childOrg) > 0){
				$org_list = $childOrg;
				$campaign_response = $campaign_response->whereIn('campaigns_response.org_id' ,$childOrg);
			}
		}
		// filter for all
		if (!empty($request->get('organisation_id'))) {
			$campaign_response = $campaign_response->where('campaigns_response.org_id', '=',  $request->organisation_id );
		}
		if (!empty($request->get('campaign_id'))) {
			$campaign_response = $campaign_response->where('campaigns_response.campaign_id', '=',  $request->campaign_id );
		}
		if (!empty($request->get('filterBy'))) {
			if($request->get('filterBy') == 'today'){
				$today = date('Y-m-d');
				$campaign_response = $campaign_response->whereDate('campaigns_response.created_at', '=', $today);
			}
			if($request->get('filterBy') == 'yesterday'){
				$yesterday = Carbon::yesterday();
				$campaign_response = $campaign_response->whereDate('campaigns_response.created_at', '=', $yesterday);
			}
			if($request->get('filterBy') == 'thisweek'){
				$campaign_response = $campaign_response->whereBetween('campaigns_response.created_at', [Carbon::now()->subWeek()->format("Y-m-d H:i:s"), Carbon::now()]);
			}
			if($request->get('filterBy') == 'thismonth'){
				$campaign_response = $campaign_response->whereMonth('campaigns_response.created_at', date('m'));
			}
			if($request->get('filterBy') == '7day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 7;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$campaign_response = $campaign_response->whereBetween('campaigns_response.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == '30day'){
				if(!empty($request->get('fromDate'))){
					$startDate = $request->get('fromDate');
					$date = Carbon::createFromFormat('Y-m-d', $startDate);
					$daysToAdd = 30;
					$date = $date->addDays($daysToAdd);
					$endDate = $date;
					$campaign_response = $campaign_response->whereBetween('campaigns_response.created_at', [$startDate, $endDate]);
				}
			}
			if($request->get('filterBy') == 'customdate'){
				if( !empty($request->get('fromDate')) && !empty($request->get('toDate')) ){
					$startDate = $request->get('fromDate');
					$endDate = $request->get('toDate');
					$campaign_response = $campaign_response->whereBetween('campaigns_response.created_at', [$startDate, $endDate]);
				}
			}
		}
		$campaign_response = $campaign_response->where('campaigns.campaign_name','!=',null);
		$campaign_response = $campaign_response->orderBy('campaigns_response.id', 'desc')->simplePaginate(15);
		$result['campaign_response'] = $campaign_response;

		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['organisation'] = $organisation;

		$campaignn = DB::table('campaigns')->where('status','=','1')->get();
		$result['campaignn'] = $campaignn;
		$result['org_list'] = $org_list;

		return view("admin.report.campaign_response",$title)->with('result', $result);
	}
	
}