
@php
 $prefix = 'admin';
@endphp
@extends('admin.layout.index')
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
										<div class="card card-custom gutter-b example example-compact">
											<div class="card-header">
												<h3 class="card-title">{{__('admin.subscriptions.edit_for_user')}} - {{$subscription->userObj->name}}</h3>
												
											</div>
											<!--begin::Form-->
											<form action="{{route($prefix.'_subscriptions_edit_post',['id' => $subscription])}}" method="post" enctype="multipart/form-data">
                                                                                   {{csrf_field()}}
												<div class="card-body">
											    @php
											        $categories = \App\Models\main_categories::get();
                                                    $packages = \App\Models\packages::where('type','<>','custom')->get();
                                                    $plans = \App\Models\plans::with('categories')->get();
                                                      $selected_plans = \App\Models\plans::with('categories')->where('package',$subscription->userObj->current_boxes->package)->get();
                                                    @endphp
                                                       <div class="form-group">
												<label for="name">
													{{__('admin.subscriptions.packages')}}
												</label>
												<select class="form-control m-select2" id="packages" name="package">
												    <option selected value="">{{__('admin.subscriptions.choose_package')}}</option>
                                                    @foreach($packages as $package)
                                                    <option @if($subscription->userObj->current_boxes->package == $package->id)selected @endif value="{{$package->id}}">
													    {{$package->title}}
                                                    </option>
                                                    @endforeach
                                                    
											</select>
											</div>
											   <div class="form-group" id="plans_group">
												<label for="name">
													{{__('admin.subscriptions.plans')}}
												</label>
												<select class="form-control m-select2" id="plans" name="plan">
												    <option selected value="">{{__('admin.subscriptions.choose_plan')}}</option>
												     @foreach($selected_plans as $plan)
                                                    <option  @if($subscription->userObj->current_boxes->plan == $plan->id)selected @endif value='{{$plan->id}}'>{{$plan->description_en}} - ({{$plan->price}} k.w) - ( {{$plan->days}} يوم)</option>
                                                    @endforeach
											</select>
											</div>
											<div class="form-group">
                                                    <label>{{__('admin.packages.days')}}</label>
                                                    <input id="days" type="number" class="form-control" name="days" value="{{$subscription->userObj->current_boxes->plan_obj->days}}">
                                                </div>
                                          <div id="categories">
                                              @foreach($subscription->userObj->current_boxes->plan_obj->categories as $cat)
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                    <label>القسم</label>
                                                    <select class="form-control form-control-solid" name="categories[]">
                                                        <option value="">اختر القسم</option>
                                                        @foreach($categories as $category)
                                                        <option @if($cat->category == $category->id) selected @endif value="{{$category->id}}">{{$category->title}}</option>
                                                        @endforeach  
                                              
                                                   </select>
                                                </div>
                                                </div>
                                                  <div class="col-lg-2"><div class="form-group">
                                                    <label>الكمية</label>
                                                    <input type="number" value="{{$cat->qty}}" class="form-control form-control-solid" name="qty[]">
                                                </div></div>
                                                <div class="col-lg-2"><div class="form-group">
                                                <label>الحد الاقصي</label>
                                                <input type="number" value="{{$cat->max}}" class="form-control form-control-solid" name="max[]">
                                            </div></div>
                                                <div class="col-lg-2"><div class="form-group">
                                                    <label>الحد الادني</label>
                                                    <input type="number" value="{{$cat->min}}" class="form-control form-control-solid" name="min[]">
                                                </div></div>
                                                    <div class="col-lg-2 displayFlex">
                                                    <i class="icon-xl la la-trash" id="trashRow"></i>	        
                                                </div>
                                                </div>
                                                @endforeach
                                          </div>
                                          <button type="button" class="btn btn-primary btn-sm" id="addRow">اضافة قسم</button>
                                                    </div>
												<div class="card-footer">
													<div class="row">
														<div class="col-12">
															<div class="form_btns_group"><button type="submit" class="btn btn-success mr-2">{{__('admin.save')}}</button>
															<button type="reset" class="btn btn-secondary">{{__('admin.cancel')}}</button></div>
														</div>
													</div>
												</div>
											</form>
										</div>
										<!--end::Card-->
    </div>
