<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VariantDriver;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Taxi;
use App\Models\Fee;
use App\Models\Service;
use DB;

class ServiceController extends Controller
{
    public function near_taxi(Request $request)
    {
      $this->validate($request, [
        'coordinates' => 'required'
      ]);
      try{
        $coordinates = $request->coordinates;
        $distance = DB::raw('( 111.045 * acos( cos( radians(' . $coordinates[0] . ') ) * cos( radians( variant_drivers.latitude ) )        * cos( radians( variant_drivers.longitude )- radians(' . $coordinates[1]  . ') ) + sin( radians(' . $coordinates[0]  . ') ) * sin( radians( variant_drivers.latitude ) ) ) )');
        $drivers =  DB::table('variant_drivers')
        ->join('drivers as drivers', 'variant_drivers.driver_id', '=', 'drivers.id')
        ->join('taxis as taxis', 'variant_drivers.taxi_id', '=', 'taxis.id')
        ->join('info_cars', 'taxis.info_car_id', '=', 'info_cars.id')
        ->join('services as services', 'drivers.id', '=', 'services.driver_id')
        ->join('fees as fees', 'services.fee_id', '=', 'fees.id')
        ->select('variant_drivers.*', 'drivers.*', 'taxis.*', 'info_cars.*', 'services.*','fees.*')
        ->selectRaw("{$distance} AS distance")
        ->orderBy('distance')->limit(1)->get();
        $avg_calls = DB::select(DB::raw('select AVG(qualification) as avg from services GROUP BY services.driver_id'));

         $drivers['avg_calls'] = $avg_calls;
        return success_response($drivers);
      } catch(\Exception $ex){
          return error_response($ex);
      }
    } 
    public function service_notification(Request $request)
    {
      $this->validate($request, [
        'driver_phone' => 'required',
        'license_plate' => 'required',
        'fee_id' => 'required',
        'time' => 'required',
        'distance' => 'required',
        'price' => 'required',
        'origin_coor' => 'required',
        'destination_coor' => 'required'
      ]);
      try{
        $driver = Driver::where('phone', $request->driver_phone)->first();
        $taxi = Taxi::where('license_plate', $request->license_plate)->first();
        $fee = Fee::where('id', $request->fee_id)->first();
        $service = Service::Create([
          'client_id' => auth('clients')->user()->id,
          'driver_id' => $driver? $driver->id:null,
          'taxi_id' => $taxi? $taxi->id:null,
          'fee_id' => $fee? $fee->id:null,
          'time' => $request->time,
          'distance' => $request->distance,
          'price' => $request->price,
          'qualification' => $request->qualification,
          'origin_coordinate' => $request->origin_coor,
          'destination_coordinate' => $request->destination_coor,
          'state' => 'new'
        ]);
        return success_response($service);
      } catch(\Exception $ex){
        return error_response($ex);
      }

    }
    public function service_qualification(Request $request)
    {
      try{
        $client = auth('clients')->user();
        $driver = Driver::where('phone', $request->phonedri)->first();
        $service = Service::where('driver_id', $driver->id)
        ->where('client_id', $client->id)
        ->latest('id')->first();
        if($service)
        {
          $service->update([
            'qualification' => $request->qualification['cal']
          ]);
        }
        return success_response($service);
      } catch(\Exception $ex){
        return error_response($ex);
      }
    }

}