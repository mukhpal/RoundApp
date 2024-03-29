<?php


namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="RoundApp OpenApi Documentation",
     *      description="RoundApp OpenApi Documentation",
     *      @OA\Contact(
     *          email="francesco.simeoli@gmail.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0 License",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="RoundApp API Server"
     * )
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
