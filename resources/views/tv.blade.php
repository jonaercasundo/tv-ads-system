<!DOCTYPE html>
<html>
<head>
    <title>TV Player</title>
    <style>
        html, body {
            margin: 0;
            height: 100%;
            background: black;
            overflow: hidden;
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
    </style>
</head>

<body>

<video id="player" autoplay muted playsinline></video>

<script>
let playlist = [];
let index = 0;

const player = document.getElementById("player");

async function loadVideos() {
    const res = await fetch('/api/videos');
    const data = await res.json();

    playlist = data.map(v => "/uploads/" + v.filename);
}

function playVideo() {
    if (playlist.length === 0) return;

    player.src = playlist[index];
    player.play();
}

player.onended = () => {
    index = (index + 1) % playlist.length;
    playVideo();
};

// refresh every 30 sec (live updates)
setInterval(loadVideos, 30000);

loadVideos().then(() => playVideo());
</script>

</body>
</html>