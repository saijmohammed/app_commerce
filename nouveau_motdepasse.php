<?php
session_start();
require 'conn.php';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if (!isset($_SESSION['reset_email'])) {
        $message = "Accès non autorisé.";
    } elseif ($new_pass !== $confirm_pass) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        $email = $_SESSION['reset_email'];
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET motDePasse=? WHERE email=?");
        $stmt->bind_param("ss", $hashed_pass, $email);
        $stmt->execute();

        session_unset();
        session_destroy();

        $message = "✅ Mot de passe réinitialisé avec succès.";

        // Redirection vers la page login.php après une modification réussie
        header("Location: login.php");
        exit; // Assurez-vous que le script s'arrête ici après la redirection
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau mot de passe</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, rgb(6, 22, 85), rgb(90, 24, 156));
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            color: #fff;
        }
        h3 {
            text-align: center;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            -webkit-background-clip: text;
            color: transparent;
            font-size: 1.5em;
            margin-bottom: 1.5rem;
        }
        input[type="password"] {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            border-radius: 10px;
            padding: 12px;
            width: 100%;
            color: #fff;
            margin-bottom: 1rem;
        }
        input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 25px;
            background: linear-gradient(to right, #ff416c, #ff4b2b);
            color: white;
            font-size: 1rem;
            transition: transform 0.2s ease;
        }
        button:hover {
            transform: scale(1.05);
            cursor: pointer;
        }
        .alert {
            background-color: rgba(0, 128, 255, 0.2);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
            color: white;
        }
        .back-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 25px;
            background: linear-gradient(to right, #8e44ad, #9b59b6);
            color: white;
            font-size: 1rem;
            margin-top: 10px;
            text-align: center;
            cursor: pointer;
        }
        .back-btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <form method="post" class="card">
        <h3>🔑 Nouveau mot de passe</h3>
        <?php if (!empty($message)): ?>
            <div class="alert"><?= $message ?></div>
        <?php endif; ?>
        <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
        <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
        <button type="submit">Réinitialiser</button>

        <!-- Bouton retour vers la page verifier_code.php -->
        <a href="login.php">
            <button type="button" class="back-btn">Retour </button>
        </a>
    </form>
</body>
</html>
