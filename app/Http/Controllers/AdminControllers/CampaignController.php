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
use App\Models\Core\Segments;
use App\Models\Core\MerchantBranch;
use App\Models\Core\Setting;
use App\Zone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use App\Models\Core\Promotion;
use App\Models\Core\Campaign;
use App\Models\Core\Redemption;
use Carbon\Carbon;
use File;
use Exception;

class CampaignController extends Controller
{
    //
    public function __construct( Cars $cars, Makes $makes, Models $models, Cities $cities, States $States, Types $types,  Promotion $promo, Redemption $redeem,  User $user, Campaign $campaign ){
        $this->Cars = $cars;
        $this->Makes = $makes;
        $this->Models = $models;
        $this->States = $States;
        $this->Cities = $cities;
        $this->Types = $types;
        $this->Promotion = $promo;
		$this->Redemption = $redeem;
		$this->User = $user;
		$this->Campaign = $campaign;
    }

	public function campaigns(Request $request){
		$title = array('pageTitle' => Lang::get("labels.ListingBanks"));
		$language_id = '1';

		$result = array();
		$message = array();
		$errorMessage = array();
		$org_list = null;

        $segments = Segments::all();
		$organisation = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		
		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;

		$campaign = DB::table('campaigns')
				->leftJoin('traffics_campaign','traffics_campaign.campaign_id','=','campaigns.campaign_id')
				->leftJoin('traffics','traffics.id','=','traffics_campaign.traffic_id');

		// check if user auth, need to gather their campaign together
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$childOrg = array();
			$campaignIds = array();
			// main parent
			$hqOrg = Auth()->user()->id;
			if($hqOrg != null){
				array_push($childOrg, $hqOrg);

				$checklvl2Org = DB::table('users')->where('users.parent_id','=', $hqOrg)->get();
				$checklvl2ChildOrg = DB::table('users')->where('users.id','=', $hqOrg)->first();
				// child - get all child id
				if($checklvl2ChildOrg->parent_id != null){
					array_push($childOrg, $checklvl2ChildOrg->parent_id);
					$checklvl2ChildOrg2 = DB::table('users')->where('users.id','=', $checklvl2ChildOrg->parent_id)->first();
					if($checklvl2ChildOrg2->parent_id != null){
						array_push($childOrg, $checklvl2ChildOrg2->parent_id);
						$checklvl2ChildOrg3 = DB::table('users')->where('users.id','=', $checklvl2ChildOrg2->parent_id)->first();
						if($checklvl2ChildOrg3->parent_id != null){
							array_push($childOrg, $checklvl2ChildOrg3->parent_id);
							$checklvl2ChildOrg4 = DB::table('users')->where('users.id','=', $checklvl2ChildOrg3->parent_id)->first();
							if($checklvl2ChildOrg4->parent_id != null){
								array_push($childOrg, $checklvl2ChildOrg4->parent_id);
								$checklvl2ChildOrg5 = DB::table('users')->where('users.id','=', $checklvl2ChildOrg4->parent_id)->first();
								if($checklvl2ChildOrg5->parent_id != null){
									array_push($childOrg, $checklvl2ChildOrg5->parent_id);
								}	
							}	
						}	
					}	
				}	
				// parent - get all parent id
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
			// get id based on selected && get campaign based on collected org id
			if(count($childOrg) > 0){
				$org_list = $childOrg;
				$campaign = $campaign->whereIn('campaigns.org_id' ,$childOrg);

				$campaignn = DB::table('campaigns')->whereIn('campaigns.org_id' ,$childOrg);
				$storeCampaignId = (Clone $campaignn)->groupBy('campaigns.campaign_id')->get();
				// dd($storeCampaignId);
                if(count($storeCampaignId) > 0){
                    foreach($storeCampaignId as $storeCampaignIds){
                        array_push($campaignIds, $storeCampaignIds->campaign_id);
                    }
                }
			}
		}

		// filtering
		if (!empty($request->get('filter_name'))) {
			$campaign = $campaign->where('campaigns.campaign_name', 'LIKE', '%' . $request->filter_name . '%');
		}
		if (!empty($request->get('start_date'))) {
			$campaign = $campaign->where('campaigns.period_start', '>=', $request->start_date);
		}
		if (!empty($request->get('end_date'))) {
			$campaign = $campaign->where('campaigns.period_end', '<=', $request->end_date);
		}
		if (!empty($request->get('organisation_id'))) {
			$campaign = $campaign->where('campaigns.org_id', '=',  $request->organisation_id );
		}
		if (!empty($request->get('status_id'))) {
			$campaign = $campaign->where('campaigns.status', '=',  $request->status_id );
		}
		else if($request->get('status_id') == '0'){
			$campaign = $campaign->where('campaigns.status', '=',  '0' );
		}
		else{
			// do nothing
		}
		// check if not admin and role organisation
		if(Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT){
			$hq_auth = Auth()->user()->id;
			$checkHq = DB::table('users')->where('users.id','=', $hq_auth)->select('users.parent_id')->first();
			// only hq can edit/delete campaign
			if($checkHq->parent_id != null){
				// if not hq then need to display their own status
				// if not hq, need to use status from campaign_status_by_level table
				$isHq = '0'; // not hq
				$checkAuthIdData1 = DB::table('campaign_status_by_level')
									->whereIn('campaign_status_by_level.campaign_id',$campaignIds)
									->where('campaign_status_by_level.organisation_id','=',$hq_auth)->get();
				if(count($checkAuthIdData1) > 0){
					$campaign = $campaign->leftJoin('campaign_status_by_level','campaign_status_by_level.campaign_id','campaigns.campaign_id')->select(
						'traffics.*','campaigns.campaign_id','campaigns.campaign_name','campaigns.period_start','campaigns.period_end',
						'campaign_status_by_level.status','campaign_status_by_level.organisation_id','campaign_status_by_level.campaign_id as c_id',
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
						DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response"),
					);
					$campaign = $campaign->where('campaign_status_by_level.organisation_id', Auth()->user()->id);
				}
				else{
					// dd(Auth()->user()->parent_id);
					$checkAuthIdData2 = DB::table('campaign_status_by_level')
									->whereIn('campaign_status_by_level.campaign_id',$campaignIds)
									->where('campaign_status_by_level.organisation_id','=',Auth()->user()->parent_id)->get();
									// dd($checkAuthIdData2);
					if(count($checkAuthIdData2) == 0){
						// find parent campaign
						$parent1 = DB::table('users')->where('users.id','=', Auth()->user()->parent_id)->select('users.parent_id')->first();
						$checkParent1 = DB::table('campaign_status_by_level')
									->whereIn('campaign_status_by_level.campaign_id',$campaignIds)
									->where('campaign_status_by_level.organisation_id','=',$parent1->parent_id)->get();
									// dd($checkParent1);
						if(count($checkParent1) > 0){
							$campaign = $campaign->leftJoin('campaign_status_by_level','campaign_status_by_level.campaign_id','campaigns.campaign_id')->select(
								'traffics.*','campaigns.campaign_id','campaigns.campaign_name','campaigns.period_start','campaigns.period_end',
								'campaign_status_by_level.status','campaign_status_by_level.organisation_id','campaign_status_by_level.campaign_id as c_id',
								DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
								DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
								DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
								DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response"),
							);
							$campaign = $campaign->where('campaign_status_by_level.organisation_id', $parent1->parent_id);
						}else{
							$parent2 = DB::table('users')->where('users.id','=', $parent1->parent_id)->select('users.parent_id')->first();
							$checkParent2 = DB::table('campaign_status_by_level')
										->whereIn('campaign_status_by_level.campaign_id',$campaignIds)
										->where('campaign_status_by_level.organisation_id','=',$parent2->parent_id)->get();
							if(count($checkParent2) > 0){
								$campaign = $campaign->leftJoin('campaign_status_by_level','campaign_status_by_level.campaign_id','campaigns.campaign_id')->select(
									'traffics.*','campaigns.campaign_id','campaigns.campaign_name','campaigns.period_start','campaigns.period_end',
									'campaign_status_by_level.status','campaign_status_by_level.organisation_id','campaign_status_by_level.campaign_id as c_id',
									DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
									DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
									DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
									DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response"),
								);
								$campaign = $campaign->where('campaign_status_by_level.organisation_id', $parent2->parent_id);
							}
						}
						
					}else{
						// if(count($checkAuthIdData2) > 0){
							$campaign = $campaign->leftJoin('campaign_status_by_level','campaign_status_by_level.campaign_id','campaigns.campaign_id')->select(
								'traffics.*','campaigns.campaign_id','campaigns.campaign_name','campaigns.period_start','campaigns.period_end',
								'campaign_status_by_level.status','campaign_status_by_level.organisation_id','campaign_status_by_level.campaign_id as c_id',
								DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
								DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
								DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
								DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response"),
							);
							$campaign = $campaign->where('campaign_status_by_level.organisation_id', Auth()->user()->parent_id);
						// }
					}
				}
				
			}
			else{
				// if hq can directly use the status from campaign table
				$isHq = '1'; //is hq
				$campaign = $campaign->select(
					'traffics.*','campaigns.*',
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
					DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response"),
				);
			}
		}
		else{
			// is admin
			$isHq = '';
			$campaign = $campaign->select(
				'traffics.*','campaigns.*',
				DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_share' THEN 1 END ) ) AS share"),
				DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_click' THEN 1 END ) ) AS click"),
				DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_interest' THEN 1 END ) ) AS interest"),
				DB::raw("COUNT( ( CASE WHEN traffics.event_type = 'campaign_response' THEN 1 END ) ) AS response"),
			);
		}
		$campaign = $campaign->groupby('campaigns.campaign_id')->get();
		
		$result['organisation'] = $organisation;
		$result['admins'] = $campaign;
		$result['segments'] = $segments;
		$result['isHq'] = $isHq;
		$result['org_list'] = $org_list;

        return view("admin.campaign.index",$title)->with('result', $result);
		//return view("admin.promotion.index",$title)->with('result', $result)->with('name', $name)->with('param', $param);
	}

