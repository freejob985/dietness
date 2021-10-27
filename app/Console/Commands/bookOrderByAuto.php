<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\boxes;
use App\Models\Orders;
use App\Models\restricted_orders;
use App\Models\User;
use App\Models\userNotifiedToBook;
use \Carbon\Carbon;
use App\Traits\MainMethods;
use Mail;
use App\Mail\Order;
class bookOrderByAuto extends Command
{
    use MainMethods;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:bookOrders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'will book orders to users if not take any action';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $boxes = boxes::where(function($query){
        //     $query->whereRaw('DATEDIFF(`from`,now()) <= ?')->setBindings([3])->orWhere('from','<',Carbon::now()->startOfDay());
        // })->whereDate('to','>=',Carbon::now()->startOfDay())->get();
        //whereRaw('DATEDIFF(`from`,now()) <= ?')->setBindings([3])->
        \Log::info('Tested');
        $boxes = boxes::whereDate('to','>=',Carbon::now()->startOfDay())->get();
        foreach($boxes as $box){
            $now = Carbon::now();
            $box_from = Carbon::parse($box->from);
            if($now->lt($box_from) && $box_from->diffInDays($now) == 1){
                
                $user = User::findOrFail($box->user);
                $current_subscription = $user->current_boxes()->first();
                $box_to = Carbon::parse($box->to)->startOfDay();
                $driver = ($user->address) ? ($user->address->driverObj) ? $user->address->driverObj->driver : null : null;
                $nowPlusDay = Carbon::now()->addDays(2);
                $checkHaveOrder = Orders::where(['user' => $user->id])->whereDate('day',$nowPlusDay)->first();
                $check_freeze = restricted_orders::where('user',$user->id)->whereDate('day',$nowPlusDay)->first();
                $getRandomOrder = $this->bookOrderByAuto($user,Carbon::now()->addDays(2)->dayOfWeek);
                if(!$check_freeze && !$checkHaveOrder && $getRandomOrder != null && Carbon::now()->addDays(2)->dayOfWeek != 5 && $user->address != null && Carbon::now()->addDays(2)->startOfDay()->lte($box_to) && Carbon::now()->addDays(2)->gte($box_from)){
                    \Log::info($box);
                    $new_order = new Orders();
                    $new_order->user = $user->id;
                    $new_order->package = $current_subscription->package;
                    $new_order->plan = $current_subscription->plan;
                    $new_order->day = Carbon::now()->addDays(2);
                    $new_order->driver = $driver;
                    $new_order->items = serialize($getRandomOrder);
                    $new_order->save();
                    Mail::to($user->email)->send(new Order(['order' => $new_order,'user' => $user]));
                    $this->sendFCMByToken($user->fcmToken, __('messages.autoOrder',['day' => Carbon::now()->addDays(2)]) , 'order');
                }
            }elseif($now->gte($box_from)){
                $user = User::findOrFail($box->user);
                $current_subscription = $user->current_boxes()->first();
                $box_to = Carbon::parse($box->to)->startOfDay();
                $driver = ($user->address) ? ($user->address->driverObj) ? $user->address->driverObj->driver : null : null;
                $nowPlusDay = Carbon::now()->addDays(2);
                $checkHaveOrder = Orders::where(['user' => $user->id])->whereDate('day','>=',$nowPlusDay->startOfDay())->whereDate('day','<=',$nowPlusDay->endOfDay())->first();
                $check_freeze = restricted_orders::where('user',$user->id)->whereDate('day','>=',$nowPlusDay->startOfDay())->whereDate('day','<=',$nowPlusDay->endOfDay())->first();
                $getRandomOrder = $this->bookOrderByAuto($user,Carbon::now()->addDays(2)->dayOfWeek);
                if(!$check_freeze && !$checkHaveOrder && $getRandomOrder != null && Carbon::now()->addDays(2)->dayOfWeek != 5 && $user->address != null && Carbon::now()->addDays(2)->startOfDay()->lte($box_to) && Carbon::now()->addDays(2)->gte($box_from)){
                    $new_order = new Orders();
                    $new_order->user = $user->id;
                    $new_order->package = $current_subscription->package;
                    $new_order->plan = $current_subscription->plan;
                    $new_order->day = Carbon::now()->addDays(2);
                    $new_order->driver = $driver;
                    $new_order->items = serialize($getRandomOrder);
                    $new_order->save();
                    Mail::to($user->email)->send(new Order(['order' => $new_order,'user' => $user]));
                    $this->sendFCMByToken($user->fcmToken, __('messages.autoOrder',['day' => Carbon::now()->addDays(2)]) , 'order');
                }
            }
           
            
            
        }
    }
}
