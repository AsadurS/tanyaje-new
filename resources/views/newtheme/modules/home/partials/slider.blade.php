<section class="slider_section main-top">
    <div class="container">
        <div class="owl-carousel owl-theme ">
            @foreach($sliders as $slider)
                <div class="item">
                    <a href="{!! $slider->sliders_url !!}" target="_blank">
                        <img src="/{!! $slider->path !!}">
                    </a>
                </div>
            @endforeach
        </>
    </div>
</section>
