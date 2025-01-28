<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';

    if ($password !== $confirmPassword) {
        $error = "Les contrasenyes no coincideixen.";
    } else {
        // Comprovar si l'usuari o correu ja existeixen
        $query = $pdo->prepare("SELECT * FROM users WHERE username = :username OR mail = :email");
        $query->execute(['username' => $username, 'email' => $email]);

        if ($query->rowCount() > 0) {
            $error = "Nom d'usuari o correu electrònic ja utilitzat.";
        } else {
            // Crear el nou usuari
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insertQuery = $pdo->prepare("
                INSERT INTO users (username, mail, passHash, userFirstName, userLastName, creationDate, active)
                VALUES (:username, :email, :hash, :firstName, :lastName, NOW(), 1)
            ");
            $insertQuery->execute([
                'username' => $username,
                'email' => $email,
                'hash' => $hash,
                'firstName' => $firstName,
                'lastName' => $lastName
            ]);
            header("Location: index.php?registered=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Registre</title>
</head>
<body>
    <h1>Registra't</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Nom d'usuari" required>
        <input type="email" name="email" placeholder="Correu electrònic" required>
        <input type="password" name="password" placeholder="Contrasenya" required>
        <input type="password" name="confirm_password" placeholder="Confirma la contrasenya" required>
        <input type="text" name="first_name" placeholder="Nom (opcional)">
        <input type="text" name="last_name" placeholder="Cognom (opcional)">
        <button type="submit">Registrar</button>
    </form>
</body>
</html>
