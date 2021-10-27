<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\boxes;
class subscriptions extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
      public function toArray($request)
    {
        $data = $this->getCollection()->map(function($subscription){
            $subscription['box'] = boxes::where('user',$subscription->userObj->id)->first();
            return $subscription;
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
