
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if(app()->getLocale() == 'ar') dir="rtl" @else dir="ltr"  @endif>
	<!--begin::Head-->
	<head>
		<meta charset="utf-8" />
		<title>Dietnesskw | دايتنيس</title>
		<meta name="description" content="Form repeater examples" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<link rel="canonical" href="https://keenthemes.com/metronic" />
		<!--begin::Fonts-->
		<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300&display=swap" rel="stylesheet"> 
		<!--end::Fonts-->
         <meta name="csrf-token" content="{{csrf_token()}}">
		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="{{asset('dashboard/')}}/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="{{asset('dashboard/')}}/assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
		<link href="{{asset('dashboard/')}}/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles-->
		<!--begin::Layout Themes(used by all pages)-->
		<link href="{{asset('dashboard/')}}/assets/css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css" />
		<link href="{{asset('dashboard/')}}/assets/css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css" />
		<link href="{{asset('dashboard/')}}/assets/css/themes/layout/brand/dark.css" rel="stylesheet" type="text/css" />
		<link href="{{asset('dashboard/')}}/assets/css/themes/layout/aside/dark.css" rel="stylesheet" type="text/css" />
		<link href="{{asset('dashboard/')}}/assets/css/custom.css" rel="stylesheet" type="text/css" />
		<!--end::Layout Themes-->
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-minimize-hoverable page-loading {{ str_replace('_', '-', app()->getLocale()) }}">