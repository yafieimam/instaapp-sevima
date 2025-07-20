<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login - InstaApp</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <h3 class="text-center">Login</h3>
                    <form method="POST" action="/login">
                        @csrf
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required />
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                        <p class="text-center mt-3">
                            Belum punya akun? <a href="/register">Daftar</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
