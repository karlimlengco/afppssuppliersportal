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
    <div class="heading">
        <h1>{{env('APP_NAME')}}</h1>
        {{-- <img src="/img/stash-logo.png" alt="" width="140" class="branding"/> --}}
        <div class="branding">
            <h2></h2>
            <div class="alert alert-info" role="alert">
                Hooray <strong>{{$user->username}}</strong> Welcome to {{env('APP_NAME')}}<br>Just a few more steps to reset your account password.
            </div>
        </div>
        @if(count($errors))
            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                @if($errors->has('password'))
                    {{ $errors->first('password') }}
                @endif
                @if($errors->has('expired'))
                    {{ $errors->first('expired') }}
                @endif
            </div>
        @endif

        @if( ! $errors->has('expired'))
        <form action="{{ route('password.change', $activationParameters) }}" method="POST">
            {{ csrf_field() }}
            <div class="input-group input-group-lg">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
            <div class="input-group input-group-lg">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat your password">
            </div>
            <button type="submit" class="float">Reset</button>
        </form>
        @else

        @endif
    </div>
</div>
</body>
</html>
