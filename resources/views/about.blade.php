@include('header')
@include('nav')
    <!-- main website content start -->
    <div id="main__content">

        <section>
            <div>
                <div data-aos="fade-down" data-aos-once="true" data-aos-duration="1000" class="main--text">
                    <h1 class="main--text-border">About us ?</h1>
                </div>
                <div class="container " data-aos="zoom-in-up" data-aos-once="true data-aos-duration="1000">
                    <div class="container shadow-sm whyus__card rounded">
                        <div class="row pr-0 whyUs rounded">
                            <div class="col-md col-lg-6" id="accordionExample">
                                <ul class="list-unstyled my-4 pl-md-5 pl-1">
                                   @php
                                           $questions = \App\Models\WhyUs::all();
                                   @endphp
                                                                           @foreach($questions as $question)
                                     <li class="my-2">
                                        <a class="btn btn-lg text-left" role="button" data-toggle="collapse"
                                            data-target="#collapseExample" aria-expanded="true"
                                            aria-controls="collapseExample">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="16"
                                                viewBox="0 0 50 16">
                                                <rect id="Rectangle_22" data-name="Rectangle 22" width="38" height="5"
                                                    rx="2" />
                                                <rect id="Rectangle_23" data-name="Rectangle 23" width="38" height="5"
                                                    rx="2" transform="translate(0 11)" />
                                            </svg>{{$question->question}}
                                        </a>
                                        <div class="collapse show my-4 pl-5 whyus__collapes" id="collapseExample"
                                            data-parent="#accordionExample">
                                            {{$question->answer}}
                                        </div>
                                    </li>
                                                                           @endforeach
                                </ul>
                            </div>
                            <div class="col-lg-6 pl-0 pr-0 ml-sm-0 ml-xm-0 pl-sm-0 pl-xm-0">
                                <img src="../assest/why-us.png" alt="..." class="img-fluid whyUs__img--rounded">
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
    <!-- main website content end -->
    
@include('foot')
@include('footer')



