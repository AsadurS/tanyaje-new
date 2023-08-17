<meta property="og:title" content="{{ $data['sale_advisor'][0]->merchant_name }}" />
<meta property="og:url" content="{{ request()->getHost() }}" />
@if ($data['sale_advisor'][0]->verified == 1)
<meta property="og:description" content="Hi, I’m a verified seller! Click on the link to view my products!">
@else
<meta property="og:description" content="Hi! Click on the link to view my products!">
@endif
@if($data['sale_advisor'][0]->profile_img !== null)
    @if(strpos($data['sale_advisor'][0]->profile_img,'/images/') !== false) 
    <meta property="og:image" itemprop="image" content="{{ $data['sale_advisor'][0]->profile_img }}">
    @else
    <meta property="og:image" itemprop="image" content="{{Request::getSchemeAndHttpHost()}}/images/sale-advisor/{{ $data['sale_advisor'][0]->profile_img }}">
    @endif
@endif
<meta property="og:locale" content="en_UK" />
<meta property="og:type" content="profile">
<meta name="csrf-token" content="{{ csrf_token() }}" />