<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Driver extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    protected $guard = 'drivers';
    protected $table = 'drivers';
    protected $primaryKey = 'id';
    protected $fillable = [
         'phone', 'fname', 'lname', 'email', 'password', 'credit_card',
         'city', 'img', 'item_order', 'status',
         'created_by', 'updated_by', 'deleted_by'
    ];
    public function variants()
    {
        return $this->hasMany('App\Models\VariantDriver');
    }
    public function my_services()
    {
        return $this->hasMany('App\Models\Service')
        ->with(['client', 'taxi', 'fee'])
        ->where('state', 'new');
    }
    public function services()
    {
        return $this->hasMany('App\Models\Service')->with(['client', 'taxi', 'fee']);
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
    public function balance()
    {
        $data = $this->services->sum('price');
        return $data ?? 0;
    }
    public function rate()
    {
        $data = $this->services->avg('qualification');
        return $data ?? 0;
    }
    public function taxis()
    {
        return $this->belongsToMany('App\Models\taxi')
    	->withTimestamps()->with(['info_car']);
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
