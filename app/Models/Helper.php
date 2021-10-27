<?php

namespace App\Models;
use App\Models\Payments;
use App\Models\Subscriptions;
use App\Models\User;
use App\Models\boxes;
use App\Models\Orders;
use App\Models\plans;
use \Carbon\Carbon;
class Helper
{
   
    public static function return($result){
    return response()->json([
        'error_flag' => 0,
        'message' => 'success',
        'result'=> $result
      ],200);
    }
    public static function returnWithMessage($message,$result){
    return response()->json([
        'error_flag' => 0,
        'message' => $message,
        'result'=> $result
      ],200);
    }
    public static function returnError($result){
    return response()->json( [
        'error_flag'    => 1,
        'message'       => $result,
        'result'        => NULL,
      ],422);
    }
    public static function returnException($e){
        \Log::info($e);
        //return $e;
        return __('messages.server_error_message');
    }
    public static function CalculateSubscriptionDays($plan){
        $plan = plans::find($plan);
        $fixed_start = Carbon::now()->endOfDay()->addDays(3);
        if($fixed_start->dayOfWeek == 5){
            $fixed_start = $fixed_start->endOfDay()->addDays(1);
            $addedDays = Carbon::now()->addDays(4)->addDays($plan->days - 1);
            $addedDays2 = Carbon::now()->addDays(4)->addDays($plan->days - 1);
            $addedDays3 = Carbon::now()->addDays(4)->addDays($plan->days - 1);
            $addedDays4 = Carbon::now()->addDays(4)->addDays($plan->days - 1);
        }
        else{
            $addedDays = Carbon::now()->addDays(3)->addDays($plan->days - 1);
            $addedDays2 = Carbon::now()->addDays(3)->addDays($plan->days - 1);
            $addedDays3 = Carbon::now()->addDays(3)->addDays($plan->days - 1);
            $addedDays4 = Carbon::now()->addDays(3)->addDays($plan->days - 1);
        }
        $fridayCountfirst = $fixed_start->diffInDaysFiltered(function(Carbon $date) {
            return $date->dayOfWeek == Carbon::FRIDAY;
        }, $addedDays);
        $addedDaysFridayFirst = $addedDays3->addDays($fridayCountfirst);
        if($addedDaysFridayFirst->dayOfWeek == 5){
            $addedDaysFridayFirst->addDays(1);
        }
        $fridayCountsecond = $addedDays4->diffInDaysFiltered(function(Carbon $date) {
            return $date->dayOfWeek == Carbon::FRIDAY;
        }, $addedDaysFridayFirst);
        return $plan->days +  $fridayCountsecond + $fridayCountfirst;
    }
    
