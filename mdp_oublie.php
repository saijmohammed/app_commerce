<?php
session_start();
include("conn.php");

// Utilisation de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Assurez-vous d'adapter ces chemins selon l'emplacement de votre installation PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    // Vérifier si l'email existe dans la base de données
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    
    if (mysqli_num_rows($result) > 0) {
        $verification_code = rand(100000, 999999); // Générer un code aléatoire à 6 chiffres
        
        // Enregistrer le code dans la session
        $_SESSION['verification_code'] = $verification_code;
        $_SESSION['reset_email'] = $email;
        $_SESSION['code_expiry'] = time() + 3600; // Expiration du code après 1 heure
        
        // Utilisation de PHPMailer pour envoyer l'email
        $mail = new PHPMailer(true);
        
        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Remplacez par votre serveur SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'saijmohamed4@gmail.com'; // Remplacez par votre email
            $mail->Password = 'votre_mot_de_passe'; // Remplacez par votre mot de passe
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            
            // Configuration des destinataires
            $mail->setFrom('noreply@gamingplanet.com', 'GamingPlanet');
            $mail->addAddress($email);
            
            // Contenu de l'email
            $mail->isHTML(true);
            $mail->Subject = "Code de vérification - GamingPlanet";
            $mail->Body = "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;'>
                        <h2 style='color: #333;'>Réinitialisation du mot de passe</h2>
                        <p>Vous avez demandé la réinitialisation de votre mot de passe sur GamingPlanet.</p>
                        <p>Votre code de vérification est : <strong style='font-size: 18px;'>{$verification_code}</strong></p>
                        <p>Ce code est valable pendant 1 heure.</p>
                        <p>Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet email.</p>
                    </div>
                </body>
                </html>
            ";
            
            $mail->send();
            echo "<script>alert('Un code de vérification a été envoyé à votre email.'); window.location='reset_password.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Erreur lors de l\'envoi du code: {$mail->ErrorInfo}');</script>";
        }
    } else {
        echo "<script>alert('Cet email n\'existe pas dans notre base de données.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - GamingPlanet</title>
    <link rel="icon" href="photo/7553408.jpg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Arrière-plan avec couleurs et effet néon */
        body {
            background: linear-gradient(45deg, #1a1a2e, #16213e, #0f3460);
            background-attachment: fixed;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        /* Effet de néon en arrière-plan */
        body::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('https://i.imgur.com/dM7Thhn.jpg') no-repeat center center/cover;
            opacity: 0.4; /* Opacité pour éviter trop de contraste */
            z-index: -1;
        }

        /* Formulaire en mode "Glassmorphism" */
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            padding: 30px;
            box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        /* Effet de titre en dégradé */
        .gradient-title {
            background: linear-gradient(90deg, #ff00ff, #00ffff, #ffcc00);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: bold;
            font-size: 28px;
        }

        /* Champs de saisie avec bord lumineux */
        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            border-radius: 5px;
        }

        .form-control:focus {
            border: 2px solid #ff00ff;
            background: rgba(255, 255, 255, 0.3);
            color: white;
            box-shadow: none;
        }

        /* Bouton lumineux */
        .btn-login {
            background: linear-gradient(90deg, rgb(0, 255, 200), rgb(153, 0, 255));
            border: none;
            color: white;
            font-weight: bold;
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: linear-gradient(90deg, #00ffff, rgb(255, 0, 0));
            transform: scale(1.05);
        }

        /* Liens */
        .link {
            color: #00ffff;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1 class="gradient-title">MOT DE PASSE OUBLIÉ</h1>
        
        <form action="" method="post">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            
            <button type="submit" class="btn btn-login w-100">Envoyer le code</button>
            
            <div class="mt-3">
                <a href="login.php" class="link">Retour à la connexion</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>