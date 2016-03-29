<?php namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use League\Fractal\TransformerAbstract;
use Illuminate\Http\Response as IlluminateResponse;

use Dingo\Api\Routing\Helpers;


Class BaseController extends Controller {
    use Helpers;
}