    public static function CreateNewSubscriptions($data){
        $amount = $data['amount'];
        $user = $data['user'];
        $package = $data['package'];
        $plan = $data['plan'];
        $subscription_days = Helper::CalculateSubscriptionDays($plan);
        $start_of_subscription = Carbon::now()->endOfDay()->addDays(3);
        $fixed_start = Carbon::now()->endOfDay()->addDays(3);
        
        if($fixed_start->dayOfWeek == 5){
            $fixed_start = $fixed_start->endOfDay()->addDays(1);
            $start_of_subscription = $start_of_subscription->endOfDay()->addDays(1);
        }
        $end_of_subscription = $fixed_start->addDays($subscription_days - 1)->endOfDay();
        $check_Subscriptions_count = Subscriptions::where('user',$user)->count();
        if($check_Subscriptions_count == 0){
            $User = User::findOrFail($user);
            $User->status = 'Active';
            $User->save();
            $data = ['user' => $user , 'package' => $package , 'plan' => $plan , 'from' => $start_of_subscription , 'to' => $end_of_subscription];
            self::CreateBoxes($data);
        }  
        $subscription_type = ($check_Subscriptions_count == 0) ? 'Approved' : 'Created';
        $new_Subscription = new Subscriptions();
        $new_Subscription->user = $user;
        $new_Subscription->package = $package;
        $new_Subscription->plan = $plan;
        $new_Subscription->amount = $amount; // current plan price
        $new_Subscription->from = $start_of_subscription;
        $new_Subscription->to = $end_of_subscription;
        $new_Subscription->status = $subscription_type;
        $new_Subscription->save();
        $new_Subscription->refresh();
        $payment_row = self::CreatePayment($user,$amount,$new_Subscription->id,'','','paid');
        return $new_Subscription;
    }
     public static function CreateSubscriptions($data){
         $amount = $data['amount'];
         $user = $data['user'];
         $package = $data['package'];
         $plan = $data['plan'];
         $payment_response = $data['payment_response'];
         $payment_id = $payment_response->Data->InvoiceReference;
         $charge_id = $payment_response->Data->InvoiceId;
         $subscription_days = Helper::CalculateSubscriptionDays($plan);
         $start_of_subscription = Carbon::now()->endOfDay()->addDays(3);
         $fixed_start = Carbon::now()->endOfDay()->addDays(3);
         if($fixed_start->dayOfWeek == 5){
            $fixed_start = $fixed_start->endOfDay()->addDays(1);
            $start_of_subscription = $start_of_subscription->endOfDay()->addDays(1);
         }
         $end_of_subscription = $fixed_start->addDays($subscription_days - 1)->endOfDay();
         $check_Subscriptions_count = Subscriptions::where('user',$user)->count();
         if($check_Subscriptions_count == 0){
             $User = User::findOrFail($user);
             $User->status = 'Active';
             $User->save();
             $data = ['user' => $user , 'package' => $package , 'plan' => $plan , 'from' => $start_of_subscription , 'to' => $end_of_subscription];
             self::CreateBoxes($data);
         }  
         $subscription_type = ($check_Subscriptions_count == 0) ? 'Approved' : 'Created';
         $new_Subscription = new Subscriptions();
         $new_Subscription->user = $user;
         $new_Subscription->package = $package;
         $new_Subscription->plan = $plan;
         $new_Subscription->amount = $amount; // current plan price
         $new_Subscription->from = $start_of_subscription;
         $new_Subscription->to = $end_of_subscription;
         $new_Subscription->status = $subscription_type;
         $new_Subscription->save();
         $new_Subscription->refresh();
         $payment_row = self::CreatePayment($user,$amount,$new_Subscription->id,$payment_id,$charge_id,'paid');
         return $new_Subscription;
     }
     public static function EditSubscriptions($data,$lastSubscription,$box){
         $amount = $data['amount'];
         $user = $data['user'];
         $package = $data['package'];
         $plan = $data['plan'];
         $subscription_days = Helper::CalculateSubscriptionDays($plan);
         $lastSubscription = $box;
         $start_of_subscription = Carbon::parse($lastSubscription->to)->addDays(1);
         $fixed_start = Carbon::parse($lastSubscription->to)->addDays(1);
         $end_of_subscription = $fixed_start->addDays($subscription_days - 1);
         $new_Subscription = new Subscriptions();
         $new_Subscription->user = $user;
         $new_Subscription->package = $package;
         $new_Subscription->plan = $plan;
         $new_Subscription->amount = $amount; // current plan price
         $new_Subscription->from = $start_of_subscription;
         $new_Subscription->to = $end_of_subscription;
         $new_Subscription->status = 'Created';
         $new_Subscription->save();
         $new_Subscription->refresh();
         if(Helper::getRemainingBoxes($user) == 0){
            $new_Subscription->status = 'Approved';
            $new_Subscription->save();
            $boxes_obj = boxes::where('user',$user)->first();
            if(Carbon::now()->lt(Carbon::parse($boxes_obj->to))){
                $boxes_obj->to = $end_of_subscription;
            }else{
                $boxes_obj->from = Carbon::now();
                $boxes_obj->to = $end_of_subscription;
            }
            $boxes_obj->save();
         }
         $payment_row = self::CreatePayment($user,$amount,$new_Subscription->id,'','','paid');
         return $new_Subscription;
     }
     public static function CreatePayment($user,$amount,$subscription,$payment_id,$charge_id,$status){
        $new_Payments = new Payments();
        $new_Payments->user = $user;
        $new_Payments->subscription = $subscription;
        $new_Payments->payment_id = $payment_id;
        $new_Payments->charge_id = $charge_id;
        $new_Payments->status = $status;
        $new_Payments->amount = $amount;
        $new_Payments->save();
        $new_Payments->refresh();
        return $new_Payments;
    }
     public static function verifyInvoice($charge){
         $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => env('Payment_url') .'/'.$charge,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => "{}",
          CURLOPT_HTTPHEADER => array(
            "authorization: Bearer " . env('TAP_KEY')
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
             $json = json_decode($response, true); 
            return $json;
        }
    }
    public static function verifyInvoiceMyFatoorah($invoiceid) {
            //$apiURL = 'https://apitest.myfatoorah.com';
            $apiURL = 'https://api.myfatoorah.com';
            $apiKey = 'HVVoZRB9ranDChJjfiuvB0m-Q7evCCUfMucV0z1zIom9PFYTHx_ePrbfWOP1_tDbd7aRcN6e2h0slxrUEjsvTKhMyFXMbo3TAHChbKVQuKtHhVpT9yYJQBKf0o_M1QqdZAAwjpHidWBLagYyuR_U-6RqgocRWNfJEPgB-Fb8wAcFO6lyuU0OSzV0zBdlFC0G2GEmODdt8C21gZUvdje2Wf_j4xiy5tWsnFOGJf_mfkboa19p5-wH_JYHvEurljtJDzht5qkEPI-eX8nzD8p-CyfJot0ga-f5NExSmuCXxk6fih9DLvLil-rPxAKPdNBhbTG513DzvwPiWd5F6pcLHmViWX-LCHSUjM4f5j4DFYvVgTneDESc-hijyHU1cCMpgyLNOoC_U_B4fgyDJ_9r_ruYTfIy2Tj88wDkAO7KKoMfUfdjkwKmCJmRDuBs-aYrAaqfdP3L8ztdLJYdNNALjbFY9Mbh4DIbhXGji3W5Gg6AYVIXrWzfGQhaLChUZGtDF6urkesIdQsBukeG5unvYie2OwzsbvzGYKp_vheGzXiDmfPpEOSSny07ojAIxRScrVOZd1qZ7oOXC_1oU8bYcRqr-uq9Yx8guRjj0G_DnZarHUJcyRyAdvGblMoBEeIDDlxD0OMffnwusTr4tbYoA0dNpdp8SEbN0rGn3nNoHHMO902XTLWQpZb80eO5rcxaCGIWzA';
            //$apiKey = 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL';
            $endpointURL = "$apiURL/v2/getPaymentStatus";
            $postFields = [
            'Key'     => $invoiceid,
            'KeyType' => "invoiceId"
            ];
            $curl = curl_init($endpointURL);
            curl_setopt_array($curl, array(
                CURLOPT_CUSTOMREQUEST  => 'POST',
                CURLOPT_POSTFIELDS     => json_encode($postFields),
                CURLOPT_HTTPHEADER     => array("Authorization: Bearer $apiKey", 'Content-Type: application/json'),
                CURLOPT_RETURNTRANSFER => true,
            ));
        
            $response = curl_exec($curl);
            $curlErr  = curl_error($curl);
        
            curl_close($curl);
        
            if ($curlErr) {
                //Curl is not working in your server
                die("Curl Error: $curlErr");
            }
        
            $error = (new self)->handleError($response);
            if ($error) {
                die("Error: $error");
            }
        
            return json_decode($response);
}
public function handleError($response) {

    $json = json_decode($response);
    if (isset($json->IsSuccess) && $json->IsSuccess == true) {
        return null;
    }

    //Check for the errors
    if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
        $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
        $blogDatas = array_column($errorsObj, 'Error', 'Name');

        $error = implode(', ', array_map(function ($k, $v) {
                    return "$k: $v";
                }, array_keys($blogDatas), array_values($blogDatas)));
    } else if (isset($json->Data->ErrorMessage)) {
        $error = $json->Data->ErrorMessage;
    }

