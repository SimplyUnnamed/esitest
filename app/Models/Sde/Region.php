<?php

namespace App\Models\Sde;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{

    protected $table = 'regions';

    protected $primaryKey = 'region_id';

    public function systems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(System::class, 'region_id', 'region_id');
    }

    public function constellations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Constellation::class, 'region_id', 'region_id');
    }
}
