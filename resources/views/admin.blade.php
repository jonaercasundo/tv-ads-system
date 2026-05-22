<!DOCTYPE html>
<html>
<head>
    <title>TV Ads Admin</title>
</head>
<body>
<h2>Upload ZIP Playlist</h2>

<form action="/api/videos/upload-zip" method="POST" enctype="multipart/form-data">
    @csrf

    <input type="file" name="zipfile" accept=".zip" required>

    <button type="submit">Upload ZIP</button>
</form>
<h2>Upload Video</h2>
@if(session('success'))
    <div style="background: green; color: white; padding: 10px; margin-bottom: 10px;">
        {{ session('success') }}
    </div>
@endif
<form action="/api/videos" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="text" name="title" placeholder="Title (1,2,3)" required>
    <input type="number" name="sort_order" placeholder="Order">
    <input type="file" name="video" required>
    <button type="submit">Upload</button>
</form>

<hr>

<h2>Playlist</h2>
<div id="list"></div>

<script>
async function load() {
    const res = await fetch('/api/videos');
    const data = await res.json();

    document.getElementById('list').innerHTML =
        data.map(v => `
            <div style="margin-bottom:10px;">
                ${v.sort_order} - ${v.title}

                <button onclick="deleteVideo(${v.id})" style="color:white;background:red;">
                    Delete
                </button>
            </div>
        `).join('');
}

async function deleteVideo(id) {
    if (!confirm("Delete this video?")) return;

    await fetch('/api/videos/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    load(); // refresh playlist
}

load();
</script>

</body>
</html>