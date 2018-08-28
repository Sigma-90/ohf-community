<?php

namespace App\Http\Resources\People;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopCardResource extends JsonResource
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
            'id' => $this->person->id,
            'name' => $this->person->name,
            'family_name' => $this->person->family_name,
            'date_of_birth' => $this->person->date_of_birth,
            'age' => $this->person->age,
            'nationality' => $this->person->nationality,
            'date' => $this->date,
            'code' => $this->code,
            'code_redeemed' => $this->code_redeemed,
            //'end' => (new Carbon($this->end_date))->toIso8601String(),
        ];
    }
}
