<!DOCTYPE html>
<html>
<head>
    <title>Welcome Email</title>
</head>

<body>
<h2>Welcome to the site {{$data['customers']['first_name']}} {{$data['customers']['last_name']}}</h2>
<br/>
Your registered email-id is {{$data['customers']['email']}} , Please click on the below link to verify your email account
<br/>
<a href="{{url('admin/user/verify', $data['token']['token'])}}">Verify Email</a>
</body>

</html>