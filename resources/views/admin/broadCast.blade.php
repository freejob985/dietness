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
												<h3 class="card-title">{{__('admin.sendBroadCast')}}</h3>
												
											</div>
											<!--begin::Form-->
											<form action="{{route($prefix.'_add_post_broadCast')}}" method="post">
                                                                                   {{csrf_field()}}
												<div class="card-body">
												    <div class="m-form__group form-group">
																<label for="">
																	نوع الارسال
																</label>
																<div class="m-radio-inline">
										
																	<label class="m-radio">
																		<input type="radio" name="send_type" value="to_active">
																		ارسال للمستخدمين النشطين
																		<span></span>
																	</label>
																	<label class="m-radio">
																		<input type="radio" name="send_type" value="to_none_active">
											ارسال للمستخدمين الغير نشطين
																		<span></span>
																	</label>
										<label class="m-radio">
																		<input type="radio" name="send_type" value="to_all">
											للجميع
																		<span></span>
																	</label>
										<label class="m-radio">
																		<input type="radio" name="send_type" value="to_none_users">
											للاعضاء الغير مسجلين
																		<span></span>
																	</label>
														
																</div>
															
															</div>
													<div class="form-group">
														<label>{{__('admin.message')}}</label>
                                                        <textarea type="text" class="form-control" name="message" rows="4">{{old('message')}}</textarea>
													</div>
                                          
                                                   
                                                    </div>
												<div class="card-footer">
													<div class="row">
														<div class="col-12">
															<div class="form_btns_group"><button type="submit" class="btn btn-success mr-2">{{__('admin.send')}}</button>
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