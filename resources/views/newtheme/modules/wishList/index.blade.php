@extends('newtheme.layouts.main')
@section('meta_for_share')
<meta property="og:title" content="Tanyaje">
<meta property="og:url" content="https://tanyaje.com.my/">
<meta property="og:image" content="http://cdn.spincar.com/swipetospin-viewers/tanyaje/3118/20200704071604.YL3PQT7T/closeups/cu-0.jpg">
<meta property="og:description" content="Tanya-Je translated as “Just Ask” is the first most advanced online digital automotive classified in South East Asia.">
@endsection
@section('content')
<!-- START breadcrumb_sec -->
<nav class="navnavigation_site_second">
    <div class="container">
        <ul>
            @if(Auth::guard('customer')->user())
                <li><a href="{{ route('profile') }}">Profile</a></li>
            @endif
            <li class="active"><a href="#">Saved Cars</a></li>
        </ul>
    </div>
</nav>
<!-- END navnavigation_site 2-->
<!-- END breadcrumb_sec -->
<!-- START content_part-->
<section id="content_part">
    <!-- START savedCarArea -->
    <section class="popularsArea savedCarArea">
        <div class="container">
            <div class="heading" style="height:30px">

                <button type="submit" id="button_compare" class="Searchbtn">COMPARE</button>
                <h4>Saved Cars</h4>
            </div>
            <div class="itemBox disflexArea d_item_box">

            </div>
        </div>
    </section>
    <!-- END savedCarArea -->
</section>
@endsection
@push('scripts')
<script type="text/javascript">
$(document).ready(function() {
    function wishList() {
        var localPro = JSON.parse(localStorage.getItem('addToList'));
        if(localPro && localPro.length > 0 ){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery.ajax({
            url: "{{ url('/wishList/Data') }}",
            method: 'post',
            data: {
                List: localPro,
            },
            success: function(result) {
                var data = result['car']['data'];
                var html = '';
                if(result != null){
                    for ( var i = 0; i < data.length; i++ ) {
                        html+='<div class="item">';
                        html+='<div class="image">';
                        html+="<a href='"+data[i]['car_url']+"'>"+
                    "<img src='https://manager.spincar.com/web-preview/walkaround-thumb/" + data[i]['sp_account'] + "/"+data[i]["vim"].toLowerCase()+"/md' alt=''></a>";
                        html+='</div>';
                        html+='<div class="sub">';
                        html+="<span class='addToList hide' id='addButton"+data[i]['car_id']+"' data-id="+data[i]['car_id']+">"+
                            "<i class='fa fa-heart'></i> </span>";
                        html+="<span class='removeItem' id='removeButton"+data[i]['car_id']+"' data-id="+data[i]['car_id']+">"+
                            "<i class='fa fa-heart red'></i> </span>";
                        html+= `<a href="`+data[i]['car_url']+`"><div class="title-container"><div class="year-car">${data[i]['year_make']}</div><div class="title-car"><h3>${data[i]['title']} </h3></div></div></a>`;
                        html+='<p>Listed by: <a class="listed-by" href="'+data[i]['merchant_branch_url']+'">'+data[i]['merchant_name'] +'</a></p>';
                        html+='<h4>RM ' + data[i]['price']+'</h4>';
                        html+='<span class="vin" style="display: none;">' + data[i]['vim'] + '</span>';
                        html+='<ul>';

                        if(typeof data[i]['mileage']  !== 'undefined' && data[i]['mileage'] != "" )
                        {
                            html+='<li><img src="../images/new/popularIcon01.png" alt="">' + data[i]['mileage'] + '</li>';
                        }

                        if( typeof data[i]['city_name'] !== 'undefined' && data[i]['city_name'] != "" )
                        {
                            html+='<li><img src="../images/new/popularIcon02.png" alt="">'+data[i]['city_name']+' , '+data[i]['state_name']+'</li>';
                        }

                        if( typeof data[i]['color'] !== 'undefined' && data[i]['color'] != "" && data[i]['color'] )
                        {
                            html+='<li><img src="../images/new/popularIcon03.png" alt="">' + data[i]['color'] + '</li>';
                        }

                        if( typeof data[i]['fuel_type'] !== 'undefined' && data[i]['fuel_type'] != "" )
                        {
                            html+='<li><img src="../images/new/popularIcon04.png" alt="">'+ data[i]['fuel_type'] + '</li>';
                        }

                        html+='</ul>';
                        html+='</div>';
                        html+='</div>';
                    }
                    $('.itemBox.disflexArea').append(html);
                }
            }
        });
        }else{
            var html='<div class="container"><div class="heading" style="text-align=center"><h2>No data found.</h2></div></div>';
            $('.itemBox.disflexArea').append(html);
        }
    }
    wishList();

    //Remove WishList items
    var tempProId = JSON.parse(localStorage.getItem('addToList'));
    var proCount = 0;
    setTimeout(function(){
     $('.removeItem').on('click',function(e){
        console.log('click');
        var proId = e.target.parentElement.dataset.id;
        $('#addButton'+proId).addClass('show').removeClass('hide');
        $('#removeButton'+proId).addClass('hide').removeClass('show');
        proCount = $('#removeButton'+proId).length;
        for (var i = 1;i <= proCount;i++) {
          var index = tempProId.indexOf(proId);
          if (index > -1) {
            var index = tempProId.splice(index, 1);
         }
        }
        localStorage.setItem('addToList', JSON.stringify(tempProId));
        window.location.reload();
    });
 },1000);
});

</script>
@endpush
