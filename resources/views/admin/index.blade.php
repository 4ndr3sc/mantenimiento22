<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
</head>
<body class="bg-white text-gray-900 p-8">
    <h1 class="text-2xl font-bold mb-4">Panel de Administración</h1>
    <p>Bienvenido, {{ auth()->user()->name }}. Aquí podrá gestionar usuarios y permisos.</p>
    <div class="mt-4">
        <a href="{{ route('admin.users.index') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Gestionar Técnicos</a>
    </div>
</body>
</html>
