<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    protected $guard = 'clients';
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $fillable = [
        'phone', 'fname', 'lname', 'email', 'password', 'credit_card',
        'city', 'img', 'item_order', 'status',
        'created_by', 'updated_by', 'deleted_by'
   ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function services()
    {
        return $this->hasMany('App\Models\Service');
    }
    public function travels()
    {
        $data = $this->services->count();
        return $data ?? 0;
    }
    public function km_of_travels()
    {
        $data = $this->services->sum('distance');
        return $data ?? 0;
    }
    public function favorites()
    {
        return $this->hasMany('App\Models\Favorite', 'client_id', 'id');
    }
}
