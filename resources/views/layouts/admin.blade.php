<!DOCTYPE html>
<html>

<head>
    @include('includes.head')
    @vite(
    [
        'resources/css/output.css',
        'resources/js/user/script.js',
        'resources/js/user/analytics.js'
    ])
</head>

<body>
    <main>
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>
