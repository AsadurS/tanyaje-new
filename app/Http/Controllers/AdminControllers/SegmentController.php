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

class SegmentController extends Controller
{
    //
    private $domain;
    public function __construct(Segments $segment,Admin $admin, Setting $setting, Order $order, Customers $customers, Cities $cities, States $States, Makes $makes, Cars $cars, User $user)
    {
		$this->segment =$segment;
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

    public function segments(Request $request){
		$title = array('pageTitle' => Lang::get("labels.ListingSegments"));
		$language_id = '1';

		$result = array();
		$message = array();
		$errorMessage = array();

		// $admins = Segments::all();
		// $result['admins'] = $admins;

        $segment = $this->segment->paginator(30);
        $result['message'] = $message;
		$result['errorMessage'] = $errorMessage;

        return view("admin.segment.index",$title)->with('result', $result)->with('segment', $segment);

		// return view("admin.segment.index",$title)->with('result', $result);
	}

	public function addsegments(Request $request){
		$title = array('pageTitle' => Lang::get("labels.addsegments"));

		$result = array();
		$message = array();
		$errorMessage = array();
		$images = new Images;
		$allimage = $images->getimages();
		//get function from ManufacturerController controller
		$myVar = new AddressController();
		$result['countries'] = $myVar->getAllCountries();
		$state = $this->States->getter();
		$result['state'] = $state;
		$city = $this->Cities->getter();
        $result['city'] = $city;
		$adminTypes = DB::table('user_types')->where('isActive', 1)->where('user_types_id','>','10')->get();
		$result['adminTypes'] = $adminTypes;

		return view("admin.segment.add",$title)->with('result', $result)->with('allimage',$allimage);
	}

    public function insertSegments(Request $request){
        // dd($request->all());
		$time = Carbon::now();
		$currentUser = Auth()->user()->id;
		$uploadImage = '';
        $branch_id = DB::table('segments')->insertGetId([
			'segment_name' => $request->segment_name,
			'sort' => $request->sort,
			'created_at' => date('Y-m-d H:i:s')
		]);
        $message = Lang::get("labels.SegmentAddedMessage");
		return redirect()->back()->with('message', $message);
    }

	public function editsegments(Request $request)
    {
        $title = array('pageTitle' => Lang::get("labels.EditSegment"));
		$myid = $request->id;
		
		$result = array();
		$message = array();
		$errorMessage = array();
		$images = new Images;
		$allimage = $images->getimages();
		//get function from other controller
		$myVar = new AddressController();
		$result['countries'] = $myVar->getAllCountries();
		$adminTypes = DB::table('user_types')->where('isActive', 1)->where('user_types_id','>','10')->get();
		
		$state = $this->States->getter();
		$result['state'] = $state;
		$city = $this->Cities->getter();
        $result['city'] = $city;

		$result['adminTypes'] = $adminTypes;
		
		$result['myid'] = $myid;

        $admins = DB::table('segments')->where('segment_id','=', $myid)->get();
		$zones = 0;

		$user_to_address = DB::table('user_to_address')->where('user_id','=', $admins[0]->segment_id)->get();
		if(count($user_to_address)>0){
			$address = DB::table('address_book')->where('address_book_id','=', $user_to_address[0]->address_book_id)->get();
		}else{
			$address = array();
		}
		$result['address'] = $address;

		if($zones>0){
			$result['zones'] = $zones;
		}else{
			$zones = new \stdClass;
			$zones->zone_id = "others";
			$zones->zone_name = "Others";
			$result['zones'][0] = $zones;
		}
		//echo "a";exit();
		//$image_path = DB::table('image_categories')->where('image_id', $admins[0]->ssm)->where('image_type', 'ACTUAL')->get();
		//echo "a";echo "<pre>";print_r($image_path);echo "</pre>";echo $admins[0]->ssm;exit();
        $result['admins'] = $admins;
		//$result['image_path'] = $image_path;
		return view("admin.segment.edit",$title)->with('result', $result)->with('allimage', $allimage);
	}

    public function updateSegments(Request $request){
        $time = Carbon::now();
        $extensions = array('pdf');
		$currentUser = Auth()->user()->id;
		$segment_id    =   $request->myid;
		
		DB::table('segments')->where('segment_id','=', $segment_id)->update([
            'segment_name' => $request->segment_name,
			'sort' => $request->sort,
			'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $message = Lang::get("labels.SegmentEditMessage");
        return redirect()->back()->with('message', $message);
    }

	public function deleteSegment(Request $request){
        $myid = $request->users_id;
        DB::table('segments')->where('segment_id','=', $myid)->delete();
        return redirect()->back()->withErrors([Lang::get("labels.DeleteSegmentMessage")]);
    }

	public function filter(Request $request){
		$name = $request->FilterBy;
		$param = $request->parameter;
		$title = array('pageTitle' => Lang::get("labels.Segment"));
		$segment = $this->segment->filter($name,$param);
		return view("admin.segment.index",$title)->with('segment', $segment)->with('name',$name)->with('param',$param);
	}
}