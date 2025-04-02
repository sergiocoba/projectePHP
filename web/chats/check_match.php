<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['target_id'])) {
    echo json_encode(['match' => false]);
    exit();
}

$user_id = $_SESSION['user_id'];
$target_id = $_POST['target_id'];

$query = $pdo->prepare("
    SELECT * FROM matches
    WHERE (usuario1 = ? AND usuario2 = ?) 
        OR (usuario1 = ? AND usuario2 = ?)
");
$query->execute([$user_id, $target_id, $target_id, $user_id]);
$match = $query->fetch();

echo json_encode(['match' => $match ? true : false]);
?>