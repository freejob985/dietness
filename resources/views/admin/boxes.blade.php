@php
 $prefix = 'admin';
@endphp
@extends('admin.layout.index')
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
											<h3 class="card-label">{{__('admin.boxes')}}</h3>
										</div>
                                        <div class="card-toolbar">
											<!--begin::Button-->
								
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
                                                        <div class="col-lg-3 col-md-9 col-sm-12">
													<input type="text" class="form-control" id="kt_datepicker_2" readonly="readonly" placeholder="From" />
												</div>
											
                                                <div class="col-lg-3 col-md-9 col-sm-12">
													<input type="text" class="form-control" id="kt_datepicker_3" readonly="readonly" placeholder="To" />
												</div>
													<div class="col-md-3 my-2 my-md-0">
													@php
													  $statusArr = ['ACTIVE','NOTACTIVE'];
													@endphp
													<select class="form-control m-select2" id="status">
                                                                <option></option>
                                                                <option value="null">{{__('admin.all')}}</option>
																@foreach($statusArr as $status)
                                                                 <option value="{{$status}}">{{__('admin.subscriptions.statusArr.'.$status)}}</option>
																 @endforeach
                                                              
															</select>
													</div>
                                                <div class="col-md-2 export_rows" style="display:none;margin-top: 20px;">
                                                        <button id="export" type="button" class="btn btn-primary" style="width: 100%;">
                                                        <span>
																<i class="la 	la-file-excel-o"></i>
																<span>
																	تصدير
																</span>
															</span>
                                                        </button>
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
var lang = '{!! app()->getLocale(); !!}';
$('#status').select2({placeholder: '{!! __("admin.subscriptions.choose_status")!!}'});
$(document).ready(function (){
 var datatable = $('#usersTable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        method: 'GET',
                        url: '{!! route($prefix."_boxes") !!}',
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
                field: 'id',
                title: '#',
                sortable: 'asc',
                width: 30,
                type: 'number',
                textAlign: 'center',
                selector: {class: 'm-checkbox--solid m-checkbox--brand'},
                template: function (row,index) {
					// callback function support for column rendering
					return index+1;
				}
            },
                        {
				field: "name",
				title: "{!! __('admin.subscriptions.username')!!}",
				template: function (row) {
					// callback function support for column rendering
					return row.user.name;
				}
			},
            {
				field: "mobile",
				title: "{!! __('admin.subscriptions.mobile')!!}",
				template: function (row) {
					// callback function support for column rendering
					return row.user.mobile;
				}
			} 
              ,
                             {
				field: "package",
				title: "{!! __('admin.subscriptions.package')!!}",
				template: function (row) {
					// callback function support for column rendering
					return row.package['title_'+lang];
				}
			},
            {
				field: "plan",
				title: "{!! __('admin.subscriptions.plan')!!}",
				template: function (row) {
					// callback function support for column rendering
					return row.plan_obj['description_'+lang];
				}
			},
                              {
				field: "date_from",
				title: "{!! __('admin.subscriptions.date_from')!!}",
				template: function (row) {
					// callback function support for column rendering
					return moment(row.from).format('YYYY-MM-DD');
				}
			},
                              {
				field: "date_to",
				title: "{!! __('admin.subscriptions.date_to')!!}",
				template: function (row) {
					// callback function support for column rendering
					return moment(row.to).format('YYYY-MM-DD');
				}
			},
			               {
				field: "remaining_boxes",
				title: "{!! __('admin.remaining_boxes')!!}",
				template: function (row) {
					// callback function support for column rendering
					return row.remaining_boxes;
				}
			}
                     ],

        });
        $('#kt_datepicker_2').on('change', function () {
			// shortcode to datatable.getDataSourceParam('query');
			var query = datatable.getDataSourceQuery();
			query.from = $(this).val().toLowerCase();
			// shortcode to datatable.setDataSourceParam('query', query);
			datatable.setDataSourceQuery(query);
			datatable.load();
		  });
          $('#kt_datepicker_3').on('change', function () {
			// shortcode to datatable.getDataSourceParam('query');
			var query = datatable.getDataSourceQuery();
			query.from = $('#kt_datepicker_2').val().toLowerCase();
			query.to = $(this).val().toLowerCase();
			// shortcode to datatable.setDataSourceParam('query', query);
			datatable.setDataSourceQuery(query);
			datatable.load();
		  });
		    $('#status').on('change', function () {
			// shortcode to datatable.getDataSourceParam('query');
			var query = datatable.getDataSourceQuery();
			query.status = $(this).val();
			// shortcode to datatable.setDataSourceParam('query', query);
			datatable.setDataSourceQuery(query);
			datatable.load();
		  });
          let selectedIDS = [];
    $('#usersTable')
			.on('datatable-on-check', function (e, args) {
				var count = datatable.getSelectedRecords().length;
                var rows = datatable.getSelectedRecords();
                selectedIDS = [];
            rows.each(function(index,item){
                var get_index = $(item).attr("data-row");
                selectedIDS.push(datatable.getRecord(get_index).dataSet[get_index].id);
                
            });
            console.log(selectedIDS);
				$('#m_datatable_selected_number').html(count);
				if (count > 0) {
					$('.export_rows').collapse('show');
				}
			})
			.on('datatable-on-uncheck datatable-on-layout-updated', function (e, args) {
				var count = datatable.getSelectedRecords().length;
                var rows = datatable.getSelectedRecords();
                selectedIDS = [];
                 rows.each(function(index,item){
                var get_index = $(item).attr("data-row");
                selectedIDS.push(datatable.getRecord(get_index).dataSet[get_index].id);
            });
                console.log(selectedIDS);
				$('#m_datatable_selected_number').html(count);
				if (count === 0) {
					$('.export_rows').collapse('hide');
				}
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
    $('#export').on('click',function(){
                var ordersID = selectedIDS;
                var url = '?';
                ordersID.forEach(function(item,index){
                    if(index != ordersID.length-1){
                        url += 'subscriptions[]=' + item + '&';
                    }else{
                        url += 'subscriptions[]=' + item ;
                    }
                    
                });
                window.location.href = "{!! route('admin_subscriptionExport')!!}" + url;
            });
});
</script>
@endsection