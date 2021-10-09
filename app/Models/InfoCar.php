<?php

namespace App\Models;


class InfoCar extends AppModel
{
    protected $table = 'info_cars';
    protected $primaryKey = 'id';
    protected $fillable = [
        'brand', 'model', 'seat', 'year', 'item_order', 'status',
        'created_by', 'updated_by', 'deleted_by'
   ];
}
