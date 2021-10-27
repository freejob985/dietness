<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Helper;
use App\Models\packages;
use App\Models\main_categories;
use App\Models\User_otp;
use App\Models\Payments;
use App\Models\Subscriptions;
use App\Models\plans;
use App\Models\products;
use App\Models\Orders;
use App\Models\boxes;
use App\Models\Addresses;
use App\Models\restricted_orders;
use App\Models\Sliders;
use App\Models\Governorates;
use App\Models\settings;
use App\Models\plan_categories;
use App\Mail\ResetPassword;
use App\Mail\NewRegisters;
use App\Mail\Order;
use Illuminate\Support\Facades\Mail;
use Validator;
use Exception;
use Hash;
use Illuminate\Support\Facades\App;
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
class UserController extends Controller
{
    private $UserApi = 'UserApi';
    private $otp_count_per_user_per_day = 20;
    private $count_freezedLimit = 5;
    private $users_can_register = 0;
    public function __construct(){
        $this->users_can_register = settings('number_of_users');
    }
    public function CreateNewUser(Request $request){
        try{
            $messages = [
                'name.required' => __('messages.CreateUser.name_required'),
                'name.string' => __('messages.CreateUser.name_string'),
                'name.min' => __('messages.CreateUser.name_min'),
                'name.max' => __('messages.CreateUser.name_max'),
                'mobile.required' => __('messages.CreateUser.mobile_required'),
                'code.required' => __('messages.CreateUser.code_required'),
                'email.required' => __('messages.CreateUser.email_required'),
                'email.email' => __('messages.CreateUser.email_email'),
                'email.max' => __('messages.CreateUser.email_max'),
                'email.unique' => __('messages.CreateUser.email_unique'),
                'password.required' => __('messages.CreateUser.password_required'),
                'password.min' => __('messages.CreateUser.password_min'),
                'password.max' => __('messages.CreateUser.password_max'),
                'verify_password.required' => __('messages.CreateUser.verify_password_required'),
                'verify_password.same' => __('messages.CreateUser.verify_password_same'),
            ];
            $rules = [
                'name' => 'required|string|min:8|max:32',
                'mobile' => 'required|unique:users,mobile,NULL,id,deleted_at,NULL',
                'email' => 'required|email|max:32|unique:users,email,NULL,id,deleted_at,NULL',
                'password' => 'required|min:1|max:32',
                'verify_password' => 'required|same:password',
                'code' => 'required',
                'plandId' => 'required|exists:plans,id',
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
            }
            $planId = $request->plandId;
            $plan = plans::findOrFail($planId);
            $package = packages::findOrFail($plan->package);
            $price = $plan->price;
            $new_User = new User();
            $new_User->name = $request->name;
            $new_User->mobile = $request->mobile;
            $new_User->country_code = $request->code;
            $new_User->email = $request->email;
            $new_User->package = $package->id;
            $new_User->plan = $plan->id;
            $new_User->price = $price;
            $new_User->password = Hash::make($request->password);
            $new_User->save();
            $new_User->refresh();
            $otp_code = $this->generateOtp();
            $new_User_otp = new User_otp();
            $new_User_otp->user = $new_User->id;
            $new_User_otp->otp = $otp_code;
            $new_User_otp->otp_type = 'mobile';
            $new_User_otp->expiry_date = Carbon::now()->addDays(30);
            $new_User_otp->save();
            $new_User_otp->refresh();
            $message = 'Your Activation code : ' . $otp_code;
            $this->Send_sms($message,$new_User->country_code.$new_User->mobile);
            $token = auth()->guard('users')->login($new_User);
            return Helper::return([
                'user' => $new_User,
                'token' => $token,
                'message' => __('messages.CreateUser.successed_added'),
            ]);
        }
        catch(Exception $e){
           return $e;
        }
    }
    public function resendOtp(Request $request){
        $check_user = auth()->guard('users')->user();
        if($check_user){
            $otp_code = $this->generateOtp();
            $new_User_otp = new User_otp();
            $new_User_otp->user = $check_user->id;
            $new_User_otp->otp = $otp_code;
            $new_User_otp->otp_type = 'mobile';
            $new_User_otp->expiry_date = Carbon::now()->addDays(30);
            $new_User_otp->save();
            $new_User_otp->refresh();
            $message = 'Your Activation code : ' . $otp_code;
            $this->Send_sms($message,$check_user->country_code.$check_user->mobile);
             return Helper::return([
            ]);
        }
    }
    private function Send_sms($message,$phone){
       $basic  = new \Nexmo\Client\Credentials\Basic(env('NEXMO_KEY'), env('NEXMO_SECRET'));
       $client = new \Nexmo\Client($basic);
       $message = $client->message()->send([
            'to' => $phone,
            'from' => 'Dietnesskw',
            'text' => $message,
            'type' => 'unicode'
       ]);
    }
    public function verify_otp_is_first(Request $request){
        try{
         $messages = [
                'emailOrmobile.required' => __('messages.LoginUser.emailOrmobile_required'),
            ];
            $rules = [
                'otp' => 'required',
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
            }
        $check_user = auth()->guard('users')->user();
         if($check_user){
            $check_otp = $check_user->User_otp()->where('status','created')->latest()->first();
            if($check_otp && $check_otp->otp == $request->otp && Carbon::parse($check_otp->expiry_date)->gt(Carbon::now())){
                Mail::to('info@dietnesskw.com')->send(new NewRegisters($check_user));
                if($check_user->email){
                    Mail::to($check_user->email)->send(new NewRegisters($check_user));
                }
                if(settings('skip_activation') == 'on'){
                    $check_user->status = 'Active';
                }
                else{
                    $check_user->status = 'Pending';
                }
                $check_user->save();
                $check_otp->status = 'verified';
                $check_otp->save();
                $check_user->refresh();
                $usersCount = User::count();
                $dataToReturn = [
                  'account_status' => $check_user->status,
            ];
                
                if($usersCount > $this->users_can_register){
                    $dataToReturn['message'] = settings('message_of_limit_users');
                }
                return Helper::return($dataToReturn);
            }else{
                return Helper::returnError(__('messages.verify_otp_as_first_error')); 
            }
        }
        }catch(Exception $e){
            return $e;
            return Helper::returnError(Helper::returnException($e)); 
        }
    }
     public function verify_otp_forget(Request $request){
         try{
        $messages = [
                'emailOrmobile.required' => __('messages.LoginUser.emailOrmobile_required'),
            ];
            $rules = [
                'emailOrmobile' => 'required',
                'code' => 'nullable',
                'otp' => 'required',
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
            }
          $check_user = new User();
          $check_user = $check_user->where(function($query) use($request){
                $query->where('email',$request->emailOrmobile);
                return $query;
            })->Orwhere(function($query) use($request){
                $query->where('country_code',$request->code);
                $query->where('mobile',$request->emailOrmobile);
                return $query;
            })->first();
         if($check_user){
            $check_otp = $check_user->User_otp()->where('status','created')->latest()->first();
            if($check_otp && $check_otp->otp == $request->otp && Carbon::parse($check_otp->expiry_date)->gt(Carbon::now())){
                return Helper::return([
            ]);
            }else{
                return Helper::returnError(__('messages.verify_otp_as_first_error')); 
            }
        }
         }catch(Exception $e){
            return Helper::returnError(Helper::returnException($e)); 
        }
    }
    public function new_password_with_forget_otp(Request $request){
        try{
        $messages = [
                'emailOrmobile.required' => __('messages.LoginUser.emailOrmobile_required'),
            ];
            $rules = [
                'emailOrmobile' => 'required',
                'code' => 'nullable',
                'otp' => 'required',
                'password' => 'required|min:1|max:32',
                'verify_password' => 'required|same:password',
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
            }
          $check_user = new User();
          $check_user = $check_user->where(function($query) use($request){
                $query->where('email',$request->emailOrmobile);
                return $query;
            })->Orwhere(function($query) use($request){
                $query->where('country_code',$request->code);
                $query->where('mobile',$request->emailOrmobile);
                return $query;
            })->first();
         if($check_user){
            $check_otp = $check_user->User_otp()->where('status','created')->latest()->first();
            if($check_otp && $check_otp->otp == $request->otp && Carbon::parse($check_otp->expiry_date)->gt(Carbon::now())){
               $check_user->password = Hash::make($request->password);
               $check_user->save();
               $check_otp->status = 'verified';
               $check_otp->save();
               return Helper::return([]);
            }else{
                return Helper::returnError(__('messages.verify_otp_as_first_error')); 
            }
        }
        }catch(Exception $e){
            return Helper::returnError(Helper::returnException($e)); 
        }
    }
    
