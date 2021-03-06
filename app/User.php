<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    protected $appends = ['last_visitor'];
    protected $fillable = [
        'name',
        'phone',
        'email',
        'phone_verified_at',
        'password',
        'fcm_token',
        'verified',
        'remember_token',
        'active',
        'seen',
        'free_ads_count',
        'paid_ads_count',
        'my_wallet',
        'free_balance',
        'payed_balance',
        'watsapp',
        'image'
      ];
    use Notifiable;

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function products() {
        return $this->hasMany('App\Product', 'user_id');
    }

    public function getLastVisitorAttribute() {
        $data = Visitor::where('user_id', $this->id)->latest('updated_at')->select('id', 'fcm_token')->first();
        return $data;
    }
}
