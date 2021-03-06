<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DriversOrders extends ResourceCollection
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
        'data' => $this->collection,
        'pagination' => [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
            'prev'  => $this->previousPageUrl(),
            'next'  => $this->nextPageUrl(),
            
        ]
    ];
    }
}
