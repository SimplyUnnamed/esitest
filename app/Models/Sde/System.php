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

    public $timestamps = false;

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

    public function killStats(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SystemKills::class, 'system_id', 'system_id');
    }

    public function killStatsLatest(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SystemKills::class, 'system_id','system_id')->latest()
            ->where('created_at', '>', Carbon::now()->subHours(1)->toDateTimeString())
            ->withDefault([
                'npc_kills' => 0,
                'pod_kills' => 0,
                'ship_kills'=>0,
            ]);
    }

    public function killStatsPrevious(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SystemKills::class, 'system_id', 'system_id')->latest()
            ->where('created_at', '>', Carbon::now()->subHours(2)->toDateTimeString())
            ->where('created_at', '<', Carbon::now()->subhours(1)->toDateTimeString())
            ->withDefault([
                'npc_kills' => 0,
                'pod_kills' => 0,
                'ship_kills'=> 0,
            ]);
    }

    public function npcKill24Hour(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->killStats()
            ->where('created_at', '>', Carbon::now()->subHours(24)->toDateTimeString());
    }

    public function getNpcDeltaAttribute(){
        return $this->killStatsLatest->npc_kills - $this->killStatsPrevious->npc_kills;
    }

    public function getNpc24HourAttribute(){
        return $this->npcKill24Hour->sum('npc_kills');
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


    public function calculateDistance(System $system)
    {
        return sqrt(
              pow($this->x - $system->x, 2) + pow($this->y - $system->y, 2) + pow($this->z - $system->z, 2)
        ) / 10000000000000000;
    }

    public function systemsWithinJumps(int $jumps = 5)
    {
        $this->jumps = 0;
        $systems = collect([$this]);

        $temp = collect([]);
        $currentBranch = $systems;
        for ($i = 0; $i < $jumps - 1; $i++) {
            $currentBranch->each(function(System $system) use ($systems, $temp){
                $system->connections()
                    ->whereNotIn('system_id', $systems->pluck('system_id'))
                    ->whereNotIn('system_id', $temp->pluck('system_id'))
                    ->with(['killStatsLatest', 'killStatsPrevious', 'npcKill24Hour'])
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
