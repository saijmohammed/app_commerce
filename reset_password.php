<?php
session_start();
include("conn.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Vérifier si l'email existe dans la base de données
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Générer un code de vérification
        $verification_code = rand(100000, 999999);
        $expiry_time = time() + 600; // Le code expire après 10 minutes

        // Sauvegarder le code de vérification et l'email dans la session
        $_SESSION['verification_code'] = $verification_code;
        $_SESSION['reset_email'] = $email;
        $_SESSION['code_expiry'] = $expiry_time;
        

        // Envoyer le code par email (assurez-vous que PHPMailer est bien configuré)
        // Exemple d'envoi avec PHPMailer (assurez-vous de configurer votre serveur SMTP dans PHPMailer)
        require 'vendor/autoload.php';

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Remplacez par votre serveur SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'saijmohamed4@gmail.com'; // Remplacez par votre email
            $mail->Password = ' '; // Remplacez par votre mot de passe
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('no-reply@example.com', 'GamingPlanet');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Code de réinitialisation de mot de passe';
            $mail->Body    = 'Voici votre code de vérification : ' . $verification_code;

            $mail->send();
            echo "<script>alert('Un code de vérification a été envoyé à votre email.'); window.location='verifier_code.php';</script>";
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'email: {$mail->ErrorInfo}";
        }
    } else {
        echo "<script>alert('L\'email n\'est pas enregistré.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Réinitialiser votre mot de passe</h2>
        <form method="POST">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Entrez votre email" required>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer le code</button>
        </form>
    </div>
</body>
</html>
