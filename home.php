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
    <title>Benvingut</title>
</head>
<body>
    <h1>Benvingut, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    <p>Aquesta és la teva pàgina principal.</p>
    <a href="logout.php">Tanca sessió</a>
</body>
</html>
