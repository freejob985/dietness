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
												<h3 class="card-title">{{__('admin.users.add_user')}}</h3>
												
											</div>
											<!--begin::Form-->
											<form action="{{route($prefix.'_post_new_users')}}" method="post">
                                                                                   {{csrf_field()}}
												<div class="card-body">
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
       $('#statuses').select2({
            placeholder: "{!! __('admin.users.choose_status') !!}"
        });  
</script>
@endsection