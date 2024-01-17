<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints for authentication"
 * )
 * @OA\Get(
 *      path="/auth/email/verify/{id}/{hash}",
 *      operationId="authEmailVerify",
 *      tags={"Auth"},
 *      summary="User email verification",
 *      description="User email verification",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="id",
 *          description="User id",
 *          required=true,
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer",
 *              example=34
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="hash",
 *          description="Email user hashed",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string",
 *              example="f4d78780a2e258bb569683079bd9305dcda818ea"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="expires",
 *          description="Url expiration time",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="integer",
 *              example=1592670595
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="signature",
 *          description="Url signature",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string",
 *              example="4b4b02ed35557beb4490f794013010a4b16057585693e3bf2e684605a91dcfaf"
 *          )
 *      ),
 *      @OA\Response(
 *          response=204,
 *          description="Successful operation",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized"
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Invalid signature"
 *      ),
 *      @OA\Response(
 *          response=429,
 *          description="Too Many Requests"
 *      )
 *  )
 *
 * @OA\Get(
 *      path="/auth/email/resend",
 *      operationId="authEmailResend",
 *      tags={"Auth"},
 *      summary="Resend User email verification",
 *      description="Resend User email verification",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *      ),
 *      @OA\Response(
 *          response=202,
 *          description="Successful operation",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *       ),
 *      @OA\Response(
 *          response=204,
 *          description="Already verified",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthorized"
 *      )
 *  )
 */
class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
