document.addEventListener("DOMContentLoaded", function () {
    const chatList = document.getElementById("chatList");
    const searchUser = document.getElementById("searchUser");
    const userSuggestions = document.getElementById("userSuggestions");
    const messagesDiv = document.getElementById("messages");
    const messageInput = document.getElementById("messageInput");
    const sendMessage = document.getElementById("sendMessage");
    const menuButton = document.getElementById("menuButton");
    const sidebar = document.getElementById("sidebar");
    const chatTitle = document.getElementById("chatTitle");

    let currentChat = null;

    document.addEventListener("click", function (event) {
        if (!sidebar.contains(event.target) && !menuButton.contains(event.target)) {
            sidebar.classList.remove("active");
        }
    });
    
    function loadChats() {
        fetch("obtener_chats.php")
            .then(res => res.json())
            .then(data => {
                chatList.innerHTML = data.length > 0 
                    ? data.map(chat => `<div class='chat-item' data-id='${chat.iduser}'>${chat.username}</div>`).join('') 
                    : "<p style='color: white; text-align: center;'>No hay chats disponibles</p>";
            })
            .catch(err => console.error("Error al cargar chats:", err));
    }

    
    function loadMessages(userId) {
        currentChat = userId;
        fetch(`obtener_mensajes.php?receptor=${userId}`)
            .then(res => res.json())
            .then(data => {
                messagesDiv.innerHTML = data.length > 0 
                    ? data.map(msg => `
                        <div class='message ${msg.position}'>
                            <strong>${msg.position === 'sent' ? 'Tú' : msg.username}:</strong> ${msg.mensaje}
                        </div>
                    `).join('') 
                    : "<p style='color: white; text-align: center;'>No hay mensajes</p>";
            })
            .catch(err => console.error("Error al cargar mensajes:", err));
    }

    function sendMessageToChat() {
        if (!currentChat || !messageInput.value.trim()) return;

        fetch("enviar_mensaje.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ receptor: currentChat, mensaje: messageInput.value })
        })
        .then(() => {
            messageInput.value = "";
            loadMessages(currentChat);
        })
        .catch(err => console.error("Error al enviar mensaje:", err));
    }

    searchUser.addEventListener("input", function () {
        fetch(`buscar_usuarios.php?q=${searchUser.value}`)
            .then(res => res.json())
            .then(data => {
                userSuggestions.innerHTML = data.length > 0 
                    ? data.map(user => `<div class='user-suggestion' data-id='${user.iduser}'>${user.username}</div>`).join('') 
                    : "<p style='color: white; text-align: center;'>No se encontraron usuarios</p>";
            })
            .catch(err => console.error("Error en la búsqueda de usuarios:", err));
    });
    
    chatList.addEventListener("click", function (e) {
        if (e.target.classList.contains("chat-item")) {
            const targetUserId = e.target.dataset.id;
            
            fetch("check_match.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ target_id: targetUserId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.match) {
                    chatTitle.textContent = e.target.textContent;
                    loadMessages(targetUserId);
                    sidebar.classList.remove("active");
                } else {
                    alert("No puedes chatear con este usuario porque no hay match.");
                }
            })
            .catch(err => console.error("Error al verificar el match:", err));
        }
    });
    
    userSuggestions.addEventListener("click", function (e) {
        if (e.target.classList.contains("user-suggestion")) {
            const targetUserId = e.target.dataset.id;
            const targetUsername = e.target.textContent;
    
            fetch("check_match.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ target_id: targetUserId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.match) {
                    chatTitle.textContent = targetUsername;
                    currentChat = targetUserId;
                    messagesDiv.innerHTML = "<p style='color: white; text-align: center;'>Cargando mensajes...</p>";
                    loadMessages(targetUserId);
                } else {
                    alert("No puedes chatear con este usuario porque no hay match.");
                }
            })
            .catch(err => console.error("Error al verificar el match:", err));
        }
    });

    sendMessage.addEventListener("click", sendMessageToChat);

    messageInput.addEventListener("keydown", function (e) {
        if (e.key === "Enter") sendMessageToChat();
    });

    menuButton.addEventListener("click", function () {
        sidebar.classList.toggle("active");
    });

    

    loadChats();
});
