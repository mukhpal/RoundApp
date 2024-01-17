<?php

namespace App\Http\Resources;

use App\Models\Campaign;
use App\Models\Consumer;
use App\Models\File;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class VideoResource
 *
 * @OA\Schema(
 *   description="Resource CampaignStats",
 *   required={
 *       "id", "title"
 *   },
 * )
 *
 * @OA\Property(
 *   property="id",
 *   type="integer",
 *   format="bigint",
 *   description="id",
 * )
 * @OA\Property(
 *   property="title",
 *   type="string",
 *   format="string",
 *   description="title",
 *   maxLength=255
 * )
 * @OA\Property(
 *   property="video",
 *   type="object",
 *   @OA\Items(ref="#/components/schemas/Video"),
 *   description="File"
 * )
 *
 * @property integer $id
 * @property string $title
 * @property Video $video
 */
class CampaignStatsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'datasets' => [
                'total' => $this->total($request),
                'paid' => $this->paid($request),
                'free' => $this->free($request),
                'website' => $this->website($request),
            ],
            'labels' => $this->labels($request)
        ];
    }

    protected function labels($request)
    {
        // $startDate = Carbon::createFromFormat('Y-m-d', $request->startDate);
        // $endDate = Carbon::createFromFormat('Y-m-d', $request->endDate);
        // $diff = $endDate->diff($startDate);
        // $diff->days
        return ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Sett', 'Ott', 'Nov', 'Dic'];
    }

    protected function total($request)
    {
        $array = range(100, 1200, 100);
        shuffle($array);
        return [
            'data' => $array,
            'label' => 'Totali'
        ];
    }

    protected function paid($request)
    {
        $array = range(100, 1200, 100);
        shuffle($array);
        return [
            'data' => $array,
            'label' => 'A pagamento'
        ];
    }

    protected function free($request)
    {
        $array = range(100, 1200, 100);
        shuffle($array);
        return [
            'data' => $array,
            'label' => 'Gratis'
        ];
    }

    protected function website($request)
    {
        $array = range(100, 1200, 100);
        shuffle($array);
        return [
            'data' => $array,
            'label' => 'Sito web'
        ];
    }
}
