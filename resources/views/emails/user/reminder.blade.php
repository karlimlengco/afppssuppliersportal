<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>You requested for password reset</title>
</head>
<body>
<pre>
Hi {{ ($user->username) ? $user->username : "{$user->first_name} {$user->surname}" }},

A password reset has been requested for your account.

If you did not make this request, You can safely ignore this email.

if you do actually want to reset your password, visit this link:
<a href="{{ $reminder }}">{{ $reminder}}</a>

</pre>
</body>
</html>
