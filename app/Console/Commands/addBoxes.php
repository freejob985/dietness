<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscriptions;
use App\Models\User;
use App\Models\boxes;
use \Carbon\Carbon;
use App\Models\Helper;
class addBoxes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'boxes:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command will check if user have valid subscription to update boxes value';

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
        \Log::info('Hi !!');
        $getActiveBoxes = boxes::get();
        foreach($getActiveBoxes as $box){
            $user = User::find($box->user);
            $days = 0;
            $userSubscriptions = Subscriptions::where('status','Created')->where('user',$user->id)->get();
            if(count($userSubscriptions) > 0){
                if(Helper::getRemainingBoxes($user->id) == 0 || Carbon::now()->gte(Carbon::parse($box->to))){
                    $lastPackage = 0;
                    $lastPlan = 0;
                    foreach($userSubscriptions as $subscription){
                        $days += Helper::CalculateSubscriptionDays($subscription->plan) - 1;
                        $subscription->status = 'Approved';
                        $subscription->approved_at = Carbon::now();
                        $subscription->save();
                        $lastPackage = $subscription->package;
                        $lastPlan = $subscription->plan;
                    }
                    $box->package = $lastPackage;
                    $box->plan = $lastPlan;
                    if(Carbon::now()->lt(Carbon::parse($box->to))){
                        $box->to = Carbon::parse($box->to)->addDays(Helper::CalculateSubscriptionDays($lastPlan));
                    }else{
                        $box->from = Carbon::now()->addDays(3);
                        $box->to = Carbon::parse($box->to)->addDays(Helper::CalculateSubscriptionDays($lastPlan));
                    }
                    $box->save();
                }
            }
        }
    }
}
