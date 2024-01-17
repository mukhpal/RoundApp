<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CampaignRequest;
use App\Http\Resources\CampaignInfoResource;
use App\Http\Resources\CampaignResource;
use App\Http\Resources\CampaignsCountersResource;
use App\Http\Resources\CampaignStatsResource;
use App\Http\Resources\DashboardResource;
use App\Models\Campaign;
use App\Models\Producer;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Campaigns",
 *     description="API Endpoints of Campaigns"
 * )
 * @OA\Get(
 *      path="/campaigns",
 *      operationId="indexCampaigns",
 *      tags={"Campaigns"},
 *      summary="Get Campaigns list",
 *      description="Get Campaigns list",
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
 *                  @OA\Items(ref="#/components/schemas/CampaignResource")
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
 *      path="/campaigns/{id}",
 *      operationId="showCampaign",
 *      tags={"Campaigns"},
 *      summary="Get Campaign",
 *      description="Get Campaign",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Parameter(
 *          name="id",
 *          description="Campaign id",
 *          required=true,
 *          in="path",
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/CampaignResource"
 *          )
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found"
 *      )
 *  )
 * @OA\Post(
 *      path="/campaigns",
 *      operationId="storeCampaigns",
 *      tags={"Campaigns"},
 *      summary="Create Campaign",
 *      description="Create Campaign",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(ref="#components/schemas/CampaignRequest")
 *          )
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/CampaignResource"
 *          )
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Unprocessable Entity"
 *      )
 *  )
 * @OA\Put(
 *      path="/campaigns/{id}",
 *      operationId="updateCampaigns",
 *      tags={"Campaigns"},
 *      summary="Update Campaign",
 *      description="Update Campaign",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Parameter(
 *          name="id",
 *          description="Campaign id",
 *          required=true,
 *          in="path",
 *      ),
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(ref="#components/schemas/CampaignRequest")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              ref="#/components/schemas/CampaignResource"
 *          )
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found"
 *      )
 *  )
 * @OA\Delete(
 *      path="/campaigns/{id}",
 *      operationId="deleteCampaigns",
 *      tags={"Campaigns"},
 *      summary="Delete Campaign",
 *      description="Delete Campaign",
 *      security={
 *          {"passport": {}}
 *      },
 *      @OA\Parameter(
 *          name="id",
 *          description="Campaign id",
 *          required=true,
 *          in="path",
 *      ),
 *      @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent()
 *       ),
 *      @OA\Response(
 *          response=401,
 *          description="Unauthenticated",
 *      ),
 *      @OA\Response(
 *          response=403,
 *          description="Forbidden"
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found"
 *      )
 *  )
 * @OA\Get(
 *      path="/campaigns/counters",
 *      operationId="campaignsCounters",
 *      tags={"Campaigns"},
 *      summary="Get Campaigns counters",
 *      description="Get Campaigns counters",
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
 *                  @OA\Items(ref="#/components/schemas/CampaignsCountersResource")
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
 * @OA\Get(
 *      path="/campaigns/info",
 *      operationId="infoCampaigns",
 *      tags={"Campaigns"},
 *      summary="Get Campaigns info",
 *      description="Get Campaigns info",
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
 *                  @OA\Items(ref="#/components/schemas/CampaignInfoResource")
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
class CampaignController extends Controller
{

    public function __construct(Request $request)
    {
        $this->authorizeResource(Campaign::class, 'campaign');
    }

    /**
     * Get the map of resource methods to ability names.
     *
     * @return array
     */
    protected function resourceAbilityMap()
    {
        $map = parent::resourceAbilityMap();
        $map['dash'] = 'viewAny';
        $map['stats'] = 'view';
        /*
        if($this->_campaign && !$this->_campaign->started)
            $map['destroy'] = 'forceDelete';
        */
        return $map;
    }

    /**
     * Get the list of resource methods which do not have model parameters.
     *
     * @return array
     */
    protected function resourceMethodsWithoutModels()
    {
        $methods = parent::resourceMethodsWithoutModels();
        $methods[] = 'dash';
        return $methods;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        /* @var User $user */
        $user = $request->user();
        $campaigns = $user->campaigns();
        if($request->post('startDate')) {
            $campaigns->whereDate('campaigns.created_at', '>=', $request->post('startDate'));
        }
        if($request->post('endDate')) {
            $campaigns->whereDate('campaigns.created_at', '<=', $request->post('endDate'));
        }
        if($request->post('producer'))
            $campaigns->where('producer_id', '=', $request->post('producer'));
        return CampaignResource::collection($campaigns->get());
    }

    public function stats(Request $request, Campaign $campaign)
    {
        return new CampaignStatsResource($campaign);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CampaignRequest $request
     * @return CampaignResource
     * @throws \ErrorException
     */
    public function store(CampaignRequest $request)
    {
        if(!($campaign = $request->saveModel(null, $error))) {
            throw new \ErrorException($error);
        }
        return new CampaignResource($campaign);
    }

    /**
     * Display the specified resource.
     *
     * @param Campaign $campaign
     * @return CampaignResource
     */
    public function show(Campaign $campaign)
    {
        return new CampaignResource($campaign);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CampaignRequest $request
     * @param Campaign $campaign
     * @return CampaignResource
     * @throws \ErrorException
     */
    public function update(CampaignRequest $request, Campaign $campaign)
    {
        if(!($campaign = $request->saveModel($campaign, $error))) {
            throw new \ErrorException("Unable to update campaign {$campaign->id}");
        }
        return new CampaignResource($campaign);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Campaign $campaign
     * @return CampaignResource
     * @throws \ErrorException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Campaign $campaign)
    {
        if($campaign->started) {
            $success = $campaign->delete();
        }
        else {
            $success = $campaign->forceDelete();
        }
        if(!$success)
            throw new \ErrorException("Unable to delete campaign {$campaign->id}");

        return new CampaignResource($campaign);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return CampaignInfoResource
     */
    public function info(Request $request)
    {
        return new CampaignInfoResource([]);
    }

    /**
     * @param Request $request
     * @return CampaignsCountersResource
     */
    public function counters(Request $request) {
        return new CampaignsCountersResource([]);
    }

    /**
     * @param Request $request
     */
    public function dash(Request $request) {
        /* @var User $user */
        $user = $request->user();
        $campaigns = $user->campaigns();
        return new DashboardResource([
            'campaigns' => $campaigns->get()
        ]);
    }
}
