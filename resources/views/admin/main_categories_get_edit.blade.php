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
												<h3 class="card-title">{{__('admin.main_categories.edit_category')}}</h3>
												
											</div>
											<!--begin::Form-->
											<form action="{{route($prefix.'_main_categories_post_edit',['id' => $main_category->id])}}" method="post">
                                                                                   {{csrf_field()}}
												<div class="card-body">
													<div class="form-group">
														<label>{{__('admin.main_categories.name_en')}}</label>
														<input type="text" class="form-control" name="name_en" value="{{$main_category->title_en}}">
													</div>
                                                    <div class="form-group">
                                                            <label>{{__('admin.main_categories.name_ar')}}</label>
                                                            <input type="text" class="form-control" name="name_ar" value="{{$main_category->title_ar}}">
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

</script>
@endsection