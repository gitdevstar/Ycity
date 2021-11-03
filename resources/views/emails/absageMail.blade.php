<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Absage von Y-CITY</title>
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
        <h2>Tut uns Leid...</h2>
        <p>Wir haben uns deine Bewerbungs angeschaut und müssen dir leider mitteilen, dass es nicht ganz für eine Aufnahme zu Y-CITY gereicht hat.</p>
        <br />
        <p>Vielen Dank für deine Bewerbung! Wir wünschen dir viel Erfolg für die Zukunft.</p>
        <br />
        <small>Y-CITY</small>
    </body>
</html>
