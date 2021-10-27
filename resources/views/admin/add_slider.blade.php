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
												<h3 class="card-title">{{__('admin.sliders.add_slider')}}</h3>
												
											</div>
											<!--begin::Form-->
											<form action="{{route($prefix.'_add_post_slider')}}" method="post" enctype="multipart/form-data">
                                                                                   {{csrf_field()}}
												<div class="card-body">
                                                    <div class="col-lg-4 col-md-9 col-sm-12 image_uploader_option" style="margin: 10px 0;padding: 0;">
                                                          <input hidden="hidden" class="uploaded_image" id="gallery_image" name="image" type="file">
											<div class="dropzone_gallery">
												<div class="addimagebutton_on_gallery">
                                                   
                                                    <i class="fa fa-plus"></i>
                                                    <p>{{__('admin.addImage')}}</p>
                                                    </div>
											</div>
										</div>
        <div class="row" style="padding-top: 30px;">
      <div class="col-lg-1">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#arabic" role="tab" aria-controls="v-pills-home" aria-selected="true">{{__('admin.sliders.arabic')}}</a>
                    <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#english" role="tab" aria-controls="v-pills-profile" aria-selected="false">{{__('admin.sliders.english')}}</a>
            </div>
                                                    </div>
<div class="col-lg-11">
                                                    <div class="tab-content" id="language_toggle">
  <div class="tab-pane fade show active" id="arabic" role="tabpanel" aria-labelledby="v-pills-home-tab">
                <div class="">
                        <div class="form-group">
                            <label>{{__('admin.sliders.first_word')}}</label>
                            <input type="text" class="form-control" name="first_word_ar" value="{{old('first_word_ar')}}">
                            </div>
                        <div class="form-group">
                            <label>{{__('admin.sliders.second_word')}}</label>
                            <input type="text" class="form-control" name="second_word_ar" value="{{old('second_word_ar')}}">
                            </div>
                          <div class="form-group">
                            <label>{{__('admin.sliders.description')}}</label>
                              <textarea type="text" class="form-control" name="description_ar">{{old('description_ar')}}</textarea>
                            </div>
                        <div class="row">
                                    <div class="col-lg-6">
                            <div class="form-group">
                            <label>{{__('admin.sliders.first_btn')}}</label>
                            <input type="text" class="form-control" name="first_btn_ar" value="{{old('first_btn_ar')}}">
                            </div>
                            </div>
                                    <div class="col-lg-6">
                            <div class="form-group">
                            <label>{{__('admin.sliders.second_btn')}}</label>
                            <input type="text" class="form-control" name="second_btn_ar" value="{{old('second_btn_ar')}}">
                            </div>
                            </div>
                                                            </div>
      </div>
                                                        </div>
  <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                               <div class="">
                        <div class="form-group">
                            <label>{{__('admin.sliders.first_word')}}</label>
                            <input type="text" class="form-control" name="first_word_en" value="{{old('first_word_en')}}">
                            </div>
                        <div class="form-group">
                            <label>{{__('admin.sliders.second_word')}}</label>
                            <input type="text" class="form-control" name="second_word_en" value="{{old('second_word_en')}}">
                            </div>
                          <div class="form-group">
                            <label>{{__('admin.sliders.description')}}</label>
                              <textarea type="text" class="form-control" name="description_en">{{old('description_en')}}</textarea>
                            </div>
                        <div class="row">
                                    <div class="col-lg-6">
                            <div class="form-group">
                            <label>{{__('admin.sliders.first_btn')}}</label>
                            <input type="text" class="form-control" name="first_btn_en" value="{{old('first_btn_en')}}">
                            </div>
                            </div>
                                    <div class="col-lg-6">
                            <div class="form-group">
                            <label>{{__('admin.sliders.second_btn')}}</label>
                            <input type="text" class="form-control" name="second_btn_en" value="{{old('second_btn_en')}}">
                            </div>
                            </div>
                                                            </div>
      </div>
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