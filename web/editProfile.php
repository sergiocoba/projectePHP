<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT username, userFirstName, userLastName, gender, attracted_to, bio, location, age, profileImage FROM users WHERE iduser = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $attractedTo = $_POST['attracted_to'] ?? '';
    $bio = trim($_POST['bio'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $age = $_POST['age'] ?? null;

    if (!empty($_FILES['profileImage']['tmp_name'])) {
        $imageData = file_get_contents($_FILES['profileImage']['tmp_name']);
        $profileImage = base64_encode($imageData);
    } else {
        $profileImage = $user['profileImage'];
    }

    $updateStmt = $pdo->prepare("UPDATE users SET username = ?, userFirstName = ?, userLastName = ?, gender = ?, attracted_to = ?, bio = ?, location = ?, age = ?, profileImage = ? WHERE iduser = ?");
    $updateStmt->execute([$username, $firstName, $lastName, $gender, $attractedTo, $bio, $location, $age, $profileImage, $user_id]);

    header("Location: profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="shortcut icon" href="../img/icono.ico" type="image/x-icon">k
    <link rel="stylesheet" href="../styles/editProfile.css">
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body>
    <div class="profile-container">
        <h1>Editar Perfil</h1>
        <form action="editProfile.php" method="post" enctype="multipart/form-data">
            <label for="username">Nombre de usuario:</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>">

            <label for="first_name">Nombre:</label>
            <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($user['userFirstName'] ?? ''); ?>">

            <label for="last_name">Apellido:</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($user['userLastName'] ?? ''); ?>">

            <label for="gender">Género:</label>
            <select name="gender" id="gender">
                <option value="M" <?php echo ($user['gender'] ?? '') === 'M' ? 'selected' : ''; ?>>Hombre</option>
                <option value="F" <?php echo ($user['gender'] ?? '') === 'F' ? 'selected' : ''; ?>>Mujer</option>
            </select>

            <label for="attracted_to">Atracción:</label>
            <select name="attracted_to" id="attracted_to">
                <option value="M" <?php echo ($user['attracted_to'] ?? '') === 'M' ? 'selected' : ''; ?>>Hombres</option>
                <option value="F" <?php echo ($user['attracted_to'] ?? '') === 'F' ? 'selected' : ''; ?>>Mujeres</option>
                <option value="B" <?php echo ($user['attracted_to'] ?? '') === 'B' ? 'selected' : ''; ?>>Ambos</option>
            </select>

            <label for="bio">Biografía:</label>
            <input type="text" name="bio" id="bio" value="<?php echo htmlspecialchars($user['bio'] ?? ''); ?>">

            <label for="location">Ubicación:</label>
            <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($user['location'] ?? ''); ?>">

            <label for="age">Edad:</label>
            <input type="number" name="age" id="age" value="<?php echo htmlspecialchars($user['age'] ?? ''); ?>">

            <label for="profileImage">Foto de Perfil:</label>
            <input type="file" name="profileImage" id="profileImage" accept="image/*">

            <button type="submit" class="btn">Guardar Cambios</button>
        </form>
        <a href="profile.php" class="btn">Cancelar</a>
    </div>
</body>
</html>
