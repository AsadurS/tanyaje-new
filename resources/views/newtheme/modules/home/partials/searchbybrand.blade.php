
<!-- START carBrandsArea -->
<section class="carBrandsArea">
    <div class="container">
        <div class="heading">
            <h2>Search By Popular <span>Car Brands</span></h2>
        </div>
        <div class="itemBox disflexArea" id="hide_8_brand">
      @if($result['feature_brand']->count()>7)
            <!--due to client still not upload the brand logo. Temporary fix the brand-->
          @foreach($result['feature_brand']->take(8) as $brand)
          <div class="item">
              <div class="corLogo">
                <a href="{{ route("carfilters") . "?brand=".$brand->id }}">
                  <img src="{{asset($brand->imgpath) }}" alt="">
                 </a> 
                </div>
               <div class="text">
                <span>{{$brand->name}}</span>
                </div>
            </div>
            @endforeach
        @else    

            <div class="item">
                <div class="corLogo">
                    <a href="{{ route("carfilters") . "?brand=57" }}">
                        <img src="{{asset('new/images/proton_logo_2.png')}}" alt="">
                    </a>
                </div>
                <div class="text">
                    <a href="{{ route("carfilters") . "?brand=57" }}">
                        <span>Proton</span>
                    </a>
                </div>
            </div>
            <div class="item">
                <div class="corLogo">
                    <a href="{{ route("carfilters") . "?brand=30" }}">
                        <img src="{{asset('new/images/carbrandImg-02.png')}}" alt="">
                    </a>
                </div>
                <div class="text">
                    <a href="{{ route("carfilters") . "?brand=30" }}">
                        <span>Honda</span>
                    </a>
                </div>
            </div>
            <div class="item">
                <div class="corLogo">
                    <a href="{{ route("carfilters") . "?brand=68" }}">
                        <img src="{{asset('new/images/carbrandImg-03.png')}}" alt="">
                    </a>
                </div>
                <div class="text">
                    <a href="{{ route("carfilters") . "?brand=68" }}">
                        <span>Toyota</span>
                    </a>
                </div>
            </div>
            <div class="item">
                <div class="corLogo">
                    <a href="{{ route("carfilters") . "?brand=54" }}">
                        <img src="{{asset('new/images/carbrandImg-04.png')}}" alt="">
                    </a>
                </div>
                <div class="text">
                    <a href="{{ route("carfilters") . "?brand=54" }}">
                        <span>Perodua</span>
                    </a>
                </div>
            </div>
            <div class="item">
                <div class="corLogo">
                    <a href="{{ route("carfilters") . "?brand=32" }}">
                        <img src="{{asset('new/images/carbrandImg-05.png')}}" alt="">
                    </a>
                </div>
                <div class="text">
                    <a href="{{ route("carfilters") . "?brand=32" }}">
                        <span>Hyundai</span>
                    </a>
                </div>
            </div>
            <div class="item">
                <div class="corLogo">
                    <a href="{{ route("carfilters") . "?brand=44" }}">
                        <img src="{{asset('new/images/carbrandImg-06.png')}}" alt="">
                    </a>
                </div>
                <div class="text">
                    <a href="{{ route("carfilters") . "?brand=44" }}">
                        <span>Mazda</span>
                    </a>
                </div>
            </div>
            <div class="item">
                <div class="corLogo">
                    <a href="{{ route("carfilters") . "?brand=13" }}">
                        <img src="{{asset('new/images/carbrandImg-07.png')}}" alt="">
                    </a>
                </div>
                <div class="text">
                    <a href="{{ route("carfilters") . "?brand=13" }}">
                        <span>BMW</span>
                    </a>
                </div>
            </div>
            <div class="item">
                <div class="corLogo">
                    <a href="{{ route("carfilters") . "?brand=46" }}">
                        <img src="{{asset('new/images/carbrandImg-08.png')}}" alt="">
                    </a>
                </div>
                <div class="text">
                    <a href="{{ route("carfilters") . "?brand=46" }}">
                        <span>Mercedes-Benz</span>
                    </a>
                </div>
            </div>

         @endif   
        </div>
        <div class="itemBox disflexArea" id="show_all" style="display: none">
               @foreach($result['feature_brand'] as $brand)
                   <div class="item">
                        <div class="corLogo">
                     <a href="{{ route("carfilters") . "?brand=".$brand->id }}">
                       <img src="{{asset($brand->imgpath) }}" alt="">
                      </a> 
                     </div>
                      <div class="text">
                          <span>{{$brand->name}}</span>
                        </div>
                   </div>
              @endforeach
          </div>
      <div class="moreBtn">
         {{--  <a href="javascript:void(0)">Display All Brands <i class="fa fa-caret-down" aria-hidden="true"></i> </a> --}}
     @if($result['feature_brand']->count()>8)
        <div class="moreBtn">
            <a href="javascript:void(0)" id="allbrand_show_all">Display All Brands <i class="fa fa-caret-down" aria-hidden="true"></i> </a>
            <a   href="javascript:void(0)" 
            style="display: none" id="allbrand_show_hide">Display All Brands <i class="fa fa-caret-up" aria-hidden="true"></i> </a>

        </div>
     @endif
    </div>
</section>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
 
    $(document).ready(function(){

         $("#allbrand_show_all").click(function(){
            $("#show_all").show();
            $("#allbrand_show_hide").show();
            $('#hide_8_brand').hide();
            $("#allbrand_show_all").hide();
            $("#allbrand_show_hide").show()

        });

        $("#allbrand_show_hide").click(function(){
            $("#show_all").hide();
            $('#allbrand_show_hide').hide();
            $('#hide_8_brand').show();
            $("#allbrand_show_all").show()
        });
       
   });
  </script>
<!-- END carBrandsArea -->