	public function addcampaigns(Request $request){
		$title = array('pageTitle' => Lang::get("labels.add_campaign"));

		$result = array();
		$message = array();
		$errorMessage = array();

		$adminTypes = DB::table('user_types')->where('isActive', 1)->where('user_types_id','>','10')->get();
		$result['adminTypes'] = $adminTypes;
         
		$segments = Segments::all();
		$result['segments'] = $segments;

		$organisations = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['organisations'] = $organisations;
		
		return view("admin.campaign.add",$title)->with('result', $result);
	}

	public function insertcampaigns(Request $request){
		$currentUser = Auth()->user()->id;
		$sa_id = null;
		$time = Carbon::now();
		$orgList = array();

		// insert new promotion
        $campaign_id = DB::table('campaigns')->insertGetId([
			'campaign_name' => $request->campaign_name,
			'campaign_image' => $request->campaign_image,
			'org_id' => $request->organization_id,
			'period_start' => $request->start_date . ' ' . $request->start_time,
			'period_end' => $request->end_date . ' ' . $request->end_time,
			'description' => $request->description,
			'status' => $request->status_id,
			'sa_id' => $sa_id,
			'response_text' => $request->response_text,
			'interest_form' => $request->interest_form,
			'response_text_color' => $request->color,
			'response_button_color' => $request->color2,
			'created_at' => date('Y-m-d H:i:s')
		]);

		// default
		// if($campaign_id != null){
		// 	$campaign_status = DB::table('campaign_status_by_level')->insertGetId([
		// 		'organisation_id' => $request->organization_id,
		// 		'campaign_id' => $campaign_id,
		// 		'status' => $request->status_id,
		// 		'created_at' => date('Y-m-d H:i:s')
		// 	]);
		// }

		// this one is hq section
		// get all child org for this hq
		$isChild = DB::table('users')->where('id','=',$request->organization_id)->first();
		array_push($orgList, $isChild->id);
		$childOrgLists = DB::table('users')->where('users.parent_id','=',$isChild->id)->select('users.id')->get();
		if(count($childOrgLists) > 0){
			foreach($childOrgLists as $childOrgList){
				array_push($orgList, $childOrgList->id);
				$childOrgLists2 = DB::table('users')->where('users.parent_id','=',$childOrgList->id)->select('users.id')->get();
				if(count($childOrgLists2) > 0){
					foreach($childOrgLists2 as $childOrgList2){
						array_push($orgList, $childOrgList2->id);
					}
				}
			}
		}
		// update status for campaign table and campaign status level by org
			$myId = $campaign_id;
			// DB::table('campaigns')
			// 	->where('campaign_id','=', $myId)
			// 	->update([
			// 		'status' => $request->status,
			// ]);

			for($x=0; $x < count($orgList); $x++){
				$checkData = DB::table('campaign_status_by_level')->where('campaign_id','=', $myId)->where('organisation_id','=',$orgList[$x])->get();
				if(!$checkData->isEmpty()){
					DB::table('campaign_status_by_level')->where('campaign_id','=', $myId)->where('organisation_id','=',$orgList[$x])->update([
						'status' => $request->status_id,
						'updated_at' => date('Y-m-d H:i:s')
					]);
				}
				else{
					$campaign_status = DB::table('campaign_status_by_level')->insertGetId([
						'organisation_id' => $orgList[$x],
						'campaign_id' => $myId,
						'status' => $request->status_id,
						'created_at' => date('Y-m-d H:i:s')
					]);
				}
			}

        $message = Lang::get("labels.CampaignAddedMessage");
		return redirect()->back()->with('message', $message);
    }

