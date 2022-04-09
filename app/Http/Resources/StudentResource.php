<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'image' => 'http://127.0.0.1:8000/storage/' . $this->image,
            'createt_by' => optional($this->createdBy)->name,
            'updated_by' => optional($this->createdBy)->name,
            'created_at' => $this->created_at->format('Y-m-d g:i a'),
            'updated_at' =>  $this->updated_at->format('Y-m-d g:i a'),
        ];
    }
}
