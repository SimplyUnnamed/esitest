<?php


namespace App\Models\Sde;


use Illuminate\Database\Eloquent\Model;

class MapDenormalize extends Model
{

    const BELT = 9;

    const CONSTELLATION = 4;

    const MOON = 8;

    const PLANET = 7;

    const REGION = 3;

    const STATION = 15;

    const SUN = 6;

    const SYSTEM = 5;

    const UBIQUITOUS = 2396;

    const COMMON = 2397;

    const UNCOMMON = 2398;

    const RARE = 2400;

    const EXCEPTIONAL = 2401;


    public $incrementing = false;

    protected $table = 'mapDenormalize';

    protected $primaryKey = 'itemID';

    private $moon_indicators;

    public function scopeMoons($query){
        return $query->where('groupID', self::MOON);
    }

    public function scopePlanets($query){
        return $query->where('groupID', self::PLANET);
    }

    public function scopeRegions($query){
        return $query->where('groupID', self::REGION);
    }

    public function scopeSystems($query){
        return $query->where('groupID', self::SYSTEM);
    }


}
