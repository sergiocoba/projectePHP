<?php
require 'db.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username_email'])) {
    $userInput = $_POST['username_email'];

    try {
        $stmt = $pdo->prepare("SELECT iduser, mail FROM users WHERE username = ? OR mail = ?");
        $stmt->execute([$userInput, $userInput]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("No s'ha trobat cap compte associat.");
        }

        $resetCode = hash('sha256', random_bytes(32));
        $expiryTime = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        $updateStmt = $pdo->prepare("UPDATE users SET resetPassCode = ?, resetPassExpiry = ? WHERE iduser = ?");
        $updateStmt->execute([$resetCode, $expiryTime, $user['iduser']]);

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
            $mail->addAddress($user['mail']);

            $mail->isHTML(true);
            $mail->Subject = 'Reset de contrasenya';
            $resetLink = "http://localhost/projectePHP2/web/resetPassword.php?code=$resetCode&mail=" . urlencode($user['mail']);
            $mail->Body = "
            <div style='max-width: 600px; margin: auto; padding: 20px; font-family: Arial, sans-serif; background-color: #2c3338; text-align: center; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); color: #eee;'>
                <h2 style='color: #eee;'>Sol·licitud de restabliment de contrasenya</h2>
                <p style='color: #bbb; font-size: 16px;'>Has sol·licitat restablir la teva contrasenya. Fes clic al botó següent per continuar:</p>
                <a href='$resetLink' style='display: inline-block; padding: 12px 24px; font-size: 18px; color: white; background-color: #ea4c88; text-decoration: none; border-radius: 5px; margin-top: 15px;'>Restablir contrasenya</a>
                <p style='color: #888; font-size: 14px; margin-top: 20px;'>Si no has sol·licitat aquest canvi, ignora aquest missatge.</p>
            </div>";

            $mail->send();
            header("Location: resetearPassword.php?status=success&message=Correu enviat! Revisa la teva safata d'entrada.");
        } catch (Exception $e) {
            header("Location: resetearPassword.php?status=error&message=Error en enviar el correu.");
        }
        exit;
    } catch (PDOException $e) {
        die("Error de connexió: " . $e->getMessage());
    }
} else {
    die("Dades no vàlides.");
}
