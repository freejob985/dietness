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
												<h3 class="card-title">{{__('admin.products.edit_products')}}</h3>
												
											</div>
											<!--begin::Form-->
											<form action="{{route($prefix.'_post_products_edit',['id' => $product->id])}}" method="post" enctype="multipart/form-data">
                                                                                   {{csrf_field()}}
												<div class="card-body">
													<div class="form-group">
														<label>{{__('admin.products.name_en')}}</label>
														<input type="text" class="form-control" name="name_en" value="{{$product->name_en}}">
													</div>
                                                    <div class="form-group">
                                                            <label>{{__('admin.products.name_ar')}}</label>
                                                            <input type="text" class="form-control" name="name_ar" value="{{$product->name_ar}}">
                                                        </div>
                                                    <div class="col-lg-4 col-md-9 col-sm-12 image_uploader_option" style="margin: 10px 0;padding: 0;">
                                                          <input hidden="hidden" class="uploaded_image" id="gallery_image" name="image" type="file">
											<div class="dropzone_gallery" style="background-image:url({{asset('/uploads/products/'.$product->image)}})">
												<div class="addimagebutton_on_gallery">
                                                   
                                                    <i class="fa fa-plus"></i>
                                                    <p>{{__('admin.addImage')}}</p>
                                                    </div>
											</div>
										</div>
                                                      <div class="form-group">
                                                @php
                                                 $days = [6,0,1,2,3,4];
                                                @endphp
                                                            <label>{{__('admin.products.day')}}</label>
                                                            <select class="form-control m-select2" id="day" name="day[]" multiple>
                                                                @foreach($days as $day)
                                                                    <option value="{{$day}}" @if(in_array($day,$product->days_to_edit_product()->toArray())) selected @endif>{{__('admin.products.days.'.$day)}}</option>
                                                                @endforeach

                                                        </select>
                                                </div>
                                                     <div class="form-group">
                                                @php
                                                 $statusArr = ['Normal','Hot','NotAvailable'];
                                                @endphp
                                                            <label>{{__('admin.products.status')}}</label>
                                                            <select class="form-control" id="status" name="status">
                                                                <option selected disabled>{{__('admin.products.statuses.chooseStatus')}}</option>
                                                                @foreach($statusArr as $status)
                                                                    <option value="{{$status}}" @if($product->status == $status) selected @endif>{{__('admin.products.statuses.'.$status)}}</option>
                                                                @endforeach

                                                        </select>
                                                </div>
                                                    <div class="form-group">
                                                            <label>{{__('admin.products.category')}}</label>
                                                            <select class="form-control" id="category" name="category">
                                                                @foreach($main_categories as $category)
                                                                    <option value="{{$category->id}}" @if($product->category == $category->id) selected @endif>{{$category->title}}</option>
                                                                @endforeach

                                                        </select>
                                                    </div>
                                                       <div class="form-group">
                                                            <label>{{__('admin.products.description_en')}}</label>
                                                           <textarea type="text" class="form-control" name="description_en" rows="4">{{$product->description_en}}</textarea>
                                                        </div>
                                                       <div class="form-group">
                                                            <label>{{__('admin.products.description_ar')}}</label>
                                                           <textarea type="text" class="form-control" name="description_ar" rows="4">{{$product->description_ar}}</textarea>
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
$('#day').select2({
        placeholder: "{{__('admin.products.days.chooseDay')}}"
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