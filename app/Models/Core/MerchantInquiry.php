<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class MerchantInquiry extends Model
{
    public function merchant()
    {
        return $this->belongsTo('App\Models\Core\User', 'user_id');
    }
}
