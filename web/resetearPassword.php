<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/pass.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="shortcut icon" href="../img/icono.ico" type="image/x-icon">
    <script src="../js/pass.js"></script>
    <title>Reset Password</title>
</head>
<body>

    <iframe id="background-frame" src="login.php"></iframe>

    <div id="overlay">
        <div class="content">
            <center>
                <span style="font-weight: bold; text-shadow: 0px 1px 4px rgba(0, 0, 0, 0.6); color: #fff;">
                </span>
            </center>
        </div>
    </div>

    <div class="row pop-up">
        <div class="box small-6 large-centered">
            <a href="login.php" class="close-button">&#10006;</a>
            <h3>Resetejar la contrassenya</h3>
            <form id="resetPasswordForm" action="resetPasswordSend.php" method="POST">
                <div class="form__field">
                    <input type="text" name="username_email" class="form__input" placeholder="Usuari o correu electrònic" required>
                </div>  
                <button type="submit" class="button">Continue</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const params = new URLSearchParams(window.location.search);
            const status = params.get("status");
            const message = params.get("message");

            if (status && message) {
                Swal.fire({
                    icon: status === "success" ? "success" : "error",
                    title: status === "success" ? "Èxit!" : "Error",
                    text: message,
                    confirmButtonColor: "#ea4c88"
                });

                window.history.replaceState(null, "", window.location.pathname);
            }
        });
    </script>

    <script>
        document.getElementById("sendResetEmail").addEventListener("click", function() {
            let email = document.getElementById("username_email").value;

            if (!email) {
                alert("Introdueix el teu correu electrònic.");
                return;
            }

            fetch("resetPassword.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "email=" + encodeURIComponent(email)
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                window.location.href = "login.php";
            })
            .catch(error => console.error("Error:", error));
        });
    </script>

</body>
</html>
