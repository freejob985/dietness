@include('header')
@include('nav')
<!-- main website content start -->
    <div id="main__content" class="contact_container">
        <section>
            <div data-aos="fade-down" data-aos-once="true" data-aos-duration="3000" class="main--text">
                <h1 class="main--text-border">Contact us</h1>
            </div>
            <div class="container">
                <div class="row">

                    <!-- right side 'form' -->
                    <div class="col-lg-6 my-5  col-md" >
                        <form>
                            <div class="form-group row" data-aos="fade-down" data-aos-once="true" data-aos-duration="1000">
                                <label for="inputName" class="col-sm-2 col-form-label">{{__('home.contact.Name')}}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputName">
                                </div>
                            </div>
                            <div class="form-group row" data-aos="fade-down" data-aos-once="true" data-aos-duration="1100">
                                <label for="inputEmail" class="col-sm-2 col-form-label">{{__('home.contact.Email')}}</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="inputEmail">
                                </div>
                            </div>
                            <div class="form-group row" data-aos="fade-down" data-aos-once="true" data-aos-duration="1200">
                                <label for="inputPhone" class="col-sm-2 col-form-label">{{__('home.contact.Phone')}}</label>
                                <div class="col-sm-10">
                                    <input type="tel" class="form-control" id="inputPhone">
                                </div>
                            </div>
                            <div class="form-group row" data-aos="fade-down" data-aos-once="true" data-aos-duration="1300">
                                <label for="inputSubject" class="col-sm-2 col-form-label">{{__('home.contact.Subject')}}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputSubject">
                                </div>
                            </div>
                            <div class="form-group row" data-aos="fade-down" data-aos-once="true" data-aos-duration="1400">
                                <label for="inputMessage" class="col-sm-2 col-form-label">{{__('home.contact.Message')}}</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="inputMessage" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="text-right" data-aos="fade-down" data-aos-once="true" data-aos-duration="1500">
                                <button type="submit" class="btn btn-warning store__button--primary">{{__('home.Send')}}</button>
                            </div>
                        </form>
                    </div>

                    <!-- left side  -->
                    <div class="col-lg-5 my-5 ml-lg-5 pl-5">
                        <div class="row" data-aos="fade-down" data-aos-once="true" data-aos-duration="1000">
                            <i class="fas fa-map-marker-alt fa-lg p-2 contactUsIcon--color mt-1" ></i>
                            <h2>Address</h2>
                        </div>
                        <div data-aos="fade-down" data-aos-once="true" data-aos-duration="1100">
                            <h6>Cairo Zamalek Street - Kuwait</h6>
                        </div>
                        <div class="row pt-3" data-aos="fade-down" data-aos-once="true" data-aos-duration="1200">
                            <i class="fas fa-phone-alt fa-lg p-2 contactUsIcon--color mt-1"></i>
                            <h2 class=" align-middle">Phone</h2>
                        </div>
                        <div data-aos="fade-down" data-aos-once="true" data-aos-duration="1300">
                            <h6>+965 55566644</h6>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
    <!-- main website content end -->
    
@include('foot')
@include('footer')



