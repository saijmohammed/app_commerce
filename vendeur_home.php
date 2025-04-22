<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['type'] != 'vendeur') {
    header('Location: login.php');
    exit();
}
$vendeur_name = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil Vendeur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Police Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            min-height: 100vh;
            padding-top: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        header {
            width: 100%;
            height: 70px;
            background: linear-gradient(135deg, #3498db, #9b59b6);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            z-index: 100;
        }
        .logo {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .right-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        nav {
            display: flex;
            gap: 20px;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: 0.3s;
        }
        nav a:hover {
            color: #f1c40f;
        }
        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile span {
            font-weight: 500;
        }
        .logout {
            background: #e74c3c;
            padding: 8px 14px;
            border-radius: 20px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: 0.3s;
        }
        .logout:hover {
            background: #c0392b;
        }
        .content {
            margin-top: 30px;
            text-align: center;
        }
        .content h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .content p {
            color: #555;
            font-size: 1.2rem;
            max-width: 700px;
            margin: auto;
            line-height: 1.5;
        }
        .actions {
            margin-top: 40px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .card {
            background: white;
            width: 250px;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
            text-align: center;
            transition: 0.3s;
        }
        .card:hover {
            transform: translateY(-8px);
        }
        .card h2 {
            color: #3498db;
            margin-bottom: 10px;
        }
        .card p {
            color: #777;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }
        .card a {
            text-decoration: none;
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            transition: 0.3s;
        }
        .card a:hover {
            background: #2980b9;
        }
    </style>

</head>

<body>

<header>
    <div class="logo">VendeurSpace</div>

    <div class="right-section">
        <nav>
            <a href="vendeur_home.php">🏠 Accueil</a>
            <a href="vendeur_ajout.php">🛒 Ajouter Vente</a>
            <a href="vendeur_historique.php">📄 Historique Ventes</a>
        </nav>
        <div class="profile">
            <span>👤 <?php echo htmlspecialchars($vendeur_name); ?></span>
            <a href="logout.php" class="logout">Déconnexion</a>
        </div>
    </div>
</header>

<section class="content">
    <h1>Bienvenue dans votre Espace Vendeur 👋</h1>
    <p>Vous pouvez ajouter de nouvelles ventes, consulter vos historiques et voir vos performances en temps réel.</p>

    <div class="actions">
        <div class="card">
            <h2>Ajouter Vente</h2>
            <p>Enregistrez rapidement les ventes réalisées aujourd'hui !</p>
            <a href="vendeur_ajout.php">Ajouter</a>
        </div>

        <div class="card">
            <h2>Historique</h2>
            <p>Consultez toutes vos ventes passées avec détails.</p>
            <a href="vendeur_historique.php">Voir Historique</a>
        </div>

        <div class="card">
            <h2>Voir Produits</h2>
            <p>Consultez le stock disponible pour vos prochaines ventes.</p>
            <a href="vendeur_produits.php">Voir Produits</a>
        </div>
    </div>
</section>

</body>
</html>
