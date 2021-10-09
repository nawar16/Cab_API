<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Favorite extends AppModel
{
    protected $guard = 'favorites';
    protected $table = 'favorites';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title', 'site_address', 'coordinate',
        'client_id','item_order', 'status',
        'created_by', 'updated_by', 'deleted_by'
   ];
   protected $casts = [
    'coordinate' => 'array'
   ];
   public function client()
   {
       return $this->belongsTo('App\Models\Client', 'client_id');
   }
}