    public function editcampaigns(Request $request){
		$title = array('pageTitle' => Lang::get("labels.add_campaign"));
		$myid = $request->id;
			
		$result = array();
		$message = array();
		$errorMessage = array();
		
		$adminTypes = DB::table('user_types')->where('isActive', 1)->where('user_types_id','>','10')->get();
		$result['adminTypes'] = $adminTypes;
		
		$result['myid'] = $myid;

		$admins = DB::table('campaigns')->where('campaign_id','=', $myid)->get();
		// dd($admins);
		$result['admins'] = $admins;

		$segments = Segments::all();
		$result['segments'] = $segments;

		$organisations = User::sortable(['id'=>'DESC'])
			->leftJoin('user_types','user_types.user_types_id','=','users.role_id')
			->select('users.*','user_types.*')
			->where('users.role_id','=',\App\Models\Core\User::ROLE_MERCHANT)
			->get();
		$result['organisations'] = $organisations;

		return view("admin.campaign.edit",$title)->with('result', $result);
	}

	public function updatecampaigns(Request $request){
		$currentUser = Auth()->user()->id;
		$campaign_id    =   $request->myid;
		// dd($request->all());
		// images upload
		$time = Carbon::now();
		
		DB::table('campaigns')->where('campaign_id','=', $campaign_id)->update([
			'campaign_name' => $request->campaign_name,
			'campaign_image' => $request->campaign_image,
			'period_start' => $request->period_start . ' ' . $request->start_time,
			'period_end' => $request->period_end . ' ' . $request->end_time,
			'org_id' => $request->organization_id,
			'description' => $request->description,
			'status' => $request->status_id,
			'response_text' => $request->response_text,
			'interest_form' => $request->interest_form,
			'response_text_color' => $request->color,
			'response_button_color' => $request->color2,
			'updated_at' => date('Y-m-d H:i:s')
		]);

		// check organisation auth
		if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT ){
			$orgList = array();
			$isChild = DB::table('users')->where('id','=',Auth()->user()->id)->first();
			array_push($orgList, $isChild->id);
			if($isChild->parent_id != null){
				// do nothing
			}
			else{
				// get all child org for this hq
				$childOrgLists = DB::table('users')->where('users.parent_id','=',$isChild->id)->select('users.id')->get();
				if(count($childOrgLists) > 0){
					foreach($childOrgLists as $childOrgList){
						array_push($orgList, $childOrgList->id);
						$childOrgLists2 = DB::table('users')->where('users.parent_id','=',$childOrgList->id)->select('users.id')->get();
						if(count($childOrgLists2) > 0){
							foreach($childOrgLists2 as $childOrgList2){
								array_push($orgList, $childOrgList2->id);
							}
						}
					}
				}
				// update status for campaign table and campaign status level by org
				if($campaign_id){
					$myId = $campaign_id;
					// DB::table('campaigns')
					// 	->where('campaign_id','=', $myId)
					// 	->update([
					// 		'status' => $request->status,
					// ]);

					for($x=0; $x < count($orgList); $x++){
						$checkData = DB::table('campaign_status_by_level')->where('campaign_id','=', $myId)->where('organisation_id','=',$orgList[$x])->get();
						if(!$checkData->isEmpty()){
							DB::table('campaign_status_by_level')->where('campaign_id','=', $myId)->where('organisation_id','=',$orgList[$x])->update([
								'status' => $request->status_id,
								'updated_at' => date('Y-m-d H:i:s')
							]);
						}
						else{
							$campaign_status = DB::table('campaign_status_by_level')->insertGetId([
								'organisation_id' => $orgList[$x],
								'campaign_id' => $myId,
								'status' => $request->status_id,
								'created_at' => date('Y-m-d H:i:s')
							]);
						}
					}
				}
			}
		}
		else{
			// check got data for campaign status level
			$checkData = DB::table('campaign_status_by_level')->where('campaign_id','=', $campaign_id)->get();
			if(!$checkData->isEmpty()){
				DB::table('campaign_status_by_level')->where('campaign_id','=', $campaign_id)->update([
					'status' => $request->status_id,
					'updated_at' => date('Y-m-d H:i:s')
				]);
			}
			else{
				$campaign_status = DB::table('campaign_status_by_level')->insertGetId([
					'organisation_id' => $request->organization_id,
					'campaign_id' => $campaign_id,
					'status' => $request->status_id,
					'created_at' => date('Y-m-d H:i:s')
				]);
			}
		}
		
