<?php

namespace App\Http\Controllers\driver;
use App\Models\main_categories;
use App\Models\products;
use App\Models\packages;
use App\Models\plans;
use App\Models\plan_categories;
use App\Models\package_plans;
use App\Models\User;
use App\Models\BroadCastMessages;
use App\Models\Drivers;
use App\Models\Subscriptions;
use App\Models\Sliders;
use App\Models\cities;
use App\Models\Orders;
use App\Models\Governorates;
use App\Models\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use \Carbon\Carbon;
use App\Events\Notify_user_status;
use App\Http\Resources\DriversOrders;
use App\Http\Resources\orders as ordersResource;
use Hash;
class driver extends Controller
{
    private $UserApi = 'driver';
    public function index(){
        return view('driver.home');
    }
    public function Login(Request $request){
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
            ];
            $validator = Validator::make($request->all(),$rules,$messages);
            if($validator->fails()){
                return Helper::returnError($validator->errors()->first()); 
            }
            $check_user = new Drivers();
            $check_user = $check_user->where(function($query) use($request){
                $query->where('email',$request->emailOrmobile);
                return $query;
            })->Orwhere(function($query) use($request){
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
            $token = auth()->guard('driver')->login($check_user);
            return Helper::return([
                'user' => $check_user,
                'token' => $token,
            ]);
        }
        catch(Exception $e){
            return Helper::returnError(Helper::returnException($e)); 
        }
    }
    public function orders(Request $request){
       $messages = [
        ];
        $rules = [
            'day' => 'nullable|date_format:Y-m-d',
            'status' => 'nullable|in:NEW,IN-ROUTE,COMPLETED',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return Helper::returnError($validator->errors()->first()); 
        }
        $day = $request->get('day');
        $status = $request->get('status');
        $where = [];
        $driver = auth()->guard('driver')->user()->id;
        $where[] = [function($query) use($driver){
            return  $query->where('driver',$driver);
        }];
        if($day){
            $day = Carbon::parse($day);
            $where[] = [function($query) use($day){return  $query->whereDate('day',$day);}];
        }
        if($status){
            $where[] = [function($query) use($status){return  $query->where('status',$status);}];
        }
        $getOrders = Orders::where($where)->with('userObj','userObj.address')->paginate();
        return Helper::return(new DriversOrders($getOrders));
        
    }
      public function orders_update_status(Request $request){
       $messages = [
        ];
        $rules = [
            'order' => 'required|exists:orders,id',
            'status' => 'required|in:NEW,IN-ROUTE,COMPLETED',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return Helper::returnError($validator->errors()->first()); 
        }
        $driver = auth()->guard('driver')->user()->id;
        $order = Orders::findOrFail($request->order);
        if($order->driver != $driver){
              return Helper::returnError(__('driver.orders_update_status_not_allowed')); 
         }
        $available_status = ['NEW','IN-ROUTE','COMPLETED'];
        $sent_status = $request->status;
        $current_status = $order->status;
        $sent_status_index = array_search($sent_status,$available_status);
        $current_status_index = array_search($current_status,$available_status);
        if($sent_status_index != $current_status_index+1){
            if(array_key_exists($current_status_index+1,$available_status)){
                return Helper::returnError(__('driver.expected_status') .' '. __('driver.orders.status.'.$available_status[$current_status_index+1])); 
            }else{
                return Helper::returnError(__('driver.orders_update_status_not_allowed')); 
            }
            
        }
        $order->status = $sent_status;
        $order->save();
        return Helper::returnWithMessage(__('driver.orders_update_status_success'),['status' => $order->status]);
    }
    
    public function get_orders(Request $request){
        if($request->ajax()){
              if(isset($request['pagination']['page'])){
                  $page = $request['pagination']['page'];
              }else{
                  $page = 1;
              }
              if(isset($request['pagination']['perpage'])){
                  $perpage = $request['pagination']['perpage'];
              }else{
                  $perpage = 50;
              }
              $where = [];
              $where[] = ['driver',auth()->guard('driver_web')->user()->id];
              $orders = Orders::with('userObj','userObj.address')->whereHas('userObj',function($query) use($request){
                   if(isset($request['query']['generalSearch'])){
                      $search = $request['query']['generalSearch'];
                      $query->where(function($query) use ($search){
                                    $query->where('name', 'LIKE', '%'.$search.'%');
                                    $query->orWhere('mobile', 'LIKE', '%'.$search.'%');
                                    $query->orWhere('email', 'LIKE', '%'.$search.'%');
                        });
                  }
                  return $query;
              })->where($where)->orderBy('id','desc')->paginate($perpage, ['*'], 'page', $page);
              return response()->json(new ordersResource($orders),200);
        }
        return view('driver.orders');
    }
   public function viewOrder($id){
       $order = Orders::findOrFail($id);
       return view('driver.viewOrder',compact('order'));
   }
    
  public function login_web(){
      if(auth()->guard('driver_web')->check()){
          return redirect(route('driver_index'));
      }
      return view('driver.login');
  }
    
    public function Dologin_web(Request $request){
           
        $checkEmailFoundOrNot = Drivers::where(['email' => $request->email])->first();
        if($checkEmailFoundOrNot && !Hash::check($request->password,$checkEmailFoundOrNot->password)){
            return response()->json(['status' => 'failed','message'=>__('admin.passwordNotCorrect')],200);
        }
        elseif($checkEmailFoundOrNot && Hash::check($request->password,$checkEmailFoundOrNot->password)){
            auth()->guard('driver_web')->attempt(['email' => $request->email , 'password' => $request->password],$request->remember);
            return response()->json(['status' => 'done','message'=> __('admin.successedSignin')],200);
        }
        else{
            return response()->json(['status' => 'failed','message'=>__('admin.emailNotCorrect')],200);
        }
                
            
    }
    
}
