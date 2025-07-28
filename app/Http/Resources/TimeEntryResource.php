<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Helpers\Format;

class TimeEntryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'user' => $this->user,
            'date' => Format::formatDate($this->date),
            'times' => $this->times,
        ];
    }
}
