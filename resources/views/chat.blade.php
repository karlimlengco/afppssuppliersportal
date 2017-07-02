<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Chat Room</title>

    <script src="/vendors/jquery/dist/jquery.min.js"></script>

    <script type="text/javascript">

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

</head>
<body>

    <div id="app">
        <example></example>
        {{-- <chat-message></chat-message> --}}
        <chat-log :messages="messages"></chat-log>
        <chat-composer v-on:messagesent="addMessage"></chat-composer>
    </div>

    <script src="/js/app.js"></script>
</body>
</html>