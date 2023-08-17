<?php

namespace App\Models\Core;
Use Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    const ROLE_SUPER_ADMIN = 1;
    const ROLE_CUSTOMER = 2;
    const ROLE_MERCHANT = 11;
    const ROLE_NORMAL_ADMIN = 12;
    const ROLE_MANAGER = 13;

    use Notifiable;
    use Sortable;
    protected $table = 'users';
    protected $appends = array('pageview','whatsapp','call','waze');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'user_name',
        'first_name',
        'last_name',
        'gender',
        'country_code',
        'phone',
        'avatar',
        'status',
        'is_seen',
        'phone_verified',
        'created_at',
        'updated_at',
        'email',
        'password',
        'last_login_at',
        'login_counter',
        'segment_id',
        'template_id',
        'corporate_email',
        'corporate_phone',
        'brn_no',
        'bank_id1',
        'bank_acc_name1',
        'bank_acc_no1',
        'bank_id2',
        'bank_acc_name2',
        'bank_acc_no2',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function saveAdmin(array $data)
    {
        return User::create([
            'role_id'    => 1,
            'user_name'  => $data['user_name'],
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
        ]);
    }
    public static function getCustomers(){
      $user = User::sortable(['id'=>'ASC'])
          ->LeftJoin('user_to_address', 'user_to_address.user_id' ,'=', 'users.id')
          ->LeftJoin('address_book','address_book.address_book_id','=', 'user_to_address.address_book_id')
          ->LeftJoin('countries','countries.countries_id','=', 'address_book.entry_country_id')
          ->LeftJoin('zones','zones.zone_id','=', 'address_book.entry_zone_id')
          ->where('role_id',2)
          ->select('users.*', 'address_book.entry_gender as entry_gender', 'address_book.entry_company as entry_company',
          'address_book.entry_firstname as entry_firstname', 'address_book.entry_lastname as entry_lastname',
          'address_book.entry_street_address as entry_street_address', 'address_book.entry_suburb as entry_suburb',
          'address_book.entry_postcode as entry_postcode', 'address_book.entry_city as entry_city',
          'address_book.entry_state as entry_state', 'countries.*', 'zones.*')
          ->groupby('users.id')
          ->paginate(10);
          return $user;

    }

    public function getFullNameAttribute()
    {
        return ucwords("{$this->first_name} {$this->last_name}");
    }

    public  function allmerchant()
    {
        $carInfo = User::sortable(['id'=>'ASC'])->where('role_id',11)->where('status',1)->get();
        return $carInfo;
    }

    public function banner()
    {
        return $this->belongsTo('App\Models\Core\Images','banner_id');
    }

    public function logo()
    {
        return $this->belongsTo('App\Models\Core\Images','logo_id');
    }

    // count pageview
    public function getPageviewAttribute()
    {
        return $this->calculatePageview();  
    }
    public function calculatePageview(){
        $pageview =  DB::table('traffics_organisation')
                ->leftJoin('traffics','traffics.id','=','traffics_organisation.traffic_id')
                ->select(DB::raw('COUNT(traffics_organisation.id) as pageview'))
                ->where('traffics_organisation.organisation_id','=',$this->id)
                ->where('traffics.event_type','=','visit')
                ->first();
        return $pageview->pageview + $this->countPageviewFromTrafficTable() ;
    }

    // count from traffic table
    public function countPageviewFromTrafficTable(){
        $pageview =  DB::table('traffics')
                ->select(DB::raw('COUNT(traffics.id) as pageview'))
                ->where('traffics.organisation_id','=',$this->id)
                ->whereDate('traffics.created_at', '<=', '2021-09-23')
                ->first();
        return $pageview->pageview;
    }

    // count whatsapp
    public function getWhatsappAttribute()
    {
        return $this->calculateWhatsapp();  
    }
    public function calculateWhatsapp(){
        $whatsapp =  DB::table('traffics_organisation')
                ->leftJoin('traffics','traffics.id','=','traffics_organisation.traffic_id')
                ->select(DB::raw('COUNT(traffics_organisation.id) as pageview'))
                ->where('traffics_organisation.organisation_id','=',$this->id)
                ->where('traffics.event_type','=','whatsapp')
                ->first();
        return $whatsapp->pageview + $this->countWhatsappFromEventTable() ;
    }
    // count from event table
    public function countWhatsappFromEventTable(){
        $whatsapp =  DB::table('events')
                ->select(DB::raw('COUNT(events.id) as pageview'))
                ->where('events.organisation_id','=',$this->id)
                ->whereDate('events.created_at', '<=', '2021-09-23')
                ->where('events.event','=','whatsapp')
                ->first();
        return $whatsapp->pageview;
    }

    // count call
    public function getCallAttribute()
    {
        return $this->calculateCall();  
    }
    public function calculateCall(){
        $call =  DB::table('traffics_organisation')
                ->leftJoin('traffics','traffics.id','=','traffics_organisation.traffic_id')
                ->select(DB::raw('COUNT(traffics_organisation.id) as pageview'))
                ->where('traffics_organisation.organisation_id','=',$this->id)
                ->where('traffics.event_type','=','call')
                ->first();
        return $call->pageview + $this->countCallFromEventTable() ;
    }
    // count from event table
    public function countCallFromEventTable(){
        $whatsapp =  DB::table('events')
                ->select(DB::raw('COUNT(events.id) as pageview'))
                ->where('events.organisation_id','=',$this->id)
                ->whereDate('events.created_at', '<=', '2021-09-23')
                ->where('events.event','=','call')
                ->first();
        return $whatsapp->pageview;
    }

    // count waze
    public function getWazeAttribute()
    {
        return $this->calculateWaze();  
    }
    public function calculateWaze(){
        $waze =  DB::table('traffics_organisation')
                ->leftJoin('traffics','traffics.id','=','traffics_organisation.traffic_id')
                ->select(DB::raw('COUNT(traffics_organisation.id) as pageview'))
                ->where('traffics_organisation.organisation_id','=',$this->id)
                ->where('traffics.event_type','=','waze')
                ->first();
        return $waze->pageview + $this->countWazeFromEventTable() ;
    }
    // count from event table
    public function countWazeFromEventTable(){
        $whatsapp =  DB::table('events')
                ->select(DB::raw('COUNT(events.id) as pageview'))
                ->where('events.organisation_id','=',$this->id)
                ->whereDate('events.created_at', '<=', '2021-09-23')
                ->where('events.event','=','waze')
                ->first();
        return $whatsapp->pageview;
    }

}
