<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\VideoResource;
use App\Http\Resources\VideoStatsResource;
use App\Models\File;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 */
class FileController extends Controller
{

    /*
    public function __construct()
    {
        $this->authorizeResource(File::class, 'file');
    }


    protected function resourceAbilityMap()
    {
        $map = parent::resourceAbilityMap();
        //$map['destroy'] = 'forceDelete';
        return $map;
    }
    */


    /**
     * Store a newly created resource in storage.
     *
     * @param FileRequest $request
     * @param string $type
     * @return FileResource|VideoResource|null
     */
    public function store(FileRequest $request, string $type)
    {
        $success = false;
        try {
            DB::beginTransaction();
            $file = File::Upload($request->file('UploadFiles'), $type);
            if($type == File::TYPE_VIDEO) {
                $getID3 = new \getID3();
                $fileInfo = $getID3->analyze($file->fullPath);
                $duration = $fileInfo['playtime_seconds'];
                $video = Video::create([
                    'file_id' => $file->id,
                    'title' => $file->name,
                    'url' => $file->url,
                    'duration' => $duration
                ]);
            }
            $success = true;
        }
        catch (\Throwable $e) {

        }
        if($success)
            DB::commit();
        else
            DB::rollBack();
        return isset($video) ? new VideoResource($video) : (isset($file) ? new FileResource($file) : null);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param string $type
     * @param File $file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Throwable
     */
    public function show(Request $request, string $type, File $file)
    {
        throw_unless($file->type === $type, new BadRequestHttpException("Mismatched file type: {$type}"));
        return response()->file($file->fullPath);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param string $type
     * @param File $file
     * @return FileResource|VideoResource
     * @throws \Throwable
     */
    public function destroy(Request $request, string $type, File $file)
    {
        throw_unless($file->type === $type, new BadRequestHttpException("Mismatched file type: {$type}"));
        $success = false;
        try {
            DB::beginTransaction();
            if($type == File::TYPE_VIDEO) {
                $video = $file->video;
                $video->delete();
            }
            $success = $file->remove();
        }
        catch (\Throwable $e) {

        }
        if($success)
            DB::commit();
        else
            DB::rollBack();
        return isset($video) ? new VideoResource($video) : new FileResource($file);
    }
}
