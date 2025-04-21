<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <style>
        /* Tout ton style reste inchangé */
        body {
            font-family: 'Roboto', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background: linear-gradient(#f4f4f4, #e0d8c7);
        }

        h1 {
            text-align: center;
            color: #333;
            padding: 30px;
            height: 20px;
        }

        .panel .product {
            color: #60dea1;
            padding: 20px;
            text-align: center;
        }

        .panel {
            color: black;
        }

        form {
            max-width: 600px;
            margin: 2rem auto;
            background: rgba(255, 255, 255, 0.8);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4b5563;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
        }

        form input[type="submit"] {
            background-color: #1e3a8a;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }

        form input[type="submit"]:hover {
            background-color: #10b981;
        }

        p {
            text-align: center;
            font-weight: bold;
            margin: 1rem;
        }

        table {
            width: 90%;
            margin: 2rem auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #1e3a8a;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        tr:hover {
            background-color: #f3f4f6;
        }

        .product-image {
            max-width: 100px;
            border-radius: 8px;
        }

        .action-links a {
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            display: inline-block;
            text-align: center;
            font-size: 1rem;
        }

        .edit-link {
            background-color: #4CAF50;
            border: 2px solid #4CAF50;
        }

        .edit-link:hover {
            background-color: #45a049;
            border-color: #45a049;
        }

        .delete-link {
            background-color: #f44336;
            border: 2px solid #f44336;
        }

        .delete-link:hover {
            background-color: #e53935;
            border-color: #e53935;
        }
    </style>
</head>
<body>
<?php include 'fournisseur_header.php'; ?>

<div class="panel">
    <h1>Gestion <span class="product">des Produits</span></h1>
</div>

<form action="" method="post" enctype="multipart/form-data">
    <label for="product_name">Nom du Produit:</label>
    <input type="text" name="product_name" required>

    <label for="price">Prix:</label>
    <input type="number" min="0" name="price" required>

    <label for="quantitate">Quantité:</label>
    <input type="number" min="0" name="quantitate" required>

    <label for="description">Description:</label>
    <textarea name="description" required></textarea>

    <label for="product_image">Image du Produit:</label>
    <input type="file" name="product_image" accept="image/jpg, image/jpeg, image/png" required>

    <label for="category">Catégorie:</label>
    <input type="text" name="category" required>

    <input type="submit" value="Ajouter le Produit" name="add_product">
</form>

<?php
require_once('conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
        $price = $_POST['price'];
        $quantitate = $_POST['quantitate'];
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);

        $target_dir = "photo/";
        $original_name = basename($_FILES["product_image"]["name"]);
        $sanitized_name = preg_replace("/[^a-zA-Z0-9\.-]/", "", $original_name);
        $final_name = time() . "_" . $sanitized_name;
        $target_file = $target_dir . $final_name;

        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (file_exists($target_file)) {
            echo "<p>❌ Le fichier existe déjà.</p>";
            $uploadOk = 0;
        }

        if ($_FILES["product_image"]["size"] > 500000) {
            echo "<p>❌ Fichier trop volumineux.</p>";
            $uploadOk = 0;
        }

        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if ($check === false) {
            echo "<p>❌ Ce fichier n'est pas une image valide.</p>";
            $uploadOk = 0;
        }

        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowedTypes)) {
            echo "<p>❌ Format non supporté. (JPG, JPEG, PNG, GIF uniquement)</p>";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                $insert_query = "INSERT INTO produits (nom, description, prix, quantite_stock, categorie, image) VALUES ('$product_name', '$description', '$price', '$quantitate', '$category', '$final_name')";
                $insert_result = mysqli_query($conn, $insert_query);

                if ($insert_result) {
                    echo '<p>✅ Produit ajouté avec succès !</p>';
                } else {
                    echo '<p>❌ Erreur lors de l\'ajout du produit : ' . mysqli_error($conn) . '</p>';
                }
            } else {
                echo "<p>❌ Erreur lors du téléchargement de l'image.</p>";
            }
        } else {
            echo "<p>❌ Le fichier n'a pas été téléchargé.</p>";
        }
    }
}

// Affichage des produits
$query = "SELECT * FROM produits";
$result = mysqli_query($conn, $query);

if ($result) {
    echo '<table>';
    echo '<tr><th>ID</th><th>Nom du Produit</th><th>Prix</th><th>Quantité</th><th>Description</th><th>Image</th><th>Catégorie</th><th>Actions</th></tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['nom'] . '</td>';
        echo '<td>' . $row['prix'] . ' MAD</td>';
        echo '<td>' . $row['quantite_stock'] . '</td>';
        echo '<td>' . $row['description'] . '</td>';
        echo '<td><img src="photo/' . $row['image'] . '" class="product-image" alt="Product Image"></td>';
        echo '<td>' . $row['categorie'] . '</td>';
        echo '<td>
                <a class="edit-link" href="edit_product.php?id=' . $row['id'] . '">Modifier</a>
                <a class="delete-link" href="delete_product.php?id=' . $row['id'] . '" onclick="return confirm(\'Voulez-vous vraiment supprimer ce produit ?\')">Supprimer</a>
              </td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p>❌ Erreur lors de la récupération des produits : ' . mysqli_error($conn) . '</p>';
}
?>
</body>
</html>