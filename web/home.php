<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Benvingut</title>
    <link rel="stylesheet" href="../styles/home.css">
</head>
<body>
    <div class="home-container">
        <h1>👋 Benvingut, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        <p>Estàs a la pàgina principal de Tuno. De moment no hi ha res però tornarem 😉</p>
        <a href="logout.php" class="logout-btn">Tanca Sessió</a>
    </div>
</body>
</html>
