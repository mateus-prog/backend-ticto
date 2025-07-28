<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Helpers\Format;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'administrator' => $this->administrator ? 1 : 0,
            'blocked' => $this->blocked ? 1 : 0,
            'attempts' => $this->attempts,
            'cpf' => $this->cpf, 
            'position' => $this->position, 
            'date_of_birth' => Format::formatDate($this->date_of_birth),
            'cep' => $this->cep, 
            'address' => $this->address, 
            'number' => $this->number, 
            'complement' => $this->complement,
            'district' => $this->district, 
            'city' => $this->city, 
            'state' => $this->state,
            'manager_id' => $this->manager_id,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
