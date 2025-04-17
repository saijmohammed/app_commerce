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
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser mot de passe</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #05365f, #ff4d88);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .card {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 30px;
            max-width: 340px;
            width: 100%;
            color: white;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        h3 {
            text-align: center;
            background-image: linear-gradient(to right, #ff9f00, #ff4d88);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-size: 28px;
            margin-bottom: 1.5rem;
        }

        input[type="password"] {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 10px;
            padding: 12px;
            width: 100%;
            color: white;
            margin-bottom: 1rem;
            font-size: 14px;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 25px;
            background: linear-gradient(to right, #ff9f00, #ff4d88);
            color: white;
            font-size: 15px;
            font-weight: 500;
            transition: 0.3s;
            margin-bottom: 10px;
        }

        button:hover {
            opacity: 0.9;
            transform: scale(1.05);
            cursor: pointer;
        }

        .alert {
            background-color: rgba(255, 255, 255, 0.15);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
            color: #ffcccc;
            font-size: 14px;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            text-decoration: none;
            color: #ffcc00;
            transition: 0.3s;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form method="post" class="card">
        <h3>🔐 Réinitialiser</h3>

        <?php if (!empty($message)): ?>
            <div class="alert"><?= $message ?></div>
        <?php endif; ?>

        <input type="password" name="new_password" placeholder="Nouveau mot de passe" required>
        <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>

        <button type="submit">Réinitialiser</button>

        <a href="login.php">⬅ Retour à la connexion</a>
    </form>
</body>
</html>
