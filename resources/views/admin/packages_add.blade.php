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
												<h3 class="card-title">{{__('admin.packages.add_package')}}</h3>
												
											</div>
											<!--begin::Form-->
											<form action="{{route($prefix.'_add_post_packages')}}" method="post" enctype="multipart/form-data">
                                                                                   {{csrf_field()}}
												<div class="card-body">
													<div class="form-group">
														<label>{{__('admin.packages.name_en')}}</label>
														<input type="text" class="form-control" name="name_en" value="{{old('name_en')}}">
													</div>
                                                    <div class="form-group">
                                                            <label>{{__('admin.packages.name_ar')}}</label>
                                                            <input type="text" class="form-control" name="name_ar" value="{{old('name_ar')}}">
                                                        </div>
                                                         <div class="col-lg-4 col-md-9 col-sm-12 image_uploader_option" style="margin: 10px 0;padding: 0;">
                                                          <input hidden="hidden" class="uploaded_image" id="gallery_image" name="image" type="file">
											<div class="dropzone_gallery">
												<div class="addimagebutton_on_gallery">
                                                   
                                                    <i class="fa fa-plus"></i>
                                                    <p>{{__('admin.addImage')}}</p>
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
@section('foot')
<script>
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