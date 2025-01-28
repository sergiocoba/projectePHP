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
        $query = $pdo->prepare("SELECT * FROM users WHERE username = :username OR mail = :email");
        $query->execute(['username' => $username, 'email' => $email]);

        if ($query->rowCount() > 0) {
            $error = "Nom d'usuari o correu electrònic ja utilitzat.";
        } else {
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
            header("Location: ../index.php?registered=1");
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
    <link rel="stylesheet" href="../styles/index.css">
</head>
<body class="align">
  <div class="grid">
    <h1 class="text--center">Registra't</h1>

    <?php if (isset($error)): ?>
        <p class="error" style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="form login">

      <div class="form__field">
        <label for="username"><svg class="icon">
            <use xlink:href="#icon-user"></use>
          </svg><span class="hidden">Nom d'usuari</span></label>
        <input autocomplete="username" id="username" type="text" name="username" class="form__input" placeholder="Nom d'usuari" required>
      </div>

      <div class="form__field">
        <label for="email"><svg class="icon">
            <use xlink:href="#icon-user"></use>
          </svg><span class="hidden">Correu electrònic</span></label>
        <input id="email" type="email" name="email" class="form__input" placeholder="Correu electrònic" required>
      </div>

      <div class="form__field">
        <label for="password"><svg class="icon">
            <use xlink:href="#icon-lock"></use>
          </svg><span class="hidden">Contrasenya</span></label>
        <input id="password" type="password" name="password" class="form__input" placeholder="Contrasenya" required>
      </div>

      <div class="form__field">
        <label for="confirm_password"><svg class="icon">
            <use xlink:href="#icon-lock"></use>
          </svg><span class="hidden">Confirma la contrasenya</span></label>
        <input id="confirm_password" type="password" name="confirm_password" class="form__input" placeholder="Confirma la contrasenya" required>
      </div>

      <div class="form__field">
        <input type="text" name="first_name" class="form__input" placeholder="Nom (opcional)">
      </div>

      <div class="form__field">
        <input type="text" name="last_name" class="form__input" placeholder="Cognom (opcional)">
      </div>

      <div class="form__field">
        <input type="submit" value="Registrar">
      </div>

    </form>

    <p class="text--center">Ja tens un compte? <a href="../index.php">Inicia sessió</a> <svg class="icon">
        <use xlink:href="#icon-arrow-right"></use>
      </svg></p>

  </div>

  <!-- Definición de los iconos SVG -->
  <svg xmlns="http://www.w3.org/2000/svg" class="icons" style="display: none;">
    <symbol id="icon-arrow-right" viewBox="0 0 1792 1792">
      <path d="M1600 960q0 54-37 91l-651 651q-39 37-91 37-51 0-90-37l-75-75q-38-38-38-91t38-91l293-293H245q-52 0-84.5-37.5T128 1024V896q0-53 32.5-90.5T245 768h704L656 474q-38-36-38-90t38-90l75-75q38-38 90-38 53 0 91 38l651 651q37 35 37 90z" />
    </symbol>
    <symbol id="icon-lock" viewBox="0 0 1792 1792">
      <path d="M640 768h512V576q0-106-75-181t-181-75-181 75-75 181v192zm832 96v576q0 40-28 68t-68 28H416q-40 0-68-28t-28-68V864q0-40 28-68t68-28h32V576q0-184 132-316t316-132 316 132 132 316v192h32q40 0 68 28t28 68z" />
    </symbol>
    <symbol id="icon-user" viewBox="0 0 1792 1792">
      <path d="M1600 1405q0 120-73 189.5t-194 69.5H459q-121 0-194-69.5T192 1405q0-53 3.5-103.5t14-109T236 1084t43-97.5 62-81 85.5-53.5T538 832q9 0 42 21.5t74.5 48 108 48T896 971t133.5-21.5 108-48 74.5-48 42-21.5q61 0 111.5 20t85.5 53.5 62 81 43 97.5 26.5 108.5 14 109 3.5 103.5zm-320-893q0 159-112.5 271.5T896 896 624.5 783.5 512 512t112.5-271.5T896 128t271.5 112.5T1280 512z" />
    </symbol>
  </svg>

</body>
</html>
