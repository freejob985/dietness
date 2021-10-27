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
												<h3 class="card-title">{{__('admin.settings')}}</h3>
												
											</div>
											<!--begin::Form-->
											<form action="{{route($prefix.'_post_settings')}}" method="post">
                                                                                   {{csrf_field()}}
												<div class="card-body">
												<div class="form-group row">
														<div class="col-4">
															<div class="row">
															<label class="col-6 col-form-label">{{__('admin.delievery_time_frame')}}</label>
														<div class="col-3">
															<span class="switch">
																<label>
																	<input type="checkbox" @if(settings('enable_delievery_timeframes') == 'on') checked="checked" @endif name="delievery_time_frame" />
																	<span></span>
																</label>
															</span>
														</div>
															</div>
														</div>
													</div>
													<div class="form-group row">
														<div class="col-4">
															<div class="row">
															<label class="col-6 col-form-label">{{__('admin.skip_activation')}}</label>
														<div class="col-3">
															<span class="switch">
																<label>
																	<input type="checkbox" @if(settings('skip_activation') == 'on') checked="checked" @endif name="skip_activation" />
																	<span></span>
																</label>
															</span>
														</div>
															</div>
														</div>
													</div>
													<div class="form-group row">
														<div class="col-12">
															<div class="row">
															<label class="col-2 col-form-label">{{__('admin.delivery_notes')}}</label>
														<div class="col-3">
														  <input type="text" class="form-control" value="{{\App\Models\settings::where('key','delivery_notes')->first()->value_en}}" name="delivery_notes_en" placeholder="{{__('admin.delivery_notes_en')}}">
														</div>
														<div class="col-3">
														  <input type="text" class="form-control" value="{{\App\Models\settings::where('key','delivery_notes')->first()->value_ar}}" name="delivery_notes_ar" placeholder="{{__('admin.delivery_notes_ar')}}">
														</div>
															</div>
														</div>
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