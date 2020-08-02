<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Book extends JsonResource
{
    public function toArray($request)
    {
        // return [
        //     'id' => $this->id,
        //     'title' => $this->title,
        //     'created_at' => $this->created_at,
        //     'updated_at' => $this->updated_at,
        // ];
        // // return parent::toArray($request);

        $parent = parent::toArray($request);
        $data['categories'] = $this->categories;
        $data['covers'] = $this->covers;
        $data = array_merge($parent, $data);

        return [
            'status' => 'success',
            'message' => 'book data',
            'data' => $data
        ];
    }


}
