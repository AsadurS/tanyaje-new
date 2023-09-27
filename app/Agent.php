<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Agent extends User
{
    const  ROLE_ID = 18;
    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    //use Notifiable;

    protected $guard = "agent";
    public static function boot(){
        parent::boot();
        static::addGlobalScope(function(Builder $builder){
           $builder->where('role_id', Agent::ROLE_ID);
        });
    }

}
