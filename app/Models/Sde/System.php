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


    public function systems_within($jumps = 10)
    {
        $systems = collect([$this]);
        $this->connected_systems->each(function(System $system) use ($systems){
           $systems->add($system->withoutRelations());
        });
        $temp = collect([]);
        $currentBranch = $systems;
        for($i=0; $i < $jumps-1; $i++)
        {
            $currentBranch->each(function($system) use ($systems, $temp){
                $system->connected_systems
                    ->whereNotIn('system_id', $systems->pluck('system_id'))
                    ->whereNotIn('system_id', $temp->pluck('system_id'))
                    ->each(function(System $system) use ($temp){
                        $temp->add($system);
                    });
            });
            $temp->each(function(System $system) use ($systems){
               $systems->add($system->withoutRelations());
            });
            $currentBranch = $temp;
            $temp = collect([]);
        }
        return $systems;

    }


    public function calculate_distance(System $system)
    {
        $x = $this->x - $system->x;
        $y = $this->y - $system->y;
        $z = $this->z - $system->z;
        $x = $x*$x;
        $y = $y*$y;
        $z = $z*$z;
        return sqrt($x + $y + $z);
    }
}
