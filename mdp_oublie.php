<?php
session_start();
include("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['code'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérifier si les sessions existent et si le code n'est pas expiré
    if (isset($_SESSION['verification_code']) && isset($_SESSION['reset_email']) && isset($_SESSION['code_expiry'])) {

        // Vérifier si le code est toujours valide
        if (time() > $_SESSION['code_expiry']) {
            echo "<script>alert('Le code de vérification a expiré. Veuillez recommencer.');</script>";
        } else if ($code == $_SESSION['verification_code']) {
            // Vérifier si les mots de passe correspondent
            if ($new_password !== $confirm_password) {
                echo "<script>alert('Les mots de passe ne correspondent pas.');</script>";
            } else {
                // Récupérer l'email de la session
                $email = $_SESSION['reset_email'];
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Mettre à jour le mot de passe dans la base de données
                $stmt = $conn->prepare("UPDATE users SET motDePasse=? WHERE email=?");
                $stmt->bind_param("ss", $hashed_password, $email);
                $result = $stmt->execute();

                if ($result) {
                    // Supprimer les sessions après la réinitialisation
                    unset($_SESSION['verification_code']);
                    unset($_SESSION['reset_email']);
                    unset($_SESSION['code_expiry']);

                    echo "<script>alert('Mot de passe mis à jour avec succès !'); window.location='login.php';</script>";
                } else {
                    echo "<script>alert('Erreur lors de la mise à jour du mot de passe.');</script>";
                }
                $stmt->close();
            }
        } else {
            echo "<script>alert('Code incorrect, veuillez réessayer.');</script>";
        }
    } else {
        echo "<script>alert('Session expirée, veuillez recommencer.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser mot de passe - Entrez le code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Entrez le code de vérification et votre nouveau mot de passe</h2>
        <form method="POST">
            <div class="mb-3">
                <input type="text" name="code" class="form-control" placeholder="Code de vérification" required>
            </div>
            <div class="mb-3">
                <input type="password" name="new_password" class="form-control" placeholder="Nouveau mot de passe" required minlength="8">
            </div>
            <div class="mb-3">
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirmer le mot de passe" required>
            </div>
            <button type="submit" class="btn btn-primary">Réinitialiser le mot de passe</button>
        </form>
    </div>
</body>
</html>
