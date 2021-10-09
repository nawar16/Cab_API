<?php

namespace App\Models;


class Service extends AppModel
{
    protected $table = 'services';
    protected $primaryKey = 'id';
    protected $fillable = [
        'time', 'hour', 'distance', 'price', 'qualification', 'state',
        'origin_coordinate', 'destination_coordinate',
        'client_id', 'driver_id', 'taxi_id', 'fee_id',
        'item_order', 'status','created_by', 'updated_by', 'deleted_by'
    ];
    protected $casts = [
       'origin_coordinate' => 'array',
       'destination_coordinate' => 'array'
    ];
    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }
    public function driver()
    {
        return $this->belongsTo('App\Models\Driver', 'driver_id');
    }
    public function taxi()
    {
        return $this->belongsTo('App\Models\Taxi', 'taxi_id');
    }
    public function fee()
    {
        return $this->belongsTo('App\Models\Fee', 'fee_id');
    }
}
