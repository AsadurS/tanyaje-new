<meta property="og:title" content="{{ $data['campaign']->campaign_name }}" />
<meta property="og:url" content="{{ request()->getHost() }}" />
@if ($data['sale_advisor'][0]->verified == 1)
<meta property="og:description" content="{{ $data['campaign']->striped_description }}">
@else
<meta property="og:description" content="{{ $data['campaign']->striped_description }}">
@endif
@if($data['campaign']->campaign_image !== null)
    <meta property="og:image" itemprop="image" content="{{ $data['campaign']->campaign_image }}">
@endif
<meta property="og:locale" content="en_UK" />
<meta property="og:type" content="profile">
<meta name="csrf-token" content="{{ csrf_token() }}" />