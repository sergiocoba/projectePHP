<?php
require 'db.php';
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $attractedTo = $_POST['attracted_to'] ?? '';
    $bio = trim($_POST['bio'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $age = $_POST['age'] ?? null;

    if ($password !== $confirmPassword) {
        die("Les contrasenyes no coincideixen.");
    }

    $query = $pdo->prepare("SELECT * FROM users WHERE mail = ?");
    $query->execute([$email]);
    if ($query->fetch()) {
        die("Aquest correu ja està registrat.");
    }

    $activationCode = hash('sha256', random_bytes(32));
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $profileImage = null;
    if (!empty($_FILES['profileImage']['tmp_name'])) {
        $imageData = file_get_contents($_FILES['profileImage']['tmp_name']);
        $profileImage = base64_encode($imageData);
    }

    $insert = $pdo->prepare("INSERT INTO users (username, mail, passHash, userFirstName, userLastName, activationCode, active, gender, attracted_to, bio, location, age, profileImage) VALUES (?, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?, ?, ?)");
    $insert->execute([$username, $email, $hashedPassword, $firstName, $lastName, $activationCode, $gender, $attractedTo, $bio, $location, $age, $profileImage]);
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tunooapp@gmail.com';
        $mail->Password = 'fuln luuj zgpt tjyn';    
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('tunooapp@gmail.com', 'TUNO APP');
        $mail->addAddress($email, $username);
        $mail->isHTML(true);
        $mail->Subject = 'Activa el teu compte';
        $activationLink = "http://localhost/projectePHP2/web/mailCheckAccount.php?code=$activationCode&mail=$email";

        $profileImageUrl = "https://drive.google.com/u/0/drive-viewer/AKGpihZNfYyRUz3QHoDj80aBCzoyXI7WQyI9IS5FD5tDr17HU7sTr17iXELzCwAjjEWnJUG5T8sE8i509PtndnD8IUnllol3ouySG14=s2560"; // Ajusta la URL según donde almacenes las imágenes

        $mail->Body = "<div style='max-width: 600px; margin: auto; padding: 20px; font-family: Arial, sans-serif; background-color: #f8f9fa; text-align: center; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);'>
            <h2 style='color: #333;'>Benvingut a TUNO APP, $username!</h2>
            <p style='color: #555; font-size: 16px;'>Per començar a utilitzar la nostra aplicació, activa el teu compte fent clic al botó següent.</p>
            <a href='$activationLink' style='display: inline-block; padding: 12px 24px; font-size: 18px; color: white; background-color: #ff4081; text-decoration: none; border-radius: 5px; margin-top: 15px;'>Activar el meu compte</a>
            <p style='color: #777; font-size: 14px; margin-top: 20px;'>Si no has creat aquest compte, ignora aquest missatge.</p>
        </div>";


        $mail->send();
        echo json_encode("Registre complet! Revisa el teu correu per activar el compte.");
    } catch (Exception $e) {
        echo json_encode( "Error en enviar el correu: " . $mail->ErrorInfo);
    }

}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre</title>
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