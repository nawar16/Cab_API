<?php

namespace App\Models;


class taxi extends AppModel
{
    protected $table = 'taxis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'license_plate', 'width', 'info_car_id',
        'item_order', 'status','created_by', 
        'updated_by', 'deleted_by'
   ];
   public function info_car()
   {
       return $this->belongsTo('App\Models\InfoCar', 'info_car_id');
   }
   public function drivers()
   {
       return $this->belongsToMany('App\Models\Driver')
       ->withTimestamps();
   }
}
