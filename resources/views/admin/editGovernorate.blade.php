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
												<h3 class="card-title">{{__('admin.governorates.edit')}}</h3>
												
											</div>
											<!--begin::Form-->
											<form action="{{route($prefix.'_governorates_edit_post',['id' => $Governorate->id])}}" method="post" enctype="multipart/form-data">
                                                                                   {{csrf_field()}}
												<div class="card-body">
													<div class="form-group">
														<label>{{__('admin.products.name_en')}}</label>
														<input type="text" class="form-control" name="name_en" value="{{$Governorate->name_en}}">
													</div>
                                                    <div class="form-group">
                                                            <label>{{__('admin.products.name_ar')}}</label>
                                                            <input type="text" class="form-control" name="name_ar" value="{{$Governorate->name_ar}}">
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
 $('#category').select2({
            placeholder: 'Select a state'
        });
$(".dropzone_gallery").click(function(){
    $(".uploaded_image").click();
});
    
    $('.uploaded_image').change(function (){
         var imageFile = document.getElementById('gallery_image');
         var reader = new FileReader();
         var time = new Date().getTime();
    reader.onload = function(e) {
        $('.dropzone_gallery').css('background-image','url('+e.target.result+')');
    }
    reader.readAsDataURL(imageFile.files[0]);
    });
</script>
@endsection