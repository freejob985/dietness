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
											<h3 class="card-label">{{__('admin.Orders.index')}}</h3>
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
													<input type="text" class="form-control" id="kt_datepicker_1" readonly="readonly" placeholder="Select date" />
												</div>
                                                <div class="col-md-3 my-2 my-md-0">
													@php
													  $timeFrames = ['AM','PM'];
													@endphp
													<select class="form-control m-select2" id="choose_time_frame">
                                                                <option></option>
                                                                <option value="null">{{__('admin.all')}}</option>
																@foreach($timeFrames as $timeFrame)
                                                                 <option value="{{$timeFrame}}">{{__('admin.Orders.'.$timeFrame)}}</option>
																 @endforeach
                                                              
															</select>
													</div>
                                                    <div class="col-md-3 my-2 my-md-0 export_days">
                                                        <button day="" id="export_in_day" type="button" class="btn btn-primary">
                                                        <span>
																<i class="la 	la-file-excel-o"></i>
																<span>
																	تصدير جميع الفواتير
																</span>
															</span>
                                                        </button>
                                                            </div>
                                                            <div class="col-lg-9 col-xl-8">
													<div class="row align-items-center">
                                                        <div class="col-md-2 my-2 my-md-0 export_rows" style="display:none;margin-top: 20px !important;">
                                                        <button id="export" type="button" class="btn btn-primary">
                                                        <span>
																<i class="la 	la-file-excel-o"></i>
																<span>
																	تصدير
																</span>
															</span>
                                                        </button>
                                                            </div>
                                                            <div class="col-md-2 my-2 my-md-0 delete_rows" style="display:none;margin-top: 20px !important;">
                                                                <button id="delete" type="button" class="btn btn-primary">
                                                                <span>
                                                                        <i class="la la-remove"></i>
                                                                        <span>
                                                                            حذف
                                                                        </span>
                                                                    </span>
                                                                </button>
                                                                    </div>
                                                                    <div class="col-md-3 my-2 my-md-0 complete_rows" style="display:none;margin-top: 20px !important;">
                                                                        <button id="completed" type="button" class="btn btn-primary">
                                                                        <span>
                                                                                <i class="la la-truck"></i>
                                                                                <span>
                                                                                    اكمال الطلبات
                                                                                </span>
                                                                            </span>
                                                                        </button>
                                                                            </div>
</div></div>
                                                                    
													</div>
												</div>
										
											</div>
										</div>
										<!--end::Search Form-->
										<!--end: Search Form-->
										<!--begin: Datatable-->
                                    <div class="datatable datatable-bordered datatable-head-custom" id="usersTable"></div>
										<!--end: Datatable-->
                                        <div id="print_report">
                                                                                 <table class="table" id="report_products" style="margin-top: 25px;">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{__('admin.Orders.meal_name')}}</th>
      <th scope="col">{{__('admin.Orders.category')}}</th>
      <th scope="col">{{__('admin.Orders.size_and_count')}}</th>
      <th scope="col">{{__('admin.Orders.meals_count')}}</th>
    </tr>
  </thead>
  <tbody id="products_report_body">
      </tbody>
</table>
                                </div>
                                <h5 style="text-align: right;">الطلبات المجمدة</h5>
                                <div id="restricted_days">
                                    <table class="table" id="restricted_days_table" style="margin-top: 25px;">
