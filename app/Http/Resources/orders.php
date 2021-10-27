<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\products;
use App\Models\plans;
use App\Models\restricted_orders;
use \Carbon\Carbon;
class orders extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected $restricted = [];
      public function toArray($request)
    {
        if(isset($request['query']['day'])){
            $day = Carbon::parse($request['query']['day']);
            $restricted_orders = restricted_orders::with('userObj')->whereDate('day',$day)->get();
            $this->restricted = $restricted_orders;
      }else{
        $restricted_orders = restricted_orders::with('userObj')->get();
        $this->restricted = $restricted_orders;
      }
        $orders = $this->getCollection()->map(function($subscription){
            return $subscription;
        });
         $products = [];
         $uniqueProducts = [];
         foreach($orders as $order){
             foreach($order['items'] as $item){
                 foreach($item['products'] as $product){
                     $products[] = array("product" => $product['id'] , 'plan' => $order['plan']);
                 }
             }
         }
         $uniqueProducts = array_unique(array_column($products, 'product'));
         $finalProducts = [];
         foreach($uniqueProducts as $product){
             $data = [];
             $data = collect($products)->filter(function($item) use($product){
                 return $item['product'] == $product;
             })->values()->toArray();
             $get_unique_plans = array_unique(array_column($data, 'plan'));
             $array_of_objects = [];
             foreach($get_unique_plans as $plan){
                 $count = collect($data)->filter(function($item) use($plan){
                 return $item['plan'] == $plan;
             })->values()->toArray();
                 $array_of_objects[] = ['plan' => plans::find($plan), 'count' => count($count)];
             }
             $finalProducts[] = ['product' => products::withTrashed()->with('category_obj')->find($product) , 'data' => $array_of_objects];
         }
         return [
        'data' => $orders,
        'products' => collect($finalProducts)->sortBy('product.category')->values(),
        'restricted' => $this->restricted,
        'meta' => [
            'total' => $this->total(),
            'perpage' => $this->perPage(),
            'page' => $this->currentPage(),
         
            
        ]
    ];
    }
    public function count_duplicate($value,$array){
        $count = 0;
        foreach($array as $val){
            if($val == $value){
                $count++;
            }
        }
        return $count;
    }
}
