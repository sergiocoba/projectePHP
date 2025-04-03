<?php
session_start();
require 'db.php';
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if ($password !== $confirmPassword) {
        $_SESSION['message'] = "Les contrasenyes no coincideixen.";
        header("Location: register.php");
        exit();
    }

    $query = $pdo->prepare("SELECT * FROM users WHERE mail = ?");
    $query->execute([$email]);
    if ($query->fetch()) {
        $_SESSION['message'] = " Aquest correu ja està registrat.";
        header("Location: register.php");
        exit();
    }

    $activationCode = hash('sha256', random_bytes(32));
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $insert = $pdo->prepare("INSERT INTO users (username, mail, passHash, activationCode, active) VALUES (?, ?, ?, ?, 0)");
    $insert->execute([$username, $email, $hashedPassword, $activationCode]);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tunooapp@gmail.com';
        $mail->Password = 'xqyp jkhs wlik vjdv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('tunooapp@gmail.com', 'TUNO APP');
        $mail->addAddress($email, $username);
        $mail->isHTML(true);
        $mail->Subject = 'Activa el teu compte';
        $activationLink = "http://localhost/projectePHP2/web/mailCheckAccount.php?code=$activationCode&mail=$email";
        $mail->Body = "<p>Benvingut a TUNO APP, $username!</p>
                       <p>Activa el teu compte fent clic <a href='$activationLink'>aquí</a>.</p>";

        $mail->send();
        header("Location: login.php?msg=Registre complet! Revisa el teu correu per activar el compte.");
        exit();
    } catch (Exception $e) {
        header("Location: login.php?msg=Error en enviar el correu.");
        exit();
    }

    header("Location: register.php");
    exit();
}
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['message'])): ?>
    <div class="alert">
        <?php echo $_SESSION['message']; ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre</title>
    <link rel="shortcut icon" href="../img/icono.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles/editProfile.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="profile-container">
        <h1>Registra't</h1>
        <form action="register.php" method="post" enctype="multipart/form-data">
            <label for="username">Nom d'usuari:</label>
            <input type="text" name="username" id="username" required>

            <label for="email">Correu electronic:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Contrasenya:</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm_password">Confirma la contrasenya:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <label for="first_name">Nom:</label>
            <input type="text" name="first_name" id="first_name">

            <label for="last_name">Cognom:</label>
            <input type="text" name="last_name" id="last_name">

            <label for="bio">Biografía:</label>
            <input type="text" name="bio" id="bio">

            <label for="location">Ubicació:</label>
            <input type="text" name="location" id="location">

            <label for="age">Edat:</label>
            <input type="number" name="age" id="age">

            <label for="gender">El teu sexe:</label>
            <select name="gender" id="gender">
                <option value="M">Home</option>
                <option value="F">Dona</option>
            </select>

            <label for="attracted_to">Sexe que t'atrau:</label>
            <select name="attracted_to" id="attracted_to">
                <option value="M">Homes</option>
                <option value="F">Dones</option>
                <option value="B">Ambdós</option>
            </select>

            <label for="profileImage">Foto de Perfil:</label>
            <input type="file" name="profileImage" id="profileImage" accept="image/*">

            <button type="submit" class="btn">Registrar</button>
        </form>
        <a href="login.php" class="btn">Ja tens un compte? Inicia sessió</a>
    </div>
</body>
</html>