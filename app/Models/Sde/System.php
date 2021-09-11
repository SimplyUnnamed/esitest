<?php

namespace App\Models\Sde;

use App\Models\Universe\SystemJumps;
use App\Models\Universe\SystemKills;
use Carbon\Carbon;
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

    public function shipJumps(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SystemJumps::class, 'system_id', 'system_id');
    }

    public function recentKills(){
        return $this->belongsTo(SystemKills::class, 'system_id','system_id')->latest()
            ->where('created_at', '>', Carbon::now()->subHours(3)->toDateTimeString())
            ->withDefault([
                'npc_kills' => 0,
                'pod_kills' => 0,
                'ship_kills'=>0,
            ]);
    }

    public function connections()
    {
        return $this->hasManyThrough(
            System::class,
            Stargate::class,
            'fromSolarSystemID',
            'system_id',
            'system_id',
            'toSolarSystemID'
        );
    }

    public function systemsWithinJumps(int $jumps = 5)
    {
        $this->jumps = 0;
        $systems = collect([$this]);

        $this->connections->each(function (System $system) use ($systems) {
            $system->jumps = 1;
            $systems->add($system->withoutRelations());
        });
        $temp = collect([]);
        $currentBranch = $systems;
        for ($i = 0; $i < $jumps - 1; $i++) {
            $currentBranch->each(function(System $system) use ($systems, $temp){
                $system->connections()
                    ->whereNotIn('system_id', $systems->pluck('system_id'))
                    ->whereNotIn('system_id', $temp->pluck('system_id'))
                    ->with('recentKills')
                    ->each(function(System $system) use ($temp){
                        $temp->add($system);
                    });
            });

            $temp->each(function(System $system) use ($systems, $i){
                $system->jumps = $i+1;
                $systems->add($system);
            });
            $currentBranch = $temp;
            $temp = collect([]);
        }
        return $systems->sortBy('jumps');
    }
}
