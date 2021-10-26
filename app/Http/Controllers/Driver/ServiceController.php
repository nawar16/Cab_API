<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\VariantDriver;
use App\Models\Taxi;
use App\Models\InfoCar;

class ServiceController extends APIController
{
    public function my_services(Request $request)
    {
      try{
        $driver = auth('drivers')->user();
        $data = $driver->my_services;
        if($data)
        {
            $data = $data->map(function($d){
                return [
                    'all' => [
                        'time' => $d->time,
                        'hour' => $d->hour,
                        'distance' => $d->distance,
                        'price' => $d->price,
                        'qualification' => $d->qualification,
                        'state' => $d->state,
                        'origin_geom' => $d['origin_coordinate'],
                        'destination_geom' => $d['origin_coordinate'],
                    ],
                    'cli' => $d->client,
                    'origin_geom' => [
                        'coordinates' => $d['origin_coordinate']
                    ],
                    'destination_geom' => [
                      'coordinates' => $d['destination_coordinate']
                    ]
                ];
            });
            return $this->success_response($data);
        } else{
            $data = [
                'status' => false,
                'code' => 500,
                'msg' => 'No services',
                'err' => 'Server Error'
            ];
            return response()->json($data, 500);
        }
      } catch(\Exception $ex){
            return $this->error_response($ex);
      }
    }
    public function ok_service(Request $request)
    {
        try{
            $driver = auth('drivers')->user();
            $services = $driver->my_services;
            if($services)
            {
                $data = $services->map(function(&$service){
                    return $service->update([
                        'state' => 'old'
                    ]);
                });
                return $this->success_response($data);
            } else{
                $data = [
                    'status' => false,
                    'code' => 500,
                    'msg' => 'No services',
                    'err' => 'Server Error'
                ];
                return response()->json($data, 500);
            }
          } catch(\Exception $ex){
                return $this->error_response($ex);
          }
    }
    public function new_position(Request $request)
    {
        try{
            $driver = auth('drivers')->user();
            $taxi = Taxi::where('license_plate', $request->taxi)->first();
            $variant = new VariantDriver([
                'taxi_id' => $taxi->id,
                'latitude' => $request->coordinates['lat'],
                'longitude' => $request->coordinates['lng']
            ]);
            $data = $driver->variants()->save($variant);
            return $this->success_response($data);
        } catch(\Exception $ex){
            return $this->error_response($ex);
        }
    }
    public function busy_position(Request $request)
    {
        try{
            $driver = auth('drivers')->user();
            $variants = $driver->variants;
            if($variants)
            {
                $data = $variants->map(function(&$variant){
                    return $variant->update([
                        'status' => 0
                    ]);
                });
                return $this->success_response($data);
            }
        } catch(\Exception $ex){
            return $this->error_response($ex);
        }
    }
    public function new_car(Request $request)
    {
        try{
            $driver = auth('drivers')->user();
            $info = InfoCar::create([
                'brand' => $request->carInfo['brand'],
                'model' => $request->carInfo['model'],
                'seat' => $request->carInfo['seat'],
                'year' => $request->carInfo['year'],
            ]);
            $taxi = Taxi::create([
                'license_plate' => $request->carInfo['plate'],
                'info_car_id' => $info->id
            ]);
            $data = $driver->taxis()->save($taxi);
            return $this->success_response($data);
        } catch(\Exception $ex){
            return $this->error_response($ex);
        }
    }
    public function delete_car(Request $request)
    {
        try{
            $car = Taxi::where('license_plate', $request->plate)->first();
            if($car)
            {
                $car->drivers()->detach();
                InfoCar::whereId($car->info_car->id)->delete();
                $data = $car->delete();
                return $this->success_response($data);
            }
            return $this->error_response(new \Illuminate\Database\Eloquent\ModelNotFoundException);
        } catch(\Exception $ex){
            return $this->error_response($ex);
        }
    }
}
