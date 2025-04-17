<?php
session_start();
require 'conn.php';
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $code = rand(100000, 999999);
        $expire = time() + 300;

        $_SESSION['reset_code'] = $code;
        $_SESSION['reset_email'] = $email;
        $_SESSION['code_expiry'] = $expire;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'saijmohamed4@gmail.com';
            $mail->Password = 'nixn ivxf sbxa bkwn';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('no-reply@parfumplanet.com', 'ParfumPlanet');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Code de vérification";
            $mail->Body = "
                <div style='font-family: Arial; background: #f9f9f9; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto;'>
                    <h2 style='color: #5D3FD3; text-align: center;'>🔐 Vérification de votre compte</h2>
                    <p>Bonjour,</p>
                    <p>Voici votre code de vérification :</p>
                    <div style='background-color: #5D3FD3; color: white; font-size: 24px; padding: 15px; text-align: center; border-radius: 6px; margin: 20px 0;'>$code</div>
                    <p>Ce code expire dans <strong>5 minutes</strong>.</p>
                    <p style='color: #888;'>Si vous n'avez pas demandé cela, ignorez ce message.</p>
                    <p>Cordialement,<br><strong>ParfumPlanet</strong></p>
                </div>
            ";

            $mail->send();
            echo "<script>window.location.href='verifier_code.php';</script>";
        } catch (Exception $e) {
            $message = "Erreur lors de l'envoi de l'e-mail : " . $mail->ErrorInfo;
        }
    } else {
        $message = "❌ Adresse email non trouvée.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #05365f, #ff4d88); /* Fond dégradé identique au login */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            padding: 40px 30px;
            width: 100%;
            max-width: 400px;
            color: #fff;
        }
        h3 {
            text-align: center;
            font-size: 2em;
            margin-bottom: 1.5rem;
            background-image: linear-gradient(to right, #ff9f00, #ff4d88);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        input[type="email"] {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            border-radius: 12px;
            padding: 14px;
            width: 100%;
            color: #fff;
            margin-bottom: 1rem;
            font-size: 1rem;
        }
        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 25px;
            background: linear-gradient(to right, #ffcc70, #ff8c8c); /* Le même dégradé que dans le login */
            color: white;
            font-size: 1rem;
            transition: transform 0.2s ease;
            margin-top: 0.5rem;
        }
        button:hover {
            transform: scale(1.05);
            cursor: pointer;
        }
        .alert {
            background-color: rgba(255, 0, 0, 0.3);
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 1rem;
            text-align: center;
        }
        .back-btn {
            margin-top: 10px;
            background: linear-gradient(to right, #9d50bb, #6e48aa);
        }
    </style>
</head>
<body>
    <form method="post" class="card">
        <h3>🔑 Mot de passe oublié</h3>
        <?php if (!empty($message)): ?>
            <div class="alert"><?= $message ?></div>
        <?php endif; ?>
        <input type="email" name="email" placeholder="Votre adresse email" required>
        <button type="submit">📩 Envoyer le code</button>
        <a href="login.php">
            <button type="button" class="back-btn">⬅ Retour</button>
        </a>
    </form>
</body>
</html>
