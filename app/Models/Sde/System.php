<?php

namespace App\Models\Sde;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{

    protected $table = 'solar_systems';

    protected $primaryKey = 'system_id';

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    public function constellation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Constellation::class, 'constellation_id', 'constellation_id');
    }

    public function stations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Station::class, 'system_id', 'system_id');
    }

    public function stargates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SystemGate::class, 'system_id', 'system_id');
    }

    public function connected_systems(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            System::class,
            SystemGate::class,
            'system_id',
            'system_id',
            'system_id',
            'destination_id'
        );
    }
}
