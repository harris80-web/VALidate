<!DOCTYPE html>
<html>

<head>
    @include('includes.head')
    @vite(
    [
        'resources/css/output.css',
        'resources/js/user/script.js'
    ])
</head>

<body>
    <main>
        @yield('content')
    </main>
</body>

</html>
