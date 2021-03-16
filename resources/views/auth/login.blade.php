<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>AFPPS END-USER KIOSK</title>
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
<body class="o-body o-body--outline">
    <div class="o-container s-login">
      <div class="p-login">
        <dic class="c-form">
          <div class="c-branding">
            <div class="c-branding__logo">
              <img src="/img/logo.png" alt="">
            </div>
          </div>
          <div style="text-align: center">
          <h4>Automated Procurement Processing,<br>Monitoring and Information System</h4>
          </div>

          @if(count($errors))
          <div class="c-alert u-border-radius">
            <p>
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
            </p>
          </div>
          @endif

          <form method="POST" route="{{ route('auth.login') }}" class="login-form">
                    {{ csrf_field() }}
            <div class="c-form-group">
              <div class="c-input-group">
                <span class="c-input-group__icon"><i class="nc-icon-mini users_single-02"></i></span>
                <input type="text" class="c-input u-border-radius" placeholder="Username" name="login" value="{{ old('email') }}" required autofocus>
              </div>
            </div>
            <div class="c-form-group">
              <div class="c-input-group">
                <span class="c-input-group__icon"><i class="nc-icon-mini gestures_pin"></i></span>
                <input type="password" class="c-input u-border-radius" placeholder="Password" name="password" required>
              </div>
            </div>
            <div class="c-form-group">
              <button class="c-button u-border-radius">
                <span class="c-button__text">Sign me in</span>

              </button>
            </div>
          </form>
        </dic>
      </div>
    </div>

{{--         <div class="login">

            <div class="left-panel">
                <div class="login-logo">
                    <a href=""><img src="/img/logo.png" alt=""></a>
                </div>
                <h1 class="login-notice-title">AFPPS</h1>
                <p class="login-notice">Automated Procurement Processing,<br>Monitoring and Information System</p>
            </div>

            <div class="right-panel">
                <form method="POST" route="{{ route('auth.login') }}" class="login-form">
                    {{ csrf_field() }}

                    <h1>Sign-in</h1>

                    <div class="form-group">

                        <div class="input-group">
                            <span class="input-group-icon"><i class="nc-icon-outline users_circle-08"></i></span>
                            <input type="text" class="login__form__input" placeholder="Username" name="login" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-icon"><i class="nc-icon-outline ui-1_lock"></i></span>
                            <input type="password" placeholder="Password" name="password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <button class="button">Sign-in</button>
                    </div>
                </form>
                <p class="version">EPMIS V 4.0.0</p>
            </div>
        </div>
 --}}
</body>
</html>
