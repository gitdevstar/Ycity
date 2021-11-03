<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Creator Informationen</title>
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
                padding: 10px 25px  !important;
                background-color: #000 !important;
                border-radius: 6px  !important;
                color: #fff !important;
                text-decoration: none  !important;
                display: inline-block  !important;
            }

            a:hover {
                opacity: 0.7;
            }

        </style>
    </head>
    <body>
        <h2>Hallo Y-City!</h2>
        <p>Du hast eine Bewerbung vom Creator <b>{{$data["firstname"]}} {{$data["lastname"]}}</b> erhalten. <br /> Alle weiteren Informationen findest du im Anhang.</p>
        <br />
        <a href="{{$data["domain"]}}/ycity/creator/apply?cId={{$data["cId_hash"]}}&uId={{$data["uId_hash"]}}">Annehmen</a>
        <a href="{{$data["domain"]}}/ycity/creator/deny?cId={{$data["cId_hash"]}}&uId={{$data["uId_hash"]}}">Ablehnen</a>
    </body>
</html>
