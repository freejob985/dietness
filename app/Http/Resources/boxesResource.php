<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\boxes;
use App\Models\Helper;

class boxesResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
      public function toArray($request)
    {
        $data = $this->getCollection()->map(function($box){
            $remaining_boxes = Helper::getRemainingBoxes($box->user);
            $box['remaining_boxes'] = $remaining_boxes;
            return $box;
        });
         return [
        'data' => $data,
        'meta' => [
            'total' => $this->total(),
            'perpage' => $this->perPage(),
            'page' => $this->currentPage(),
         
            
        ]
    ];
    }
}