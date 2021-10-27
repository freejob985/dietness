@include('header')
@include('nav')
   <!-- main website content start -->
    <div id="main__content">
        <section>

             @php
              $main_cats = \App\Models\main_categories::has('products')->get();
             @endphp
            <!-- buttons group start -->
            <div class="container my-5 mx-auto row nav nav-pills mb-3" data-aos-once="true" data-aos="slide-down"
                data-aos-duration="1000" id="tabs">
                @foreach($main_cats as $cat)
                 <li class="nav-item col-xs-12 col-xl m-1">
                <a type="button" id="storeBtn_0{{$cat->id}}" class="nav-link btn btn-lg  @if(!$loop->first) store__button--nonPrimary btn-light @else btn-warning store__button--primary @endif" ata-toggle="pill" href="#meal-0{{$cat->id}}" role="tab" aria-controls="v-pills-messages" aria-selected="false">{{$cat->title}}</a>
                </li>
               @endforeach
            </div>
            <!-- buttons group end -->

            <!-- images group start -->
            <div class="tab-content">
            @foreach($main_cats as $cat)
            <!-- meal 01 button -->
            <div class="container my-5 @if($loop->first) show active @endif tab-pane fade" id="meal-0{{$cat->id}}">
                <div class="row">
                    @foreach($cat->products as $product)
                    <div class="col-md-4 my-2">
                        <img class="rounded store__img" data-aos-once="true" data-aos="slide-right"
                            data-aos-duration="1000" src="{{asset('uploads/products/'.$product->image)}}" alt="Lights"
                            style="width:100%">
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
            <!-- images group start end -->
            </div>
        </section>
    </div>
    <!-- main website content end -->

@include('foot')
@include('footer')



