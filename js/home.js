document.addEventListener("DOMContentLoaded", function () {
    const profileCard = document.querySelector(".profile-card");

    if (profileCard) {
        const hammer = new Hammer(profileCard);

        hammer.on("swipeleft", function () {
            profileCard.classList.add("swipe-left");
            setTimeout(() => {
                darDislike();
                loadNewProfile();
            }, 500);
        });

        hammer.on("swiperight", function () {
            profileCard.classList.add("swipe-right");
            setTimeout(() => {
                darLike();
                loadNewProfile();
            }, 500);
        });
    }
});

function loadNewProfile() {
    location.reload();
}
function darLike(targetId) {
    fetch('../web/chats/like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'target_id=' + targetId
    })
    .then(response => response.json())
    .then(data => {
        alert(data.match ? "¡Es un match! Se ha abierto el chat." : "Like registrado. Esperando match.");
        loadNewProfile();
    })
    .catch(error => console.error('Error:', error));
}

function darDislike(targetId) {
    fetch('../web/chats/dislike.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'target_id=' + targetId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Usuario rechazado.");
        } else {
            alert("Error al registrar el rechazo.");
        }
        loadNewProfile();
    })
    .catch(error => console.error('Error:', error));
}

function loadNewProfile() {
    location.reload();
}


let images = json_encode($fotos);
let currentIndex = 0;

function changeImage(direction) {
    currentIndex = (currentIndex + direction + images.length) % images.length;
    document.getElementById("profile-image").src = images[currentIndex];
}