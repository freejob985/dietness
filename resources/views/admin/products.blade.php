<style>
    #usersTable .datatable-table .datatable-body tr{
        cursor: grabbing;
    }
</style>
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
											<h3 class="card-label">{{__('admin.products.products')}}</h3>
										</div>
										<div class="card-toolbar">
											<!--begin::Button-->
											<a href="{{route($prefix.'_add_get_products')}}" class="btn btn-primary font-weight-bolder">
											<span class="svg-icon"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Code\Plus.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
        <path d="M11,11 L11,7 C11,6.44771525 11.4477153,6 12,6 C12.5522847,6 13,6.44771525 13,7 L13,11 L17,11 C17.5522847,11 18,11.4477153 18,12 C18,12.5522847 17.5522847,13 17,13 L13,13 L13,17 C13,17.5522847 12.5522847,18 12,18 C11.4477153,18 11,17.5522847 11,17 L11,13 L7,13 C6.44771525,13 6,12.5522847 6,12 C6,11.4477153 6.44771525,11 7,11 L11,11 Z" fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span>{{__('admin.products.add_product')}}</a>
											<!--end::Button-->
										</div>
									</div>		
									<div class="card-body">
										<!--begin: Search Form-->
										<!--begin::Search Form-->
										<div class="mb-7">
											<div class="row align-items-center">
												<div class="col-lg-12 col-xl-12">
													<div class="row align-items-center">
														<div class="col-md-3 my-2 my-md-0">
															<div class="input-icon">
																<input type="text" class="form-control" placeholder="Search..." id="kt_datatable_search_query" />
																<span>
																	<i class="flaticon2-search-1 text-muted"></i>
																</span>
															</div>
														</div>
													<div class="col-md-3 my-2 my-md-0">
													@php
													  $statusArr = ['Normal', 'Hot', 'NotAvailable'];
													  $days = [6,0,1,2,3,4];
													  $categories = \App\Models\main_categories::get();
													@endphp
													<select class="form-control m-select2" id="choose_day">
                                                                <option></option>
                                                                <option value="null">{{__('admin.all')}}</option>
																@foreach($days as $day)
                                                                 <option value="{{$day}}">{{__('admin.products.days.'.$day)}}</option>
																 @endforeach
                                                              
															</select>
													</div>
													<div class="col-md-3 my-2 my-md-0">
													<select class="form-control m-select2" id="status">
                                                                <option></option>
                                                                <option value="null">{{__('admin.all')}}</option>
																@foreach($statusArr as $status)
                                                                 <option value="{{$status}}">{{__('admin.products.statuses.'.$status)}}</option>
																 @endforeach
                                                              
															</select>
													</div>
													<div class="col-md-3 my-2 my-md-0">
													<select class="form-control m-select2" id="category">
                                                                <option></option>
                                                                <option value="null">{{__('admin.all')}}</option>
																@foreach($categories as $category)
                                                                 <option value="{{$category->id}}">{{$category->title}}</option>
																 @endforeach
                                                              
															</select>
													</div>
													</div>
												</div>
										
											</div>
										</div>
										<!--end::Search Form-->
										<!--end: Search Form-->
										<!--begin: Datatable-->
                                        <div class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded">
										<div class="datatable datatable-bordered datatable-head-custom" id="usersTable"></div>
									
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js" integrity="sha512-Izh34nqeeR7/nwthfeE0SI3c8uhFSnqxV0sI9TvTcXiFJkMd6fB644O64BRq2P/LA/+7eRvCw4GmLsXksyTHBg==" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
var lang = '{!! app()->getLocale(); !!}';
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
	$('#choose_day').select2({placeholder: '{!! __("admin.choose_day")!!}'});
	$('#status').select2({placeholder: '{!! __("admin.status")!!}'});
	$('#category').select2({placeholder: '{!! __("admin.category")!!}'});
    var datatable = $('#usersTable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        method: 'GET',
                        url: '{!! route($prefix."_products") !!}',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function(raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                             $( function() {
            $( "#usersTable .datatable-table .datatable-body" ).sortable({
        stop: function(event, ui) {
        var data = [];

        $("#usersTable .datatable-table .datatable-body tr .row_id").each(function(i, el){
            data.push($(this).attr('data'));
        });
            if(data.length > 0){
                    $.ajax({
                    type: 'POST',
                    url: '{!! route("admin_change_products_order") !!}',
                    data: {products  : data },
                    dataType: 'json',
                    success: function(e) {
                  
                    },
                    error: function(e) {
                        console.log(e);
        }
                });
            }
    }
    });
          } );
                            return dataSet;
                        },
                    },
                },
                pageSize: 1000,
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
            },

            // layout definition
            layout: {
                scroll: false,
                footer: false,
            },

            // column sorting
            sortable: false,
            toolbar : {
                items : {
                    pagination : {
                        pageSizeSelect: [1000]
                    }
                }
            },
            pagination: true,

            search: {
                input: $('#kt_datatable_search_query'),
                key: 'generalSearch'
            },

            // columns definition
            columns: [{
                field: 'id',
                title: '#',
                sortable: 'asc',
                width: 30,
                type: 'number',
                textAlign: 'center',
                template: function (row,index) {
					// callback function support for column rendering
					return '<span data="'+row.id+'" class="row_id">'+row.id+'</span>';
				}
            },
			{
				field: "name",
				title: "{!! __('admin.products.name')!!}",
				template: function (row) {
					// callback function support for column rendering
					return row['name_'+lang];
				}
			},
			{
				field: "parent_category",
				title: "{!! __('admin.products.parent_category')!!}",
				template: function (row) {
					// callback function support for column rendering
					return row.category_obj['title_'+lang];
				}
			},
			{
				field: "status",
				title: "{!! __('admin.products.status')!!}",
				template: function (row) {
					// callback function support for column rendering
					var status = {
						'Normal' : {title_en : 'Normal' , title_ar:'عادي'},
						'Hot' : {title_en : 'Hot' , title_ar:'مميز'},
						'NotAvailable' : {title_en : 'NotAvailable' , title_ar:'غير متوفر'},
					};
					return status[row.status]['title_'+lang];
				}
			},
			{
				field: "days",
				title: "{!! __('admin.related_days')!!}",
				template: function (row) {
					var days = {
						'6' : {title_en : 'Saturday' , title_ar : 'السبت'},
						'0' : {title_en : 'Sunday' , title_ar : 'الاحد'},
						'1' : {title_en : 'Monday' , title_ar : 'الاثنين'},
						'2' : {title_en : 'Tuesday' , title_ar : 'الثلاثاء'},
						'3' : {title_en : 'Wednesday' , title_ar : 'الاربعاء'},
						'4' : {title_en : 'Thursday' , title_ar : 'الخميس'},
					};
					var html = '';
					var daysArr = row.days;
					daysArr.forEach(day => {
						html += '<span class="label label-primary label-inline font-weight-lighter mr-2" style="margin-bottom: 2px;">'+days[day.day]['title_'+lang]+'</span>';
					});
					return html;
				}
			},
			{
				field: "created_at",
				title: "{!! __('admin.Orders.created_at')!!}",
				template: function (row) {
					// callback function support for column rendering
					return moment(row.created_at).format('YYYY-MM-DD');
				}
			} ,
			{
				field: "options",
				title: "{!! __('admin.Orders.options')!!}",
				template: function (row) {
					// callback function support for column rendering
					var url1 = '{!! route("admin_products_edit",":id")!!}';
                    url1 = url1.replace(':id', row.id);
					var url2 = '{!! route("admin_get_products_remove",":id")!!}';
                    url2 = url1.replace(':id', row.id);
                    var childrens = '<a title="'+"{!!__('admin.products.edit_products')!!}"+'" href="'+url1+'"><i class="icon-xl la la-edit"></i></a><a title="'+"{!!__('admin.products.remove_products')!!}"+'" href="#" class="remove_btn" data="'+url2+'"><i class="icon-xl la la-trash"></i></a>';
                    return childrens;
				}
			}
			
               
          
                     ],

        });
		$('#choose_day').on('change', function () {
			// shortcode to datatable.getDataSourceParam('query');
			var query = datatable.getDataSourceQuery();
			query.day = $(this).val().toLowerCase();
			// shortcode to datatable.setDataSourceParam('query', query);
			datatable.setDataSourceQuery(query);
			datatable.load();
		  });
		  $('#category').on('change', function () {
			// shortcode to datatable.getDataSourceParam('query');
			var query = datatable.getDataSourceQuery();
			query.category = $(this).val().toLowerCase();
			// shortcode to datatable.setDataSourceParam('query', query);
			datatable.setDataSourceQuery(query);
			datatable.load();
		  });
		  $('#status').on('change', function () {
			// shortcode to datatable.getDataSourceParam('query');
			var query = datatable.getDataSourceQuery();
			query.status = $(this).val().toLowerCase();
			// shortcode to datatable.setDataSourceParam('query', query);
			datatable.setDataSourceQuery(query);
			datatable.load();
		  });
		 
});
</script>
@endsection