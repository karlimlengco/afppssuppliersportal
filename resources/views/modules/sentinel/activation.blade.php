<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>

     <link rel="stylesheet" href="/fonts/fontawesome/font-awesome.css">
     <link rel="stylesheet" href="/css/login.css">
</head>
<body>
<div class="login">
    <div class="login-box">

        <div class="login-form">
        Hooray <strong>{{$user->username}}</strong> Welcome to {{env("APP_NAME")}}<br>Just a few more steps to activate your account.
        <form action="{{ route('activation.activate', $activationParameters) }}" method="POST">
            {{ csrf_field() }}

            <div class="form-group">
                <div class="input-group">
                    <input type="password" name="password"  placeholder="Password">
                    <span class="input-group-icon"><i class="fa fa-lock"></i></span>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <input type="password" name="password_confirmation"  placeholder="Repeat your password">
                    <span class="input-group-icon"><i class="fa fa-lock"></i></span>
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    <div class="form-group">
                        <input type="submit" value="Activate">
                    </div>
                </div>
                {{-- <div class="six columns">
                    <a href="{{ route('activation.activate', $activationParameters) }}"> or click here</a>
                </div> --}}
            </div>
        </form>

        </div>
        <div class="login-foot">©2017 Revlv Solutions, INC.</div>
    </div>

</div>
</body>
</html>
