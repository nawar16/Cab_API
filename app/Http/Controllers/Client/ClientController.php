<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\APIController;
use App\Models\Client;
use App\Traits\AuthTrait;
use App\Traits\ProfileTrait;

class ClientController extends APIController
{
    use AuthTrait;
    use ProfileTrait;
    protected $model = Client::class;
    protected $guard = 'clients';
}
