<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>{{ config('app.name', 'Bus Schedule') }}</title>
        <link href="{{ asset('vendor/fontawesome-free/css/all.css') }}" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class="pt-3">
        <div class="container">
            <div id="root"></div>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>