@php
 $prefix = 'driver';
@endphp
@extends('driver.layout.index')
@section('content')
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
<div class="card card-custom">
							<div class="card-header flex-wrap border-0 pt-6 pb-0">
										<div class="card-title">
											<h3 class="card-label">{{__('admin.Orders.index')}}</h3>
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
                                    <div class="datatable datatable-bordered datatable-head-custom" id="usersTable"></div>
										<!--end: Datatable-->
									</div>
								</div>
@endsection
@section('foot')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js" integrity="sha512-Izh34nqeeR7/nwthfeE0SI3c8uhFSnqxV0sI9TvTcXiFJkMd6fB644O64BRq2P/LA/+7eRvCw4GmLsXksyTHBg==" crossorigin="anonymous"></script>
<script>
$(document).ready(function (){
 var datatable = $('#usersTable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        method: 'GET',
                        url: '{!! route($prefix."_orders") !!}',
                        // sample custom headers
                        // headers: {'x-my-custom-header': 'some value', 'x-test-header': 'the value'},
                        map: function(raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
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

            pagination: true,

            search: {
                input: $('#kt_datatable_search_query'),
                key: 'generalSearch'
            },

            // columns definition
            columns: [{
                field: 'RecordID',
                title: '#',
                sortable: 'asc',
                width: 30,
                type: 'number',
                selector: false,
                textAlign: 'center',
                template: function (row,index) {
					// callback function support for column rendering
					return index+1;
				}
            },
                        {
				field: "username",
				title: "{!! __('admin.Orders.username')!!}",
				template: function (row) {
					// callback function support for column rendering
					return row.user_obj.name;
				}
			} ,
                        {
				field: "mobile",
				title: "{!! __('admin.Orders.mobile')!!}",
				template: function (row) {
					// callback function support for column rendering
					return row.user_obj.mobile;
				}
			}   
                    ,
                        {
				field: "address",
				title: "{!! __('admin.Orders.address')!!}",
                width : 400,            
				template: function (row) {
					// callback function support for column rendering
					 var address = [];
                    if(row.user_obj.address.region){
                        address.push(row.user_obj.address.region.name_ar);
                    }
                    if(row.user_obj.address.piece){
                        address.push('{!! __("admin.Orders.piece") !!} : '+ row.user_obj.address.piece);
                    }
                    if(row.user_obj.address.street){
                        address.push('{!! __("admin.Orders.street") !!} : ' + row.user_obj.address.street);
                    }
                    if(row.user_obj.address.avenue){
                        address.push('{!! __("admin.Orders.Avenue") !!} : ' + row.user_obj.address.avenue);
                    }
                    if(row.user_obj.address.house){
                        address.push('{!! __("admin.Orders.Home") !!} : ' + row.user_obj.address.house);
                    }
					return address.join(' , ');
				}
			}   
                      ,
                        {
				field: "day",
				title: "{!! __('admin.Orders.day')!!}",
				template: function (row) {
					// callback function support for column rendering
					return moment(row.day).format('YYYY-MM-DD');
				}
			}                         ,
                        {
				field: "created_at",
				title: "{!! __('admin.Orders.created_at')!!}",
				template: function (row) {
					// callback function support for column rendering
					return moment(row.created_at).format('YYYY-MM-DD');
				}
			}   
                                             ,
                        {
				field: "options",
				title: "{!! __('admin.Orders.options')!!}",
				template: function (row) {
					// callback function support for column rendering
					var url = '{!! route("driver_viewOrder",":id")!!}';
                    url = url.replace(':id', row.id);
                    var childrens = '<a type="button" href="'+url+'" class="btn btn-success btn-sm">'+'{!! __("admin.Orders.viewOrder")!!}'+'</a>';
                    return childrens;
				}
			}   
               
          
                     ],

        });
    $(document).on('click','.active_subscription',function(){
        var subscription = $(this).attr('data');
        $.ajax({
                    type: 'POST',
                    url: '{!! route("admin_update_subscription") !!}',
                    data: {id  : subscription},
                    dataType: 'json',
                    success: function(e) {
                       if(e.status == 'done'){
                           $('.options .active_subscription[data="'+e.id+'"]').remove();
                           $('.options').html('-');
                           $('.subscription_status[row="'+e.id+'"]').removeClass('label-danger').addClass('label-success').siblings('.text-danger').addClass('text-success').removeClass('text-danger').text(e.message);
                       }
                    },
                    error: function(e) {
                        console.log(e);
        }
                });
    });
});
</script>
@endsection