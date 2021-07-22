<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class Character extends Model
{

    protected $primaryKey = 'character_id';

    protected $fillable = [
        'character_id', 'name', 'user_id'
    ];

    protected $casts = [
        'tracking' => 'boolean',
    ];


    public function toggleTracking(){
        $this->tracking = !$this->tracking;
        $this->save();
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function RefreshToken(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(RefreshToken::class,'character_id', 'character_id');
    }

    public function locations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LocationHistory::class, 'character_id', 'character_id');
    }

    public function currentLocation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(LocationHistory::class, 'character_id', 'character_id')->latest();
    }

    public function scopeBelongsToUser(Builder $query): Builder
    {
        return $query->whereHas('RefreshToken', function($query){
            $query->where('user_id', auth()->user()->getKey());
        });
    }

    public function scopeTracking(Builder $query): Builder
    {
        return $query->where('tracking', true);
    }
}
