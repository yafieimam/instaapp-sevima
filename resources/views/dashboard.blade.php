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
                <h3>Feed - {{ session('user.name') }}</h3>
                <div>
                    <a href="/profile/{{ session('user.id') }}" class="btn btn-primary">Profil Saya</a>
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
                    <a href="/post/{{ $post['id'] }}">
                        <div class="card-header">
                            <strong>{{ $post['user']['username'] }}</strong>
                            <span class="text-muted float-end">{{ \Carbon\Carbon::parse($post['created_at'])->diffForHumans() }}</span>
                        </div>
                    </a>
                    <div class="card-body">
                        <img src="{{ asset('storage/' . $post['image_path']) }}" class="post-image mb-3" />
                        @if ($post['caption'])
                            <p>{{ $post['caption'] }}</p>
                        @endif
                        @if ($post['user']['id'] == session('user.id'))
                            <div class="mb-2">
                                <form method="POST" action="/posts/{{ $post['id'] }}/edit">
                                    @csrf
                                    <label>
                                        Edit Caption
                                    </label>
                                    <textarea name="caption" class="form-control mb-1">{{ $post['caption'] }}</textarea>
                                    <label>
                                        <input type="checkbox" name="allow_comment" {{ $post['allow_comment'] ? 'checked' : '' }}>
                                        Izinkan komentar
                                    </label>
                                    <button class="btn btn-sm btn-warning mt-1">Simpan Perubahan</button>
                                </form>

                                <form method="POST" action="/posts/{{ $post['id'] }}/delete" class="mt-2">
                                    @csrf
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus post ini?')">Hapus Post</button>
                                </form>
                            </div>
                        @endif
                        <form method="POST" action="/posts/{{ $post['id'] }}/like">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary">
                                ❤️ Like
                            </button>
                        </form>
                        <p class="text-muted mt-2">
                            {{ count($post['likes']) }} orang menyukai ini
                        </p>
                        @if ($post['allow_comment'])
                            <form method="POST" action="/posts/{{ $post['id'] }}/comments" class="mb-3">
                                @csrf
                                <textarea name="content" class="form-control mb-2" rows="2" placeholder="Tulis komentar..." required></textarea>
                                <button class="btn btn-sm btn-secondary">Kirim Komentar</button>
                            </form>

                            @php
                                $commentRes = Http::withToken(session('token'))->get(url("http://localhost/instaapp_sevima/public/api/posts/{$post['id']}/comments"));
                                $comments = $commentRes->ok() ? $commentRes->json() : [];
                            @endphp

                            @foreach ($comments as $comment)
                                <div class="border rounded p-2 mb-2 bg-light">
                                    <strong>{{ $comment['user']['username'] }}</strong>:
                                    {{ $comment['content'] }}
                                    @if ($comment['user']['id'] == session('user.id') || $post['user']['id'] == session('user.id'))
                                        <form method="POST" action="/comments/{{ $comment['id'] }}/delete" style="display:inline">
                                            @csrf
                                            <button class="btn btn-sm btn-link text-danger p-0" onclick="return confirm('Hapus komentar?')">hapus</button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </body>
</html>
