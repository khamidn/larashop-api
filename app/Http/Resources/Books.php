<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Books extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        return [
            'status' => 'success',
            'message' => 'books data',
            'data' => parent::toArray($request),
        ];

        // $parent = parent::toArray($request);
        // // $data['categories'] = $this->categories;
        // $data['covers'] = $this->covers;
        // $data = array_merge($parent, $data);

        // return [
        //     'status' => 'success',
        //     'message' => 'book data',
        //     'data' => $data
        // ];
    }
}
