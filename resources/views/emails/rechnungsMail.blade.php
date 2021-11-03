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
        @php
            $jobEnd = date("d.m.Y", strtotime($data["job_end"]));
        @endphp
        <h3>Sehr geehrte/r {{$data["client_firstname"]}} {{$data["client_lastname"]}}</h3>
        <p>Der Auftrag "<b>{{$data["job_name"]}}</b>" wurde erfolgreich abgeschlossen. Hier ist deine Rechnung:</p>
        <h3>Firma</h3>
        <p>
            {{$data["client_name"]}}<br />
            {{$data["client_street"]}}<br />
            {{$data["client_plz"]}} {{$data["client_place"]}} {{$data["client_canton_short"]}}
        </p>
        <h4>Creator</h4>
        <p>
            {{$data["creator_firstname"]}} {{$data["creator_lastname"]}}<br />
            {{$data["creator_street"]}}<br />
            {{$data["creator_plz"]}} {{$data["creator_place"]}} {{$data["creator_canton_short"]}}
        </p>
        <h4>Kosten</h4>
        <p>
            {{$data["job_cost"]}} CHF
        </p>
        <h4>Enddatum</h4>
        <p>
            {{$jobEnd}}
        </p>
        <br />
        <p>
            Freundliche Grüsse
            <br />
            Y-CITY
        </p>
    </body>
</html>
