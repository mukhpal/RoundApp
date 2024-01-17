<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Auth\RequestGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints for authentication"
 * )
 * @OA\Post(
 *      path="/auth/login",
 *      operationId="authLogin",
 *      tags={"Auth"},
 *      summary="Request OAuth2 token flow password",
 *      description="Request OAuth2 token flow password",
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
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
 *                  example={"email": "test@roundapp.local", "password": "roundapptest"}
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="token_type",
 *                      example="Bearer",
 *                      type="string",
 *                  ),
 *                  @OA\Property(
 *                      property="expires_in",
 *                      type="integer"
 *                  ),
 *                  @OA\Property(
 *                      property="access_token",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="refresh_token",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="verified",
 *                      type="boolean"
 *                  ),
 *                  example={
 *                      "token_type": "Bearer",
 *                      "expires_id": 31535999,
 *                      "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNDkyNDlmMTkzOTJlY2RmZWFjOWZlN2NhMjY5NDYxNjhmNzdmZjExOGYxZjJiNmNkZGFjNTRkNjM1ODAxMmRlNWVhNGIxOTMyMDBmZDNkZTAiLCJpYXQiOjE1OTI1ODA0MDcsIm5iZiI6MTU5MjU4MDQwNywiZXhwIjoxNjI0MTE2NDA2LCJzdWIiOiIxMSIsInNjb3BlcyI6WyIqIl19.DL4Wx6oteW00Af-U5uSzdNjACNkZoYdypiXATP1YsSWA77smZnOMlRuQbg4H2TYzLH-Vw5bPB7t4Q2PBOmG7Afkm6RuKgTZMLXE2HNcaYlQRdtY_Mywcm1wJp-g6A5re-0kEQwWvhyZzlC4hnNdww-lG2ofclgn5dLV6VGZ65ARUedNYRbVNX0LAyNTUgeke4BKfpyeHMNdmAcgrpwdDgBelbvBz_fl05EmF3dFhOR55nrY6Obh2BFFX5SmiWztCIsY4blOR04JitSiLsf1aBRHE_xoAdmBAR-Kq9jDfCfx7-l3371SlUgzmwRQ1NtGfjNMgCdJe1XjNidqob-VR2hW9s29rozcnt4UUVGCHRopltYpQv7N8500PU4og7pUJ9V-VNxKxjDww3y0Bl-54yg_DRwpdrCcT-wwGdtdDn5FF041k_LrV8j8Wbap0JdeWKOCc96zZXJUpELcikFq-NAN66D5dpCt6r0RZsZAhvNaqanleDTaJzbcHZ9z",
 *                      "refresh_token": "def502006860d206e0b8f88e208d3fd9dbfd55dacad90c03f6276334642aa6213b3e3a541889583d90e301c566bc2d4e0baf5e8733e59a284bc177e2951ea2733775f64b8ecebd2277edac4fbf8e6a4872d6940a9ada4c1ff7ffece5631f3742f9a1c9368ec9d3dddf85c303e87d4143226cf094e4bca99bfb39b987e921b8674071bebd1cd23a17e8a5545ef767bcac3cb7fc3bb9fcb19c80ec8a7d19d407d42084e31d56a47a95e8c80244b7ac0e3ba7c95c35bf80230d11df45690543ca3cef315821c1c8f8fa11e4f32cf8cf6ad06b6ca9211f32873dd902c369a900ddb482cc780b1522565ddaac0ca05a7039011605276b1d64a0e34067e987e38849d109bbd660fedf12e76a03f359f3cf9e2b99b03a535209d8b60ae9ae87697407021667605b4ce0b185ed6fd7888c2d0b95e91210a8f4f16dfa2e1135ee7d3f88fc5af969ad9b3099e4e9794d9b81a60a2720582b61a17157627cfca1ebd8f7c6815ffa465dd9",
 *                      "verified": false
 *                  }
 *              )
 *          )
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="Invalid request",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="error",
 *                      type="string",
 *                  ),
 *                  @OA\Property(
 *                      property="error_description",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="hint",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="message",
 *                      type="string"
 *                  ),
 *                  example={
 *                      "error": "invalid_grant",
 *                      "error_description": "The provided authorization grant (e.g., authorization code, resource owner credentials) or refresh token is invalid, expired, revoked, does not match the redirection URI used in the authorization request, or was issued to another client.",
 *                      "hint": "",
 *                      "message": "The provided authorization grant (e.g., authorization code, resource owner credentials) or refresh token is invalid, expired, revoked, does not match the redirection URI used in the authorization request, or was issued to another client."
 *                  }
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Wrong credentials",
 *      )
 *  )
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'oauthLogout']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function oauthLogin(Request $request)
    {
        $this->validateLogin($request);
        $email = $request->post('email');
        $code = 401;
        $params = [
            'grant_type' => 'password',
            'client_id' => config('l5-swagger.security.passport.flows.password.client_id'),
            'client_secret' => config('l5-swagger.security.passport.flows.password.secret'),
            'username' => $request->post('email'),
            'password' => $request->post('password'),
            'scope' => '*'
        ];
        $oauthRequest = Request::create('/oauth/token', 'POST', $params);
        $response = app()->handle($oauthRequest);
        $json = json_decode($response->content(), true);
        $loginResponse = Response::json($json, $code);
        if(key_exists('access_token', $json) && !empty($json['access_token'])) {
            $code = 200;
            $user = User::firstWhere('email', $email);
            auth()->setUser($user);

            //$json['id'] = $user->id;
            //$json['name'] = $user->name;
            //$json['verified'] = $user->hasVerifiedEmail();
            //$json['favouritePaymentAccount'] = $user->favouritePaymentAccount;
            //$json['last_login_at'] = $user->last_login_at;
            //$json['last_login_ip'] = $user->last_login_ip;
            if (isset($user->image))
                $json['image'] = $user->image->url;

            $userResource = new UserResource($user);
            $json = array_replace_recursive($json, $userResource->toArray($request));
            $this->authenticated($request, $user);
        }
        return Response::json($json, $code);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function oauthLogout(Request $request)
    {
        $success = auth()->user()->token()->revoke();
        $json = ['success' => $success];
        $code = $success ? 200 : 400;
        return Response::json($json, $code);
    }

    public function authenticated(Request $request, $user)
    {
        $user->update([
            'last_login_at' => Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp()
        ]);
    }
}
