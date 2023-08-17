@if ($data['sale_advisor'][0]->contactMe == 1)
    @if ($openSticky == true)
    <div class="sticky-needhelp active" style="background-color: {{ $data['template'][0]->colour3 }} ">
        <a class="openSticky">HI, can I help? <svg width="15" height"15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96.154 96.154"><defs/><path d="M.561 20.971l45.951 57.605c.76.951 2.367.951 3.127 0l45.956-57.609c.547-.689.709-1.716.414-2.61a2.686 2.686 0 00-.186-.437 2.004 2.004 0 00-1.765-1.056H2.093c-.736 0-1.414.405-1.762 1.056a2.62 2.62 0 00-.184.426c-.297.905-.136 1.934.414 2.625z"/></svg></a>
    </div>
    <div class="sticky-needhelpContent active">
        <a onclick="clickWithTrack('{{ $data['sale_advisor'][0]->whatsapp_url .'?text='.$data['sale_advisor'][0]->whatsapp_message }}', 'whatsapp', 'newtab')">
            @if(strpos($data['sale_advisor'][0]->profile_img,'/images/') !== false) 
            <img src="{{ $data['sale_advisor'][0]->profile_img}}">
            @else
            <img src="/images/sale-advisor/{{ $data['sale_advisor'][0]->profile_img}}">
            @endif
            <span>Let's Chat With Me!</span>
        </a>
    </div>
    @else
    <div class="sticky-needhelp" style="background-color: {{ $data['template'][0]->colour3 }} ">
        <a class="openSticky">HI, can I help? <svg width="15" height"15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96.154 96.154"><defs/><path d="M.561 20.971l45.951 57.605c.76.951 2.367.951 3.127 0l45.956-57.609c.547-.689.709-1.716.414-2.61a2.686 2.686 0 00-.186-.437 2.004 2.004 0 00-1.765-1.056H2.093c-.736 0-1.414.405-1.762 1.056a2.62 2.62 0 00-.184.426c-.297.905-.136 1.934.414 2.625z"/></svg></a>
    </div>
    <div class="sticky-needhelpContent">
        <a onclick="clickWithTrack('{{ $data['sale_advisor'][0]->whatsapp_url .'?text='.$data['sale_advisor'][0]->whatsapp_message }}', 'whatsapp', 'newtab')">
            @if(strpos($data['sale_advisor'][0]->profile_img,'/images/') !== false) 
            <img src="{{ $data['sale_advisor'][0]->profile_img}}">
            @else
            <img src="/images/sale-advisor/{{ $data['sale_advisor'][0]->profile_img}}">
            @endif
            <span>Let's Chat With Me!</span>
        </a>
    </div>
    @endif
@endif