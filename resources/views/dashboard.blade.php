<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Dashboard - InstaClone</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <h3>Selamat Datang, {{ session('user.name') }}</h3>
            <p>Username: <strong>{{ session('user.username') }}</strong></p>
            <p><a href="/posts/create" class="btn btn-primary">Create a Post</a></p>
            <p><a href="/logout" class="btn btn-danger">Logout</a></p>
        </div>
    </body>
</html>
