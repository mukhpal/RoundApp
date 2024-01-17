<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertiserUpdateRequest;
use App\Http\Resources\AdvertiserResource;
use App\Models\Advertiser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/*APIDOC*/
class AdvertiserController extends Controller
{
    public function __construct(Request $request)
    {
        // $this->authorizeResource(Advertiser::class, 'advertiser');
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
        Gate::authorize('viewAny', $user->someone);
        return AdvertiserResource::collection(Advertiser::with('user')->whereHas('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return AdvertiserResource
     */
    public function showMe(Request $request)
    {
        /* @var User $user */
        $user = $request->user();
        Gate::authorize('view', $user->someone);
        return new AdvertiserResource($user->someone);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdvertiserUpdateRequest $request
     * @return AdvertiserResource
     * @throws \Throwable
     */
    public function updateMe(AdvertiserUpdateRequest $request)
    {
        /* @var User $user */
        $user = $request->user();
        Gate::authorize('update', $user->someone);
        $attributes = $request->getModelAttributes();
        throw_if(empty($attributes), new BadRequestHttpException("Empty attributes"));
        if($request->newPassword()) {
            throw_unless($user->changePassword($attributes['password_new'], $attributes['password']), new BadRequestHttpException('Unable to change password!'));
        }
        if(isset($attributes['name']))
            $user->name = $attributes['name'];

        if(isset($attributes['advertiser_type'])) {
            $advertiser = $user->updateAdvertiser([
                'type' => $attributes['advertiser_type']
            ]);
        }
        throw_unless($user->save(), new BadRequestHttpException("Unable to save user model"));
        return new AdvertiserResource($user->someone);
    }


    public function image(Request $request)
    {
        /* @var User $user */
        $user = $request->user();
        Gate::authorize('image', $user->someone);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
