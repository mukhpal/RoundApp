<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;


/**
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints for reset password"
 * )
 *
 * @OA\Post(
 *      path="/auth/password/reset",
 *      operationId="authPasswordReset",
 *      tags={"Auth"},
 *      summary="Password reset",
 *      description="Password reset",
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="token",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="email",
 *                      type="string",
 *                      format="email"
 *                  ),
 *                  @OA\Property(
 *                      property="password",
 *                      type="string",
 *                      format="password"
 *                  ),
 *                  @OA\Property(
 *                      property="password_confirmation",
 *                      type="string",
 *                      format="password"
 *                  ),
 *                  example={
 *                      "token": "ce31036da62de3d297ab806cfa72c6d3a9d7215118463346a83a4d1cb5cc9c37",
 *                      "email": "test@roundapp.local",
 *                      "password": "12345678",
 *                      "password_confirmation": "12345678"
 *                  }
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *     ),
 *      @OA\Response(
 *          response=422,
 *          description="Unprocessable Entity (invalid token, invalid email, invalid password)",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  example={
 *                      "message": "The given data was invalid.",
 *                      "errors": {
 *                          "email":
 *                              "We can't find a user with that email address."
 *                      }
 *                  }
 *              )
 *          )
 *     ),
 * )
 */
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));
        $this->guard()->setUser($user);
        /*
        $json = [];
        $autoLogin = config('auth.login_after_registration');
        if($autoLogin) {
            $response = app()->call("App\\Http\\Controllers\\Api\\Auth\\LoginController@oauthLogin");
            $json = array_replace_recursive(json_decode($response->getContent(), true), $json);
        }
        */
    }
}
