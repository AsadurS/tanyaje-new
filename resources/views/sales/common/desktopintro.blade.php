<script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=64a52dade8c286001998cf11&product=sop' async='async'></script>
<div class="desktop-sideContent d-none d-md-block offset-md-2 col-md-4 px-4">
    @if(strpos($data['organisation']->logo,'/images/') !== false) 
    <img src="{{$data['organisation']->logo}}" style="width:100%; margin-bottom:15px;">
    @else
    <img src="/images/logo/{{$data['organisation']->logo}}" style="width:100%; margin-bottom:15px;">
    @endif
    <h1>Hi I'm<br/>
    @foreach($data['sale_advisor'] as $sa)
        {{$sa->merchant_name}}
    @endforeach
    </h1>
    <hr>
    @if ($data['sale_advisor'][0]->display_qr == 1)
    <p>SCAN HERE TO</p>
    <div id="qrcode"></div>
    <p>DISCOVER MORE</p>
    <hr>
    <p>or click here to</p>
    @else
    <p>Click here to</p>
    @endif
    <button class="shareBtn" onclick="openShare()">Share</button>
    <div class="hide backdrop"></div>
    <div class="shareModal hide">
        <button onclick="closeShare()"><svg height="24px" viewBox="0 0 329.26933 329" width="24px" xmlns="http://www.w3.org/2000/svg"><path d="m194.800781 164.769531 128.210938-128.214843c8.34375-8.339844 8.34375-21.824219 0-30.164063-8.339844-8.339844-21.824219-8.339844-30.164063 0l-128.214844 128.214844-128.210937-128.214844c-8.34375-8.339844-21.824219-8.339844-30.164063 0-8.34375 8.339844-8.34375 21.824219 0 30.164063l128.210938 128.214843-128.210938 128.214844c-8.34375 8.339844-8.34375 21.824219 0 30.164063 4.15625 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921875-2.089844 15.082031-6.25l128.210937-128.214844 128.214844 128.214844c4.160156 4.160156 9.621094 6.25 15.082032 6.25 5.460937 0 10.921874-2.089844 15.082031-6.25 8.34375-8.339844 8.34375-21.824219 0-30.164063zm0 0"/></svg>
        </button>
        <h2>Share this to:
        
                <!-- facebook -->
              <!--  <a class="facebook" target="blank"><i class="fab fa-facebook"></i></a>-->
                <!-- twitter -->
              <!--  <a class="twitter" target="blank"><i class="fab fa-twitter"></i></a>-->
                <!-- linkedin -->
              <!--  <a class="linkedin" target="blank"><i class="fab fa-linkedin"></i></a>-->
                <!-- whatsapp -->
              <!--<a class="whatsapp" target="blank"><i class="fab fa-whatsapp"></i></a>-->
                <!-- telegram -->
              <!--  <a class="telegram" target="blank"><i class="fab fa-telegram"></i></a>-->
            
                
                <div class="sharethis-inline-share-buttons"></div>
           
        </h2>    
 
    </div>
</div>
<script>

    // const url = window.location.href;
    // const link = encodeURI(url);
    // const msg = encodeURIComponent('Hey');
    // const title = encodeURIComponent('Article or Post Title Here');
    // const fb = document.querySelector('.facebook');
    // fb.href = `https://www.facebook.com/share.php?u=${link}`;
    // const twitter = document.querySelector('.twitter');
    // twitter.href = `http://twitter.com/share?&url=${link}&text=${msg}&hashtags=javascript,programming`;
    // const linkedIn = document.querySelector('.linkedin');
    // linkedIn.href = `https://www.linkedin.com/sharing/share-offsite/?url=${link}`;
    // const whatsapp = document.querySelector('.whatsapp');
    // whatsapp.href = `https://api.whatsapp.com/send?text=${msg}: ${link}`;
    
    // const telegram = document.querySelector('.telegram');
    // telegram.href = `https://telegram.me/share/url?url=${link}&text=${msg}`;
    // setTimeout(()=>{
    //       const targetDiv = document.querySelector('.addthis_inline_share_toolbox');
    //       console.log(targetDiv);
    //       const link = document.createElement('div');
    //       link.className  += 'sharethis-inline-share-buttons';
         
    //       targetDiv.appendChild(link);
    //          console.log(targetDiv,link)
    //   },10000)
</script>