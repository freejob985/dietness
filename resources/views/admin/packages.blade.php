@php
 $prefix = 'admin';
 use \Carbon\Carbon;
@endphp
@extends('admin.layout.index')
@section('content')
  @if(session()->has('message'))
     <div class="alert alert-success danger-errors">
      <p>{{session()->get('message')}}</p>
    </div>
    @endif
  @if(session()->has('error'))
     <div class="alert alert-danger danger-errors">
      <p>{{session()->get('error')}}</p>
    </div>
    @endif
<div class="card card-custom">
							<div class="card-header flex-wrap border-0 pt-6 pb-0">
										<div class="card-title">
											<h3 class="card-label">{{__('admin.packages.packages')}}</h3>
										</div>
										<div class="card-toolbar">
											<!--begin::Button-->
											<a href="{{route($prefix.'_add_get_packages')}}" class="btn btn-primary font-weight-bolder">
											<span class="svg-icon"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Code\Plus.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
        <path d="M11,11 L11,7 C11,6.44771525 11.4477153,6 12,6 C12.5522847,6 13,6.44771525 13,7 L13,11 L17,11 C17.5522847,11 18,11.4477153 18,12 C18,12.5522847 17.5522847,13 17,13 L13,13 L13,17 C13,17.5522847 12.5522847,18 12,18 C11.4477153,18 11,17.5522847 11,17 L11,13 L7,13 C6.44771525,13 6,12.5522847 6,12 C6,11.4477153 6.44771525,11 7,11 L11,11 Z" fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span>{{__('admin.packages.add_package')}}</a>
											<!--end::Button-->
										</div>
									</div>		
									<div class="card-body">
										<!--begin: Search Form-->
										<!--begin::Search Form-->
										<div class="mb-7">
											<div class="row align-items-center">
												<div class="col-lg-9 col-xl-8">
													<div class="row align-items-center">
														<div class="col-md-4 my-2 my-md-0">
															<div class="input-icon">
																<input type="text" class="form-control" placeholder="Search..." id="kt_datatable_search_query" />
																<span>
																	<i class="flaticon2-search-1 text-muted"></i>
																</span>
															</div>
														</div>
													
													</div>
												</div>
										
											</div>
										</div>
										<!--end::Search Form-->
										<!--end: Search Form-->
										<!--begin: Datatable-->
                                        <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded">
										<table class="datatable datatable-bordered datatable-head-custom" id="kt_datatable">
											<thead>
												<tr>
													<th title="Field #1">#</th>
													<th title="Field #2">{{__('admin.packages.name')}}</th>
													<th title="Field #4">{{__('admin.packages.created_at')}}</th>
													<th title="Field #5">{{__('admin.packages.options')}}</th>
												
												</tr>
											</thead>
											<tbody>
                                               
                                                 @foreach($packages as $package)
												<tr>
													<td>{{$loop->iteration}}</td>
													<td>{{$package->title}}</td>
													<td>{{Carbon::parse($package->created_at)->format('Y-m-d')}}</td>
													<td>
                                                        <a title="{{__('admin.packages.add_plans')}}" href="{{route($prefix.'_packages_add_plan',['id' => $package->id])}}"><i class="icon-xl la la-book-medical"></i></a>
                                                        <a title="{{__('admin.packages.edit_package')}}" href="{{route($prefix.'_packages_edit',['id' => $package->id])}}"><i class="icon-xl la la-edit"></i></a>
                                                        <a title="{{__('admin.packages.remove_package')}}" href="#" class="remove_btn" data="{{route($prefix.'_packages_remove',['id' => $package->id])}}"><i class="icon-xl la la-trash"></i></a>
                                                    
                                                    </td>
													
												</tr>
												@endforeach
                                                
												
											</tbody>
										</table>
                                            </div>
										<!--end: Datatable-->
									</div>
								</div>
@endsection
@section('foot')
<!-- Modal-->
<div class="modal fade" id="confirm_remove" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('admin.confirm_message')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body text-center">
                {{__('admin.please_confirm_to_remove')}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{__('admin.cancel')}}</button>
                <button type="button" class="btn btn-primary font-weight-bold" id="remove_confirm_btn">{{__('admin.confirm')}}</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).on('click','.remove_btn',function (e){
        e.preventDefault();
        var url = $(this).attr('data');
        $('#remove_confirm_btn').attr('data',url);
        $('#confirm_remove').modal('show');
    });
$(document).on('click','#remove_confirm_btn',function (e){
        var url = $(this).attr('data');
        window.location.href = url;
    });
    
$(document).ready(function (){
    var datatable = $('#kt_datatable').KTDatatable({
			data: {
				saveState: {cookie: false},
			},
			search: {
				input: $('#kt_datatable_search_query'),
				key: 'generalSearch'
			}
    });
});
</script>
@endsection