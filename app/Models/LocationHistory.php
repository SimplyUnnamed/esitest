<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationHistory extends Model
{

    protected $fillable = [
          'character_id', 'solar_system_id', 'station_id', 'structure_id'
    ];


    public function isSameLocationAs(LocationHistory $location){
        return (
            $this->solar_system_id == $location->solar_system_id &&
            $this->station_id == $location->station_id &&
            $this->structure_id == $location->structure_id
        );
    }


}
