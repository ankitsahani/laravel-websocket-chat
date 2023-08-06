<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <title>Websocket Example</title>
    @vite('resources/css/style.css')
</head>

<body>
    <p>
        <b>Trade:- </b> <span id="trade"></span>
        <hr>
        <hr>
        <hr>
        <b>Message:- </b> <span id="message"></span>
        <hr>
        <hr>
        <hr>
        <b>Presence Message:- </b> <span id="presence-message"></span>
    </p>
    @vite('resources/js/app.js')
    @vite('resources/js/custom.js')
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            Echo.channel(`hello-channel`)
                .listen('HelloEvent', (e) => {
                    console.log('Hello Event')
                    console.log(e);
                    document.getElementById('trade').innerHTML = e.hello;
                });

            Echo.private(`message-channel`)
                .listen('MessageEvent', (e) => {
                    console.log('message')
                    console.log(e);
                    document.getElementById('message').innerHTML = e.message;
                });
            Echo.join(`presence-channel`)
                .here((user) => {
                    console.log(user);
                })
                .joining((user) => {
                    console.log('New User Joined : -' + user.name)
                })
                .leaving((user) => {
                    console.log('User Leaved :-' + user.name)
                })
                .listen('PresenceEvent', (e) => {
                    console.log('presence-message')
                    console.log(e);
                    document.getElementById('presence-message').innerHTML = e.message;
                });
        });
    </script>
</body>

</html>
