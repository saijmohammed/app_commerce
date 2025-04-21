<?php
require_once('conn.php');

$message = ''; // pour afficher le message plus tard

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);

    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $categorie = mysqli_real_escape_string($conn, $_POST['categorie']);

    $update_query = "UPDATE produits SET
        nom = '$nom',
        prix = '$prix',
        quantite_stock = '$quantite',
        categorie = '$categorie',
        description = '$details'
        WHERE id = '$product_id'";

    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        $message = '<p class="success">✅ Produit mis à jour avec succès !</p>';
    } else {
        $message = '<p class="error">❌ Erreur lors de la mise à jour : ' . mysqli_error($conn) . '</p>';
    }
}

$product_id = '';
$row = null;

if (isset($_GET['id']) || isset($_POST['product_id'])) {
    $product_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : mysqli_real_escape_string($conn, $_POST['product_id']);
    $query = "SELECT * FROM produits WHERE id = '$product_id'";
    $result = mysqli_query($conn, $query);
    if ($result && $result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        $message = '<p class="error">❌ Produit non trouvé.</p>';
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Produit</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(to right, #bdc3c7, #2c3e50);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 40px auto;
            background-color: #ffffffdd;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }

        h1 {
            text-align: center;
            color:rgb(79, 129, 179);
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
            color: #34495e;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        input[type="submit"] {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            margin-top: 20px;
            cursor: pointer;
            transition: 0.3s;
            border-radius: 8px;
            padding: 10px 20px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #27ae60;
        }

        .success {
            color: #27ae60;
            font-weight: bold;
            text-align: center;
        }

        .error {
            color: #e74c3c;
            font-weight: bold;
            text-align: center;
        }

        .btn-retour {
            display: inline-block;
            margin-top: 20px;
            background-color: #34495e;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
            width: 92%;
            text-align: center;
            text-decoration: none;
        }

        .btn-retour:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Modifier le Produit</h1>

    <?php if (!empty($message)) echo $message; ?>

    <?php if ($row): ?>
        <form action="" method="post">
            <input type="hidden" name="product_id" value="<?= $row['id']; ?>">

            <label for="nom">Nom du Produit :</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($row['nom']); ?>" required>

            <label for="prix">Prix :</label>
            <input type="number" min="0" name="prix" value="<?= $row['prix']; ?>" required>

            <label for="quantite">Quantité :</label>
            <input type="number" min="0" name="quantite" value="<?= $row['quantite_stock']; ?>" required>

            <label for="details">Description :</label>
            <textarea name="details" required><?= htmlspecialchars($row['description']); ?></textarea>

            <label for="categorie">Catégorie :</label>
            <input type="text" name="categorie" value="<?= htmlspecialchars($row['categorie']); ?>" required>

            <input type="submit" name="update_product" value="💾 Enregistrer les modifications">
            <a href="admin_produits.php" class="btn-retour">← Retour à la liste des produits</a>

        </form>
    <?php endif; ?>
</div>
</body>
</html>
