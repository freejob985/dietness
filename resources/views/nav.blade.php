<!-- navbar start -->
    <nav class=" rtl navbar sticky-top navbar-expand-lg navbar-light bg-light py-md-4">
        <div class="rtl container" data-aos="fade-down" data-aos-once="true" data-aos-duration="1000">

            <!-- website logo -->
            <a class="navbar-brand" href="index.html">
                <img src="{{asset('front-end')}}/assest/logo.svg" width="183" height="30" alt="" loading="lazy">
            </a>

            <!-- responsive button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- navbar links -->
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item  @if(\Request::route()->getName() == 'home') active @endif">
                        <a class="nav-link" href="{{route('home')}}">{{__('home.Home')}} <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item @if(\Request::route()->getName() == 'store') active @endif">
                        <a class="nav-link" href="{{route('store')}}">{{__('home.Store')}}</a>
                    </li>
                    <li class="nav-item @if(\Request::route()->getName() == 'contact') active @endif">
                        <a class="nav-link" href="{{route('contact')}}">{{__('home.Contact')}}</a>
                    </li>
                    <li class="nav-item @if(\Request::route()->getName() == 'about') active @endif">
                        <a class="nav-link" href="{{route('about')}}">{{__('home.About')}}</a>
                    </li>
                </ul>
                <ul class="navbar-nav" id="nav-link-right">
                    <li class="nav-item">
                        @php
                                 $lang = (app()->getLocale() == 'ar') ? 'en' : 'ar';
                                @endphp
                        <a class="nav-link" href="{{route('lang',['locale' => $lang])}}">
                        @if(app()->getLocale() == 'ar') English @else عربي @endif</a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>
    <!-- navbar end -->
