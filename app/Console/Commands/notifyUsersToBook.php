<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\boxes;
use App\Models\Orders;
use App\Models\User;
use App\Models\userNotifiedToBook;
use \Carbon\Carbon;
use App\Traits\MainMethods;
class notifyUsersToBook extends Command
{
    use MainMethods;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:notifyUsersToBook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'will notify users to create order';

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
        
        $boxes = boxes::where('to','>=',Carbon::now()->startOfDay())->get();
        foreach($boxes as $box){
            $user = User::findOrFail($box->user);
            $current_subscription = $user->current_subscription()->where('status','Approved')->latest()->first();
            $now = Carbon::now();
            $from = Carbon::parse($box->from);
            $currentSubscriptionApprovedAt = Carbon::parse($current_subscription->approved_at);
            $to = Carbon::parse($box->to);
            $ordersCheck = Orders::where(['user' => $user->id])->whereBetween('day',[$from->startOfDay(),$to->endOfDay()])->count();
            $differenceBetweenNow = $now->diffInMinutes($currentSubscriptionApprovedAt);
            $checkUserHasNotified = userNotifiedToBook::where(['user' =>  $user->id, 'subscription' => $current_subscription->id])->count();
            if($checkUserHasNotified == 0){
                \Log::info("notifyUsersToBook");
                \Log::info($user);
                $new_userNotifiedToBook = new userNotifiedToBook();
                $new_userNotifiedToBook->user = $user->id;
                $new_userNotifiedToBook->subscription = $current_subscription->id;
                $new_userNotifiedToBook->save();
                $this->sendFCMByToken($user->fcmToken, 'برجاء اختيار وجبتك ليوم ' . Carbon::now()->addDays(3)->format('Y-m-d'), 'notifyOrder');
            }
            
            
        }
        
    }
}
