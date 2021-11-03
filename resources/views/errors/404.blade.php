<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include("global.head")
    <title>Seite nicht gefunden - {{ config('app.name', 'Laravel') }}</title>
</head>
<body>
@include("global.navi")
<div class="mainContent bg-white">
    <div id="fullpage" class="showOnLoad">
        <h2 class="mb-1">Hoppla! Diese Seite wurde nicht gefunden.</h2>
        <a href="/ycity" class="btn btn-primary mt-2" role="button" aria-pressed="true">zur Startseite</a>
    </div>
</div>
@include("global.footer")
</body>
</html>
