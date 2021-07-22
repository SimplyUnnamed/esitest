<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $casts = [
        'last_activity' => 'datetime'
    ];

    public static function booted(){

        self::addGlobalScope('current', function($builder){
            $builder->where('last_activity', '>=', carbon('now')->subMinutes(15)->timestamp);
        });

    }

}
