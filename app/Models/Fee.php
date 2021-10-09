<?php

namespace App\Models;


class Fee extends AppModel
{
    protected $table = 'fees';
    protected $primaryKey = 'id';
    protected $fillable = [
        'day', 'price_km', 'start_date', 'end_date',
        'item_order', 'status','created_by',
        'updated_by', 'deleted_by'
   ];
}
