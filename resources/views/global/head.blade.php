<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/png" href="/favicon.png" />
<!-- Scripts -->
<script src="{{ asset('js/navigation.js') }}"></script>
<script src="{{ asset('js/functions.js') }}"></script>
<script src="{{ asset('js/vue.js') }}"></script>
<script src="{{ asset('js/bootstrap-min.js') }}"></script>
<script src="{{ asset('js/popper-min.js') }}"></script>
<!-- Styles -->
<link href="{{ asset('css/navigation.css?v=').time()}}" rel="stylesheet">
<link href="{{ asset('css/app.css?v=').time()}}" rel="stylesheet">
<link href="{{ asset('css/main.css?v=').time()}}" rel="stylesheet">
<link href="{{ asset('css/footer.css?v=').time()}}" rel="stylesheet">
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"/>
@isset($activeJob) <meta name="job-id" content="{{ $job->id }}"> @endisset
@isset($creators_id) <meta name="creator-id" content="{{ $creators_id }}"> @endisset
<meta name="userId" content="{{ Auth::check() ? Auth::user()->id : '' }}">
