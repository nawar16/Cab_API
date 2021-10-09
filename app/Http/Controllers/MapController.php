<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VariantDriver;

class MapController extends Controller
{
    public function index()
    {
        try{
            $points = VariantDriver::where('hour', \Carbon\Carbon::now()->hour)
            ->where('date', \Carbon\Carbon::now()->format('Y-m-d'))->get();
            $data = $points->map(function($point){
                return [
                    'id' => $point['id'],
                    'geom' => [
                        'coordinates' => [
                            $point['latitude'], 
                            $point['longitude']
                        ]
                    ]
                ];
            });
            return success_response($data);
        } catch(\Exception $ex){
            return error_response($ex);
        }
    }
}
