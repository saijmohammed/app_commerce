<?php
session_start();
include("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['code'];

    // Vérifier si le code existe dans la session
    if (isset($_SESSION['verification_code']) && $code == $_SESSION['verification_code']) {
        $_SESSION['is_verified'] = true;  // Flag pour indiquer que l'utilisateur peut réinitialiser le mot de passe
        echo "<script>window.location='reset_password.php';</script>";
    } else {
        echo "<script>alert('Code incorrect ou expiré.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser mot de passe - GamingPlanet</title>
</head>
<body>
    <form method="POST" action="code_oublie.php">
        <label for="code">Code de vérification :</label>
        <input type="text" id="code" name="code" required>
        <button type="submit">Vérifier le code</button>
    </form>
</body>
</html>
