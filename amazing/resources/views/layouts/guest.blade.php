<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Login')</title>

  @unless(app()->environment('testing'))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endunless
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50 text-gray-900">
  <main class="w-full max-w-md p-6 bg-white rounded-2xl shadow">
    @yield('content')
  </main>
</body>
</html>
