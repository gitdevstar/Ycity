<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Willkommen bei Y-CITY</title>
        <!-- Styles -->
        <style>
            @font-face {
                font-family: 'StabilGrotesk_Regular';
                src:  url('/fonts/StabilGrotesk-Regular.woff2') format('woff2'),
                url('/fonts/StabilGrotesk-Regular.woff') format('woff');
            }

            @font-face {
                font-family: 'StabilGrotesk_Medium';
                src:  url('/fonts/StabilGrotesk-Medium.woff2') format('woff2'),
                url('/fonts/StabilGrotesk-Medium.woff') format('woff');
            }

            body {
                font-family: 'StabilGrotesk_Regular', Arial, Calibri, sans-serif;
            }

            h1, h2 ,h3 {
                font-family: 'StabilGrotesk_Medium', Arial, Calibri, sans-serif;
            }

            td {
                padding-right: 1.5rem;
                padding-bottom: 25px;
            }

            a {
                padding: 10px 25px;
                background-color: #000;
                border-radius: 6px;
                color: #FFF;
                text-decoration: none;
            }

            a:hover {
                opacity: 0.7;
            }

        </style>
    </head>
    <body>
        <h2>Herzlich Willkommen bei Y-CITY!</h2>
        <p>Wir haben uns deine Bewerbung angeschaut und möchten dir mit Freude mitteilen, dass du zu Y-CITY aufgenommen wurdest.</p>
        <br />
        <a href="{{env('APP_URL')}}">zu Y-CITY</a>
    </body>
</html>
