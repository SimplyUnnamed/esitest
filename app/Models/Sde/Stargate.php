<?php

namespace App\Models\Sde;

use Illuminate\Database\Eloquent\Model;

class Stargate extends Model
{

    protected $table = 'mapSolarSystemJumps';

    protected $primaryKey = null;
    public $incrementing = false;


    public function source(){
        return $this->belongsTo(System::class, 'fromSolarSystemID', 'system_id');
    }

    public function destination(){
        return $this->belongsTo(System::class, 'fromSolarSystemID', 'system_id');
    }

}
