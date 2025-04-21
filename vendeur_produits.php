<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['email']) || $_SESSION['type'] != 'vendeur') {
    header('Location: login.php');
    exit();
}

$vendeur_name = $_SESSION['email'];

// Récupérer tous les produits
$produits = mysqli_query($conn, "SELECT * FROM produits ORDER BY nom ASC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Voir Produits</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        nav a, .profile {
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
        .logout-btn {
            background: #e74c3c;
            padding: 8px 14px;
            border-radius: 20px;
            text-decoration: none;
            color: white;
            font-weight: 600;
            transition: 0.3s;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        .container {
            width: 95%;
            max-width: 1200px;
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            margin-top: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .product-card {
            background: #f9f9f9;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
            transition: 0.3s;
            text-align: center;
            padding: 20px;
        }
        .product-card:hover {
            transform: translateY(-8px);
        }
        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            margin-bottom: 15px;
            border-radius: 8px;
        }
        .product-card h3 {
            color: #3498db;
            margin-bottom: 10px;
        }
        .product-card p {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 8px;
        }
        .out-of-stock {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>

<header>
    <div class="logo">VendeurSpace</div>
    <nav>
        <a href="vendeur_home.php">🏠 Accueil</a>
        <a href="vendeur_ajout.php">🛒 Ajouter Vente</a>
        <a href="vendeur_historique.php">📄 Historique Ventes</a>
        <a href="vendeur_produits.php">📦 Voir Produits</a>
        <div class="profile">
            👤 <?php echo htmlspecialchars($vendeur_name); ?>
            <a href="logout.php" class="logout-btn">Déconnexion</a>
        </div>
    </nav>
</header>

<div class="container">
    <h1>Produits Disponibles 📦</h1>

    <div class="products-grid">
        <?php if (mysqli_num_rows($produits) > 0): ?>
            <?php while($produit = mysqli_fetch_assoc($produits)): ?>
                <div class="product-card">
                    <?php
                        $image = htmlspecialchars($produit['image']);
                        $image_src = ($produit['is_external_image'] == 1) ? $image : 'photo/' . $image;
                    ?>
                    <img src="<?php echo $image_src; ?>" alt="Image produit">
                    <h3><?php echo htmlspecialchars($produit['nom']); ?></h3>
                    <p>Prix : <strong><?php echo (float)$produit['prix']; ?> DH</strong></p>
                    <p>Catégorie : <?php echo htmlspecialchars($produit['categorie']); ?></p>
                    <?php if ($produit['quantite_stock'] > 0): ?>
                        <p>Stock : <?php echo (int)$produit['quantite_stock']; ?> pièces</p>
                    <?php else: ?>
                        <p class="out-of-stock">Rupture de stock ❌</p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">Aucun produit disponible pour le moment.</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
