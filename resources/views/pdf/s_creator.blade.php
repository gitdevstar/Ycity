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

        </style>
    </head>
    <body>
        <h1>Bewerbung als Creator für Y-City</h1>
        <h3>selbständiger Creator</h3>
        <table class="table table-borderless">
            <tr>
                <td class="pr-3">Vorname</td>
                <td>{{ $pdfData["firstname"] }}</td>
            </tr>
            <tr>
                <td class="pr-3">Nachname</td>
                <td>{{ $pdfData["lastname"] }}</td>
            </tr>
            <tr>
                <td class="pr-3">Birthdate</td>
                <td>{{ nl2br($pdfData["birthdate"]) }}</td>
            </tr>
            <tr>
                <td class="pr-3">Beschreibung</td>
                <td>{{ $pdfData["description"] }}</td>
            </tr>
            <tr>
                <td class="pr-3">Strasse</td>
                <td>{{ $pdfData["street"] }}</td>
            </tr>
            <tr>
                <td class="pr-3">PLZ</td>
                <td>{{ $pdfData["plz"] }}</td>
            </tr>
            <tr>
                <td class="pr-3">Telefon</td>
                <td>+41 {{ $pdfData["telephone"] }}</td>
            </tr>
            <tr>
                <td class="pr-3">Website (optional)</td>
                <td>{{ $pdfData["website"] }}</td>
            </tr>
            <tr>
                <td class="pr-3">Skills (optional)</td>
                <td>{{ $pdfData["skills_text"] }}</td>
            </tr>
            <tr>
                <td class="pr-3">Bewerbungstext</td>
                <td>{{ nl2br($pdfData["apply_text"]) }}</td>
            </tr>
        </table>
        <h3>Firma</h3>
        <table class="table table-borderless">
            <tr>
                <td class="pr-3">Typ</td>
                <td>{{ $pdfData["organisation_type"] }}</td>
            </tr>
            <tr>
                <td class="pr-3">Name</td>
                <td>{{ $pdfData["organisation_name"] }}</td>
            </tr>
            <tr>
                <td class="pr-3">UID</td>
                <td>CHE-{{ $pdfData["organisation_uid"] }}</td>
            </tr>
        </table>
        <br />
        <br />
        <small class="color-grey">Datum: {{date("d-m-Y")}}</small>
        <br />
        <small class="color-grey">&copy; Y-City</small>
    </body>
</html>
