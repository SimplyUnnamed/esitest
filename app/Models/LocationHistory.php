<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Znck\Eloquent\Traits\BelongsToThrough;

class LocationHistory extends Model
{
    use BelongsToThrough;

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

    public function refreshToken(){
        return $this->belongsTo(RefreshToken::class, 'character_id', 'character_id');
    }

    public function character(){
        return $this->belongsTo(Character::class, 'character_id', 'character_id');
    }


}
