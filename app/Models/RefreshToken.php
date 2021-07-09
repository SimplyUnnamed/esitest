<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefreshToken extends Model
{
    use SoftDeletes {
        SoftDeletes::runSoftDelete as protected traitRunSoftDelete;
    }

    const CURRENT_VERSION = 2;

    protected $primary_key = 'character_id';

    protected $attributes = [
        'version'   => self::CURRENT_VERSION,
    ];

    protected $dates    =   ['expires_one', 'deleted_at'];

    protected $fillable = [
        'character_id', 'version', 'user_id', 'refresh_token', 'scopes', 'expires_on', 'token', 'character_owner_hash'
    ];

    public $incrementing = false;

    public static function boot(){
        parent::boot();

        static::created(function(RefreshToken $token){
            //Get character information

        });
    }

    public function getTokenAttribute($value)
    {
        if($this->expires_on->gt(Carbon::now()))
            return $value;

        return null;
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function character(){
        return $this->hasOne(Character::class, 'character_id', 'character_id')
            ->withDefault();
    }

    protected function runSoftDelete(){
        $this->traitRunSoftDelete();

        $this->fireModelEvent('softDelete', false);
    }
}