        $message = Lang::get("labels.CampaignEditMessage");
		return redirect()->back()->with('message', $message);
	}

	public function deletecampaigns(Request $request){
        $campaign_id  =  $request->users_id;
		
        DB::table('campaigns')->where('campaign_id','=', $campaign_id)->delete();
        return redirect()->back()->withErrors([Lang::get("labels.DeleteCampaignMessage")]);
    }

	public function campaignemailtemplate(Request $request){
		$title = array('pageTitle' => Lang::get("labels.linkCampaignEmail"));
		$language_id = '1';
	

		$result = array();
		$message = array();
		$errorMessage = array();

		$admins = DB::table('campaign_email')->get();
		$result['message'] = $message;
		$result['errorMessage'] = $errorMessage;
		

		$result['admins'] = $admins;
       // dd($admins);
        return view("admin.campaign.emailtemp",$title)->with('result', $result);
	}

	public function updatecampaignemailtemplate(Request $request){
		$currentUser = Auth()->user()->id;

		// dd($request);
		DB::table('campaign_email')->where('id','=','1')->update([
			'sa_subject' => $request->sa_subject,
			'sa_email' => $request->sa_email,
		]);
        $message = Lang::get("labels.campaignEmailEditMessage");
		return redirect()->back()->with('message', $message);
	}
	
	public function changeStatus(Request $request)
    {
		// check organisation auth
		if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT ){
			$orgList = array();
			if($request->campaign_id != null && $request->status != null && $request->current_user != null){
				$isChild = DB::table('users')->where('id','=',$request->current_user)->first();
				array_push($orgList, $isChild->id);
				if($isChild->parent_id != null){
					// add 1 more function to check wheter other camapign for this auth id got or no
					$campaignIdStore = array();
					$check1 = DB::table('campaign_status_by_level')->where('campaign_status_by_level.organisation_id','=',$isChild->parent_id)->get();
					foreach($check1 as $check1s){
						// array_push($campaignIdStore, $check1s[0]->campaign_id);
						$check2 = DB::table('campaign_status_by_level')->where('campaign_status_by_level.organisation_id','=',$isChild->id)->where('campaign_status_by_level.campaign_id','=',$check1s->campaign_id)->get();
						if(!$check2->isEmpty()){
							
						}
						else{
							$campaign_status = DB::table('campaign_status_by_level')->insertGetId([
								'organisation_id' => $isChild->id,
								'campaign_id' => $check1s->campaign_id,
								'status' => $check1s->status,
								'created_at' => date('Y-m-d H:i:s')
							]);
						}
					}
					// is a child, then get the parent to see the upper status
					// if parent set to disabled, child cannot change the status
					// if parent set to enabled, child can set enabled/disabled for their child
					$checkParentStatus = DB::table('campaign_status_by_level')->where('campaign_id','=',$request->campaign_id)->where('organisation_id','=',$isChild->parent_id)->get();
					if(count($checkParentStatus) > 0){
						if($checkParentStatus[0]->status == '1'){
							$childOrgLists = DB::table('users')->where('users.parent_id','=',$isChild->id)->select('users.id')->get();
							if(count($childOrgLists) > 0){
								foreach($childOrgLists as $childOrgList){
									array_push($orgList, $childOrgList->id);
									$childOrgLists2 = DB::table('users')->where('users.parent_id','=',$childOrgList->id)->select('users.id')->get();
									if(count($childOrgLists2) > 0){
										foreach($childOrgLists2 as $childOrgList2){
											array_push($orgList, $childOrgList2->id);
										}
									}
								}
							}
							// update status for campaign table and campaign status level by org
							if($request->campaign_id){
								$myId = $request->campaign_id;
								// DB::table('campaigns')
								// 	->where('campaign_id','=', $myId)
								// 	->update([
								// 		'status' => $request->status,
								// ]);

								for($x=0; $x < count($orgList); $x++){
									$checkData = DB::table('campaign_status_by_level')->where('campaign_id','=', $myId)->where('organisation_id','=',$orgList[$x])->get();
									if(!$checkData->isEmpty()){
										DB::table('campaign_status_by_level')->where('campaign_id','=', $myId)->where('organisation_id','=',$orgList[$x])->update([
											'status' => $request->status,
											'updated_at' => date('Y-m-d H:i:s')
										]);
									}
									else{
										$campaign_status = DB::table('campaign_status_by_level')->insertGetId([
											'organisation_id' => $orgList[$x],
											'campaign_id' => $myId,
											'status' => $request->status,
											'created_at' => date('Y-m-d H:i:s')
										]);
									}
								}

								return response()->json(['success'=>'Status change successfully.']);
							}
							else{
								return response()->json(['success'=>'Status change failed. Please get help from Upper Management']);
							}
						}

					}
					else{
						return response()->json(['success'=>'Status change failed. Please get help from Upper Management']);
					}
	

				}
				else{
					// this one is hq section
					// get all child org for this hq
					$childOrgLists = DB::table('users')->where('users.parent_id','=',$isChild->id)->select('users.id')->get();
					if(count($childOrgLists) > 0){
						foreach($childOrgLists as $childOrgList){
							array_push($orgList, $childOrgList->id);
							$childOrgLists2 = DB::table('users')->where('users.parent_id','=',$childOrgList->id)->select('users.id')->get();
							if(count($childOrgLists2) > 0){
								foreach($childOrgLists2 as $childOrgList2){
									array_push($orgList, $childOrgList2->id);
								}
							}
						}
					}
					// update status for campaign table and campaign status level by org
					if($request->campaign_id){
						$myId = $request->campaign_id;
						DB::table('campaigns')
							->where('campaign_id','=', $myId)
							->update([
								'status' => $request->status,
						]);

						for($x=0; $x < count($orgList); $x++){
							$checkData = DB::table('campaign_status_by_level')->where('campaign_id','=', $myId)->where('organisation_id','=',$orgList[$x])->get();
							if(!$checkData->isEmpty()){
								DB::table('campaign_status_by_level')->where('campaign_id','=', $myId)->where('organisation_id','=',$orgList[$x])->update([
									'status' => $request->status,
									'updated_at' => date('Y-m-d H:i:s')
								]);
							}
							else{
								$campaign_status = DB::table('campaign_status_by_level')->insertGetId([
									'organisation_id' => $orgList[$x],
									'campaign_id' => $myId,
									'status' => $request->status,
									'created_at' => date('Y-m-d H:i:s')
								]);
							}
						}

						return response()->json(['success'=>'Status change successfully.']);
					}
					else{
						return response()->json(['success'=>'Status change failed.']);
					}
				}
			}
		}
		else{
			if($request->campaign_id){
				$orgList = array();
				$myId = $request->campaign_id;
				$getCampaignOwner = DB::table('campaigns')->where('campaign_id','=',$myId)->first();

				if($request->campaign_id != null && $request->status != null && $request->current_user != null){
					$isChild = DB::table('users')->where('id','=',$getCampaignOwner->org_id)->first();
					array_push($orgList, $isChild->id);
					if($isChild->parent_id != null){
						
					}
					else{
						// get all child org for this hq
						$childOrgLists = DB::table('users')->where('users.parent_id','=',$isChild->id)->select('users.id')->get();
						if(count($childOrgLists) > 0){
							foreach($childOrgLists as $childOrgList){
								array_push($orgList, $childOrgList->id);
								$childOrgLists2 = DB::table('users')->where('users.parent_id','=',$childOrgList->id)->select('users.id')->get();
								if(count($childOrgLists2) > 0){
									foreach($childOrgLists2 as $childOrgList2){
										array_push($orgList, $childOrgList2->id);
									}
								}
							}
						}
						// update status for campaign table and campaign status level by org
						if($request->campaign_id){
							$myId = $request->campaign_id;
							DB::table('campaigns')
								->where('campaign_id','=', $myId)
								->update([
									'status' => $request->status,
							]);
	
							for($x=0; $x < count($orgList); $x++){
								$checkData = DB::table('campaign_status_by_level')->where('campaign_id','=', $myId)->where('organisation_id','=',$orgList[$x])->get();
								if(!$checkData->isEmpty()){
									DB::table('campaign_status_by_level')->where('campaign_id','=', $myId)->where('organisation_id','=',$orgList[$x])->update([
										'status' => $request->status,
										'updated_at' => date('Y-m-d H:i:s')
									]);
								}
								else{
									$campaign_status = DB::table('campaign_status_by_level')->insertGetId([
										'organisation_id' => $orgList[$x],
										'campaign_id' => $myId,
										'status' => $request->status,
										'created_at' => date('Y-m-d H:i:s')
									]);
								}
							}
	
							return response()->json(['success'=>'Status change successfully.']);
						}
						else{
							return response()->json(['success'=>'Status change failed.']);
						}
					}
				}
			}
		}

    }

	public function syncCampaign(){
		if( Auth()->user()->role_id == \App\Models\Core\User::ROLE_MERCHANT ){
			// dd(Auth()->user()->id);
			$current_user_id = Auth()->user()->id;
			$parent_id = Auth()->user()->parent_id;
			$orgList = array();

			array_push($orgList, $current_user_id);
			if($parent_id != null){
				// add 1 more function to check wheter other camapign for this auth id got or no
				$campaignIdStore = array();
				$check1 = DB::table('campaign_status_by_level')->where('campaign_status_by_level.organisation_id','=',$parent_id)->get();
				// dd($check1);
				foreach($check1 as $check1s){
					// array_push($campaignIdStore, $check1s[0]->campaign_id);
					$check2 = DB::table('campaign_status_by_level')->where('campaign_status_by_level.organisation_id','=',$current_user_id)->where('campaign_status_by_level.campaign_id','=',$check1s->campaign_id)->get();
					if(!$check2->isEmpty()){
						
					}
					else{
						$campaign_status = DB::table('campaign_status_by_level')->insertGetId([
							'organisation_id' => $current_user_id,
							'campaign_id' => $check1s->campaign_id,
							'status' => $check1s->status,
							'created_at' => date('Y-m-d H:i:s')
						]);
					}
				}
			}
		}
		$message = "Refreshed.";
		return redirect()->back()->with('message', $message);
	}

}
