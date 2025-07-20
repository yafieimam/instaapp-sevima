<!DOCTYPE html>
<html lang="en">
    <head>
        <title>My Profile - InstaApp</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container mt-4">
            <a href="/dashboard" class="btn btn-secondary">Back</a>
            <div class="card">
                @if ($post['image_path'])
                    <img src="{{ asset('storage/' . $post['image_path']) }}" class="card-img-top" alt="Post image">
                @endif
                <div class="card-body">
                    <h5>{{ $post['user']['name'] }}</h5>
                    <p>{{ $post['caption'] }}</p>
                    <p class="text-muted small">{{ $post['created_at'] }}</p>
                    <p>❤️ {{ count($post['likes']) }} | 💬 {{ count($post['comments']) }}</p>

                    @if ($user['id'] != $post['user']['id'])
                        <form action="/like/{{ $post['id'] }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger">❤️ Like</button>
                        </form>
                    @endif

                    @if ($post['allow_comment'])
                        <hr>
                        <form action="/comment/{{ $post['id'] }}" method="POST">
                            @csrf
                            <textarea name="content" class="form-control" rows="2" required></textarea>
                            <button class="btn btn-primary btn-sm mt-2">Kirim Komentar</button>
                        </form>
                    @endif

                    <hr>
                    <h6>Komentar:</h6>
                    @forelse ($post['comments'] as $comment)
                        <div class="mb-2">
                            <strong>{{ $comment['user']['name'] }}</strong><br>
                            {{ $comment['content'] }}
                            @if ($comment['user']['id'] == session('user.id') || $post['user']['id'] == session('user.id'))
                                <form method="POST" action="/comments/{{ $comment['id'] }}/delete" style="display:inline">
                                    @csrf
                                    <button class="btn btn-sm btn-link text-danger p-0" onclick="return confirm('Hapus komentar?')">hapus</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">Belum ada komentar.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </body>
</html>
