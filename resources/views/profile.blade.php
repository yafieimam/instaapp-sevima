<!DOCTYPE html>
<html lang="en">
    <head>
        <title>My Profile - InstaApp</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container mt-4">
            <h2>Profil {{ $user['name'] }}</h2>
            <p>Email: {{ $user['email'] }}</p>
            <a href="/dashboard" class="btn btn-secondary">Back</a>

            <h4 class="mt-4">Postingan</h4>
            <div class="row">
                @foreach ($posts as $post)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            @if ($post['image_path'])
                                <img src="{{ asset('storage/' . $post['image_path']) }}" class="card-img-top" alt="Post image">
                            @endif
                            <div class="card-body">
                                <p class="card-text">{{ $post['caption'] }}</p>
                                <p class="text-muted small">{{ $post['created_at'] }}</p>
                                <p>❤️ {{ count($post['likes']) }} | 💬 {{ count($post['comments']) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </body>
</html>
