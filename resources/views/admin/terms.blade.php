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
												<h3 class="card-title">{{__('admin.terms')}}</h3>
												
											</div>
											<!--begin::Form-->
											<form action="{{route($prefix.'_postTerms')}}" method="post">
                                                                                   {{csrf_field()}}
												<div class="card-body">
												<div class="form-group m-form__group row">
										<label class="col-form-label col-lg-3 col-sm-12">
										{{__('admin.text_ar')}}
										</label>
										<div class="col-lg-9 col-md-9 col-sm-12">
											<textarea  name="text_ar" style="width: 100%;" rows="5">{{\App\Models\settings::where('key','terms')->first()->value_ar}}</textarea>
										</div>
									</div>
									
									<div class="form-group m-form__group row">
										<label class="col-form-label col-lg-3 col-sm-12">
											{{__('admin.text_en')}}
										</label>
										<div class="col-lg-9 col-md-9 col-sm-12">
												<textarea  name="text_en" style="width: 100%;" rows="5">{{\App\Models\settings::where('key','terms')->first()->value_en}}</textarea>
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
@section('foot')
<script>
    var SummernoteDemo = function () {    
    //== Private functions
    var demos = function () {
        $('.summernote').summernote({
            height: 150, 
        });
    }

    return {
        // public functions
        init: function() {
            demos(); 
        }
    };
}();
jQuery(document).ready(function() {
    SummernoteDemo.init();
});
</script>
@endsection