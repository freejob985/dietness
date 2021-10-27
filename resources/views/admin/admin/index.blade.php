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
                <h3 class="card-label">{{__('admin.admins.index')}}</h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="{{route('admins.create')}}" class="btn btn-primary font-weight-bolder">
                    <span class="svg-icon">
                        <!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Code\Plus.svg--><svg
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10" />
                                <path
                                    d="M11,11 L11,7 C11,6.44771525 11.4477153,6 12,6 C12.5522847,6 13,6.44771525 13,7 L13,11 L17,11 C17.5522847,11 18,11.4477153 18,12 C18,12.5522847 17.5522847,13 17,13 L13,13 L13,17 C13,17.5522847 12.5522847,18 12,18 C11.4477153,18 11,17.5522847 11,17 L11,13 L7,13 C6.44771525,13 6,12.5522847 6,12 C6,11.4477153 6.44771525,11 7,11 L11,11 Z"
                                    fill="#000000" />
                            </g>
                        </svg>
                        <!--end::Svg Icon--></span>{{__('admin.admins.add_admins')}}</a>
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
                                    <input type="text" class="form-control" placeholder="Search..."
                                        id="kt_datatable_search_query" />
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
            <div
                class="datatable datatable-bordered datatable-head-custom datatable-default datatable-primary datatable-loaded">
                <table class="datatable datatable-bordered datatable-head-custom" id="kt_datatable">
                    <thead>
                        <tr>
                            <th title="Field #1">#</th>
                            <th title="Field #2">{{__('admin.admins.name')}}</th>
                            <th title="Field #3">{{__('admin.admins.email')}}</th>
                            <th title="Field #5">{{__('admin.admins.created_at')}}</th>
                            <th title="Field #5">{{__('admin.admins.options')}}</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$admin->name}}</td>
                            <td>{{$admin->email}}</td>
                            <td>{{$admin->created_at}}</td>
                            <td>
                                <a title="{{__('admin.admins.edit_admins')}}"
                                    href="{{route('admins.edit',['admin' => $admin->id])}}"><i
                                        class="icon-xl la la-edit"></i></a>
                                {{-- <a title="{{__('admin.admins.remove_admins')}}" href="#" class="remove_btn"
                                    data="{{route('admins.destroy',['admin' => $admin->id])}}"><i
                                        class="icon-xl la la-trash"></i></a> --}}

                                <form  id="delete_form_{{$admin->id}}" style="display:inline;" action="{{route('admins.destroy',['admin' => $admin->id])}}" method="POST" >
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button style="border:none; background:none" title="{{__('admin.admins.remove_admins')}}"
                                        class = "px-3 text-danger" type="submit">
                                        <i class="icon-xl la la-trash remove_btn" data="{{$admin->id}}"></i>
                                    </button>
                                </form>
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
    <div class="modal fade" id="confirm_remove" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
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
                    <button type="button" class="btn btn-light-primary font-weight-bold"
                        data-dismiss="modal">{{__('admin.cancel')}}</button>
                    <button type="button" class="btn btn-primary font-weight-bold"
                        id="remove_confirm_btn">{{__('admin.confirm')}}</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click','.remove_btn',function (e){
            e.preventDefault();
            const id = $(this).attr('data');
            $('#remove_confirm_btn').attr('data',id);
            $('#confirm_remove').modal('show');
        });
        $(document).on('click','#remove_confirm_btn',function (e){
            const id = $(this).attr('data');
            console.log(id)
            $(`#delete_form_${id}`).submit();
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
