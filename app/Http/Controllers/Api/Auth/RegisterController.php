<?php

namespace App\Http\Controllers\Api\Auth;

use App\Exceptions\Handler;
use App\Http\Controllers\Controller;
use App\Models\Advertiser;
use App\Models\Consumer;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints for authentication"
 * )
 * @OA\Post(
 *      path="/auth/register",
 *      operationId="authRegister",
 *      tags={"Auth"},
 *      summary="User registration",
 *      description="User registration",
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="name",
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
 *                  example={"name":"New User", "email": "newuser@roundapp.local", "password": "12345678", "password_confirmation": "12345678"}
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Successful operation",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="success",
 *                      type="boolean",
 *                  ),
 *                  @OA\Property(
 *                      property="message",
 *                      type="string",
 *                  ),
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
 *                      type="false"
 *                  ),
 *                  example={
 *                      "success": true,
 *                      "message": "Registration completed. Verify your email address to activate the account.",
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
 *          response=400,
 *          description="Invalid Request",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="success",
 *                      type="boolean",
 *                  ),
 *                  @OA\Property(
 *                      property="message",
 *                      type="string",
 *                  ),
 *                  @OA\Property(
 *                      property="errors",
 *                      type="json",
 *                  ),
 *                  example={
 *                      "success": false, "message": "Validation failed", "errors": {}
 *                  }
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Unprocessable Entity",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="success",
 *                      type="boolean",
 *                  ),
 *                  @OA\Property(
 *                      property="message",
 *                      type="string",
 *                  ),
 *                  example={
 *                      "success": false, "message": "The given data was invalid."
 *                  }
 *              )
 *          )
 *      )
 *  )
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(string $type, array $data)
    {
        if($type === 'advertiser') {
            $someone = $this->createAdvertiser($data);
            $someoneType = Advertiser::class;
        }
        elseif($type === 'consumer') {
            $someone = $this->createConsumer($data);
            $someoneType = Consumer::class;
        }
        else {
            throw new BadRequestHttpException("Invalid type {$type}");
        }
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'someone_id' => $someone->id,
            'someone_type' => $someoneType,
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function createAdvertiser(array $data)
    {
        return Advertiser::create([
            'type' => $data['advertiser_type'] ?? null
        ]);
    }

    protected function createConsumer(array $data)
    {
        return Consumer::create([
            'name' => $data['consumer_name'],
            'surname' => $data['consumer_surname'],
            'birth_year' => $data['consumer_birth_year'],
            'gender' => $data['consumer_gender'],
        ]);
    }

    public function oauthRegister(Request $request, string $type)
    {
        /* @var Response $response */
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($type, $request->all())));
        $json = [];
        $autoLogin = config('auth.login_after_registration');
        if($autoLogin) {
            $response = app()->call("App\\Http\\Controllers\\Api\\Auth\\LoginController@oauthLogin");
            $json = array_replace_recursive(json_decode($response->getContent(), true), $json);
        }
        if ($responseRegistered = $this->registered($request, $user)) {
            return $responseRegistered;
        }
        return response()->json($json, 201);
    }
}
