@php
 $prefix = 'admin';
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
											<h3 class="card-label">{{__('admin.users.users')}}</h3>
										</div>
										<div class="card-toolbar">
											<!--begin::Button-->
											
                                            <a href="{{route($prefix.'_new_users')}}" class="btn btn-primary font-weight-bolder">
											<span class="svg-icon"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Code\Plus.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
        <path d="M11,11 L11,7 C11,6.44771525 11.4477153,6 12,6 C12.5522847,6 13,6.44771525 13,7 L13,11 L17,11 C17.5522847,11 18,11.4477153 18,12 C18,12.5522847 17.5522847,13 17,13 L13,13 L13,17 C13,17.5522847 12.5522847,18 12,18 C11.4477153,18 11,17.5522847 11,17 L11,13 L7,13 C6.44771525,13 6,12.5522847 6,12 C6,11.4477153 6.44771525,11 7,11 L11,11 Z" fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span>{{__('admin.users.add_new_user')}}</a>
                                            
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
                                    <div class="datatable datatable-bordered datatable-head-custom" id="usersTable"></div>
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
<script>
$(document).ready(function (){
 var datatable = $('#usersTable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        method: 'GET',
                        url: '{!! route($prefix."_users") !!}',
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
				field: "name",
				title: "{!! __('admin.users.name')!!}",
				template: function (row) {
					// callback function support for column rendering
					return row.name;
				}
			}
                      ,
                        {
				field: "email",
				title: "{!! __('admin.users.email')!!}",
				template: function (row) {
					// callback function support for column rendering
					return row.email;
				}
			}
                      ,
                        {
				field: "mobile",
				title: "{!! __('admin.users.mobile')!!}",
				template: function (row) {
					// callback function support for column rendering
					return row.country_code+ '-' +row.mobile;
				}
			},
		
            {
				field: "address",
				title: "{!! __('admin.users.address')!!}",
				template: function (row) {
					// callback function support for column rendering
                    var address = [];
                    if(row.address == null){
                            return '';
                    }
                    if(row.address.region){
                        address.push(row.address.region.name_ar);
                    }
                    if(row.address.piece){
                        address.push('{!! __("admin.Orders.piece") !!} : '+ row.address.piece);
                    }
                    if(row.address.street){
                        address.push('{!! __("admin.Orders.street") !!} : ' + row.address.street);
                    }
                    if(row.address.avenue){
                        address.push('{!! __("admin.Orders.Avenue") !!} : ' + row.address.avenue);
                    }
                    if(row.address.house){
                        address.push('{!! __("admin.Orders.Home") !!} : ' + row.address.house);
                    }
					return address.join(' , ');
				}
			}
                          ,
                        {
				field: "status",
				title: "{!! __('admin.users.status')!!}",
				template: function (row) {
					// callback function support for column rendering
					var status = {
                        'Active': {
                            'title': '{!! __("admin.users.statuses.Active")!!}',
                            'state': 'success'
                        },
                        'Blocked': {
                            'title': '{!! __("admin.users.statuses.Blocked")!!}',
                            'state': 'danger'
                        },
                        'Pending': {
                            'title': '{!! __("admin.users.statuses.Pending")!!}',
                            'state': 'info'
                        },
                         'Deleted': {
                            'title': '{!! __("admin.users.statuses.Deleted")!!}',
                            'state': 'success'
                        },
                         'Waiting_payment': {
                            'title': '{!! __("admin.users.statuses.Waiting_payment")!!}',
                            'state': 'primary'
                        },
                         'New': {
                            'title': '{!! __("admin.users.statuses.New")!!}',
                            'state': 'danger'
                        },
                        
                        
                    };
                    return '<span class="user_status label label-' + status[row.status].state + ' label-dot mr-2 ml-2" row="'+row.id+'"></span><span class="font-weight-bold text-' + status[row.status].state + '">' +
                        status[row.status].title + '</span>';
				}
			}
                        ,
                        {
				field: "created_at",
				title: "{!! __('admin.users.created_at')!!}",
				template: function (row) {
					// callback function support for column rendering
					return moment(row.created_at).format('YYYY-MM-DD');
				}
			}
                        ,
                        {
				field: "options",
				title: "{!! __('admin.users.options')!!}",
                width: 'auto',
				template: function (row) {
					// callback function support for column rendering
                    var actions = '';
                    if(row.status == 'Pending'){
                        actions += '<button type="button" class="btn btn-success btn-sm active_user" data="'+row.id+'">'+'{!! __("admin.users.make_active")!!}'+'</button>';
                    }
					 return actions +'\
                        <a href="/admin/users/view/'+row.id+'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit details">\
                            <span class="svg-icon svg-icon-md">\
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                        <rect x="0" y="0" width="24" height="24"/>\
                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
                                    </g>\
                                </svg>\
                            </span>\
                        </a>\
                        <a  data="{!! url("/admin/users/remove/")!!}/'+row.id+'" class="btn btn-sm btn-clean btn-icon remove_btn" title="Delete">\
                            <span class="svg-icon svg-icon-md">\
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                        <rect x="0" y="0" width="24" height="24"/>\
                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>\
                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>\
                                    </g>\
                                </svg>\
                            </span>\
                        </a>\
                    ';
				}
			},
				          {
				field: "package",
				title: "{!! __('admin.users.package')!!}",
				template: function (row) {
					// callback function support for column rendering
					return (row.package_obj) ? row.package_obj.title_ar : '-';
				}
			},
			          {
				field: "plan",
				title: "{!! __('admin.users.plan')!!}",
				template: function (row) {
					// callback function support for column rendering
					return (row.plan_obj) ? row.plan_obj.description_ar : '-';
				}
			},
			        {
				field: "price",
				title: "{!! __('admin.users.price')!!}",
				template: function (row) {
					// callback function support for column rendering
					return (row.price) ? row.price + 'KD' : '-';
				}
			}
                     ],

        });
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
       $(document).on('click','.active_user',function(){
        var user = $(this).attr('data');
        $.ajax({
                    type: 'POST',
                    url: '{!! route("admin_update_user") !!}',
                    data: {id  : user},
                    dataType: 'json',
                    success: function(e) {
                       if(e.status == 'done'){
                           $('.active_user[data="'+e.id+'"]').remove();
                           $('.user_status[row="'+e.id+'"]').removeClass('label-info').addClass('label-success').siblings('.text-info').addClass('text-success').removeClass('text-info').text(e.message);
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