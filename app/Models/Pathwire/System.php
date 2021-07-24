<?php

namespace App\Models\Pathwire;

use App\Models\Character;
use App\Models\LocationHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{

    protected $table = 'systems';

    protected $fillable = ['system_id', 'created_by', 'updated_by'];

    const whRegions = [
        11000001,
        11000002,
        11000003,
        11000004,
        11000005,
        11000006,
        11000007,
        11000008,
        11000009,
        11000010,
        11000011,
        11000012,
        11000013,
        11000014,
        11000015,
        11000016,
        11000017,
        11000018,
        11000019,
        11000020,
        11000021,
        11000022,
        11000023,
        11000024,
        11000025,
        11000026,
        11000027,
        11000028,
        11000029,
        11000030,
        11000031,
        11000032,
        11000033,
    ];



    public function isWormhole(): bool
    {
        return in_array($this->region_id, self::whRegions);
    }

    public function map(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Map::class);
    }

    public function system(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Sde\System::class, 'system_id', 'system_id');
    }

    public function connections(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Connection::class, 'origin');
    }

    public function currentLocations(){
        return $this->hasMany(LocationHistory::class, 'solar_system_id', 'system_id')
            ->groupBy('character_id')->latest();
    }

    public function characters(){
        return $this->currentLocations()->with('character');
    }
}
