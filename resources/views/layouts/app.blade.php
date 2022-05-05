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
    <div id="control">
        <div id="insideControl">
            <div id="control-1">
                <button class="button-12" role="button">Upload pdf</button>
                <button class="button-12" role="button">Download pdf</button>
                <button class="button-12" role="button">Upload TOC</button>
                <button class="button-12" role="button">Download TOC</button>
            </div>
            <div id="control-2">
                <button class="button-12" role="button">Extract TOC</button>
                <button class="button-12" role="button">Insert TOC</button>
                <button class="button-12" role="button">Save</button>
                <button class="button-12" role="button">Help</button>
            </div>>
        </div>
    </div>

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
    <!--Left side, below Empty - holds the editing buttons-->
    <div id="editing"> 
        <div id="editingTitle">Editing Operations</div>
        <button class="button-14" role="button">Insert Line</button>
        <button class="button-14" role="button">Delete Line</button>
        <button class="button-14" role="button">Add Characters</button>
        <button class="button-14" role="button">Remove Characters</button>
        <button class="button-14" role="button">Replace Characters</button>
        <button class="button-14" role="button">Remove page numbers from title</button>
        <button class="button-14" role="button">Update page numbers</button>
        <button class="button-14" role="button">Add space for specicif level</button>
        <button class="button-14" role="button">Add caracter for specific level</button>
        <button class="button-14" role="button">Remove Characters from specific level</button>
        <button class="button-14" role="button">remove space from specific level</button>
        <button class="button-14" role="button">save current work</button>  
    </div>

    <!--Center, below Control - hold the contents -->
    <div id="content">
        <div id="contentChild">contentChild

        </div>
    </div>

    <!--Right side below Login - There's nothing there yet-->
    <div id="side">Sidebar</div>

    <!--Botton of the page, like Heder, it takes the whole bottom-->
    <footer>Footer</footer>

</div> <!--container-->

<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
</body>
