<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProducerResource;
use App\Models\Producer;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Producers",
 *     description="API Endpoints of Producers"
 * )
 * @OA\Get(
 *      path="/producers",
 *      operationId="indexProducers",
 *      tags={"Producers"},
 *      summary="Get Producers list",
 *      description="Get Producers list",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(type="array",
 *            @OA\Items(ref="#/components/schemas/Producer")
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
 * @OA\Get(
 *      path="/producers/{id}",
 *      operationId="showProducer",
 *      tags={"Producers"},
 *      summary="Get Producer",
 *      description="Get Producer",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Producer"
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
 * @OA\Post(
 *      path="/producers",
 *      operationId="storeProducers",
 *      tags={"Producers"},
 *      summary="Create Producer",
 *      description="Create Producer",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Producer"
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
 *      path="/producers/{id}",
 *      operationId="updateProducers",
 *      tags={"Producers"},
 *      summary="Update Producer",
 *      description="Update Producer",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/Producer"
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
 * @OA\Delete(
 *      path="/producers/{id}",
 *      operationId="deleteProducers",
 *      tags={"Producers"},
 *      summary="Delete Producer",
 *      description="Delete Producer",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
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
class ProducerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return ProducerResource::collection($request->user()->producers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producer  $producer
     * @return \Illuminate\Http\Response
     */
    public function show(Producer $producer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producer  $producer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producer $producer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producer  $producer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producer $producer)
    {
        //
    }
}
