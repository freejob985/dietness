@php
 $prefix = 'admin';
@endphp
@extends('admin.layout.index')
@section('content')
<div class="row add_content">
<div class="col-lg-12">
           @if(session()->has('driver_errors'))
    <div class="alert alert-danger danger-errors">
      @foreach (session()->get('driver_errors') as $error)
        <p>{{ $error }}</p>
            @endforeach
    </div>
    @endif
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
												<h3 class="card-title">{{__('admin.drivers.edit_drivers')}} - {{$driver->name}}</h3>
												
											</div>
											<!--begin::Form-->
											<form action="{{route($prefix.'_editDriversPost',['id' => $driver->id])}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                                                                   {{csrf_field()}}
												<div class="card-body">
													<div class="form-group">
														<label>{{__('admin.drivers.name')}}</label>
														<input type="text" class="form-control" name="name" value="{{$driver->name}}">
													</div>
                                                    <div class="form-group">
                                                            <label>{{__('admin.drivers.email')}}</label>
                                                            <input type="email" class="form-control" name="email" value="{{$driver->email}}">
                                                        </div>
                                                     <div class="form-group">
                                                            <label>{{__('admin.drivers.mobile')}}</label>
                                                            <input type="text" class="form-control" name="mobile" value="{{$driver->mobile}}">
                                                        </div>
                                                     <div class="form-group">
												<label for="name">
													{{__('admin.drivers.addDriver.city')}}
												</label>
                                                @php
                                                  $cities = \App\Models\cities::get();
                                                @endphp
                                          
												<select class="form-control m-select2" id="city" name="city[]" multiple>
												  
                                                    @foreach($cities as $city)
                                                    <option value="{{$city->id}}" @if(in_array($city->id,$driver->cities_in_edit_driver()->toArray())) selected @endif>
													    {{$city->name}}
                                                    </option>
                                                    @endforeach
											</select>
											</div>
                                                     <div class="form-group">
                                                            <label>{{__('admin.drivers.password')}}</label>
                                                            <input type="password" class="form-control" name="password" value="">
                                                        </div>
                                                    <div class="form-group">
                                                            <label>{{__('admin.drivers.verify_password')}}</label>
                                                            <input type="password" class="form-control" name="verify_password" value="">
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
      $('#city').select2({
            placeholder: "{{__('admin.drivers.addDriver.chooseCity')}}"
        });
</script>
@endsection