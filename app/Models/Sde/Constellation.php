<?php

namespace App\Models\Sde;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Constellation extends Model
{

    protected $table = 'constellations';

    protected $primaryKey = 'constellation_id';

    public function region(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    public function systems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(System::class, 'constellation_id', 'constellation_id');
    }

}
