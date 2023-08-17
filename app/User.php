<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Intervention\Image\Facades\Image;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	//use user id of admin
    protected $primaryKey = 'id';

    public function avatar_url()
    {
        $source_path = public_path() . "/uploads/users/".$this->avatar;
        if (!empty($this->avatar) && file_exists($source_path))
            return $source_path;
        else
            return asset('images/default_user.jpg');
    }

    public static function crop_image($file,$source) {

        $source_path = public_path() . "/uploads/users/".$file;
        if ($file) {
            Image::make($source)->save($source_path);
        }
    }
}
