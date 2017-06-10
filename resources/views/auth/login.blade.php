<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>AFP Procurement Service</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- app icon -->
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" href="favicon.png">

    <!-- font -->
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="/fonts/nucleo-webfonts/mini/css/nucleo-mini.css">
    <link rel="stylesheet" href="/fonts/nucleo-webfonts/glyph/css/nucleo-glyph.css">
    <link rel="stylesheet" href="/fonts/nucleo-webfonts/outline/css/nucleo-outline.css">


    <!-- main style -->
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/main.css">

    <!-- scripts -->
    <script src="/js/vendor/modernizr-2.8.3.min.js"></script>

</head>
<body>

        <div class="login">
            <div class="login__logo">
                <h1 class="login__logo__text">Sign-in</h1>
            </div>
            <form method="POST" route="{{ route('auth.login') }}" class="login__form">
                {{ csrf_field() }}

                @if(count($errors))
                <div class="message-box message-box--login message-box--error">
                    <span class="message-box__message">
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
                    </span>
                </div>
                @endif

                <div class="input-group">
                    <span class="login__form__input__icon"><i class="nc-icon-mini users_single-01"></i></span>
                    <input type="text" class="login__form__input" placeholder="Username" name="login" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="input-group">
                    <span class="login__form__input__icon"><i class="nc-icon-mini gestures_pin"></i></span>
                    <input type="password" class="login__form__input" placeholder="Password" name="password" required>
                </div>
                <button class="login__form__button">Sign-in</button>
                <a href="#" class="forgot-password">Forgot your password?</a>
            </form>
        </div>

</body>
</html>
