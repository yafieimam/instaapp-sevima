<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Upload Post - InstaClone</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container mt-5">
            <h3>Upload Post</h3>
            <form method="POST" action="/posts" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label>Gambar</label>
                    <input type="file" name="image" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label>Caption</label>
                    <textarea name="caption" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="allow_comment" class="form-check-input" checked>
                    <label class="form-check-label">Izinkan komentar</label>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
                <a href="/dashboard" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </body>
</html>