    public function forget_password(Request $request){
        try{
         $messages = [
                'emailOrmobile.required' => __('messages.LoginUser.emailOrmobile_required'),
            ];
            $rules = [
                'emailOrmobile' => 'required',
                'code' => 'nullable',
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
            }
          $check_user = new User();
          $check_user = $check_user->where(function($query) use($request){
                $query->where('email',$request->emailOrmobile);
                return $query;
            })->Orwhere(function($query) use($request){
                $query->where('country_code',$request->code);
                $query->where('mobile',$request->emailOrmobile);
                return $query;
            })->first();
            if(!$check_user){
                return Helper::returnError(__('messages.LoginUser.userNotFound')); 
            }
            if($check_user->status == 'Deleted'){
                return Helper::returnError(__('messages.LoginUser.userNotFound'));
            }
             if($check_user->status == 'Blocked'){
                return Helper::returnError(__('messages.LoginUser.userBlocked'));
            }
           $user_User_otp_count = $check_user->User_otp_count()->whereBetween('created_at',[Carbon::now()->startOfDay(),Carbon::now()])->where(function($query){
               $query->where('status','created');
               $query->orWhere('status','verified');
               return $query;
           })->count();
           if($this->otp_count_per_user_per_day <= $user_User_otp_count){
               return Helper::returnError(__('messages.day_messages_exceeded'));
           }
           $otp_code = $this->generateOtp();
           $forget_type = ($check_user->email == $request->emailOrmobile) ? 'email' : 'mobile';
           $new_User_otp = new User_otp();
           $new_User_otp->user = $check_user->id;
           $new_User_otp->otp = $otp_code;
           $new_User_otp->otp_type = $forget_type;
           $new_User_otp->expiry_date = Carbon::now()->addMinutes(10);
           $new_User_otp->save();
           if($forget_type == 'mobile'){
               $message = 'Your reset code : ' . $otp_code;
               $this->Send_sms($message,$check_user->country_code.$check_user->mobile);
               $result = __('messages.forget_password_sent_to_mobile');
           }elseif($forget_type == 'email'){
               Mail::to($check_user->email)->send(new ResetPassword($otp_code));
               $result = __('messages.forget_password_sent_to_email');
           }
        return Helper::return([
                'message' => $result,
            ]);
    }catch(Exception $e){
            return Helper::returnError(Helper::returnException($e)); 
        }
        
           
    }
    public function LoginUser(Request $request){
        try{
            $messages = [
                'emailOrmobile.required' => __('messages.LoginUser.emailOrmobile_required'),
                'password.required' => __('messages.LoginUser.password_required'),
                'password.min' => __('messages.LoginUser.password_min'),
                'password.max' => __('messages.LoginUser.password_max'),
            ];
            $rules = [
                'emailOrmobile' => 'required',
                'password' => 'required|min:1|max:32',
                'code' => 'nullable',
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
            }
            $check_user = new User();
            $check_user = $check_user->where(function($query) use($request){
                $query->where('email',$request->emailOrmobile);
                return $query;
            })->Orwhere(function($query) use($request){
                $query->where('country_code',$request->code);
                $query->where('mobile',$request->emailOrmobile);
                return $query;
            })->first();
            if(!$check_user){
                return Helper::returnError(__('messages.LoginUser.userNotFound')); 
            }
            if($check_user->status == 'Deleted'){
                return Helper::returnError(__('messages.LoginUser.userNotFound'));
            }
             if($check_user->status == 'Blocked'){
                return Helper::returnError(__('messages.LoginUser.userBlocked'));
            }
            if(!Hash::check($request->password,$check_user->password)){
                return Helper::returnError(__('messages.LoginUser.userWrongPassword'));
            }
            $token = auth()->guard('users')->login($check_user);
            return Helper::return([
                'user' => $check_user,
                'token' => $token,
                'current_subscription' => $check_user->current_subscription
            ]);
        }
        catch(Exception $e){
            return Helper::returnError(Helper::returnException($e)); 
        }
    }
        public function packages_withCustom(){
        try{
        $packages = packages::with('plans')->orderBy('order')->get();
        $packages = collect($packages)->map(function($package){
            $package['name'] = $package->title;
            $package['plans'] = collect($package['plans'])->map(function($item){
                $item['description'] = $item->disc;
                unset($item['description_en']);
                unset($item['description_ar']);
                $item['plan_categories'] = collect($item->categories)->map(function ($cats){
                    $cats['category'] = $cats->category_obj;
                    $cats['category']['name'] = $cats->category_obj->title;
                    unset($cats['id']);
                    unset($cats['category']['title_en']);
                    unset($cats['category']['title_ar']);
                    unset($cats['category_obj']);
                    unset($cats['plan']);
                    return $cats;
                });
                unset($item['package']);    
                unset($item['plan_categories']);    
                unset($item['plan_obj']);    
                return $item;
            });
            unset($package['title_en']);
            unset($package['title_ar']);
            return $package;
        });
        return Helper::return([
                'packages' => $packages,
                'image_url' => url('/uploads/packages')
            ]);
    }
     catch(Exception $e){
            return Helper::returnError(Helper::returnException($e)); 
        }
    }
    public function packages(){
        try{
        $packages = packages::with('plans')->where('type','normal')->orderBy('order')->get();
        $packages = collect($packages)->map(function($package){
            $package['name'] = $package->title;
            $package['plans'] = collect($package['plans'])->map(function($item){
                $item['description'] = $item->disc;
                unset($item['description_en']);
                unset($item['description_ar']);
                $item['plan_categories'] = collect($item->categories)->map(function ($cats){
                    $cats['category'] = $cats->category_obj;
                    $cats['category']['name'] = $cats->category_obj->title;
                    unset($cats['id']);
                    unset($cats['category']['title_en']);
                    unset($cats['category']['title_ar']);
                    unset($cats['category_obj']);
                    unset($cats['plan']);
                    return $cats;
                });
                unset($item['package']);    
                unset($item['plan_categories']);    
                unset($item['plan_obj']);    
                return $item;
            });
            unset($package['title_en']);
            unset($package['title_ar']);
            return $package;
        });
        return Helper::return([
                'packages' => $packages,
                'image_url' => url('/uploads/packages')
            ]);
    }
     catch(Exception $e){
            return Helper::returnError(Helper::returnException($e)); 
        }
    }
    public function getSliders(){
        $getSliders = Sliders::select('image')->get();
         return Helper::return([
                'sliders' => $getSliders,
                'image_url' => url('/uploads/sliders')
            ]);
    }
    public function categories(Request $request){
        try{
               $messages = [
          
            ];
            $rules = [
                    'day' => 'nullable|date_format:Y-m-d'
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
            }
            $day_val = $request->get('day');
            $day = Carbon::parse($day_val)->dayOfWeek;
            $user = auth()->guard('users')->user();
            $current_subscription = ($user) ? ($user->current_boxes) ? $user->current_boxes : null : null;
            if($day_val){
                $main_categories = main_categories::whereHas('products',function($q) use($day){
                    return $q->whereHas('days',function($q) use($day){
                        return $q->where('day',(String)$day);
                    });
                });
                if($current_subscription){
                    $main_categories = $main_categories->whereHas('plan_categories',function($q) use($current_subscription){
                    return $q->where('plan',$current_subscription->plan)->where('qty','!=',0);
                });
                }
                
                $main_categories = $main_categories->get();
            }else{
                $main_categories = new main_categories();
                if($current_subscription){
                    $main_categories = $main_categories->whereHas('plan_categories',function($q) use($current_subscription){
                    return $q->where('plan',$current_subscription->plan)->where('qty','!=',0);
                });
                }
                $main_categories = $main_categories->get();
            }
            $main_categories = collect($main_categories)->map(function ($category) use ($day_val){
            $category['name'] = $category->title;
            $day = Carbon::parse($day_val)->dayOfWeek;
            if($day_val){
                $products = products::where('category',$category['id'])->with('days')->whereHas('days',function($q) use($day){
                return $q->where('day',(String)$day);
                })->where('status','!=','NotAvailable')->get();
            }else{
                $products = products::where('category',$category['id'])->where('status','!=','NotAvailable')->get();
            }
            
            $category['products'] = $products;
            $category['products'] = collect($category['products'])->map(function ($product){
                    $product['title'] = $product->name;
                    $product['desc'] = $product->description;
                    unset($product['name_en']);
                    unset($product['name_ar']);
                    unset($product['description_en']);
                    unset($product['description_ar']);
                    return $product;
                });
            unset($category['title_en']);
            unset($category['title_ar']);
            return $category;
        });
        return Helper::return([
                'categories' => $main_categories,
                'image_url' => url('/uploads/products')
            ]);
    }
         catch(Exception $e){
             return $e;
            return Helper::returnError(Helper::returnException($e)); 
        }
    }
    public function payment(Request $request){
        try{
              $messages = [
          
            ];
            $comments = $request->Comments;
            $arrayString = explode('-',$comments);
            $request['packageId'] = $arrayString[0];
            $request['planId'] = $arrayString[1];
            $request['userId'] = $arrayString[2];
            $rules = [
                'InvoiceId' => 'required|unique:payments,charge_id',
                'packageId' => 'required|exists:packages,id',
                'planId' => 'required|exists:plans,id,package,'.$request['packageId'],
                'userId' => 'required|exists:users,id',
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
            }
            $user = auth()->guard('users')->user();
            if($user->id != $request['userId']){
                return Helper::returnError(__('messages.faild_payment')); 
            }
            $package = $request['packageId'];
            $plan = $request['planId'];
            $payment_response = Helper::verifyInvoiceMyFatoorah($request->InvoiceId);
            $package = packages::findOrFail($package);
            $plan = plans::findOrFail($plan);
            if($payment_response && $plan){
                $payment_status = $payment_response->Data->InvoiceStatus; 
                $payment_amount = $payment_response->Data->InvoiceValue;
                if($plan->price == $payment_amount && $payment_status == 'Paid'){
                    $data = ['amount' => $plan->price , 'user' => $user->id , 'package' => $package->id , 'plan' => $plan->id , 'payment_response' => $payment_response];
                    $res = Helper::CreateSubscriptions($data);
                    if($res->status == 'Approved'){
                        $message = __('messages.done_payment');
                    }else{
                        $message = __('messages.done_payment_need_approve');
                    }
                     return Helper::return([
                        'message' => $message,
                    ]);
                }else{
                    return Helper::returnError(__('messages.faild_payment')); 
                }
            }else{
                return Helper::returnError(__('messages.faild_payment')); 
            }
        }
        catch(Exception $e){ 
            return Helper::returnError(Helper::returnException($e));
        }
    }
    public function getRemainingBoxes(){
        $user = auth()->guard('users')->user();
        $boxes_obj = boxes::where('user',$user->id)->first();
        if(!$boxes_obj){
            return Helper::return([
                        'days' => 0,
                        'from' => null,
                        'to' => null,
                    ]);
        }
        $from = Carbon::parse($boxes_obj->from)->endOfDay();
        $to = Carbon::parse($boxes_obj->to)->endOfDay();
        $remain_days = Helper::getRemainingBoxes($user->id);
        return Helper::return([
                        'days' => $remain_days,
                        'from' => $from,
                        'to' => $to,
                    ]);
    }
    public function get_custom_remaining_boxes($id){
        //return Helper::CalculateSubscriptionDays(7);
        $user = User::find($id);
        $boxes_obj = boxes::where('user',$user->id)->first();
        if(!$boxes_obj){
            return Helper::return([
                        'days' => 0,
                        'from' => null,
                        'to' => null,
                    ]);
        }
        $from = Carbon::parse($boxes_obj->from);
        $to = Carbon::parse($boxes_obj->to);
        $remain_days = Helper::getRemainingBoxes($user->id);
        return Helper::return([
                        'days' => $remain_days,
                        'from' => $from,
                        'to' => $to,
                    ]);
    }
    public function order(Request $request){
        $user = auth()->guard('users')->user();
        $current_subscription = $user->current_boxes()->first();
        $get_plan_category_count = plan_categories::where('plan',$current_subscription->plan)->where('min','!=',0)->count();
        $get_plan_category_real_count = plan_categories::where('plan',$current_subscription->plan)->count();
        $messages = [
          'items.min' => __('messages.order.items_min',['count' => $get_plan_category_count]),
        ];
        $day = Carbon::parse($request->day);
        \Log::info($request['items']);
        $rules = [
            'day' => 'required',
            'items' => 'required|array|min:'.$get_plan_category_count.'|max:'.$get_plan_category_real_count,
            'items.*.category' => 'required|exists:main_categories,id',
            'items.*.products' => ['required','array',function($attribute, $value, $fail) use($request,$get_plan_category_count,$get_plan_category_real_count){
                    $get_index = explode('.',$attribute)[1];
                    $get_category = $request['items'][$get_index]['category'];
                    $user = auth()->guard('users')->user();
                    $current_subscription = $user->current_boxes()->first();
                    $count_products = count($value);
                    $get_plan_category = plan_categories::where('category',$get_category)->where('plan',$current_subscription->plan)->first();
                    $get_main_category = main_categories::find($get_category);
                    if($count_products < $get_plan_category->max && ($get_plan_category_real_count != count($request['items']))){
                        return $fail(__('messages.order.item_products_min_count',['item' => $get_main_category->title , 'min' => $get_plan_category->max]));
                    } 
                    if($count_products < $get_plan_category->min){
                        return $fail(__('messages.order.item_products_min_count',['item' => $get_main_category->title , 'min' => $get_plan_category->min]));
                    } 
                    if($count_products > $get_plan_category->qty  && ($get_plan_category_real_count == count($request['items']))){
                        return $fail(__('messages.order.item_products_max_count',['item' => $get_main_category->title , 'max' => $get_plan_category->qty]));
                    }
            }],
            'items.*.products.*' => 'required|exists:products,id|exists:products_days,product,day,'.$day->dayOfWeek,
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return Helper::returnError($validator->errors()->first()); 
        }
        $user = auth()->guard('users')->user();
        
        if($day->dayOfWeek == Carbon::FRIDAY){
            return Helper::returnError(__('messages.order_in_friday'));
        }
        if($user->address == null){
            return Helper::returnError(__('messages.update_address'));
        }
        $check_freeze = restricted_orders::where('user',$user->id)->whereDate('day',$day)->first();
        if($check_freeze){
            return Helper::returnError(__('messages.already_freeze_day'));
        }
        $items = serialize($request->items);
        $current_subscription = $user->current_boxes()->first();
        if(!$current_subscription){
            return Helper::returnError(__('messages.user_have_no_subscription'));
        }
        $from = Carbon::parse($current_subscription->from)->startOfDay();
        $to = Carbon::parse($current_subscription->to)->endOfDay();
        if($day->gte($from) && $day->lte($to)){
            $check_order = Orders::where('user',$user->id)->whereDate('day',$day)->first();
            if(!$check_order){
                $driver = ($user->address) ? ($user->address->driverObj) ? $user->address->driverObj->driver : null : null;
                $data = ['user' => $user->id , 'plan' => $current_subscription->plan , 'package' => $current_subscription->package , 'day' => $day , 'items' => $items , 'driver' => $driver];
                $order = Helper::CreateOrder($data);
                //Mail::to($user->email)->send(new Order(['order' => $order,'user' => $user]));
                return Helper::return([]);
            }else{
               return Helper::returnError(__('messages.exists_booking'));  
            }
        }else{
            return Helper::returnError(__('messages.faild_book_order')); 
        }
        
        
    }
    public function getOrderByDay(Request $request){
         $user = auth()->guard('users')->user();
         $day = Carbon::parse($request->get('day'));
         $check_order = Orders::with('package','plan')->where('user',$user->id)->whereDate('day',$day)->first();
         if($check_order){
             return Helper::return(['order' => $check_order]);
         }else{
             return Helper::returnError(__('messages.no_booking_in_day')); 
         }
    }
    public function get_profile(){
        $user = auth()->guard('users')->user();
        $user['address'] = $user->address;
        return Helper::return(['profile' => $user]);
    }
    public function UpdateProfile(Request $request){
        try{
            $user = auth()->guard('users')->user();
              $messages = [
          
            ];
            $rules = [
                'name' => "required|string|min:8|max:32",
                'mobile' => "required|unique:users,mobile,{$user->id},id",
                'email' => "required|email|max:32|unique:users,email,{$user->id},id",
                'birth' => "nullable",
                'delivery_timeframe' => "nullable|in:AM,PM",
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
            }  
            $user->name = $request->name;
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $user->birth = $request->birth;
            $user->delivery_timeframe = $request->delivery_timeframe;
            $user->save();
            return Helper::return(['message' => __('messages.profile_updated')]);
        }
        catch(Exception $e){
            return Helper::returnError(Helper::returnException($e)); 
        }
    }
    public function UpdateUserDelieveryTimeframe(Request $request){
        try{
            $user = auth()->guard('users')->user();
              $messages = [
          
            ];
            $rules = [
                'delivery_timeframe' => "required|in:AM,PM",
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
            }  
            $user->delivery_timeframe = $request->delivery_timeframe;
            $user->save();
            return Helper::return(['message' => __('messages.profile_updated')]);
        }
        catch(Exception $e){
            return Helper::returnError(Helper::returnException($e)); 
        }
    }
    public function UpdatePassword(Request $request){
            try{
            $user = auth()->guard('users')->user();
            $messages = [
          
            ];
            $rules = [
             'current_password' => 'required|min:6|max:32',
             'password' => 'required|min:1|max:32',
             'verify_password' => 'required|same:password',
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
            }  
            $check_password = Hash::check($request->current_password,$user->password);
            if(!$check_password){return Helper::returnError(__('messages.wrong_current_password')); }
            $user->password = Hash::make($request->password);
            $user->save();
            return Helper::return(['message' => __('messages.password_updated')]);    
        }
        catch(Exception $e){
            return Helper::returnError(Helper::returnException($e)); 
        }
    }
    public function createAddress($request){
        $new_address = new Addresses();
        $new_address->fill($request->all(['user','country','governorate','region','piece','street','avenue','house','floor','flat','notes','lat','lng']))->save();
    }
    public function add_address(Request $request){
            $messages = [
          
            ];
            $rules = [
              'country' => 'required',
              'governorate' => 'required|exists:governorates,id',
              'region' => 'required|exists:cities,id',
              'piece' => 'nullable',
              'street' => 'nullable',
              'avenue' => 'nullable',
              'house' => 'nullable',
              'floor' => 'nullable',
              'flat' => 'nullable',
              'flat' => 'nullable',
              'notes' => 'nullable',
              'lat' => 'nullable',
              'lng' => 'nullable',
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
            } 
        $user = auth()->guard('users')->user();
        $check_address = $user->address;
        if($check_address){
            return Helper::returnError(__('messages.address_exists'));
        }
        $request['user'] = $user->id;
        $this->createAddress($request);
        return Helper::return(['message' => __('messages.address_added')]); 
    }
     public function change_address(Request $request){
         $messages = [
          
         ];
        $rules = [
              'country' => 'required',
              'governorate' => 'required|exists:governorates,id',
              'region' => 'required|exists:cities,id',
              'piece' => 'nullable',
              'street' => 'nullable',
              'avenue' => 'nullable',
              'house' => 'nullable',
              'floor' => 'nullable',
              'flat' => 'nullable',
              'flat' => 'nullable',
              'notes' => 'nullable',
              'lat' => 'nullable',
              'lng' => 'nullable',
            ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
        } 
        $user = auth()->guard('users')->user();
        $check_address = $user->address;
        if(!$check_address){
            $request['user'] = $user->id;
            $this->createAddress($request);
            return Helper::return(['message' => __('messages.address_changed')]); 
        }
        $this->updateAddress($request);
        return Helper::return(['message' => __('messages.address_changed')]); 
    }
     public function updateAddress($request){
        $user = auth()->guard('users')->user();
        $user->address->update($request->all(['country','governorate','region','piece','street','avenue','house','floor','flat','notes','lat','lng']));
    }
    private function generateOtp(){
        $otp = mt_rand(1111,9999);
        if(strlen($otp) == 3){
            $otp .= 9; 
        }
        return $otp;
    }
    public function getUserReservedDates(Request $request){
        $user = auth()->guard('users')->user();
        $dates = Orders::where('user', $user->id)->pluck('day')->toArray();
        $completed = Orders::where('user', $user->id)->where('status','COMPLETED')->pluck('day')->toArray();
        return Helper::return(['dates' => $dates, 'completed' => $completed]);
    }

