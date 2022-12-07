<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
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
            "id"                    => $this->id,
            "user_id"               => $this->user_id,
            "user_type"             => $this->user_type,
            "address"               => $this->address,
            "profile_picture_url"   => $this->profile_picture_url,
            "current_school"        => $this->current_school,
            "previous_school"       => $this->previous_school,
        ];
    }
}
