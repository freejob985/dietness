<?php
  
namespace App\Traits;
use PushNotification;
use Illuminate\Http\Request;
use App\Models\main_categories;
use App\Models\plans;
trait MainMethods {
    // renewal  , order , topic , notifyOrder
  
  public function sendFCMByToken($to,$body,$action = 'order')
    {
        $push = PushNotification::setService('fcm')
                        ->setMessage([
                             'content_available' => true,
                             'data' => [
                                     'data' => $body,
                                     'action' => $action
                                     ]
                             ])
                        ->setApiKey(env('FCM_SERVER_KEY'))
                        ->setDevicesToken($to)//Array
                        ->send()
                        ->getFeedback(); 
                        return $push;
    }
      public function sendFCMByTopic($body,$action = 'topic',$topic_name = 'all')
    {
        $push = PushNotification::setService('fcm')
                        ->setMessage([
                             'content_available' => true,
                             'data' => [
                                     'data' => $body,
                                     'action' => $action
                                     ]
                             ])
                        ->setApiKey(env('FCM_SERVER_KEY'))
                        ->setConfig([
                                'priority' => 'high',
                                'dry_run' => true,
                                'time_to_live' => 3
                            ])
                        ->sendByTopic($topic_name);
                        return $push;
    }
    public function bookOrderByAuto($user,$day){
        $user = $user;
        $current_subscription = $user->current_boxes()->first();
        $currentPlan = plans::findOrFail($current_subscription->plan);
        $currentPlanCategories = $currentPlan->categories;
        $cats = main_categories::all();
        $order = [];
        foreach($currentPlanCategories as $cat){
            $products = $cat->category_obj->products()->whereHas('days',function($q) use($day){
                 $q->where('day',(String)$day);
            })->where('status','Hot')->get();
            $countOfProducts = count($products);
            $item = [];
            if($countOfProducts > 0){
                foreach($products as $product){
                    if(count($item) < $cat->qty){
                        $item[] = $product['id'];
                    }else{
                        break;
                    }
                }
                $order[] = ['category' => $cat->category, 'products' => $item];
            }
          
        }
        return (count($order) > 0) ? $order : null;
    }
    public function generateRandomProduct($randomProduct,$item,$products){
        while(in_array($randomProduct,$item)){
            $randomProduct = $products[rand(0, count($products) - 1)]['id'];
         }
        return $randomProduct;
                    
    }
  
}