<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LibraryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->aggregateCollection()
        ];
    }

    protected function aggregateCollection()
    {
        $this->collection->each(function() {

        });
        return $this->collection;
    }
}