</div>
@endsection
@section('foot')
<script>
    var language = '{!! app()->getLocale() !!}';
    var categories = @json($categories);
    var plans = @json($plans);
      $('#packages').select2({
            placeholder: '{!! __('admin.subscriptions.choose_package') !!}'
        });
        $('#plans').select2({
            placeholder: '{!! __('admin.subscriptions.choose_plan') !!}'
        });
        
    $(document).on('change','#packages',function(){
        var value = $(this).val();
        var selectedPlans = plans.filter((item)=>{ return item.package == value;});
        var options = "<option selected>{{__('admin.subscriptions.choose_plan')}}</option>";
        selectedPlans.forEach((item)=>{
            options += "<option value='"+item.id+"'>"+item['description_'+language]+" - ("+item.price+" د . ك ) - ("+item.days+" عدد الايام )</option>";
        });
        console.log(options);
        $('#plans').children().remove();
        $('#plans').append(options).select2({
            placeholder: "Select a state"
        });;
    });
    $(document).on('change','#plans',function(){
        var value = $(this).val();
        var selectedPlans = plans.find((item)=>{ return item.id == value;});
        if(selectedPlans){
            $('#days').val(selectedPlans.days);
            $('#categories').html('');
            selectedPlans.categories.forEach((item)=>{
                   let categoriesOptions = '';
                categories.forEach((child)=>{
                    var selected = '';
                    if(item.category == child.id){
                        selected = 'selected';
                    }
                    categoriesOptions += "<option "+selected+"  value='"+child.id+"'>"+child['title_'+language]+"</option>";
                });
                var html = ' <div class="row"><div class="col-lg-4"><div class="form-group"><label>القسم</label><select class="form-control form-control-solid" name="categories[]"><option value="" selected>اختر القسم</option>'+categoriesOptions+'</select></div></div><div class="col-lg-2"><div class="form-group"><label>الكمية</label><input type="number" class="form-control form-control-solid" name="qty[]" value="'+item.qty+'"></div></div><div class="col-lg-2"><div class="form-group"><label>الحد الاقصي</label><input type="number" class="form-control form-control-solid" name="max[]" value="'+item.max+'"></div></div><div class="col-lg-2"><div class="form-group"><label>الحد الادني</label><input type="number" class="form-control form-control-solid" name="min[]" value="'+item.min+'"></div></div><div class="col-lg-2 displayFlex"><i class="icon-xl la la-trash trashRow"></i></div></div>';
               $('#categories').append(html);
                });
        }
        console.log(selectedPlans);
    });
    $(document).on('click','#addRow',function(){
        if($("#categories .row").length < categories.length){
                let categoriesOptions = '';
                categories.forEach((item)=>{
                    categoriesOptions += "<option value='"+item.id+"'>"+item['title_'+language]+"</option>";
                });
                var html = ' <div class="row"><div class="col-lg-4"><div class="form-group"><label>القسم</label><select class="form-control form-control-solid" name="categories[]"><option value="" selected>اختر القسم</option>'+categoriesOptions+'</select></div></div><div class="col-lg-2"><div class="form-group"><label>الكمية</label><input type="number" class="form-control form-control-solid" name="qty[]"></div></div><div class="col-lg-2"><div class="form-group"><label>الحد الاقصي</label><input type="number" class="form-control form-control-solid" name="max[]"></div></div><div class="col-lg-2"><div class="form-group"><label>الحد الادني</label><input type="number" class="form-control form-control-solid" name="min[]"></div></div><div class="col-lg-2 displayFlex"><i class="icon-xl la la-trash trashRow"></i></div></div>';
               $('#categories').append(html);
        }
       
    });
    $(document).on('click','.la-trash',function(){
        $(this).parent().parent().remove();
    });

</script>
@endsection