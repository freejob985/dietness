<div id="kt_header" class="header header-fixed">
						<!--begin::Container-->
						<div class="container-fluid d-flex align-items-stretch justify-content-between">
							<!--begin::Topbar-->
							<div class="topbar header-menu-wrapper header-menu-wrapper-left">
								<!--begin::User-->
								<div class="topbar-item">
									<div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
										<span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">مرحبا,</span>
										<span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3"  style="    margin: 0 10px;">{{auth()->guard('driver_web')->user()->name}}</span>
										<span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
											<span class="symbol-label font-size-h5 font-weight-bold">{{substr(auth()->guard('driver_web')->user()->name,0,1)}}</span>
										</span>
									</div>
								</div>
								<!--end::User-->
							</div>
							<!--end::Topbar-->
						</div>
						<!--end::Container-->
					</div>