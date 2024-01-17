<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;


/**
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints for reset password"
 * )
 *
 * @OA\Post(
 *      path="/auth/password/forgot",
 *      operationId="authPasswordForgot",
 *      tags={"Auth"},
 *      summary="Password reset request",
 *      description="Password reset request",
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="email",
 *                      type="string",
 *                      format="email"
 *                  ),
 *                  example={"email": "test@roundapp.local"}
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
 *                      property="message",
 *                      type="string",
 *                  ),
 *                  example={
 *                      "message": "We have emailed your password reset link!"
 *                  }
 *              )
 *          )
 *       ),
 *      @OA\Response(
 *          response=422,
 *          description="Unprocessable Entity (invalid email | email not registered)",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(
 *                      property="message",
 *                      type="string"
 *                  ),
 *                  @OA\Property(
 *                      property="errors",
 *                      type="json",
 *                  ),
 *                  example={
 *                      "message": "The given data was invalid.",
 *                      "errors": {
 *                          "email":
 *                              "We can't find a user with that email address."
 *                      }
 *                  }
 *              )
 *          )
 *      )
 * )
 *
 */
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
}
