@extends('admin.layout.index')

@section('content')
    <div class="row add_content">
        <div class="col-lg-12">
            {{-- Include errors from partials --}}
            @include('admin.partials.errors')

            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">{{__('admin.admins.edit_admins')}} - {{$admin->name}}</h3>

                </div>
                <!--begin::Form-->
                <form action="{{route('admins.update',['admin' => $admin->id])}}" method="post"
                    enctype="multipart/form-data" autocomplete="off">
                    {{csrf_field()}}
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{__('admin.admins.name')}}</label>
                            <input type="text" class="form-control" name="name" value="{{$admin->name}}">
                        </div>
                        <div class="form-group">
                            <label>{{__('admin.admins.email')}}</label>
                            <input type="email" class="form-control" name="email" value="{{$admin->email}}">
                        </div>

                        <div class="form-group">
                            <label for="name">
                                {{__('admin.admins.permissions')}}
                            </label>
                            <select class="form-control m-select2" id="permissions" name="permissions[]" multiple="multiple"
                                style="direction: rtl;">
                                @foreach($permissions as $key=>$permission)
                                <option value="{{$permission->id}}" @if((old('permission') && in_array($permission->id, old('permission'))) || (in_array($permission->id, $admin_permissions))) selected
                                    @endif >
                                    @if($locale == "ar") {{$permission->title_ar}} @else {{$permission->title_en}} @endif
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{__('admin.admins.password')}}</label>
                            <input type="password" class="form-control" name="password" value="">
                        </div>
                        <div class="form-group">
                            <label>{{__('admin.admins.verify_password')}}</label>
                            <input type="password" class="form-control" name="verify_password" value="">
                        </div>

                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12">
                                <div class="form_btns_group"><button type="submit"
                                        class="btn btn-success mr-2">{{__('admin.save')}}</button>
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
        $('#permissions').select2({
                placeholder: "{{__('admin.admins.choose_permissions')}}"
            });
    </script>
@endsection
