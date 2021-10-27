<?php

namespace App\Http\Controllers\admin;
use App\Models\main_categories;
use App\Models\products;
use App\Models\packages;
use App\Models\plans;
use App\Models\plan_categories;
use App\Models\package_plans;
use App\Models\settings;
use App\Models\User;
use App\Models\Addresses;
use App\Models\BroadCastMessages;
use App\Models\Drivers;
use App\Models\Subscriptions;
use App\Models\Sliders;
use App\Models\cities;
use App\Models\CitiesDrivers;
use App\Models\Orders;
use App\Models\Governorates;
use App\Models\ProductsDays;
use App\Models\admins;
use App\Models\boxes;
use App\Models\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use \Carbon\Carbon;
use App\Events\Notify_user_status;
use App\Http\Resources\users as userResource;
use App\Http\Resources\subscriptions as subscriptionsResource;
use App\Http\Resources\boxesResource as boxesResource;
use App\Http\Resources\products as productsResource;
use App\Http\Resources\orders as ordersResource;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;
use App\Exports\subscriptionsExport;
use Hash;
use PDF;
use Response;
class admin extends Controller
{
    public function createAddress($request){
        $new_address = new Addresses();
        $new_address->fill($request->all(['user','country','governorate','region','piece','street','avenue','house','floor','flat','notes','lat','lng']))->save();
    }
    public function logout(){
        auth()->guard('admins')->logout();
        return redirect(route('admin_login'));
    }
    public function DownloadOrder($id) {
        $order = Orders::find($id);
        $data = [
            'order' => $order
        ];
        //return view('pdf.order', compact('order'));
        $pdf = PDF::loadView('pdf.order', $data);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->download('order-'.$order->userObj->name.'.pdf');
    }
    public function exportOrdersinDay(Request $request){
        $messages = [
            'day.required' => __('messages.orders.day_required'),
            'day.date_format' => __('messages.orders.day_date_format'),
        ];
        $rules = [
          'day' => 'required|date_format:m/d/Y',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $day = Carbon::parse($request->get('day'));
        $get_orders = Orders::whereDate('day',$day)->get();
        $zip_file = 'invoices.zip';
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        foreach($get_orders as $key=>$order){
            $data = [
                'order' => $order
            ];
            $pdf = PDF::loadView('pdf.order', $data);
            $pdf->SetProtection(['copy', 'print'], '', 'pass');
            $output = $pdf->output();
            $invoice_file = 'order-'.$order->userObj->name.'.pdf';
            $zip->addFromString($invoice_file,$output);
            
        }
        $zip->close();
        return response()->download($zip_file)->deleteFileAfterSend(true);
    }
    public function DownloadOrder_word($id) {
        $order = Orders::find($id);
        $data = [
            'order' => $order
        ];
        $headers = array(
            "Content-type"=>"text/html",
            "Content-Disposition"=>"attachment;Filename=order-".$order->userObj->name.".doc"
        );
        return Response::make(view('pdf.order', compact('order')),200, $headers);
    }
    
    public function index(){
        return view('admin.home');
    }
    public function categories(){
        $main_categories = main_categories::all();
        return view('admin.main_categories',compact('main_categories'));
    }
     public function add_get_categories(){
        return view('admin.add_main_categories');
    }
    public function main_categories_get_edit($id){
        $main_category = main_categories::findOrFail($id);
        return view('admin.main_categories_get_edit',compact('main_category'));
    }
    
       public function add_post_categories(Request $request){
          $messages = [
    
            
        ];
        $rules = [
          'name_en' => 'required|string|unique:main_categories,title_en',
          'name_ar' => 'required|string|unique:main_categories,title_ar',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $new_main_category = new main_categories();
        $new_main_category->title_en = $request->name_en;
        $new_main_category->title_ar = $request->name_ar;
        $new_main_category->save();
         return back()->with('message',__('admin.main_categories.main_categories_added'));
    }
    public function main_categories_post_edit(Request $request,$id){
          $messages = [
    
            
        ];
        $request['id'] = $id;
        $rules = [
          'id' => "required|exists:main_categories,id",
          'name_en' => "required|string|unique:main_categories,title_en,{$id},id",
          'name_ar' => "required|string|unique:main_categories,title_ar,{$id},id",
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $main_category =  main_categories::findOrFail($id);
        $main_category->title_en = $request->name_en;
        $main_category->title_ar = $request->name_ar;
        $main_category->save();
         return back()->with('message',__('admin.main_categories.main_categories_edited'));
    }
      public function main_categories_get_remove($id){
        $main_category = main_categories::findOrFail($id);
        if($main_category->products()->count() <= 0 && $main_category->plan_categories()->count() <= 0){
             $main_category->forceDelete();
             return redirect(route('admin_categories'))->with('message',__('admin.main_categories.main_categories_removed'));
        }else{
             $main_category->delete();
             return redirect(route('admin_categories'))->with('message',__('admin.main_categories.main_categories_removed'));
        }
    }
     public function products(Request $request){
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
            $products = new products();
            $products = $products->with('category_obj','days');
            if(isset($request['query']['generalSearch'])){
                $search = $request['query']['generalSearch'];
                $products->where('name_en','LIKE','%'.$search.'%')->orWhere('name_ar','LIKE','%'.$search.'%');
            }
            if(isset($request['query']['day']) && $request['query']['day'] != 'null'){
                $day = $request['query']['day'];
                $products->whereHas('days',function($query) use($day){
                    $query->where('day',$day);
                });
                
            }
            if(isset($request['query']['status']) && $request['query']['status'] != 'null'){
                $status = $request['query']['status'];
                $products->where('status',$status);
                
            }
            if(isset($request['query']['category']) && $request['query']['category'] != 'null'){
                $category = $request['query']['category'];
                $products->where('category',$category);
                
            }
            $products = $products->orderBy('ordering','asc')->paginate($perpage, ['*'], 'page', $page);
            return response()->json(new productsResource($products),200);
      }
        return view('admin.products');
    }
     public function add_get_products(){
        $main_categories = main_categories::all();
        return view('admin.add_products',compact('main_categories'));
    }
      public function add_post_products(Request $request){
          $messages = [
    
            
        ];
        $rules = [
          'name_en' => 'required|string|unique:products,name_en',
          'name_ar' => 'required|string|unique:products,name_ar',
          'description_en' => 'required|string',
          'description_ar' => 'required|string',
          'category' => 'required|string|exists:main_categories,id',
          'image' => "nullable|file|mimes:jpeg,jpg,png,gif|max:10000",
          'status' => "nullable|in:Hot,NotAvailable,Normal",
          'day' => "required|array",
          'day.*' => "required|in:6,0,1,2,3,4",
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $days = $request->day;
        $new_product = new products();
        $new_product->name_en = $request->name_en;
        $new_product->name_ar = $request->name_ar;
        $new_product->description_en = $request->description_en;
          if($request->status){
              $new_product->status = $request->status;
          }
        $new_product->description_ar = $request->description_ar;
        $new_product->category = $request->category;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $name = 'new_product-'. time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/products');
            $image->move($destinationPath, $name);
            $new_product->image = $name;
        }
        $new_product->save();
        foreach($days as $day){
            $new_ProductsDay = new ProductsDays();
            $new_ProductsDay->day = $day;
            $new_ProductsDay->product = $new_product->id;
            $new_ProductsDay->save();
        }
        return back()->with('message',__('admin.products.products_added'));
    }
        public function post_products_edit(Request $request,$id){
          $messages = [
    
            
        ];
        $request['id'] = $id;  
        $rules = [
          'id' => 'required|exists:products,id',
          'name_en' => "required|string|unique:products,name_en,{$id},id",
          'name_ar' => "required|string|unique:products,name_ar,{$id},id",
          'description_en' => 'required|string',
          'description_ar' => 'required|string',
          'category' => 'required|string|exists:main_categories,id',
          'image' => "nullable|file|mimes:jpeg,jpg,png,gif|max:10000",
          'status' => "nullable|in:Hot,NotAvailable,Normal",
          'day' => "required|array",
          'day.*' => "required|in:6,0,1,2,3,4",
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $days = $request->day;
        $product =  products::findOrFail($id);
        $productDays = $product->days()->delete();
        $product->name_en = $request->name_en;
        $product->name_ar = $request->name_ar;
        $product->description_en = $request->description_en;
        $product->description_ar = $request->description_ar;
          if($request->status){
              $product->status = $request->status;
          }
        $product->category = $request->category;
         if($request->hasFile('image')){
            if($product->image != 'placeholder.jpg'){
                $unlink_image = public_path('/uploads/products/'.$product->image);
                if(file_exists($unlink_image)){
                    unlink($unlink_image);
                }
            }
            $image = $request->file('image');
            $name = $product->id .'-'.time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/products');
            $image->move($destinationPath, $name);
            $product->image = $name;
        }
        $product->save();
        foreach($days as $day){
            $new_ProductsDay = new ProductsDays();
            $new_ProductsDay->day = $day;
            $new_ProductsDay->product = $product->id;
            $new_ProductsDay->save();
        }
        return back()->with('message',__('admin.products.products_edited'));
    }
    public function get_products_remove($id){
        $product = products::findOrFail($id);
        if(!$product){
             $unlink_image = public_path('/uploads/products/'.$product->image);
             if(file_exists($unlink_image)){
                    unlink($unlink_image);
                }
             $product->forceDelete();
             return redirect(route('admin_products'))->with('message',__('admin.products.product_removed'));
        }else{
             $product->delete();
             return redirect(route('admin_products'))->with('message',__('admin.products.product_removed'));
        }
    }
    public function get_products_edit($id){
        $product = products::findOrFail($id);
        $main_categories = main_categories::all();
        return view('admin.edit_products',compact('main_categories','product'));
    }
     public function packages(){
        $packages = packages::where('type','normal')->get();
        return view('admin.packages',compact('packages'));
    }
    public function edit_packages($id){
        $package = packages::findOrFail($id);
        return view('admin.packages_edit',compact('package'));
    }
    public function edit_post_packages(Request $request,$id){
              $messages = [
    
            
        ];
        $request['id'] = $id;
        $rules = [
          'id' => "required|exists:packages,id",
          'name_en' => "required|string|unique:packages,title_en,{$id},id",
          'name_ar' => "required|string|unique:packages,title_ar,{$id},id",
          'image' => "nullable|file|mimes:jpeg,jpg,png,gif|max:10000",
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $package = packages::findOrFail($id);
        $package->title_en = $request->name_en;
        $package->title_ar = $request->name_ar;
        if($request->hasFile('image')){
            if($package->image != 'placeholder.jpg'){
                $unlink_image = public_path('/uploads/packages/'.$package->image);
                if(file_exists($unlink_image)){
                    unlink($unlink_image);
                }
            }
            $image = $request->file('image');
            $name = $package->id .'-'.time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/packages');
            $image->move($destinationPath, $name);
            $package->image = $name;
        }
        $package->save();
        return back()->with('message',__('admin.packages.packages_edited'));
    }
    public function get_packages_remove($id){
        $package = packages::findOrFail($id);
        if($package->plans()->count() <= 0){
             $unlink_image = public_path('/uploads/packages/'.$package->image);
             if(file_exists($unlink_image)){
                    unlink($unlink_image);
                }
             $package->forceDelete();
             return redirect(route('admin_packages'))->with('message',__('admin.packages.package_removed'));
        }else{
             $package->delete();
             return redirect(route('admin_packages'))->with('message',__('admin.packages.package_removed'));
        }
    }
    public function remove_plan(Request $request){
          $messages = [
    
            
        ];
        
        $rules = [
            'plan' => 'required|exists:plans,id',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return response()->json($validator->errors()->all(),422);
        }
         $plan = plans::findOrFail($request->plan);
        if(!$plan){
             $plan->forceDelete();
             return response()->json(['message' => __('admin.plans.plan_removed')],200);
        }else{
             $plan->delete();
             return response()->json(['message' => __('admin.plans.plan_removed')],200);
        }
    }
    public function add_get_packages(){
       return view('admin.packages_add'); 
    }
    public function add_post_packages(Request $request){
                  $messages = [
    
            
        ];
        $rules = [
          'name_en' => "required|string|unique:packages,title_en",
          'name_ar' => "required|string|unique:packages,title_ar",
          'image' => "nullable|file|mimes:jpeg,jpg,png,gif|max:10000",
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $package = new packages();
        $package->title_en = $request->name_en;
        $package->title_ar = $request->name_ar;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $name = 'new_package-'. time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/packages');
            $image->move($destinationPath, $name);
            $package->image = $name;
        }
        $package->save();
        return redirect(route('admin_packages'))->with('message',__('admin.packages.packages_added'));
    }
    public function add_plans_in_single_package($id){
        $package = packages::findOrFail($id);
        return view('admin.add_plans_in_single_package',compact('package'));
    }
    public function get_package_plans($id){
        $package = packages::with('plans')->findOrFail($id);
        $package['plans'] = collect($package['plans'])->map(function($item){
                $item['categories'] = collect($item->categories)->map(function ($cats){
                    $cats['category'] = $cats->category_obj;
                    unset($cats['category_obj']);
                    return $cats;
                });  
                return $item;
            });
        $main_categories = main_categories::all();
        return response()->json(['package'=> $package , 'categories' => $main_categories],200);
    }
    public function edit_plan_by_id(Request $request,$id){
          $messages = [
    
            
        ];
        $rules = [
            'plan.id' => 'required|exists:plans,id',
            'plan.price' => 'required|numeric|min:1',
            'plan.days' => 'required|numeric|min:1',
            'plan.description_en' => 'required|string|min:3|max:64',
            'plan.description_ar' => 'required|string|min:3|max:64',
            'plan.sub_description_en' => 'nullable|string|min:3|max:64',
            'plan.sub_description_ar' => 'nullable|string|min:3|max:64',
            'plan.categories' => 'required|array|min:1',
            'plan.categories.*.qty' => 'required|min:1|max:20',
            'plan.categories.*.max' => 'required|min:1|max:20',
            'plan.categories.*.min' => 'required|min:0|max:20',
            'plan.categories.*.category.id' => 'required|exists:main_categories,id',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return response()->json($validator->errors()->all(),422);
        }
        $plan = plans::find($id);
        $plan->days = $request['plan']['days'];
        $plan->price = $request['plan']['price'];
        $plan->description_en = $request['plan']['description_en'];
        $plan->description_ar = $request['plan']['description_ar'];
        $plan->sub_description_en = $request['plan']['sub_description_en'];
        $plan->sub_description_ar = $request['plan']['sub_description_ar'];
        $plan->save();
        $plan_categories = $request['plan']['categories'];
        $plan->categories()->delete();
        foreach($plan_categories as $category){
            $new_plan_category = new plan_categories();
            $new_plan_category->plan = $plan->id;
            $new_plan_category->category = $category['category']['id'];
            $new_plan_category->qty = $category['qty'];
            $new_plan_category->max = $category['max'];
            $new_plan_category->min = $category['min'];
            $new_plan_category->save();
        }
        return response()->json(['message' => __('admin.plans.plan_edited_success')],200);
    }
        public function create_new_plan(Request $request,$package){
        $package = packages::findOrFail($package);
        $messages = [
    
            
        ];
        $rules = [
            'plan.days' => 'required|numeric|min:1',
            'plan.price' => 'required|numeric|min:1',
            'plan.description_en' => 'required|string|min:3|max:64',
            'plan.description_ar' => 'required|string|min:3|max:64',
            'plan.sub_description_en' => 'nullable|string|min:3|max:64',
            'plan.sub_description_ar' => 'nullable|string|min:3|max:64',
            'plan.categories' => 'required|array|min:1',
            'plan.categories.*.qty' => 'required|min:1|max:20',
            'plan.categories.*.max' => 'required|min:1|max:20',
            'plan.categories.*.min' => 'required|min:0|max:20',
            'plan.categories.*.category.id' => 'required|exists:main_categories,id',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return response()->json($validator->errors()->all(),422);
        }
        $plan = new plans();
        $plan->price = $request['plan']['price'];
        $plan->days = $request['plan']['days'];
        $plan->description_en = $request['plan']['description_en'];
        $plan->description_ar = $request['plan']['description_ar'];
        $plan->sub_description_en = $request['plan']['sub_description_en'];
        $plan->sub_description_ar = $request['plan']['sub_description_ar'];
        $plan->package = $package->id;
        $plan->save();
        $plan_categories = $request['plan']['categories'];
        $plan->categories()->delete();
        foreach($plan_categories as $category){
            $new_plan_category = new plan_categories();
            $new_plan_category->plan = $plan->id;
            $new_plan_category->category = $category['category']['id'];
            $new_plan_category->qty = $category['qty'];
            $new_plan_category->max = $category['max'];
            $new_plan_category->min = $category['min'];
            $new_plan_category->save();
        }
        
                $plan['categories'] = collect($plan['categories'])->map(function ($cats){
                    $cats['category'] = $cats->category_obj;
                    unset($cats['category_obj']);
                    return $cats;
                });  
        return response()->json(['message' => __('admin.plans.plan_added_success'),'data' => $plan],200);
    }
    public function UserChangeStatus($user){
        $User = User::findOrFail($user);
        if($User->status == 'Pending'){
            broadcast(new Notify_user_status($User->id,null,'Waiting_payment'));
            $User->status = 'Waiting_payment';
            $User->save();
            echo 'done';
        }
    }
    public function users(Request $request){
        if($request->ajax()){
              if(isset($request['pagination']['page'])){
                  $page = $request['pagination']['page'];
              }else{
                  $page = 1;
              }
              if(isset($request['pagination']['perpage'])){
                  $perpage = $request['pagination']['perpage'];
              }else{
                  $perpage = 100;
              }
             $where = [];
             $users = new User();
                  if(isset($request['query']['generalSearch'])){
                  $search = $request['query']['generalSearch'];
                     $where[] = [function($query) use ($search){
                                $query->where('name', 'LIKE', '%'.$search.'%');
                                $query->orWhere('email', 'LIKE', '%'.$search.'%');
                                $query->orWhere('mobile', 'LIKE', '%'.$search.'%');
                    }];
              }
         
             
             $users = $users->with('address','planObj','packageObj')->where($where)->orderBy('id','desc')->paginate($perpage, ['*'], 'page', $page);
            return response()->json(new userResource($users),200);
             
          }
        return view('admin.users');
    }
    public function subscriptions(Request $request){
        if($request->ajax()){
              if(isset($request['pagination']['page'])){
                  $page = $request['pagination']['page'];
              }else{
                  $page = 1;
              }
              if(isset($request['pagination']['perpage'])){
                  $perpage = $request['pagination']['perpage'];
              }else{
                  $perpage = 100;
              }
             $where = [];
             $Subscriptions = new Subscriptions();
             $Subscriptions = $Subscriptions->with('userObj','user:id,name,mobile','package:id,title_en,title_ar');
             if(isset($request['query']['generalSearch'])){
                  $search = $request['query']['generalSearch'];
                  $Subscriptions = $Subscriptions->whereHas('userObj',function($q) use($search){
                            $q->where(function($query2) use ($search){
                                $query2->where('name', 'LIKE', '%'.$search.'%');
                                $query2->orWhere('mobile', 'LIKE', '%'.$search.'%');
                                $query2->orWhere('email', 'LIKE', '%'.$search.'%');
                           });
                       });
            }
              if(isset($request['query']['from'])){
                $from = $request['query']['from'];
                $from = Carbon::parse($from);
                   $where[] = [function($query) use ($from){
                              $query->whereDate('from', '>=', $from);
                  }];
            }
            if(isset($request['query']['to'])){
                $to = $request['query']['to'];
                $to = Carbon::parse($to);
                   $where[] = [function($query) use ($to){
                              $query->whereDate('to', '<=', $to);
                  }];
            }
            if(isset($request['query']['status'])){
                $status = $request['query']['status'];
                if($status == 'ACTIVE'){
                    $Subscriptions = $Subscriptions->whereHas('userObj',function($query){
                        return $query->whereHas('current_boxes',function($q){
                            return $q->whereDate('to','>=',Carbon::now());
                        });
                    });
                }elseif($status == 'NOTACTIVE'){
                    $Subscriptions = $Subscriptions->whereHas('userObj',function($query){
                        return $query->whereHas('current_boxes',function($q){
                            return $q->whereDate('to','<',Carbon::now());
                        });
                    });
                }
            }
         
             
             $Subscriptions = $Subscriptions->where($where)->orderBy('id','desc')->paginate($perpage, ['*'], 'page', $page);
            return response()->json(new subscriptionsResource($Subscriptions),200);
             
          }
        return view('admin.subscriptions');
    }
    public function boxes(Request $request){
        if($request->ajax()){
              if(isset($request['pagination']['page'])){
                  $page = $request['pagination']['page'];
              }else{
                  $page = 1;
              }
              if(isset($request['pagination']['perpage'])){
                  $perpage = $request['pagination']['perpage'];
              }else{
                  $perpage = 100;
              }
             $where = [];
             $boxes = new boxes();
             $boxes = $boxes->with('userObj','user:id,name,mobile','package:id,title_en,title_ar','plan_obj:id,description_en,description_ar');
             if(isset($request['query']['generalSearch'])){
                  $search = $request['query']['generalSearch'];
                  $boxes = $boxes->whereHas('userObj',function($q) use($search){
                            $q->where(function($query2) use ($search){
                                $query2->where('name', 'LIKE', '%'.$search.'%');
                                $query2->orWhere('mobile', 'LIKE', '%'.$search.'%');
                                $query2->orWhere('email', 'LIKE', '%'.$search.'%');
                           });
                       });
            }
              if(isset($request['query']['from'])){
                $from = $request['query']['from'];
                $from = Carbon::parse($from);
                   $where[] = [function($query) use ($from){
                              $query->whereDate('from', '>=', $from);
                  }];
            }
            if(isset($request['query']['to'])){
                $to = $request['query']['to'];
                $to = Carbon::parse($to);
                   $where[] = [function($query) use ($to){
                              $query->whereDate('to', '<=', $to);
                  }];
            }
            if(isset($request['query']['status'])){
                $status = $request['query']['status'];
                if($status == 'ACTIVE'){
                    $boxes = $boxes->whereHas('userObj',function($query){
                        return $query->whereHas('current_boxes',function($q){
                            return $q->whereDate('to','>=',Carbon::now());
                        });
                    });
                }elseif($status == 'NOTACTIVE'){
                    $boxes = $boxes->whereHas('userObj',function($query){
                        return $query->whereHas('current_boxes',function($q){
                            return $q->whereDate('to','<',Carbon::now());
                        });
                    });
                }
            }
         
             
             $boxes = $boxes->where($where)->orderBy('boxes.to','desc')->paginate($perpage, ['*'], 'page', $page);
            return response()->json(new boxesResource($boxes),200);
             
          }
        return view('admin.boxes');
    }
    public function users_search(Request $request){
        $search = (array_key_exists('term',$request->get('search'))) ? $request->get('search')['term'] : null;
        $users = User::where(function($query2) use ($search){
            if($search){
                $query2->where('name', 'LIKE', '%'.$search.'%');
                $query2->orWhere('mobile', 'LIKE', '%'.$search.'%');
                $query2->orWhere('email', 'LIKE', '%'.$search.'%');
            }
       })->select('id','name')->get();
       return $users;
    }
    public function Userview($user){
        $user = User::findOrFail($user);
        return view('admin.usersView',compact('user'));
    }
    public function add_new_user_get(){
        return view('admin.usersAdd');
    }
    public function add_new_user_post(Request $request){
        $messages = [
    
            
        ];
        $rules = [
            'name' => "required|string|min:3",
            'email' => "required|email|unique:users,email",
            'country_code' => "required|string",
            'mobile' => "required|string|unique:users,mobile",
            'status' => "nullable|in:Active,Blocked",
            'password' => "nullable|min:8",
            'verify_password' => "required_with:password|same:password",
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->country_code = $request->country_code;
        $user->mobile = $request->mobile;
        if($request->status){
            $user->status = $request->status;
        }else{
            $user->status = 'Active';
        }
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return back()->with('message',__('admin.users.users_created'));
    }
     public function UserEdit(Request $request,$user){
        $user = User::findOrFail($user);
        $messages = [
    
            
        ];
        $rules = [
            'name' => "required|string|min:3",
            'email' => "required|email|unique:users,email,{$user->id},id,deleted_at,NULL",
            'country_code' => "required|string",
            'mobile' => "required|string|unique:users,mobile,{$user->id},id,deleted_at,NULL",
            'status' => "required|in:Active,Blocked",
            'password' => "nullable|min:8",
            'verify_password' => "required_with:password|same:password",
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator);
        }
         $user->name = $request->name;
         $user->email = $request->email;
         $user->country_code = $request->country_code;
         $user->mobile = $request->mobile;
         $user->status = $request->status;
         if($request->password){
             $user->password = Hash::make($request->password);
         }
         $user->save();
         return back()->with('message',__('admin.users.users_edited'));
     }
    public function update_subscription(Request $request){
        $messages = [
    
            
        ];
        $rules = [
            'id' => "required|exists:subscriptions,id",
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator);
        }
        $Subscription = Subscriptions::findOrFail($request->id);
        if($Subscription->status == 'Created'){
            $Subscription->status = 'Approved';
            $Subscription->save();
            return response()->json([
                 'status' => 'done',
                 'id' => $Subscription->id,
                 'message' => __('admin.subscriptions.Approved') 
            ],200);
        }
    }
    public function accept_user(Request $request){
            $messages = [
    
            
        ];
        $rules = [
            'id' => "required|exists:users,id",
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator);
        }
        $user = User::findOrFail($request->id);
          if($user->status == 'Pending'){
            broadcast(new Notify_user_status($user->id,null,'Waiting_payment'));
            $user->status = 'Waiting_payment';
            $user->save();
            return response()->json([
                 'status' => 'done',
                 'id' => $user->id,
                 'message' => __('admin.users.statuses.Waiting_payment') 
            ],200);
            
        }
    }
    public function addSlider(){
        return view('admin.add_slider');
    }
    public function addpostSlider(Request $request){
        $messages = [
    
            
        ];
        $rules = [
            'image' => "required|file|mimes:jpeg,jpg,png,gif|max:10000",
            'first_word_ar' => "required|string|min:3",
            'second_word_ar' => "required|string|min:3",
            'description_ar' => "required|string|min:3",
            'first_btn_ar' => "required|string|min:3",
            'second_btn_ar' => "required|string|min:3",
            'first_word_en' => "required|string|min:3",
            'second_word_en' => "required|string|min:3",
            'description_en' => "required|string|min:3",
            'first_btn_en' => "required|string|min:3",
            'second_btn_en' => "required|string|min:3",
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $data = $request->all(['image','first_word_ar','second_word_ar','description_ar','first_btn_ar','second_btn_ar','first_word_en','second_word_en','description_en','first_btn_en','second_btn_en']);
        if($request->hasFile('image')){
            $image = $request->file('image');
            $name = 'new_slider-'. time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/sliders');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        $slider = new Sliders();
        $slider->fill($data)->save();
        return back()->with('message',__('admin.sliders.slider_added'));
    }
     public function editSlider($id){
        $slider = Sliders::findOrFail($id);
        return view('admin.edit_slider',compact('slider'));
    }
    public function editpostSlider(Request $request,$id){
        $messages = [
    
            
        ];
        $rules = [
            'image' => "nullable|file|mimes:jpeg,jpg,png,gif|max:10000",
            'first_word_ar' => "required|string|min:3",
            'second_word_ar' => "required|string|min:3",
            'description_ar' => "required|string|min:3",
            'first_btn_ar' => "required|string|min:3",
            'second_btn_ar' => "required|string|min:3",
            'first_word_en' => "required|string|min:3",
            'second_word_en' => "required|string|min:3",
            'description_en' => "required|string|min:3",
            'first_btn_en' => "required|string|min:3",
            'second_btn_en' => "required|string|min:3",
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $data = $request->all(['first_word_ar','second_word_ar','description_ar','first_btn_ar','second_btn_ar','first_word_en','second_word_en','description_en','first_btn_en','second_btn_en']);
        if($request->hasFile('image')){
            $image = $request->file('image');
            $name = 'slider-'. time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/sliders');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        $slider = new Sliders();
        $slider->where('id',$id)->first()->fill($data)->save();
        return back()->with('message',__('admin.sliders.slider_edited'));
    }
    public function sliders(){
        $sliders = Sliders::all();
        return view('admin.sliders',compact('sliders'));
    }
    public function slider_remove($id){
        $slider = Sliders::findOrFail($id);
        $unlink_image = public_path('/uploads/sliders/'. $slider->image);
        if(file_exists($unlink_image)){
            unlink($unlink_image);
        }
        $slider->forceDelete();
        return redirect(route('admin_sliders'))->with('message',__('admin.sliders.slider_removed'));
    }
    public function UserRemove($id){
        $user = User::findOrFail($id);
        $user->forceDelete();
        return redirect(route('admin_users'))->with('message',__('admin.users.user_removed'));
    }
    public function getbroadCastToAll(){
        return view('admin.broadCast');
    }
    public function postbroadCastToAll(Request $request){
         $messages = [
    
            'message.required' => 'محتوي الرسالة مطلوب .',
            'message.string' => 'محتوي الرسالة مطلوب',
            'send_type.required' => 'نوع الارسال مطلوب .',
            'send_type.in' => 'نوع الارسال مطلوب .',
        ];
        $rules = [
            'send_type' => "required|in:to_all,to_active,to_none_active,to_none_users",
            'message' => "required|string",
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
            $new_BroadCastMessage = new BroadCastMessages();
            $new_BroadCastMessage->message = $request->message;
            $new_BroadCastMessage->save();
            $test = [];
            if($request->send_type == 'to_all'){
                $this->sendFCMByTopic($request->message,'topic','all');
            }elseif($request->send_type == 'to_active'){
                $get_all_active_users = User::whereHas('current_boxes',function($q){
                            return $q->whereDate('to','>',Carbon::now());
                        })->pluck('fcmToken')->toArray();
                foreach($get_all_active_users as $token){
                   $this->sendFCMByToken($token,$request->message,'topic');
                }
            }elseif($request->send_type == 'to_none_active'){
                $get_all_active_users = User::whereHas('current_boxes',function($q){
                            return $q->whereDate('to','<',Carbon::now());
                        })->pluck('fcmToken')->toArray();
                foreach($get_all_active_users as $token){
                   $this->sendFCMByToken($token,$request->message,'topic');
                }
            }
            else{
                 $this->sendFCMByTopic($request->message,'topic','all_non_users');
            }
            
            return back()->with('message',__('admin.messageSentToAll'));
    }
     public function Governorates(){
        $all_governorates = Governorates::all();
        return view('admin.governorates',compact('all_governorates'));
    }
     public function add_get_governorates(){
        return view('admin.addGovernorate');
    }
     public function edit_get_governorates($id){
         $Governorate = Governorates::find($id);
         if(!$Governorate){return abort(404);}
        return view('admin.editGovernorate',compact('Governorate'));
    }
    
    public function add_post_governorates(Request $request){
          $messages = [
    
            
        ];
        $rules = [
                'name_en' => 'required|string|unique:governorates,name_en',    
                'name_ar' => 'required|string|unique:governorates,name_ar',    
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator);
        }
                $new_Governorate = new Governorates();
                $new_Governorate->name_en = $request->name_en;
                $new_Governorate->name_ar = $request->name_ar;
                $new_Governorate->save();
                return back()->with('message',__('admin.addgovernorate.governorate_added_success'));
    }
      public function edit_post_governorates(Request $request,$id){
           $Governorate = Governorates::find($id);
         if(!$Governorate){return abort(404);}
          $messages = [
    
            
        ];
        $rules = [
                'name_en' => 'required|string|unique:governorates,name_en,'.$id,    
                'name_ar' => 'required|string|unique:governorates,name_ar,'.$id,    
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator);
        }
                $Governorate->name_en = $request->name_en;
                $Governorate->name_ar = $request->name_ar;
                $Governorate->save();
                return back()->with('message',__('admin.addgovernorate.governorate_edited_success'));
    }
     public function governorates_delete($id){
         $Governorate = Governorates::find($id);
         if(!$Governorate){return abort(404);}
         $Governorate->delete();
         return back()->with('message',__('admin.governorates.governorate_removed'));
         
    }
    public function cities_delete($id){
          $city_obj = cities::find($id);
         if(!$city_obj){return abort(404);}
         $city_obj->delete();
        return back()->with('message',__('admin.Cities.removed'));
         
    }
    
    public function cities(){
        $all_cities = cities::all();
        return view('admin.cities',compact('all_cities'));
    }
     public function add_get_cities(){
        return view('admin.addCity');
    }
    public function edit_get_cities($id){
        $city_obj = cities::find($id);
         if(!$city_obj){return abort(404);}
        return view('admin.editCity',compact('city_obj'));
    }
    
    public function add_post_cities(Request $request){
          $messages = [
    
            
        ];
        $rules = [
                'name_en' => 'required|string|unique:cities,name_en',    
                'name_ar' => 'required|string|unique:cities,name_ar',    
                'governorate' => 'required|string|exists:governorates,id',    
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator);
        }
                $new_City = new cities();
                $new_City->name_en = $request->name_en;
                $new_City->name_ar = $request->name_ar;
                $new_City->governorate = $request->governorate;
                $new_City->save();
                return back()->with('message',__('admin.Cities.added'));
    }
       public function edit_post_cities(Request $request,$id){
            $city_obj = cities::find($id);
         if(!$city_obj){return abort(404);}
          $messages = [
    
            
        ];
        $rules = [
                'name_en' => 'required|string|unique:cities,name_en,'.$id.',id',    
                'name_ar' => 'required|string|unique:cities,name_ar,'.$id.',id',    
                'governorate' => 'required|string|exists:governorates,id',    
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator);
        }
                $city_obj->name_en = $request->name_en;
                $city_obj->name_ar = $request->name_ar;
                $city_obj->governorate = $request->governorate;
                $city_obj->save();
                return back()->with('message',__('admin.Cities.edited'));
    }
    public function get_subscriptions_edit($id){
        $subscription = Subscriptions::findOrFail($id);
        return view('admin.edit_subscription',compact('subscription'));
    }
    public function add_get_subscription(){
        return view('admin.add_subscription');
    }
    public function add_post_subscription(Request $request){
        $messages = [
    
            
        ];
        $rules = [];
        if($request->user == 'NEW'){
            $rules['name'] = 'required|string|min:3';
            $rules['email'] = 'nullable|email|unique:users,email';
            $rules['country_code'] = 'required|string';
            $rules['mobile'] = 'required|string|unique:users,mobile';
            $rules['status'] = 'nullable|in:Active,Blocked';
            $rules['password'] = 'required|min:8';
            $rules['verify_password'] = 'same:password';
            $rules['governorate'] = 'required|exists:governorates,id';
            $rules['region'] = 'required|exists:cities,id';
        }else{
            $rules['user'] = 'required|string|exists:users,id';
        }
        if($request->package == 'NEW'){
            $rules['package_en'] = 'required|string|unique:packages,title_en';
            $rules['package_ar'] = 'required|string|unique:packages,title_ar';
            $rules['price'] = 'required|numeric|min:1';
            $rules['days'] = 'required|numeric|min:1';
            $rules['description_en'] = 'required|string|min:3|max:64';
            $rules['description_ar'] = 'required|string|min:3|max:64';
            $rules['categories'] = 'required|array|min:1';
            $rules['categories.*'] = 'required|exists:main_categories,id';
            $rules['qty'] = 'required|array|max:'.count($request->categories);
            $rules['qty.*'] = 'required|min:1|max:20';
            $rules['max'] = 'required|array|max:'.count($request->categories);
            $rules['max.*'] = 'required|min:1|max:20';
            $rules['min'] = 'required|array|max:'.count($request->categories);
            $rules['min.*'] = 'required|min:0|max:20';
      
        }else{
            $rules['package'] = 'required|string|exists:packages,id';
            $rules['plan'] = 'required|string|exists:plans,id';
        }
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator);
        }
        if($request->user == 'NEW'){
            $new_User = new User();
            $new_User->name = $request->name;
            $new_User->mobile = $request->mobile;
            $new_User->country_code = $request->country_code;
            $new_User->email = $request->email;
            $new_User->password = Hash::make($request->password);
            $new_User->status = 'Active';
            $new_User->save();
            $new_User->refresh();
            $request['user'] = $new_User->id;
            $this->createAddress($request);
            $getUser = $new_User;
        }else{
            $getUser = User::findOrFail($request->user);
        }
        if($request->package == 'NEW'){
            $package = new packages();
            $package->title_en = $request->package_en;
            $package->title_ar = $request->package_ar;
            $package->type = 'custom';
            $package->save();
            $plan = new  plans();
            $plan->days = $request['days'];
            $plan->price = $request['price'];
            $plan->package = $package->id;
            $plan->description_en = $request['description_en'];
            $plan->description_ar = $request['description_ar'];
            $plan->save();
            $plan_categories = $request['categories'];
            $qty = $request['qty'];
            $max = $request['max'];
            $min = $request['min'];
            foreach($plan_categories as $key=>$category){
                $new_plan_category = new plan_categories();
                $new_plan_category->plan = $plan->id;
                $new_plan_category->category = $category;
                $new_plan_category->qty = $qty[$key];
                $new_plan_category->max = $max[$key];
                $new_plan_category->min = $min[$key];
                $new_plan_category->save();
            }
        }else{
            $package = packages::findOrFail($request->package);
            $plan = plans::findOrFail($request->plan);
        }
        $data = ['user' => $getUser->id,'package' => $package->id , 'plan' => $plan->id , 'amount' => $plan->price];
        Helper::CreateNewSubscriptions($data);
        return back()->with('message',__('admin.subscriptions.subscription_created',['user' => $getUser->name]));
    }
    public function post_subscriptions_edit(Request $request,$id){
           $messages = [
    
            
        ];
        $rules = [
          
                'package' => 'nullable|string|exists:packages,id',      
                'plan' => 'required|string|exists:plans,id',      
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator);
        }
        $subscription = Subscriptions::findOrFail($id);
        $enteredPakcage = $request->package;
        $enteredPlan = $request->plan;
        $getUser = User::findOrFail($subscription->user);
        $package = packages::findOrFail(($enteredPakcage && $enteredPakcage != '') ? $enteredPakcage : $subscription->package);
        $plan = plans::findOrFail(($enteredPlan && $enteredPlan != '') ? $enteredPlan : $subscription->plan);
        $lastSubscription = Subscriptions::where('user',$getUser->id)->latest()->first();
        $boxes_obj = boxes::where('user',$getUser->id)->first();
        $new_package = new packages();
        $new_package->title_en = $package->title_en;
        $new_package->title_ar = $package->title_ar;
        $new_package->type = 'custom';
        $new_package->save();
            $new_plan = new  plans();
            $new_plan->days = $request->days;
            $new_plan->price = $plan->price;
            $new_plan->package = $new_package->id;
            $new_plan->description_en = $plan->description_en;
            $new_plan->description_ar = $plan->description_ar;
            $new_plan->save();
            $plan_categories = $request['categories'];
            $qty = $request['qty'];
            $max = $request['max'];
            $min = $request['min'];
            foreach($plan_categories as $key=>$category){
                $new_plan_category = new plan_categories();
                $new_plan_category->plan = $new_plan->id;
                $new_plan_category->category = $category;
                $new_plan_category->qty = $qty[$key];
                $new_plan_category->max = $max[$key];
                $new_plan_category->min = $min[$key];
                $new_plan_category->save();
            }
        $subscription->plan = $new_plan->id;
        $subscription->package = $new_package->id;
        $subscription->save();
        $subscription_days = Helper::CalculateSubscriptionDays($new_plan->id);
        $start_of_subscription = Carbon::parse($boxes_obj->to)->addDays(1);
        $fixed_start = Carbon::parse($boxes_obj->to)->addDays(1);
        $end_of_subscription = $fixed_start->addDays($subscription_days - 1);
        if($boxes_obj){
            
            $boxes_obj->plan = $new_plan->id;
            $boxes_obj->package = $new_package->id;
            $boxes_obj->to = $end_of_subscription;
            $boxes_obj->save();
            
        }
        if($subscription){
            $subscription->plan = $new_plan->id;
            $subscription->package = $new_package->id;
            $subscription->to = $end_of_subscription;
            $subscription->save();
        }
        return redirect(route('admin_subscriptions'))->with('message',__('admin.subscriptions.subscription_edited',['user' => $getUser->name]));
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
              if(isset($request['query']['day'])){
                    $day = Carbon::parse($request['query']['day']);
                    $where[] = [function($query) use ($day){
                            $query->whereDate('day',$day);
                    }];
              }
              if(isset($request['query']['time_frame']) && $request['query']['time_frame'] != null && $request['query']['time_frame'] != 'null'){
                $time_frame = $request['query']['time_frame'];
                $where[] = [function($query) use ($time_frame){
                        $query->where('delivery_timeframe',$time_frame);
                }];
              }
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
        return view('admin.orders');
    }   
   public function viewOrder($id){
       $order = Orders::findOrFail($id);
       return view('admin.viewOrder',compact('order'));
   }
   public function drivers(){
       $getDrivers = Drivers::get();
       return view('admin.drivers',compact('getDrivers'));
   }
    public function addDrivers(){
        return view('admin.addDriver');
    }
    public function addDriversPost(Request $request){
         $messages = [
    
            
        ];
        $rules = [
                'name' => 'required|string|unique:drivers,name',         
                'email' => 'required|string|unique:drivers,email',         
                'mobile' => 'required|string|unique:drivers,mobile', 
                'city' => 'required|array',       
                'city.*' => 'required|exists:cities,id', 
                'password' => 'required|string|min:3',         
                'verify_password' => 'required|same:password',         
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $cities = $request->city;
        $errors = [];
        foreach($cities as $city){
            $check_city = CitiesDrivers::where('city',$city)->first();
            if($check_city){
                $errors[] = __('admin.drivers.addDriver.city_have_driver',['city' => $check_city->city_obj->name]);
            }
        }
       
        if(count($errors) > 0){
           return back()->with('driver_errors',$errors)->withInput();    
        }
        $data = ['name' => $request->name, 'email' => $request->email,'mobile' => $request->mobile,'password' => Hash::make($request->password)];
        $new_driver = Drivers::create($data);
        foreach($cities as $city){
                    $new_cities_drivers = new CitiesDrivers();
                    $new_cities_drivers->city = $city;
                    $new_cities_drivers->driver = $new_driver->id;
                    $new_cities_drivers->save();
        }
        return back()->with('message',__('admin.drivers.added'));
    }
    public function editDriversPost(Request $request,$id){
        $driver =  Drivers::findOrFail($id);
        $messages = [
    
            
        ];
        $rules = [
                'name' => 'required|string|unique:drivers,name,'.$driver->id.',id',         
                'email' => 'required|string|unique:drivers,email,'.$driver->id.',id',         
                'mobile' => 'required|string|unique:drivers,mobile,'.$driver->id.',id',         
                'password' => 'nullable|string|min:3',         
                'verify_password' => 'required_with:password|same:password',         
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $cities = $request->city;
        $errors = [];
        foreach($cities as $city){
            $check_city = CitiesDrivers::where('city',$city)->where('driver','!=',$id)->first();
            if($check_city){
                $errors[] = __('admin.drivers.addDriver.city_have_driver',['city' => $check_city->city_obj->name]);
            }
        }
       
        if(count($errors) > 0){
           return back()->with('driver_errors',$errors)->withInput();    
        }
       $get_driver_cities = CitiesDrivers::where(['driver' => $id])->get();
       if($get_driver_cities){
           $get_driver_cities->each->delete();
       }
       foreach($cities as $city){
                    $new_cities_drivers = new CitiesDrivers();
                    $new_cities_drivers->city = $city;
                    $new_cities_drivers->driver = $driver->id;
                    $new_cities_drivers->save();
        }
        $data = ['name' => $request->name, 'email' => $request->email,'mobile' => $request->mobile];
        if($request->password){
            $data['password'] = Hash::make($request->password);
        }
        $update_driver = $driver->update($data);
        return back()->with('message',__('admin.drivers.updated'));
    }
    public function editDrivers($id){
        $driver =  Drivers::findOrFail($id);
        return view('admin.editDriver',compact('driver'));
    }
     public function login(){
      if(auth()->guard('admins')->check()){
          return redirect(route('admin_index'));
      }
      return view('admin.login');
  }
     public function Dologin(Request $request){
         $checkEmailFoundOrNot = admins::where(['email' => $request->email])->first();
        if($checkEmailFoundOrNot && !Hash::check($request->password,$checkEmailFoundOrNot->password)){
            return response()->json(['status' => 'failed','message'=>__('admin.passwordNotCorrect')],200);
        }
        elseif($checkEmailFoundOrNot && Hash::check($request->password,$checkEmailFoundOrNot->password)){
            auth()->guard('admins')->attempt(['email' => $request->email , 'password' => $request->password],$request->remember);
            return response()->json(['status' => 'done','message'=> __('admin.successedSignin')],200);
        }
        else{
            return response()->json(['status' => 'failed','message'=>__('admin.emailNotCorrect')],200);
        }
    }
    public function exportOrders(Request $request){
        $orders = $request->orders;
        return Excel::download(new OrdersExport($orders), 'orders.xlsx');
    }
    public function deleteOrders(Request $request){
        $orders = $request->orders;
        $deleted_orders = Orders::whereIn('id',$orders)->delete();
        return back()->with('message',__('admin.orders_deleted'));
    }
    public function completeOrders(Request $request){
        $orders = $request->orders;
        $completed_orders = Orders::whereIn('id',$orders)->update(['status' => 'COMPLETED']);
        return back()->with('message',__('admin.orders_completed'));
    }
    public function subscriptionsExport(Request $request){
        $subscriptions = $request->subscriptions;
        return Excel::download(new subscriptionsExport($subscriptions), 'subscriptions.xlsx');
    }
    public function getTerms(){
        return view('admin.terms');
    }
    public function postTerms(Request $request){
        settings::where('key','terms')->update(['value_en' => $request->text_en,'value_ar' => $request->text_ar]);
        return back()->with('message',__('admin.terms_updated'));
    }
     public function getWelcomeText(){
        return view('admin.welcome_text');
    }
    public function postWelcomeText(Request $request){
        settings::where('key','welcome_text')->update(['value_en' => $request->text_en,'value_ar' => $request->text_ar]);
        return back()->with('message',__('admin.welcome_text_updated'));
    }
    public function getSettings(){
        return view('admin.settings');
    }
    public function postSettings(Request $request){
        $skip_activation = ($request->skip_activation) ? $request->skip_activation : 'off';
        $delievery_time_frame = ($request->delievery_time_frame) ? $request->delievery_time_frame : 'off';
        settings::where('key','enable_delievery_timeframes')->update(['value_en' => $delievery_time_frame]);
        settings::where('key','skip_activation')->update(['value_en' => $skip_activation]);
        settings::where('key','delivery_notes')->update(['value_en' => $request->delivery_notes_en,'value_ar' => $request->delivery_notes_ar]);
        return back()->with('message',__('admin.settings_updated'));
    }
    public function change_products_order(Request $request){
       $products = $request->products;  
       foreach($products as $key=>$product){
           $product = products::find($product);
           $product->ordering = $key+1;
           $product->save();
       }
    }
    
    
}
