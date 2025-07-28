<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TimeEntryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => TimeEntryResource::collection($this->collection),
            'meta' => [
                'total' => $this->collection->count(),
                'status' => 200
            ],
        ];
    }
}
