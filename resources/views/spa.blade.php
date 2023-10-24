<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="api-base-url" content={{ config('app.url') }} />
    <meta name="csrf-token" value="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('/images/favicon.png') }}" type="image/png">
    <title>Admin Panel</title>
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700|Material+Icons"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" scoped>

</head>

<body>
    <div id="appMain" class="appMain" />

    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
</body>

</html>
