<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DOCU PARSER</title>
    <link href="/css/login.css" rel="stylesheet">

    <link href="/fonts/nucleo-webfonts/glyph/css/nucleo-glyph.css" rel="stylesheet">
    <link href="/fonts/nucleo-webfonts/mini/css/nucleo-mini.css" rel="stylesheet">
    <link href="/fonts/nucleo-webfonts/outline/css/nucleo-outline.css" rel="stylesheet">

</head>
<body>
<div class="login">
    <div class="login-box">
        <div class="login-logo">
            <span class="nc-icon-outline files_replace x3"></span>
            <h1></h1>
            {{-- <img src="src/img/arsenal-logo.png" alt=""> --}}
        </div>

        @if(count($errors))
        <div class="login-error">
            @if($errors->has('login') or $errors->has('password'))
                Oooppsss! Please check your fields.
            @endif
            @if($errors->has('auth'))
                {{ $errors->first('auth') }}
            @endif
            @if($errors->has('expired'))
                {{ $errors->first('expired') }}
            @endif
            @if($errors->has('email'))
                {{ $errors->first('email') }}
            @endif
        </div>
        @endif

        <div class="login-form">
            <form method="POST" route="{{ route('auth.login') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <div class="input-group">
                        <input type="text" placeholder="Username/Email" name="login" value="{{ old('email') }}" required autofocus>
                        <span class="input-group-icon"><i class="nc-icon-outline users_single-02"></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="password" placeholder="Password" name="password" required>
                        <span class="input-group-icon"><i class="nc-icon-outline ui-1_lock-circle"></i></span>
                    </div>
                </div>
                <div class="row">

                    <div class="twelve columns">
                        <div class="form-group">
                            <input type="submit" value="Sign-in">
                        </div>
                    </div>

                </div>
            </form>
        </div>
        <div class="login-foot">©Revlv Solutions Inc.</div>
    </div>
</div>

</body>
</html>
