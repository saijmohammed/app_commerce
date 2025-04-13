<?php
session_start();
require 'conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if (!isset($_SESSION['reset_code']) || !isset($_SESSION['code_expiry'])) {
        echo "<script>alert('Aucun code actif.'); window.location='reset_password.php';</script>";
        exit;
    }

    if (time() > $_SESSION['code_expiry']) {
        echo "<script>alert('Le code a expiré.'); window.location='reset_password.php';</script>";
        exit;
    }

    if ($code != $_SESSION['reset_code']) {
        echo "<script>alert('Code incorrect');</script>";
    } elseif ($new_pass != $confirm_pass) {
        echo "<script>alert('Les mots de passe ne correspondent pas');</script>";
    } else {
        $email = $_SESSION['reset_email'];
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET motDePasse=? WHERE email=?");
        $stmt->bind_param("ss", $hashed_pass, $email);
        $stmt->execute();

        // Nettoyer la session
        unset($_SESSION['reset_code']);
        unset($_SESSION['reset_email']);
        unset($_SESSION['code_expiry']);

        echo "<script>alert('Mot de passe réinitialisé avec succès'); window.location='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vérification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white d-flex align-items-center justify-content-center min-vh-100">
<div class="container">
    <form method="post" class="card p-4 bg-secondary">
        <h3 class="text-center">Vérification</h3>
        <input type="text" name="code" class="form-control my-2" placeholder="Code reçu par mail" required>
        <input type="password" name="new_password" class="form-control my-2" placeholder="Nouveau mot de passe" required>
        <input type="password" name="confirm_password" class="form-control my-2" placeholder="Confirmer mot de passe" required>
        <button type="submit" class="btn btn-light">Réinitialiser</button>
    </form>
</div>
</body>
</html>
