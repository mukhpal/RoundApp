<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConsumerResource;
use App\Models\Consumer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Consumers",
 *     description="API Endpoints of Consumers"
 * )
 * @OA\Get(
 *      path="/consumers/me",
 *      operationId="showConsumer",
 *      tags={"Consumers"},
 *      summary="Get Consumer",
 *      description="Get Consumer",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Consumer"
 *          )
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      )
 *  )
 * @OA\Put(
 *      path="/consumers/me",
 *      operationId="updateConsumers",
 *      tags={"Consumers"},
 *      summary="Update Consumer",
 *      description="Update Consumer",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Consumer"
 *          )
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      )
 *  )
 */
class ConsumerController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request  $request)
    {
        /* @var User $user */
        $user = $request->user();
        return $user->consumer();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Consumer  $consumer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Consumer $consumer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Consumer  $consumer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Consumer $consumer)
    {
        //
    }
}
