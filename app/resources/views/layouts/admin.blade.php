<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        <title>{{ config('app.name', 'Laravel') }}</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    </head>

    <body class="bg-white">
        <div id="app">
            <top-menu></top-menu>
            <div style="height:55px;"></div>
            <div class="container pt-3" style="min-height:78vh;">
                <router-view></router-view>
            </div>
            <button onclick="send(i++)">123</button>
            <div class="footer mt-3" style="min-height: 15vh;background:#333;"></div>
        </div>

        <script>
            var conn = new WebSocket('ws://192.168.1.98:8290');
            var i = 0;
            conn.onopen = function(e) {
                console.log('Соединение открыто')
            }

            conn.onmessage = function(e) {
                console.log('Данные получены: ' + e.data)
            }

            function send(msg)
            {
                var data = JSON.stringify({auth:47})
                conn.send(data)
                console.log('Отправлено')
            }
        </script>

    </body>

</html>
