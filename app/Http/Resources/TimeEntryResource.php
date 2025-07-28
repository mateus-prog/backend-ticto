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
            'id' => $this->id,
            'employee' => $this->employee,
            'manager' => $this->manager,
            'position' => $this->position,
            'age' => $this->age,
            'time' => Format::formatDateTime($this->time),
        ];
    }
}