    if (empty($error)) {
        $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
    }

    return $error;
}
     public static function CreateBoxes($data){
         $new_box = new boxes();
         $new_box->user = $data['user'];
         $new_box->package = $data['package'];
         $new_box->plan = $data['plan'];
         $new_box->from = $data['from'];
         $new_box->to = $data['to'];
         $new_box->save();
     }
    public static function getRemainingBoxes($userId){
        $boxes_obj = boxes::where('user',$userId)->first();
        $boxes = 0;
        if($boxes_obj){
            $from = Carbon::parse($boxes_obj->from);
            $to = Carbon::parse($boxes_obj->to);
            $now = Carbon::now()->startOfDay();
            if($now->lt(Carbon::parse($boxes_obj->from)->startOfDay())){
                $fridayCount = Carbon::parse($boxes_obj->from)->startOfDay()->diffInDaysFiltered(function(Carbon $date) {
                    return $date->dayOfWeek == Carbon::FRIDAY;
                }, Carbon::parse($boxes_obj->to)->endOfDay());
                $countOrders = Orders::where('user',$userId)->whereBetween('day',[Carbon::parse($boxes_obj->from)->startOfDay(),Carbon::parse($boxes_obj->to)->endOfDay()])->count();
                $pausedDays = restricted_orders::where('user',$userId)->whereBetween('day',[Carbon::parse($boxes_obj->from)->startOfDay(),Carbon::parse($boxes_obj->to)->endOfDay()])->count();
                $diff  = 0;
                $diff = Carbon::parse($boxes_obj->from)->startOfDay()->diffInDays(Carbon::parse($boxes_obj->to)->endOfDay()) + 1;
                $boxes = $diff  - $fridayCount - $countOrders - $pausedDays;
            }else{
                    if($now->lte(Carbon::parse($boxes_obj->to))){
                       $fridayCount = Carbon::parse($boxes_obj->from)->diffInDaysFiltered(function(Carbon $date) {
                            return $date->dayOfWeek == Carbon::FRIDAY;
                          }, Carbon::parse($boxes_obj->to));
                       $countOrders = Orders::where('user',$userId)->whereBetween('day',[Carbon::parse($boxes_obj->from)->startOfDay(),Carbon::parse($boxes_obj->to)->endOfDay()])->count();
                       $pausedDays = restricted_orders::where('user',$userId)->whereBetween('day',[Carbon::parse($boxes_obj->from)->startOfDay(),Carbon::parse($boxes_obj->to)->endOfDay()])->count();
                       $diff  = 0;
                       $diff = Carbon::parse($boxes_obj->from)->startOfDay()->diffInDays(Carbon::parse($boxes_obj->to)->endOfDay()) + 1;
                       $boxes = $diff  - $fridayCount - $countOrders - $pausedDays; 
                    }else{
                       $boxes = 0;
                    }
            }

        }
        else{
               $boxes = 0; 
        }
        return $boxes;
    }
    public static function CreateOrder($data){
        $user = $data['user'];
        $get_user = User::find($user);
        $package = $data['package'];
        $plan = $data['plan'];
        $day = $data['day'];
        $items = $data['items'];
        $driver = $data['driver'];
        $new_Order = new Orders();
        $new_Order->user = $user;
        $new_Order->package = $package;
        $new_Order->plan = $plan;
        $new_Order->day = $day;
        $new_Order->driver = $driver;
        $new_Order->items = $items;
        $new_Order->delivery_timeframe = $get_user->delivery_timeframe;
        $new_Order->save();
        return $new_Order;
    }
}