<thead>
<tr>
<th scope="col">#</th>
<th scope="col">{{__('admin.Orders.username')}}</th>
<th scope="col">{{__('admin.Orders.day')}}</th>
</tr>
</thead>
<tbody id="products_report_body">
</tbody>
</table>
</div>
									</div>
								</div>
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
@endsection
@section('foot')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js" integrity="sha512-Izh34nqeeR7/nwthfeE0SI3c8uhFSnqxV0sI9TvTcXiFJkMd6fB644O64BRq2P/LA/+7eRvCw4GmLsXksyTHBg==" crossorigin="anonymous"></script>
<script>
var lang = '{!! app()->getLocale(); !!}';
$(document).ready(function (){
    $('#choose_time_frame').select2({placeholder: '{!! __("admin.choose_time_frame")!!}'});
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
                             var products = raw.products;
                             var restricted_orders = raw.restricted;
                                var html = '';
                                var html2 = '';
                                var total = 0;
                                var totalShow = 0;
                                raw.products.forEach(function(item,index){
                                    var rowId = index+1;
                                    var data = item.data;
                                    var counts = '';
                                    var total_qty = 0;
                                    data.forEach(function(da,i){
                                        counts += '(<span>'+da.plan['description_'+lang] +' / '+ da.count +'</span>)';
                                        total_qty = total_qty + da.count;
                                        if(i != data.length - 1){
                                           counts += ' - ';   
                                        }
                                    });
                                    html += '<tr><td>'+rowId+'</td><td>'+item.product['name_'+lang]+'</td>><td>'+item.product.category_obj['title_'+lang]+'</td><td>'+counts+'</td><td>'+total_qty+'</td></tr>';
                                });
                                raw.restricted.forEach(function(item,index){
                                    var rowId = index+1;
                                    html2 += '<tr><td>'+rowId+'</td><td>'+item.user_obj.name+'</td><td>'+item.day+'</td></tr>';
                                });
                                
                                $('#report_products tbody').html(html);
                                $('#report_products').show();
                                $('#restricted_days_table tbody').html(html2);
                                $('#restricted_days_table').show();
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
					return row.id;
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
				if(row.user_obj.address == null){
				    return '';
				}
                    if(row.user_obj.address.region){
                        address.push(row.user_obj.address.region['name_'+lang]);
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
			}   ,        {
				field: "status",
				title: "{!! __('admin.Orders.status')!!}",
				template: function (row) {
					// callback function support for column rendering
                    var status = {
                        'NEW':{'text_en' : 'NEW' ,'text_ar' : 'جديد'},
                        'COMPLETED':{'text_en' : 'COMPLETED' ,'text_ar' : 'تم التوصيل'},
                        'IN-ROUTE':{'text_en' : 'IN-ROUTE' ,'text_ar' : 'في الطريق'},
                    };
					return status[row.status]['text_'+lang];
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
					var url = '{!! route("admin_viewOrder",":id")!!}';
                    url = url.replace(':id', row.id);
                    var childrens = '<a type="button" href="'+url+'" class="btn btn-success btn-sm">'+'{!! __("admin.Orders.viewOrder")!!}'+'</a>';
                    return childrens;
				}
			}   
               
          
                     ],

        });
        $('#kt_datepicker_1').on('change', function () {
			// shortcode to datatable.getDataSourceParam('query');
			var query = datatable.getDataSourceQuery();
			query.day = $(this).val().toLowerCase();
            $('#export_in_day').attr('day',$(this).val().toLowerCase());
			// shortcode to datatable.setDataSourceParam('query', query);
			datatable.setDataSourceQuery(query);
			datatable.load();
		  });
          $('#choose_time_frame').on('change', function () {
			// shortcode to datatable.getDataSourceParam('query');
			var query = datatable.getDataSourceQuery();
			query.time_frame = $(this).val();
			// shortcode to datatable.setDataSourceParam('query', query);
			datatable.setDataSourceQuery(query);
			datatable.load();
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
					$('.delete_rows').collapse('show');
					$('.complete_rows').collapse('show');
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
					$('.delete_rows').collapse('hide');
					$('.complete_rows').collapse('hide');
				}
			});
            $('#export').on('click',function(){
                var ordersID = selectedIDS;
                var url = '?';
                ordersID.forEach(function(item,index){
                    if(index != ordersID.length-1){
                        url += 'orders[]=' + item + '&';
                    }else{
                        url += 'orders[]=' + item ;
                    }
                    
                });
                window.location.href = "{!! route('admin_exportOrders')!!}" + url;
            });
            $('#export_in_day').on('click',function(){
                var day = $(this).attr('day');
                var url = "{!! route('admin_exportOrdersinDay')!!}";
                if(day != '' && day != null && typeof day !== "undefined"){
                    url += '?day='+day;
                }
                window.location.href =  url;
            });
            
    $(document).on('click','#delete',function (e){
        e.preventDefault();
        $('#confirm_remove').modal('show');
    });
            $('#remove_confirm_btn').on('click',function(){
                var ordersID = selectedIDS;
                var url = '?';
                ordersID.forEach(function(item,index){
                    if(index != ordersID.length-1){
                        url += 'orders[]=' + item + '&';
                    }else{
                        url += 'orders[]=' + item ;
                    }
                    
                });
                window.location.href = "{!! route('admin_deleteOrders')!!}" + url;
            });
            $('#completed').on('click',function(){
                var ordersID = selectedIDS;
                var url = '?';
                ordersID.forEach(function(item,index){
                    if(index != ordersID.length-1){
                        url += 'orders[]=' + item + '&';
                    }else{
                        url += 'orders[]=' + item ;
                    }
                    
                });
                window.location.href = "{!! route('admin_completeOrders')!!}" + url;
            });
            
});

</script>
@endsection