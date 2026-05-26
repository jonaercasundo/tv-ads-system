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

/* ---------------------------
   LOAD PLAYLIST (SAFE VERSION)
----------------------------*/
async function loadVideos() {
    try {
        const res = await fetch('/api/videos', { cache: "no-store" });
        const data = await res.json();

        playlist = data.map(v => "/uploads/" + v.filename);

        if (playlist.length > 0 && player.paused) {
            playVideo();
        }

    } catch (err) {
        console.log("Playlist fetch failed, retrying...");
    }
}

/* ---------------------------
   PLAY VIDEO (SAFE AUTOPLAY)
----------------------------*/
function playVideo() {
    if (playlist.length === 0) return;

    player.src = playlist[index];

    player.play().catch(err => {
        console.log("Autoplay blocked, retrying...", err);
        setTimeout(() => player.play().catch(()=>{}), 1000);
    });
}

/* ---------------------------
   NEXT VIDEO HANDLER
----------------------------*/
player.onended = () => {
    index = (index + 1) % playlist.length;
    playVideo();
};

/* ---------------------------
   TV RECOVERY EVENTS
----------------------------*/
player.addEventListener("pause", () => {
    player.play().catch(()=>{});
});

player.addEventListener("waiting", () => {
    player.play().catch(()=>{});
});

player.addEventListener("stalled", () => {
    player.load();
    player.play().catch(()=>{});
});

player.addEventListener("error", () => {
    index = (index + 1) % playlist.length;
    playVideo();
});

/* ---------------------------
   INTERNET RECOVERY
----------------------------*/
window.addEventListener("online", () => {
    location.reload();
});

window.addEventListener("offline", () => {
    console.log("Offline mode");
});

/* ---------------------------
   WATCHDOG (CRITICAL FOR TV)
----------------------------*/
setInterval(() => {
    if (!navigator.onLine) return;

    if (player.paused || player.readyState < 3) {
        player.play().catch(()=>{});
    }
}, 3000);

/* ---------------------------
   AUTO REFRESH (STABILITY)
----------------------------*/
setInterval(() => {
    location.reload();
}, 1000 * 60 * 30); // 30 min

/* ---------------------------
   LIVE PLAYLIST UPDATE
----------------------------*/
setInterval(loadVideos, 30000);

loadVideos().then(() => playVideo());
</script>

</body>
</html>