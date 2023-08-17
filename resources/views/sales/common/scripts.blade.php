<script>
var touchedSticky = false;
function openShare() {
    $('.shareModal').removeClass('hide');
    $('.backdrop').removeClass('hide');
    @if (isset($shareTrack) && $shareTrack == 'campaign_share')
        clickWithTrack('', 'campaign_share');
    @endif 
}
function closeShare() {
    $('.shareModal').addClass('hide');
    $('.backdrop').addClass('hide');
}
$('.backdrop').click(function(){
    $('.shareModal').addClass('hide');
    $('.backdrop').addClass('hide');
})
$('.openSticky').click(function() {
    $('.sticky-needhelp').toggleClass('active')
    $('.sticky-needhelpContent').toggleClass('active')
    touchedSticky = true;
})
@if ($openSticky == true)
    $(document).ready(function(){
        $('.sticky-needhelp').addClass('active')
        $('.sticky-needhelpContent').addClass('active')
        setTimeout(function(){ 
            if(touchedSticky == false) {
                $('.sticky-needhelp').removeClass('active')
                $('.sticky-needhelpContent').removeClass('active')
            }
        }, 2000);
    });
@endif
@if ($data['sale_advisor'][0]->display_qr == 1)
var qrcode = new QRCode("qrcode", {
    text: window.location.href,
    width: 128,
    height: 128,
    colorDark : "#000000",
    colorLight : "#ffffff",
    correctLevel : QRCode.CorrectLevel.H
});
@endif
function clickWithTrack(link, event, tab) {
    console.log('track '+ event);
    $.ajax({
        url: "/eventtracker",
        type:"POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data:{
            "event": event
        },
        success:function(response){
            if (link !== '') {
                window.location.href  = link;
            }
        },
        async: false
    });
}
</script>
<script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=64a52dade8c286001998cf11&product=sop' async='async'></script>
        <script>
            setTimeout(()=>{
           const targetDiv = document.querySelector('.addthis_inline_share_toolbox');
           const link = document.createElement('div');
           link.className  += 'sharethis-inline-share-buttons';
        
           targetDiv.appendChild(link);
            console.log(targetDiv)
       })
 </script>       
       
