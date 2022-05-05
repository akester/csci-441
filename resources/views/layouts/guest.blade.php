<!DOCTYPE html>
<head>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset = "utf-8">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div id="container"> <!--Container has all 8 areas of the viewport-->
    <!--The top sector, It takes all the top part  -->
    <header>
        <div id="logo"><img src="{{ asset('img/header.png') }}"></div>
    </header>

    <!--Left side right below header - nothing on it at the moment-->
    <div id="empty">Empty</div>

    <!--Righ below header in the center, it holds the control buttons-->
    <div id="control"></div>

    <!--Right below header, right side - holds login/register buttons-->
    <div id="login">
        <div id="ïnsideLogin">
            <div id="login-1">
                <button class="button-12" role="button">Login</button>
            </div>
            <div id="login-1">
                <button class="button-12" role="button">Register</button>
            </div>
        </div>
    </div>

    {{ $slot }}

    <!--Right side below Login - There's nothing there yet-->
    <div id="side">Sidebar</div>

    <!--Botton of the page, like Heder, it takes the whole bottom-->
    <footer>Footer</footer>

</div> <!--container-->

<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
</body>
