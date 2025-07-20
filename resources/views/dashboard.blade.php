<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Dashboard - InstaApp</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <style>
            .post-image { width: 50%; max-height: 400px; object-fit: cover; }
            .post-card { margin-bottom: 30px; }
        </style>
    </head>
    <body>
        <div class="container mt-4">
            <div class="d-flex justify-content-between mb-3">
                <h3>Feed</h3>
                <div>
                    <a href="/posts/create" class="btn btn-success">+ Upload</a>
                    <a href="/logout" class="btn btn-danger">Logout</a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @php
                $response = Http::withToken(session('token'))->get(url('http://localhost/instaapp_sevima/public/api/posts'));
                $posts = $response->successful() ? $response->json() : [];
            @endphp

            @foreach ($posts as $post)
                <div class="card post-card">
                    <div class="card-header">
                        <strong>{{ $post['user']['username'] }}</strong>
                        <span class="text-muted float-end">{{ \Carbon\Carbon::parse($post['created_at'])->diffForHumans() }}</span>
                    </div>
                    <div class="card-body">
                        <img src="{{ asset('storage/' . $post['image_path']) }}" class="post-image mb-3" />
                        @if ($post['caption'])
                            <p>{{ $post['caption'] }}</p>
                        @endif
                        <form method="POST" action="/posts/{{ $post['id'] }}/like">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary">
                                ❤️ Like
                            </button>
                        </form>
                        @if ($post['user']['id'] == session('user.id'))
                            @php
                                $likeRes = Http::withToken(session('token'))->get(url('http://localhost/instaapp_sevima/public//api/posts/' . $post['id'] . '/likes'));
                                $likers = $likeRes->ok() ? $likeRes->json() : [];
                            @endphp
                            <p class="text-muted mt-2">
                                {{ $likers['count'] ?? 0 }} orang menyukai ini
                                @if (!empty($likers['users']))
                                    <br><small>Liked by: {{ implode(', ', $likers['users']) }}</small>
                                @endif
                            </p>
                        @endif
                        <p class="text-muted mb-0">
                            Komentar {{ $post['allow_comment'] ? 'diizinkan' : 'dimatikan' }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </body>
</html>
