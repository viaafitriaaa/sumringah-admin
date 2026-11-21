
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Warkop Sumringah</title>
</head>
<body>
    <h1>Warkop Sumringah</h1>

    <h2>Dashboard</h2>

    <p>Selamat datang, {{ Auth::user()->name }}!</p>

    <p>Role: {{ Auth::user()->role }}</p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
