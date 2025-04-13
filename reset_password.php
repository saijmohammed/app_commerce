<?php
session_start();
require 'conn.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Vérifie si l'email existe
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $code = rand(100000, 999999);
        $expire = time() + 300; // 5 minutes

        $_SESSION['reset_code'] = $code;
        $_SESSION['reset_email'] = $email;
        $_SESSION['code_expiry'] = $expire;

        require 'vendor/autoload.php';

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'saijmohamed4@gmail.com';
            $mail->Password = 'zbjy lvma ijqd fgjj'; // ⚠️ Ne jamais l'afficher en prod
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('no-reply@gamingplanet.com', 'GamingPlanet');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Code de vérification";
            $mail->Body = "<h3>Votre code est : <strong>$code</strong></h3><p>Il expire dans 5 minutes.</p>";
            $mail->send();

            echo "<script>alert('Un code a été envoyé à votre email'); window.location.href='verifier_code.php';</script>";
        } catch (Exception $e) {
            echo "Erreur: " . $mail->ErrorInfo;
        }
    } else {
        echo "<script>alert('Email non trouvé');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 bg-dark text-white">
<div class="container">
    <form method="post" class="card p-4 bg-secondary">
        <h3 class="text-center">Mot de passe oublié</h3>
        <input type="email" name="email" class="form-control my-2" placeholder="Votre email" required>
        <button type="submit" class="btn btn-light">Envoyer le code</button>
    </form>
</div>
</body>
</html>
