<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LibraryCollection;
use App\Http\Resources\VideoResource;
use App\Http\Resources\VideoStatsResource;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Videos",
 *     description="API Endpoints of Videos"
 * )
 * @OA\Get(
 *      path="/videos",
 *      operationId="indexVideos",
 *      tags={"Videos"},
 *      summary="Get Videos list",
 *      description="Get Videos list",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(
 *                  property="data",
 *                  type="array",
 *                  @OA\Items(ref="#/components/schemas/VideoResource")
 *              ),
 *              @OA\Property(
 *                  property="pool",
 *                  type="array",
 *                  @OA\Items({
 *                      @OA\Property(
 *                          property="tags",
 *                          type="array",
 *                          @OA\Items(additionalProperties={"type":"string"})
 *                      ),
 *                      @OA\Property(
 *                          property="producers",
 *                          type="array",
 *                          @OA\Items(additionalProperties={"type":"string"})
 *                      ),
 *                      @OA\Property(
 *                          property="ages",
 *                          type="array",
 *                          @OA\Items(additionalProperties={"type":"string"})
 *                      ),
 *                      @OA\Property(
 *                          property="genders",
 *                          type="array",
 *                          @OA\Items(additionalProperties={"type":"string"})
 *                      ),
 *                      @OA\Property(
 *                          property="types",
 *                          type="array",
 *                          @OA\Items(additionalProperties={"type":"string"})
 *                      ),
 *                  })
 *              )
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
 *      path="/videos/{id}",
 *      operationId="statsVideo",
 *      tags={"Videos"},
 *      summary="Get Video stats",
 *      description="Get Video stats",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Parameter(
 *          name="id",
 *          description="Video id",
 *          required=true,
 *          in="path",
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(
 *                  property="data",
 *                  type="array",
 *                  @OA\Items(ref="#/components/schemas/VideoResource")
 *              ),
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
class VideoController extends Controller
{
    protected $_campaign;

    public function __construct(Request $request, Video $campaign = null)
    {
        $this->_campaign = $campaign;
        $this->authorizeResource(Video::class, 'video');
    }

    protected function resourceAbilityMap()
    {
        $map['stats'] = 'view';
        return $map;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return VideoResource::collection($request->user()->videos);
    }

    /**
     * @param Request $request
     * @return LibraryCollection
     */
    public function showcase(Request $request)
    {
        // $user = $request->user();
        $user = User::find(1);
        return new LibraryCollection($user->library());
    }


    public function stats(Request $request, Video $video)
    {
        return new VideoStatsResource($video);
    }
}
