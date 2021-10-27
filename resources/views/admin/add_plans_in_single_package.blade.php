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
												<h3 class="card-title">{{__('admin.packages.plans_related_to')}} - {{$package->title}}</h3>
												
											</div>
											<!--begin::Form-->
											<div class="card-body">
													<div id="app">
                                                <plans :package_id="{{$package->id}}"></plans>
                                                </div>
                                                    </div>
										</div>
										<!--end::Card-->
    </div>
</div>
@endsection
@section('foot')
<script src="{{asset('/js/app.js')}}"></script>
<script>
</script>
@endsection