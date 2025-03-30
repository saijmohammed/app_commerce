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
        }
        // Vérifier si le code est correct
        else if ($code == $_SESSION['verification_code']) {
            // Vérifier si les mots de passe correspondent
            if ($new_password !== $confirm_password) {
                echo "<script>alert('Les mots de passe ne correspondent pas.');</script>";
            } else {
                $email = $_SESSION['reset_email'];
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Utilisation de requête préparée pour plus de sécurité
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
    <title>Réinitialiser mot de passe - GamingPlanet</title>
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
            margin-bottom: 15px;
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
        
        /* Style du message d'erreur pour la confirmation du mot de passe */
        .password-error {
            color: #ff0066;
            font-size: 0.85rem;
            margin-top: -10px;
            margin-bottom: 10px;
            display: none;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1 class="gradient-title">RÉINITIALISATION</h1>
        
        <form method="POST" id="resetForm">
            <div class="mb-3">
                <input type="text" name="code" class="form-control" placeholder="Code de vérification" required>
            </div>
            
            <div class="mb-3">
                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Nouveau mot de passe" required minlength="8">
            </div>
            
            <div class="mb-3">
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirmer le mot de passe" required>
                <div id="password-error" class="password-error">Les mots de passe ne correspondent pas</div>
            </div>
            
            <button type="submit" class="btn btn-login w-100">Changer le mot de passe</button>
            
            <div class="mt-3">
                <a href="mdp_oublie.php" class="link">Renvoyer un code</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        // Vérification en temps réel de la correspondance des mots de passe
        document.getElementById('confirm_password').addEventListener('keyup', function() {
            var password = document.getElementById('new_password').value;
            var confirmPassword = this.value;
            var errorElement = document.getElementById('password-error');
            
            if (password !== confirmPassword) {
                errorElement.style.display = 'block';
            } else {
                errorElement.style.display = 'none';
            }
        });
        
        // Validation du formulaire avant soumission
        document.getElementById('resetForm').addEventListener('submit', function(event) {
            var password = document.getElementById('new_password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                event.preventDefault();
                alert('Les mots de passe ne correspondent pas.');
            }
        });
    </script>
</body>
</html>