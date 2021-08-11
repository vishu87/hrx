@if($type == "registration")
    <p>
        Dear {{$user->name}},
    </p>
    <p>
        Your account has been created. You can login with following credentials<br>
        Weblink - <b>{{url('/')}}</b><br>
        Username - <b>{{$user->email}}</b><br>
        Password - <b>{{$password}}</b><br>
    </p>
    <p>
        Thanks.
    </p>
@endif

@if($type == "password_reset")
    <p>
        Dear {{$user->name}},
    </p>

    <p>
        Your password has been reset successfully, <b>{{$user->password_check}}</b> is your new password , <a target="_blank" href="{{url('/')}}">Click here </a> to login to your account
    </p>
    
    <p>
        Thanks.
    </p>
@endif
