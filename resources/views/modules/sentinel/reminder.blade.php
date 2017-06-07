<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,700);
        @import url(https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css);
        @import url(https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css);

        * {
            margin: 0;
            padding: 0;
        }

        html {
            background: #ecf0f1;
            /*background: url(https://dl.dropboxusercontent.com/u/159328383/background.jpg) no-repeat center center fixed;*/
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        body {
            background: transparent;
        }

        body, input, button {
            font-family: 'Source Sans Pro', sans-serif;
        }

        .login {
            padding: 15px;
            width: 400px;
            min-height: 400px;
            margin: 5% auto 0 auto;
        }
        .login .heading {
            text-align: center;
            margin-top: 1%;
        }
        .login .heading h2 {
            font-size: 3em;
            font-weight: 300;
            color: #222;
            display: inline-block;
            margin-top: 0;
            font-family: 'Oswald';
            padding-top: 5px;
            padding-bottom: 15px;
        }
        .login form .input-group {
            border-top: 1px solid #222;
            border-bottom: 1px solid #222;
            /*border-bottom: 1px solid rgba(255, 255, 255, 0.1);*/
            /*border-top: 1px solid rgba(255, 255, 255, 0.1);*/
        }
        .login form .input-group:last-of-type {
            border-top: none;
        }
        .login form .input-group span {
            background: transparent;
            min-width: 53px;
            border: none;
        }
        .login form .input-group span i {
            font-size: 1.5em;
            color: #222;
        }
        .login form input.form-control {
            display: block;
            width: auto;
            height: auto;
            border: none;
            outline: none;
            box-shadow: none;
            background: none;
            border-radius: 0px;
            padding: 10px;
            font-size: 1.6em;
            width: 100%;
            background: transparent;
            color: #222;
        }
        .login form input.form-control:focus {
            border: none;
        }
        .login form button {
            margin-top: 20px;
            background: #098688;
            border: none;
            font-size: 1.6em;
            font-weight: 300;
            padding: 5px 0;
            width: 100%;
            border-radius: 3px;
            color: #FFF;
            /*border-bottom: 4px solid #1e8449;*/
        }
        .login form button:hover {
            background: #0a9699;
            -webkit-animation: hop 1s;
            animation: hop 1s;
        }
        .float {
            display: inline-block;
            -webkit-transition-duration: 0.3s;
            transition-duration: 0.3s;
            -webkit-transition-property: transform;
            transition-property: transform;
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            box-shadow: 0 0 1px transparent;
        }
        .float:hover, .float:focus, .float:active {
            /*-webkit-transform: translateY(-3px);*/
            /*transform: translateY(-3px);*/
        }

        /* Large Devices, Wide Screens */
        @media only screen and (max-width: 1200px) {
            .login {
                width: 600px;
                font-size: 2em;
            }
        }
        @media only screen and (max-width: 1100px) {
            .login {
                margin-top: 2%;
                width: 600px;
                font-size: 1.7em;
            }
        }
        /* Medium Devices, Desktops */
        @media only screen and (max-width: 992px) {
            .login {
                margin-top: 1%;
                width: 550px;
                font-size: 1.7em;
                min-height: 0;
            }
        }
        /* Small Devices, Tablets */
        @media only screen and (max-width: 768px) {
            .login {
                margin-top: 0;
                width: 500px;
                font-size: 1.3em;
                min-height: 0;
            }
        }
        /* Extra Small Devices, Phones */
        @media only screen and (max-width: 480px) {
            .login {
                margin-top: 0;
                width: 400px;
                font-size: 1em;
                min-height: 0;
            }
            .login h2 {
                margin-top: 0;
            }
        }
        /* Custom, iPhone Retina */
        @media only screen and (max-width: 320px) {
            .login {
                margin-top: 0;
                width: 200px;
                font-size: 0.7em;
                min-height: 0;
            }
        }
    </style>
</head>
<body>
<div class="login">
    <div class="heading">
        <div class="branding">
            <h2></h2>
        </div>
        @if(count($errors))
            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                @if($errors->has('email'))
                    {{ $errors->first('email') }}
                @endif
            </div>
        @endif
        <form action="{{ route('reminder.submit') }}" method="POST">
            {{ csrf_field() }}
            <div class="input-group input-group-lg">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input name="email" type="text" class="form-control" placeholder="Email Address">
            </div>
            <button type="submit" class="float">Reset Password</button>
        </form>
    </div>
</div>
</body>
</html>