    public function getUserRestrictedDates(Request $request){
        $user = auth()->guard('users')->user();
        $boxes_obj = boxes::where('user',$user->id)->first();
        if(!$boxes_obj){
            return Helper::return(['dates' => []]);
        }
        $from = Carbon::parse($boxes_obj->from)->startOfDay();
        $to = Carbon::parse($boxes_obj->to);
        $fridays = $this->getFridays($from,$to);
        $dates = $this->getFreezeDayPerMonth();
        $dates = array_merge($dates,$fridays);
        return Helper::return(['dates' => $dates]);
    }
    private function getFridays($from,$to){
        $period = CarbonPeriod::create($from,$to);
        $dates = [];
        foreach ($period as $date) {
            if($date->dayOfWeek == Carbon::FRIDAY){
                array_push($dates,$date->format('Y-m-d'));
            } 
        }
        return $dates;
    }
    public function removeUserOrder(Request $request){
        try{
            $messages = [
          
         ];
        $rules = [
              'day' => 'required|date_format:Y-m-d',
            ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
        } 
        $day = Carbon::parse($request->day);
        $user = auth()->guard('users')->user();
        $check_order = Orders::where('user',$user->id)->whereDate('day',$day)->first();
        if($check_order){
            $get_diff =  $day->endOfDay()->diffInDays(Carbon::now()->startOfDay());
            if($get_diff >= 3){
                $check_order->delete();
                return Helper::return(['message' => __('messages.order_deleted')]); 
            }else{
                return Helper::returnError(__('messages.not_allowed')); 
            }
        }else{
            return Helper::returnError(__('messages.not_allowed')); 
        }
        
    }
        catch(Exception $e){
            return Helper::returnError(Helper::returnException($e)); 
        }
    }
    public function CreateRestrictedDay(Request $request){
        try{
            $messages = [
          
         ];
        $rules = [
              'day' => 'required|date_format:Y-m-d',
            ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
        } 
        $day = Carbon::parse($request->day);
        $user = auth()->guard('users')->user();
        $current_subscription = $user->current_subscription()->where('status','Approved')->latest()->first();
        if(!$current_subscription){
            return Helper::returnError(__('messages.user_have_no_subscription'));
        }
        $from = Carbon::parse($current_subscription->from)->startOfDay();
        $to = Carbon::parse($current_subscription->to);
        if($day->gte($from) && $day->lte($to)){
            $check_order = Orders::where('user',$user->id)->whereBetween('day',[$day->startOfDay(),$day->endOfDay()])->first();
            if(!$check_order){
                $check_freeze = restricted_orders::where('user',$user->id)->whereDate('day',$day)->first();
                if(!$check_freeze){
                    $count_freezedPerMonth = $this->countFreezeDayPerMonth();
                    if($count_freezedPerMonth < $this->count_freezedLimit){
                        $this->freezeDay($day);
                        return Helper::return([]);
                    }else{
                        return Helper::returnError(__('messages.freezed_days_exceed')); 
                    }
                }else{
                   return Helper::returnError(__('messages.already_freeze_day'));  
                }
            }else{
               return Helper::returnError(__('messages.exists_booking'));  
            }
        }else{
            return Helper::returnError(__('messages.faild_freeze_day')); 
        }
        }
        catch(Exception $e){
            return Helper::returnError(Helper::returnException($e)); 
        }
    }
    public function unFreezeDay(Request $request){
        try{
               $messages = [
          
                 ];
                $rules = [
                      'day' => 'required|date_format:Y-m-d',
                    ];
                $validator = Validator::make($request->all(),$rules,$messages);
                if($validator->fails()){
                        return Helper::returnError($validator->errors()->first()); 
                } 
                $day = Carbon::parse($request->day);
                $user = auth()->guard('users')->user();
                $check_freeze = restricted_orders::where('user',$user->id)->whereDate('day',$day)->first();
                if($check_freeze){
                        $check_freeze->delete();
                        $boxes_obj = boxes::where('user',$user->id)->first();
                        if($boxes_obj){
                            $boxes_obj->to = Carbon::parse($boxes_obj->to)->subDays(1);
                            $boxes_obj->save();
                        }
                        return Helper::return([]);
                }else{
                    return Helper::returnError(__('messages.freezed_day_un_freezed')); 
                }
        }
         catch(Exception $e){
            return Helper::returnError(Helper::returnException($e)); 
        }
    }
    private function freezeDay($day){
        $user = auth()->guard('users')->user();
        $current_subscription = $user->current_subscription()->where('status','Approved')->latest()->first();
        $new_restricted_day = new restricted_orders();
        $new_restricted_day->user = $user->id;
        $new_restricted_day->subscription = $current_subscription->id;
        $new_restricted_day->day = $day;
        $new_restricted_day->save();
        $boxes_obj = boxes::where('user',$user->id)->first();
        if($boxes_obj){
            $boxes_obj->to = Carbon::parse($boxes_obj->to)->addDays(1);
            $boxes_obj->save();
        }
    }
    private function countFreezeDayPerMonth(){
        $user = auth()->guard('users')->user();
        $current_subscription = $user->current_subscription()->where('status','Approved')->latest()->first();
        $from = Carbon::parse($current_subscription->from)->startOfDay();
        $to = Carbon::parse($current_subscription->to);
        $count_freezed = restricted_orders::where(['user'=>$user->id,'subscription' => $current_subscription->id])->whereBetween('day',[$from,$to])->count();
        return $count_freezed;
    }
     private function getFreezeDayPerMonth(){
        $user = auth()->guard('users')->user();
        $current_subscription = $user->current_subscription()->where('status','Approved')->latest()->first();
        $from = Carbon::parse($current_subscription->from)->startOfDay();
        $to = Carbon::parse($current_subscription->to);
        $resrticted_days = restricted_orders::where(['user'=>$user->id,'subscription' => $current_subscription->id])->whereBetween('day',[$from,$to])->pluck('day')->toArray();
        return $resrticted_days;
    }
    public function contact_mobile(){
        $mobile = settings('mobile');
        $message_to_renew = settings('message_to_renew');
        return Helper::return(['contact_mobile' => $mobile , 'message_to_renew' => $message_to_renew]);
    }
    public function getCities(){
        $governates = Governorates::with('cities','cities:id,governorate,name_en,name_ar')->select('id','name_en','name_ar')->get();
        collect($governates)->map(function($governate){
            $governate['title'] = $governate->name;
            unset($governate['name_en']);
            unset($governate['name_ar']);
            $governate['cities'] = collect($governate['cities'])->map(function($city){
                 $city['title'] = $city->name;
                 unset($city['name_en']);
                 unset($city['name_ar']);
                 return $city;
            });
            return $governate;
        });
        return Helper::return(['governates' => $governates]);
    }
    public function CalculateSubscriptionDays(){
        return Helper::CalculateSubscriptionDays(3);
    }
    public function test(){
        $boxes = boxes::whereDate('to','>=',Carbon::now()->startOfDay())->get();
        foreach($boxes as $box){
            $now = Carbon::now();
            $box_from = Carbon::parse($box->from);
            if($now->lt($box_from) && $box_from->diffInDays($now) == 1){
                  if(Carbon::now()->addDays(2)->gte($box_from)){
                      echo $box;
                  }
                  
            }
        }
        return '   -   ' . Carbon::now();
      //return   $this->bookOrderByAuto(User::find(120),0);
      //  $data = ['amount'=>20,'user' => 1,'package' => 13, 'plan' => 10 ,'payment_response' => []];
        //$remain_days = Helper::CalculateSubscriptionDays(12);
       // $remain_days = Helper::CreateNewSubscriptions($data);
       //return User::find(120)->current_boxes()->first();
       $remain_days = Helper::getRemainingBoxes(214);
        return Helper::return([
                        'days' => $remain_days
                    ]);
        return Carbon::now()->addDays(2)->format('Y-m-d');
        return new NewRegisters(User::find(61));
        $from = Carbon::parse('2021-07-24')->startOfDay();
        $to = Carbon::parse('2021-08-08');
        $day = Carbon::parse('2021-08-08');
        if($day->gte($from) && $day->lte($to)){
            return 'ok';
        }
        return $this->bookOrderByAuto(User::find(19),Carbon::parse('2021-07-21')->dayOfWeek);
        $user = auth()->guard('users')->user();
        $current_subscription = $user->current_subscription()->where('status','Approved')->latest()->first();
        $currentPlan = plans::findOrFail($current_subscription->plan);
        $currentPlanCategories = $currentPlan->categories;
        $cats = main_categories::all();
        $order = [];
        foreach($currentPlanCategories as $cat){
            $products = $cat->category_obj->products()->where('status','Hot')->get();
            $countOfProducts = count($products);
            $item = [];
            if($countOfProducts > 0){
                foreach($products as $product){
                    if(count($item) < $cat->qty){
                        $randomProduct = $products[rand(0, count($products) - 1)]['id'];
                        $item[] = $this->generateRandomProduct($randomProduct,$item,$products);
                    }else{
                        break;
                    }
                }
                $order[] = ['category' => $cat->category, 'products' => $item];
            }
          
        }
        $finalOrder = ['items' => $order];
        return (count($order) > 0) ? $finalOrder : null;
    }
    public function generateRandomProduct($randomProduct,$item,$products){
        while(in_array($randomProduct,$item)){
            $randomProduct = $products[rand(0, count($products) - 1)]['id'];
         }
        return $randomProduct;
                    
    }
    public function updateFcmToken(Request $request){
          $messages = [
          
         ];
        $rules = [
              'token' => 'required|string|min:5',
            ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
        } 
        $user = auth()->guard('users')->user();
        $fcmToken = $request->token;
        $user->fcmToken = $fcmToken;
        $user->save();
        return Helper::return([]);
    }
    public function get_main_settings(){
        $data = [];
        $data['status'] = 'success';
        $data['enable_delievery_timeframes'] = settings('enable_delievery_timeframes') ;
        $data['skip_activation'] = settings('skip_activation') ;
        $data['delivery_notes'] = settings('delivery_notes') ;
        $data['terms'] = settings('terms') ;
        $data['welcome_text'] = settings('welcome_text') ;
        return Helper::return($data);
    }
}
