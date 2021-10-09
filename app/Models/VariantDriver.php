<?php

namespace App\Models;


class VariantDriver extends AppModel
{
    protected $guard = 'variant_drivers';
    protected $table = 'variant_drivers';
    protected $primaryKey = 'id';
    protected $fillable = [
         'driver_id', 'taxi_id', 'date', 'hour', 'latitude', 'longitude',
         'item_order', 'status', 'created_by', 'updated_by', 'deleted_by'
    ];  
    public function taxi()
    {
        return $this->belongsTo('App\Models\Taxi', 'taxi_id');
    }
    public function driver()
    {
        return $this->belongsTo('App\Models\Driver', 'driver_id');
    }

}
