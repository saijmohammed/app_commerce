<?php
session_start();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];

    if (!isset($_SESSION['reset_code']) || time() > $_SESSION['code_expiry']) {
        $message = "Code expiré ou invalide.";
    } elseif ($code == $_SESSION['reset_code']) {
        header("Location: nouveau_motdepasse.php");
        exit;
    } else {
        $message = "Code incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérifier le code</title>
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
            background: linear-gradient(to right, #00f260, #0575e6);
            -webkit-background-clip: text;
            color: transparent;
            font-size: 1.7em;
            margin-bottom: 1.5rem;
        }
        input[type="text"] {
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
            background-color: rgba(255, 0, 0, 0.3);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
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
            cursor: pointer;
        }
        .back-btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <form method="post" class="card">
        <h3>📩 Vérification du code</h3>
        <?php if (!empty($message)): ?>
            <div class="alert"><?= $message ?></div>
        <?php endif; ?>
        <input type="text" name="code" placeholder="Entrez le code reçu" required>
        <button type="submit">Vérifier</button>
        
        <!-- Bouton retour vers la page login.php -->
        <a href="login.php">
            <button type="button" class="back-btn">Retour</button>
        </a>
    </form>
</body>
</html>
