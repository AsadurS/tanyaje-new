<!DOCTYPE html>
<html>
<head>
    <title>Tanyaje</title>
</head>
<body>
@if($isAgent)
    <h2>Hello, {{ $agentOrAdvisor['advisor']->merchant_name }}!</h2>
    <h3>I am {{$agentOrAdvisor['user']->user_name}}</h3>
    <h4>Email: {{$agentOrAdvisor['user']->email}}</h4>
    I have created you as sales advisor in Tanyaje
@else
    <h2>Hello, {{ $agentOrAdvisor->user->user_name }}!</h2>
    <h3>I am {{$agentOrAdvisor->merchant_name}}</h3>
    <h4>Email: {{$agentOrAdvisor->merchant_email}}</h4>
    <h4>phone {{$agentOrAdvisor->merchant_phone_no}}</h4>
    I want to renew my account please check and let me know
@endif


</body>
</html>