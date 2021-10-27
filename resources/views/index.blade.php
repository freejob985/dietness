@include('header')
@include('nav')

    <!-- main website content start -->
    <div id="main__content">

        <!-- slider start -->
        <main>
            <div class="slider__container">
                <div class="slider__text">
                    <div class="container">
                        <div class="slider__text--in">

                            <h1>With <span class="slider__text--primary-color">DIETNESS</span></h1>
                            <h4>Lorem ipsum dolor sit amet, consectetur adipisicing elit. </h4>
                            <div class="slider__btns">
                                <a href="en/contactUs.html"
                                    class="btn-lg btn btn-warning slider__button--primary">Contact
                                    us</a>
                                <button type="button"
                                    class="btn-lg btn-outline-light btn slider__button--regular">more</button>
                            </div>

                        </div>
                        <div class="slider__text--in">
                            <label class="prev" id="prev" ><span></span></label>
                            <label class="next"  id="next"><span></span></label>
                        </div>
                    </div>
                </div>

                <!-- slider bottom dots -->
                <input type="radio" id="i1" name="images" checked />
                <input type="radio" id="i2" name="images" />
                <input type="radio" id="i3" name="images" />

                <!-- slider images -->
                <div class="slide_img" id="one">
                    <img src="{{asset('front-end')}}/assest/slider-02.png">
                </div>

                <div class="slide_img" id="two">
                    <img src="{{asset('front-end')}}/assest/slider-03.jpeg">
                </div>

                <div class="slide_img" id="three">
                    <img src="{{asset('front-end')}}/assest/slider-01.jpeg">
                </div>

                <!-- slider bottom dots labels -->
                <div id="nav_slide">
                    <label for="i1" class="dots" id="dot1"></label>
                    <label for="i2" class="dots" id="dot2"></label>
                    <label for="i3" class="dots" id="dot3"></label>
                </div>

            </div>
        </main>
        <!-- slider end -->

        <!-- package section start -->
        <section>
            <div>
                <!-- section tilte -->
                <div data-aos="fade-down" data-aos-once="true" data-aos-duration="1000" class="main--text">
                    <h1 class="main--text-border">{{__('home.Packages')}}</h1>
                </div>
                <!-- cards container -->
                <div class="container">
                    <div class="card-deck">
                        <!-- card number 1 -->
                        <div type="button" data-toggle="modal" data-target="#myModal" class="card text-center shadow-sm"
                            data-aos-once="true" data-aos="zoom-in-up" data-aos-duration="1000">
                            <img src="{{asset('front-end')}}/assest/package-card-01.png" class="card-img-top" alt="...">
                            <div class="card-body">
                                <button class="btn btn-warning slider__button--primary" type="button"
                                    data-toggle="modal" data-target="#myModal">{{__('home.See_more')}}</button>
                            </div>
                        </div>
                        <!-- card number 2 -->
                        <div type="button" data-toggle="modal" data-target="#myModal" class="card text-center shadow-sm"
                            data-aos-once="true" data-aos="zoom-in-up" data-aos-duration="1000">
                            <img src="{{asset('front-end')}}/assest/package-card-02.png " class="card-img-top" alt="...">
                            <div class="card-body">
                                <button class="btn btn-warning slider__button--primary" type="button"
                                    data-toggle="modal" data-target="#myModal">{{__('home.See_more')}}</button>
                            </div>
                        </div>
                        <!-- card number 3 -->
                        <div type="button" data-toggle="modal" data-target="#myModal" class="card text-center shadow-sm"
                            data-aos-once="true" data-aos="zoom-in-up" data-aos-duration="1000">
                            <img src="{{asset('front-end')}}/assest/package-card-01.png" class="card-img-top" alt="...">
                            <div class="card-body">
                                <button class="btn btn-warning slider__button--primary" type="button"
                                    data-toggle="modal" data-target="#myModal">{{__('home.See_more')}}</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal 'pop up' -->
                <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg ">
                        <div class="modal-content">
                            <!-- close icon -->
                            <button type="button" class="close text-right p-2" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <!-- card body -->
                            <div class="row">
                                <!-- left side section - image section -->
                                <div class="col-lg-4 col-sm modal__leftSide">
                                    <img class="rounded store__img" data-aos-once="true" data-aos="zoom-in-up"
                                        data-aos-duration="1000" src="{{asset('front-end')}}/assest/popup-01.jpeg" alt="Lights"
                                        style="width:100%">
                                </div>
                                <!-- right side section - description section -->
                                <div class="col-lg-7 col-sm modal__rightSide">
                                    <div class="vertical-center">
                                        <h3 class="">TIM'S BURGER</h3>
                                        <h5>20 D . K</h5>
                                        <hr>
                                        <h6>Tim's Burger, Kuwait. 28K likes. Fresh beef.</h6>
                                        <div class="d-block my-2 ">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="customRadioInline1" name="customRadioInline1"
                                                    class="custom-control-input">
                                                <label class="custom-control-label" for="customRadioInline1">100
                                                    gm</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="customRadioInline2" name="customRadioInline1"
                                                    class="custom-control-input">
                                                <label class="custom-control-label" for="customRadioInline2">150
                                                    gm</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="customRadioInline3" name="customRadioInline1"
                                                    class="custom-control-input">
                                                <label class="custom-control-label" for="customRadioInline3">200
                                                    gm</label>
                                            </div>
                                        </div>
                                        <div class="btn-group mr-3 my-sm-2" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-outline-dark">-</button>
                                            <button type="button" class="btn btn-outline-dark">200</button>
                                            <button type="button" class="btn btn-outline-dark">+</button>
                                        </div>
                                        <button type="button"
                                            class="btn btn-warning mx-3 my-sm-2 ml-sm-0 ml-md-0 px-md-3">Add to
                                            cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- package section end -->

        <!-- Why Us section start  -->
        <section>

            <!-- Why Us main -->
            <div data-aos="fade-down" data-aos-once="true" data-aos-duration="1000" class="main--text">
                <h1 class="main--text-border">{{__('home.why_us')}}</h1>
            </div>
            <div class="container " data-aos="zoom-in-up" data-aos-once="true" data-aos-duration="1000">
                <div class="container shadow-sm whyus__card rounded">
                    <div class="row pr-0 whyUs rounded">
                        <div class="col-md col-lg-6" id="accordionExample">
                            <ul class="list-unstyled my-4 pl-md-5 pl-1">
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
                                        </svg>Lorem Ipsum is simply dummy
                                    </a>
                                    <div class="collapse show my-4 pl-5 whyus__collapes" id="collapseExample"
                                        data-parent="#accordionExample">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                        richardson ad
                                    </div>
                                </li>
                                <li class="media-body my-2">
                                    <a class="btn btn-lg btn-block text-left text-lg" role="button"
                                        data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false"
                                        aria-controls="collapseExample1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="16"
                                            viewBox="0 0 50 16">
                                            <rect id="Rectangle_22" data-name="Rectangle 22" width="38" height="5"
                                                rx="2" />
                                            <rect id="Rectangle_23" data-name="Rectangle 23" width="38" height="5"
                                                rx="2" transform="translate(0 11)" />
                                        </svg>Lorem Ipsum is simply dummy
                                    </a>
                                    <div class="collapse my-4 pl-5 whyus__collapes" id="collapseExample1"
                                        data-parent="#accordionExample">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                        richardson ad

                                    </div>
                                </li>
                                <li class="media-body my-2">
                                    <a class="btn btn-lg btn-block text-left text-lg" role="button"
                                        data-toggle="collapse" data-target="#collapseExample2" aria-expanded="false"
                                        aria-controls="collapseExample2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="16"
                                            viewBox="0 0 50 16">
                                            <rect id="Rectangle_22" data-name="Rectangle 22" width="38" height="5"
                                                rx="2" />
                                            <rect id="Rectangle_23" data-name="Rectangle 23" width="38" height="5"
                                                rx="2" transform="translate(0 11)" />
                                        </svg>Lorem Ipsum is simply dummy
                                    </a>
                                    <div class="collapse my-4 pl-5 whyus__collapes" id="collapseExample2"
                                        data-parent="#accordionExample">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                        richardson ad

                                    </div>
                                </li>
                                <li class="media-body my-2">
                                    <a class="btn btn-lg btn-block text-left text-lg" role="button"
                                        data-toggle="collapse" data-target="#collapseExample3" aria-expanded="false"
                                        aria-controls="collapseExample3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="16"
                                            viewBox="0 0 50 16">
                                            <rect id="Rectangle_22" data-name="Rectangle 22" width="38" height="5"
                                                rx="2" />
                                            <rect id="Rectangle_23" data-name="Rectangle 23" width="38" height="5"
                                                rx="2" transform="translate(0 11)" />
                                        </svg>Lorem Ipsum is simply dummy
                                    </a>
                                    <div class="collapse my-4 pl-5 whyus__collapes" id="collapseExample3"
                                        data-parent="#accordionExample">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                        richardson ad

                                    </div>
                                </li>
                                <li class="media-body my-2">
                                    <a class="btn btn-lg btn-block text-left text-lg" role="button"
                                        data-toggle="collapse" data-target="#collapseExample4" aria-expanded="false"
                                        aria-controls="collapseExample4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="16"
                                            viewBox="0 0 50 16">
                                            <rect id="Rectangle_22" data-name="Rectangle 22" width="38" height="5"
                                                rx="2" />
                                            <rect id="Rectangle_23" data-name="Rectangle 23" width="38" height="5"
                                                rx="2" transform="translate(0 11)" />
                                        </svg>Lorem Ipsum is simply dummy
                                    </a>
                                    <div class="collapse my-4 pl-5 whyus__collapes" id="collapseExample4"
                                        data-parent="#accordionExample">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry
                                        richardson ad

                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-6 pl-0 pr-0 ml-sm-0 ml-xm-0 pl-sm-0 pl-xm-0">
                            <img src="{{asset('front-end')}}/assest/why-us.png" alt="..." class="img-fluid whyUs__img--rounded">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Why Us section end  -->

    </div>
    <!-- main website content end -->

    @include('foot')
    @include('footer')



