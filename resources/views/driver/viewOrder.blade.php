@php
 $prefix = 'admin';
 use \Carbon\Carbon;
@endphp
@extends('driver.layout.index')
@section('content')
<div class="row add_content">
<div class="col-lg-12">
		       @if($errors->any())
    <div class="alert alert-danger danger-errors">
      @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
            @endforeach
    </div>
    @endif
    @if(session()->has('message'))
     <div class="alert alert-success danger-errors">
      <p>{{session()->get('message')}}</p>
    </div>
    @endif
<!--begin::Card-->
      @php
                    $address = [];
                    if($order->userObj->address->region){
                        $address[] =  $order->userObj->address->region()->first()->name;
                    }
                    if($order->userObj->address->piece){
                        $address[] =  __("admin.Orders.piece")  .' : ' . $order->userObj->address->piece;
                    }
                    if($order->userObj->address->street){
                        $address[] =  __("admin.Orders.street") .' : ' . $order->userObj->address->street;
                    }
                    if($order->userObj->address->avenue){
                        $address[] =  __("admin.Orders.Avenue") .' : ' . $order->userObj->address->avenue;
                    }
                    if($order->userObj->address->house){
                        $address[] =  __("admin.Orders.Home"). ' : ' . $order->userObj->address->house;
                    }
                    @endphp
                
										<div class="card card-custom gutter-b example example-compact">
											<div class="card-header">
												<h3 class="card-title">{{__('admin.Orders.viewOrderDetails',['id' => $order->id])}}</h3>
												
											</div>
											<!--begin::Form-->
											<form action="" method="post" enctype="multipart/form-data">
                                                                                   {{csrf_field()}}
												<div class="card-body">
													<div class="form-group">
														<label>{{__('admin.Orders.username')}}</label>
														<input type="text" class="form-control" name="name_en" value="{{$order->userObj->name}}" disabled>
													</div>
                                                    <div class="form-group">
														<label>{{__('admin.Orders.mobile')}}</label>
														<input type="text" class="form-control" name="name_en" value="{{$order->userObj->mobile}}" disabled>
													</div>
                                                    @if($order->userObj->email)
                                                     <div class="form-group">
														<label>{{__('admin.Orders.email')}}</label>
														<input type="text" class="form-control" name="name_en" value="{{$order->userObj->email}}" disabled>
													</div>
                                                  @endif
                                                    <div class="form-group">
														<label>{{__('admin.Orders.address')}}</label>
														<input type="text" class="form-control" name="name_en" value="{{implode(' , ',$address)}}" disabled>
													</div>
                                                      <div class="form-group">
														<label>{{__('admin.Orders.day')}}</label>
														<input type="text" class="form-control" name="name_en" value="{{Carbon::parse($order->day)->format('Y-m-d')}}" disabled>
													</div>
                                 <div class="form-group">
                                <table class="table m-table m-table--head-bg-brand" id="orderDetials">
											<thead>
												<tr>
													<th>
														#
													</th>
													<th>
														{{__('admin.Orders.category')}}
													</th>
													<th>
														{{__('admin.Orders.products')}}
													</th>    
												</tr>
											</thead>
											<tbody>
                                             @foreach($order->items as $key=>$item)
                                           	<tr>
													<td>
														{{$loop->iteration}}
                                                </td>
													<td>
														{{$item['category']['title']}}
                                                </td>
													<td>
                                                        @php
                                                          $products = [];
                                                        @endphp
														@foreach($item['products'] as $key=>$product)
                                                           @php
                                                             $products[] = $product->name;
                                                           @endphp
                                                        @endforeach
                                                        {{implode(' , ',$products)}}
                                                </td>    
												</tr>
                                              
                                                @endforeach
                                               
                                                											</tbody>
										</table>
                                </div>
                                                    </div>
									
											</form>
										</div>
										<!--end::Card-->
    </div>
</div>
@endsection