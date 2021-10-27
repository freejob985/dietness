
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
												<h3 class="card-title">{{__('admin.subscriptions.add_new_subscription')}}</h3>
												
											</div>
											<!--begin::Form-->
											<form autocomplete="off" action="{{route('admin_add_post_subscription')}}" method="post" enctype="multipart/form-data">
                                                                                   {{csrf_field()}}
												<div class="card-body">
											    @php
                                                    $users = \App\Models\User::all();
                                                    $packages = \App\Models\packages::where('type','<>','custom')->get();
                                                    $plans = \App\Models\plans::get();
                                                    @endphp
                                            <div class="form-group">
												<label for="name">
													{{__('admin.subscriptions.user')}}
												</label>
												<select class="form-control m-select2" id="users" name="user">
												    <option selected>{{__('admin.subscriptions.choose_user')}}</option>
                                                    @foreach($users as $user)
                                                    <option value="{{$user->id}}">
													    {{$user->name}}
                                                    </option>
                                               
                                                    @endforeach
											</select>
											</div>
                                            <div id="new_user" style="display:none">
                                                <h4 style="text-align: right;">{{__('admin.users.user_info')}}</h4>
                                            <div class="form-group">
														<label>{{__('admin.users.name')}}</label>
														<input type="text" class="form-control" name="name" value="{{old('name')}}">
													</div>
                                                    <div class="form-group">
                                                            <label>{{__('admin.users.email')}}</label>
                                                            <input type="email" class="form-control" name="email" value="{{old('email')}}">
                                                        </div>
                                                    <div class="form-group row">
                                                            <div class="col-lg-2">
                                                        <label>{{__('admin.users.country_code')}}</label>
                                                            <input type="text" class="form-control" name="country_code" value="{{old('country_code')}}">
                                                        </div>
                                                            <div class="col-lg-10">
                                                          <label>{{__('admin.users.mobile')}}</label>
                                                            <input type="text" class="form-control" name="mobile" value="{{old('mobile')}}">
                                                        </div>
                                                        </div>
                                                    <div class="form-group ">
                                                               <label>{{__('admin.users.status')}}</label>
                                                                    @php
                                                                $statuses = ['Active','Blocked'];
                                                                @endphp
															<select class="form-control m-select2" id="statuses" name="status">
                                                                <option></option>
                                                                @foreach($statuses as $key=>$status)
																<option value="{{$status}}" @if($status == old('status')) selected @endif>{{__('admin.users.statuses.'.$status)}}</option>
																@endforeach
															</select>
													</div>
                                                       <div class="form-group">
                                                            <label>{{__('admin.users.password')}}</label>
                                                            <input type="password" class="form-control" name="password">
                                                        </div>
                                                    <div class="form-group">
                                                            <label>{{__('admin.users.verify_password')}}</label>
                                                            <input type="password" class="form-control" name="verify_password">
                                                        </div>
                                                   <h4 style="text-align: right;">{{__('admin.users.user_address')}}</h4>
                                                   <div class="form-group">
                                                    <label for="name">
                                                        {{__('admin.users.governorate')}}
                                                    </label>
                                                    @php
                                                     $governorates = \App\Models\Governorates::with('cities')->get();
                                                    @endphp
                                                    <select class="form-control m-select2" id="governorates" name="governorate">
                                                        <option selected>{{__('admin.users.governorate')}}</option>
                                                        @foreach($governorates as $governorate)
                                                        <option value="{{$governorate->id}}">
                                                            {{$governorate->name}}
                                                        </option>
                                                        @endforeach
                                                </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">
                                                        {{__('admin.users.region')}}
                                                    </label>
                                                    <select class="form-control m-select2" id="regions" name="region">
                                                        <option selected>{{__('admin.users.region')}}</option>
                                                </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__('admin.users.piece')}}</label>
                                                    <input type="text" class="form-control" name="piece" value="{{old('piece')}}">
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__('admin.users.street')}}</label>
                                                    <input type="text" class="form-control" name="street" value="{{old('street')}}">
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__('admin.users.avenue')}}</label>
                                                    <input type="text" class="form-control" name="avenue" value="{{old('avenue')}}">
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__('admin.users.house')}}</label>
                                                    <input type="text" class="form-control" name="house" value="{{old('house')}}">
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__('admin.users.floor')}}</label>
                                                    <input type="text" class="form-control" name="floor" value="{{old('floor')}}">
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__('admin.users.flat')}}</label>
                                                    <input type="text" class="form-control" name="flat" value="{{old('flat')}}">
                                                </div>
                                            </div>
                                               <div class="form-group">
												<label for="name">
													{{__('admin.subscriptions.packages')}}
												</label>
												<select class="form-control m-select2" id="packages" name="package">
												    <option selected>{{__('admin.subscriptions.choose_package')}}</option>
                                                    @foreach($packages as $package)
                                                    <option value="{{$package->id}}">
													    {{$package->title}}
                                                    </option>
                                                    @endforeach
                                                    <option value="NEW">
													    {{__('admin.packages.add_new')}}
                                                    </option>
											</select>
											</div>
                                            <div id="new_package" style="display:none">
                                                <h4 style="text-align: right;">{{__('admin.packages.package_info')}}</h4>
                                            
                                                <div class="form-group">
                                                    <label>{{__('admin.packages.name_en')}}</label>
                                                    <input type="text" class="form-control" name="package_en" value="{{old('package_en')}}">
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__('admin.packages.name_ar')}}</label>
                                                    <input type="text" class="form-control" name="package_ar" value="{{old('package_ar')}}">
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__('admin.packages.price')}}</label>
                                                    <input type="number" class="form-control" name="price" value="{{old('price')}}">
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__('admin.packages.days')}}</label>
                                                    <input type="number" class="form-control" name="days" value="{{old('days')}}">
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__('admin.packages.description_en')}}</label>
                                                    <textarea rows="4" class="form-control" name="description_en">{{old('description_en')}}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>{{__('admin.packages.description_ar')}}</label>
                                                    <textarea rows="4" class="form-control" name="description_ar">{{old('description_ar')}}</textarea>
                                                </div>
                                                <h5 class="texth5">الاقسام</h5>
                                          <div id="categories">
                                            <div class="row" >
                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                    <label>القسم</label>
                                                    <select class="form-control form-control-solid" name="categories[]">
                                                        <option value="" selected>اختر القسم</option>
                                            @php
                                              $categories = \App\Models\main_categories::get();
                                            @endphp
                                            @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->title}}</option>
                                            @endforeach  
                                                   </select>
                                                </div>
                                                </div>
                                                  <div class="col-lg-2"><div class="form-group">
                                                    <label>الكمية</label>
                                                    <input type="number" class="form-control form-control-solid" name="qty[]">
                                                </div></div>
                                                <div class="col-lg-2"><div class="form-group">
                                                <label>الحد الاقصي</label>
                                                <input type="number" class="form-control form-control-solid" name="max[]">
                                            </div></div>
                                                <div class="col-lg-2"><div class="form-group">
                                                    <label>الحد الادني</label>
                                                    <input type="number" class="form-control form-control-solid" name="min[]">
                                                </div></div>
                                                    <div class="col-lg-2 displayFlex">
                                                    <i class="icon-xl la la-trash" id="trashRow"></i>	        
                                                </div>
                                                </div>
                                          </div>
                                                <button type="button" class="btn btn-primary btn-sm" id="addRow">اضافة قسم</button>
                                            </div>
                                                      <div class="form-group" id="plans_group">
												<label for="name">
													{{__('admin.subscriptions.plans')}}
												</label>
												<select class="form-control m-select2" id="plans" name="plan">
												    <option selected>{{__('admin.subscriptions.choose_plan')}}</option>
											</select>
											</div>
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
    var governorates = @json($governorates);
      $('#packages,#plans').select2({
            placeholder: "Select a state"
        });
        $('#users').select2({
            placeholder: "Select a state" ,
            ajax: {
                url: '{!! route("admin_users_search") !!}',
                dataType: 'json',
                type: "GET",
                quietMillis: 50,
                data: function (search) {
                    return {
                        search: search
                    };
                },
                processResults: function (data) {
                    data.push({
                                name: " {!! __('admin.users.add_user') !!}",
                                id: 'NEW'
                            });
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                }
    }
        });
    $(document).on('change','#packages',function(){
        var value = $(this).val();
        if(value == 'NEW'){
            $('#new_package').show();
            var options = "<option selected>{{__('admin.subscriptions.choose_plan')}}</option>";
            $('#plans').children().remove();
            $('#plans').append(options).select2({
                placeholder: "Select a state"
            });
            $('#plans_group').hide();
            return 0;
        }
        $('#new_package').hide();
        var selectedPlans = plans.filter((item)=>{ return item.package == value;});
        var options = "<option selected>{{__('admin.subscriptions.choose_plan')}}</option>";
        selectedPlans.forEach((item)=>{
            options += "<option value='"+item.id+"'>"+item['description_'+language]+" - ("+item.price+" د . ك ) - ("+item.days+" عدد الايام )</option>";
        });
        $('#plans').children().remove();
        $('#plans_group').show();
        $('#plans').append(options).select2({
            placeholder: "Select a state"
        });
        
    });
    $(document).on('change','#governorates',function(){
        var value = $(this).val();
        var cities = governorates.find((item)=>{ return item.id == value;}).cities;
        var options = "<option selected>{{__('admin.users.region')}}</option>";
        cities.forEach((item)=>{
            options += "<option value='"+item.id+"'>"+item['name_'+language] + "</option>";
        });
        $('#regions').children().remove();
        $('#regions').append(options).select2({
            placeholder: "{!! __('admin.users.region') !!}"
        });
    });
    $(document).on('change','#users',function(){
        var value = $(this).val();
        if(value == 'NEW'){
            $('#new_user').show();
        }else{
            $('#new_user').hide();
        }
    });
    $(document).on('click','.trashRow',function(){
       $(this).parent().parent().remove();
    });
    $(document).on('click','#addRow',function(){
        let categoriesOptions = '';
        categories.forEach((item)=>{
            categoriesOptions += "<option value='"+item.id+"'>"+item['title_'+language]+"</option>";
        });
        var html = ' <div class="row"><div class="col-lg-4"><div class="form-group"><label>القسم</label><select class="form-control form-control-solid" name="categories[]"><option value="" selected>اختر القسم</option>'+categoriesOptions+'</select></div></div><div class="col-lg-2"><div class="form-group"><label>الكمية</label><input type="number" class="form-control form-control-solid" name="qty[]"></div></div><div class="col-lg-2"><div class="form-group"><label>الحد الاقصي</label><input type="number" class="form-control form-control-solid" name="max[]"></div></div><div class="col-lg-2"><div class="form-group"><label>الحد الادني</label><input type="number" class="form-control form-control-solid" name="min[]"></div></div><div class="col-lg-2 displayFlex"><i class="icon-xl la la-trash trashRow"></i></div></div>';
       $('#categories').append(html);
    });
    
</script>
@endsection