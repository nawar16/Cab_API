<?php



use App\Http\Controllers\APIController;
use App\Models\Driver;
use App\Traits\AuthTrait;
use App\Traits\ProfileTrait;

class DriverController extends APIController
{
    use AuthTrait;
    use ProfileTrait;
    protected $model = Driver::class;
    protected $guard = 'drivers';
    public function cars(Request $request)
    {
        try{
            $driver = auth('drivers')->user();
            $data = $driver->taxis;
            return $this->success_response($data);
        } catch(\Exception $ex){
            return $this->error_response($ex);
        }
    }
}
