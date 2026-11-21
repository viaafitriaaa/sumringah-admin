
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Warkop Sumringah</title>
</head>
<body>

    <h1>Dashboard Warkop Sumringah</h1>

    <h3>Selamat datang, {{ Auth::user()->name }}!</h3>

    <p>Role: {{ Auth::user()->role }}</p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

</body>
</html>
