<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'gender' => $this->gender,
            'address' => $this->address,
            'PlaceOfBirth' => $this->PlaceOfBirth,
            'DateOfBirth' => $this->DateOfBirth,
            'phone' => $this->phone,
            'email' => $this->email,
            'createt_by' => optional($this->createdBy)->name,
            'updated_by' => optional($this->createdBy)->name,
            'created_at' => $this->created_at->format('g:ia \o\n l jS F Y'),
            'updated_at' =>  $this->updated_at->format('g:ia \o\n l jS F Y'),
        ];
    }
}
