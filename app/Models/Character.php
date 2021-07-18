<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Character extends Model
{

    protected $primaryKey = 'character_id';

    protected $fillable = [
        'character_id', 'name', 'user_id'
    ];

    protected $casts = [
        'tracking' => 'boolean',
    ];



    public function user(){
        return $this->belongsTo(User::class);
    }

    public function toggleTracking(){
        $this->tracking = !$this->tracking;
        $this->save();
    }

    public function RefreshToken(){
        return $this->hasOne(RefreshToken::class,'character_id', 'character_id');
    }

    public function locations(){
        return $this->hasMany(LocationHistory::class, 'character_id', 'character_id');
    }

    public function currentLocation(){
        return $this->hasOne(LocationHistory::class, 'character_id', 'character_id')->latest();
    }

    public function scopeTracking(Builder $query){
        return $query->where('tracking', true);
    }
}
