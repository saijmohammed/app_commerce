<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (isset($_SESSION['admin_name']) && isset($_SESSION['admin_email']) && isset($_SESSION['admin_phone'])) {
    $admin_name = htmlspecialchars($_SESSION['admin_name']);
    $admin_email = htmlspecialchars($_SESSION['admin_email']);
    $admin_phone = htmlspecialchars($_SESSION['admin_phone']);
    $compte_connecte = true;
} else {
    // Si la session n'est pas active, redirige vers la page de connexion
    $compte_connecte = false;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0; 
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #05365f, #ff4d88);  /* Fond dégradé similaire au login */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.1); /* Carte translucide */
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(15px); /* Effet blur */
            border-radius: 20px;
            padding: 40px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 25px;
            color: #fff;
        }

        .info {
            font-size: 16px;
            text-align: left;
            margin: 15px 0;
            color: #fff;
        }

        .info strong {
            color: #aaa;
            width: 100px;
            display: inline-block;
        }

        .buttons {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .buttons a {
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 30px;
            background: linear-gradient(135deg, #ff9f00, #ff4d88); /* Boutons arrondis avec gradient */
            color: white;
            font-weight: 600;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .buttons a:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .logout {
            background: linear-gradient(135deg, #ff416c, #ff4b2b); /* Bouton déconnexion */
        }

        @media (max-width: 500px) {
            .container {
                padding: 25px;
            }

            h2 {
                font-size: 22px;
            }

            .buttons a {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Mon Compte Administrateur</h2>
    
    <?php if ($compte_connecte): ?>
        <div class="info">
            <strong>Mon compte admin connecté</strong> <!-- Affiche que le compte est connecté -->
        </div>
        <div class="info">
            <strong>Email :</strong> <?php echo $admin_email; ?>
        </div>

        <div class="info">
            <strong>Nom :</strong> <?php echo $admin_name; ?>
        </div>

        <div class="info">
            <strong>Téléphone :</strong> <?php echo $admin_phone; ?>
        </div>

        <div class="buttons">
            <a href="admin_page.php">← Retour</a>
            <a href="logout.php" class="logout">Déconnexion</a>
        </div>
    <?php else: ?>
        <div class="info">
            <strong>Veuillez vous connecter pour accéder à votre compte.</strong> <!-- Message pour les utilisateurs non connectés -->
        </div>
    <?php endif; ?>
</div>

</body>
</html>
