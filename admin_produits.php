<?php
session_start();
require_once('conn.php');
ob_start(); // Pour éviter les erreurs de header

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
  $nom = mysqli_real_escape_string($conn, $_POST['nom']);
  $prix = (float)$_POST['prix'];
  $quantite_stock = (int)$_POST['quantite_stock'];
  $description = mysqli_real_escape_string($conn, $_POST['description']);
  $categorie = mysqli_real_escape_string($conn, $_POST['categorie']);
  $image_type = $_POST['image_type'];
  $image_name = '';
  $uploadOk = 1; // On part du principe que l'insertion est possible

  if ($image_type === 'upload') {
      if (isset($_FILES["image_upload"]["tmp_name"]) && !empty($_FILES["image_upload"]["tmp_name"])) {
          $target_dir = "photo/";
          if (!is_dir($target_dir)) {
              mkdir($target_dir, 0755, true);
          }

          $original_name = basename($_FILES["image_upload"]["name"]);
          $timestamp = time();
          $safe_name = preg_replace("/[^a-zA-Z0-9.\-_]/", "_", $original_name);
          $image_name = $timestamp . "_" . $safe_name;
          $target_file = $target_dir . $image_name;
          $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
          $valid_extensions = ["jpg", "jpeg", "png", "gif"];

          if (!getimagesize($_FILES["image_upload"]["tmp_name"])) {
              $error_message = "❌ Le fichier n'est pas une image valide.";
              $uploadOk = 0;
          }

          if ($_FILES["image_upload"]["size"] > 500000) {
              $error_message = "❌ L'image est trop volumineuse (max 500KB).";
              $uploadOk = 0;
          }

          if (!in_array($imageFileType, $valid_extensions)) {
              $error_message = "❌ Format d'image non autorisé.";
              $uploadOk = 0;
          }

          if ($uploadOk) {
              if (!move_uploaded_file($_FILES["image_upload"]["tmp_name"], $target_file)) {
                  $error_message = "❌ Erreur lors de l'envoi de l'image.";
                  $uploadOk = 0;
              }
          }
      } else {
          $error_message = "❌ Veuillez sélectionner une image.";
          $uploadOk = 0;
      }
  } else { // Utilisation URL externe
      $image_name = mysqli_real_escape_string($conn, $_POST['image_url']);
      if (!filter_var($image_name, FILTER_VALIDATE_URL)) {
          $error_message = "❌ URL invalide pour l'image.";
          $uploadOk = 0;
      }
  }

  // ➡️ Ici insertion si aucune erreur
  if ($uploadOk && !empty($image_name)) {
      if ($image_type === 'upload') {
          $image_path = $image_name; // photo/xxxx.jpg
          $is_external = 0;
      } else {
          $image_path = $image_name; // https://...
          $is_external = 1;
      }

      $query = "INSERT INTO produits (nom, description, prix, quantite_stock, image, categorie, is_external_image)
                VALUES ('$nom', '$description', $prix, $quantite_stock, '$image_path', '$categorie', $is_external)";
      
      if (mysqli_query($conn, $query)) {
          $success_message = "✅ Produit ajouté avec succès !";
          header("Location: admin_produits.php");
          exit;
      } else {
          $error_message = "❌ Erreur SQL : " . mysqli_error($conn);
      }
  }
}

// Avant d'afficher les produits, vérifier si la colonne is_external_image existe
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM produits LIKE 'is_external_image'");
if (mysqli_num_rows($check_column) == 0) {
    // La colonne n'existe pas, on la crée
    mysqli_query($conn, "ALTER TABLE produits ADD COLUMN is_external_image TINYINT(1) DEFAULT 0");
}

// Le reste du code HTML suit ici
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Produits</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
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

        h2 {
            text-align: center;
            color: #333;
            margin-top: 40px;
            font-size: 24px;
            position: relative;
            padding-bottom: 10px;
        }

        h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background-color: #3498db;
            border-radius: 2px;
        }
        
        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
        }
        
        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        form:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        
        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border 0.3s ease;
        }

        input:focus, textarea:focus, select:focus {
            border: 1px solid #3498db;
            outline: none;
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        input[type="submit"] {
            background-color: #555;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        input[type="submit"]:hover {
            background-color: green;
        }

        /* Partie modernisée pour l'affichage des produits */
        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .product-card {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }

        .product-details {
            padding: 15px;
        }

        .product-title {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 5px;
            color: #333;
        }

        .product-category {
            display: inline-block;
            background-color: #e0f2f1;
            color: #009688;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .product-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            max-height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .product-price {
            font-weight: 700;
            color: #e74c3c;
        }

        .product-stock {
            background-color: #f8f9fa;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            color: #555;
        }

        .product-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .view-toggle {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .view-btn {
            background-color: #f8f9fa;
            border: none;
            padding: 8px 15px;
            margin: 0 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .view-btn.active {
            background-color: #3498db;
            color: white;
        }

        .view-btn:hover:not(.active) {
            background-color: #e9ecef;
        }

        /* Styles pour le tableau */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #555;
            color: #fff;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        tr:hover {
            background-color: #ddd;
        }
        
        table img {
            max-width: 80px;
            max-height: 80px;
            border-radius: 5px;
        }
        
        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin: 2px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-weight: 500;
            font-size: 14px;
            text-align: center;
        }
        
        .btn-edit {
            background-color: #3498db;
        }
        
        .btn-edit:hover {
            background-color: #2980b9;
        }
        
        .btn-delete {
            background-color: #e74c3c;
        }
        
        .btn-delete:hover {
            background-color: #c0392b;
        }
        
        .table-actions {
            white-space: nowrap;
        }
        
        .message {
            padding: 15px;
            margin: 20px auto;
            border-radius: 8px;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .success { 
            background-color: #d4edda; 
            color: #155724; 
            border-left: 4px solid #28a745;
        }
        
        .error { 
            background-color: #f8d7da; 
            color: #721c24; 
            border-left: 4px solid #dc3545;
        }
        
        .panel .product {
            color: #60dea1;
            padding: 20px;
            text-align: center;
        }
        
        .panel {
            color: black;
        }

        /* Sélecteur d'image */
        .image-type-selector {
            margin-bottom: 15px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .products-container {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 8px;
            }
            
            .container {
                padding: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .products-container {
                grid-template-columns: 1fr;
            }
            
            form {
                padding: 15px;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }
        
        /* Affichage par défaut : grille visible, tableau caché */
        #table-view {
            display: none;
        }
    </style>
</head>
<body>

<?php include 'admin_header.php'; ?>

<div class="container">
    <h1>Ajouter un Produit</h1>

    <?php
    // Affichage des messages d'erreur ou de succès
    if (isset($error_message)) {
        echo "<div class='message error'>" . $error_message . "</div>";
    }
    if (isset($success_message)) {
        echo "<div class='message success'>" . $success_message . "</div>";
    }
    ?>

    <form action="" method="post" enctype="multipart/form-data">
        <label for="nom">Nom du Produit:</label>
        <input type="text" name="nom" required>

        <label for="prix">Prix (MAD):</label>
        <input type="number" step="0.01" name="prix" required>

        <label for="quantite_stock">Quantité en stock:</label>
        <input type="number" name="quantite_stock" required>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea>

        <label for="categorie">Catégorie:</label>
        <input type="text" name="categorie" required>

        <div class="image-type-selector">
            <label>Type d'image:</label>
            <select name="image_type" id="image-type" onchange="toggleImageInput()">
                <option value="upload">Télécharger une image</option>
                <option value="url">Utiliser une URL externe</option>
            </select>
        </div>

        <div id="upload-section">
            <label for="image_upload">Image du Produit:</label>
            <input type="file" name="image_upload" id="image_upload" accept="image/*">
        </div>

        <div id="url-section" style="display: none;">
            <label for="image_url">URL de l'image:</label>
            <input type="url" name="image_url" id="image_url" placeholder="https://exemple.com/image.jpg">
        </div>

        <input type="submit" value="Ajouter le Produit" name="add_product">
    </form>

    <h2>Produits existants</h2>
    
    <div class="view-toggle">
        <button class="view-btn active" onclick="toggleView('grid')">Vue Grille</button>
        <button class="view-btn" onclick="toggleView('table')">Vue Tableau</button>
    </div>

    <?php
    $res = mysqli_query($conn, "SELECT * FROM produits ORDER BY id DESC");
    if (mysqli_num_rows($res) > 0) {
        // Vue en grille (moderne)
        echo '<div id="grid-view" class="products-container">';
        while ($row = mysqli_fetch_assoc($res)) {
            echo '<div class="product-card">';
            
            // Déterminer si l'image est externe ou locale
            $is_external = isset($row['is_external_image']) && $row['is_external_image'] == 1;
            $image_path = $is_external ? $row['image'] : 'photo/' . $row['image'];
            
            // Si l'URL commence par 'http:' ou aucun protocole, c'est une URL externe
            if (strpos($row['image'], 'http') === 0 || strpos($row['image'], '//') === 0) {
                $image_path = $row['image'];
            }
            
            echo '<img src="' . $image_path . '" class="product-image" alt="' . $row['nom'] . '">';
            echo '<div class="product-details">';
            echo '<h3 class="product-title">' . $row['nom'] . '</h3>';
            echo '<span class="product-category">' . $row['categorie'] . '</span>';
            echo '<p class="product-description">' . $row['description'] . '</p>';
            echo '<div class="product-meta">';
            echo '<span class="product-price">' . $row['prix'] . ' MAD</span>';
            echo '<span class="product-stock">Stock: ' . $row['quantite_stock'] . '</span>';
            echo '</div>';
            echo '<div class="product-actions">';
            echo '<a href="edit_product.php?id=' . $row['id'] . '" class="btn btn-edit">Modifier</a>';
            echo '<a href="delete_product.php?id=' . $row['id'] . '" class="btn btn-delete" onclick="return confirm(\'Supprimer ce produit ?\')">Supprimer</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        
        // Vue en tableau (traditionnelle)
        mysqli_data_seek($res, 0);
        echo '<div id="table-view">';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Nom</th>';
        echo '<th>Description</th>';
        echo '<th>Prix</th>';
        echo '<th>Quantité</th>';
        echo '<th>Image</th>';
        echo '<th>Catégorie</th>';
        echo '<th>Actions</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        while ($row = mysqli_fetch_assoc($res)) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['nom'] . '</td>';
            echo '<td>' . substr($row['description'], 0, 100) . (strlen($row['description']) > 100 ? '...' : '') . '</td>';
            echo '<td>' . $row['prix'] . ' MAD</td>';
            echo '<td>' . $row['quantite_stock'] . '</td>';
            
            // Déterminer si l'image est externe ou locale
            $is_external = isset($row['is_external_image']) && $row['is_external_image'] == 1;
            $image_path = $is_external ? $row['image'] : 'photo/' . $row['image'];
            
            // Si l'URL commence par 'http:' ou aucun protocole, c'est une URL externe
            if (strpos($row['image'], 'http') === 0 || strpos($row['image'], '//') === 0) {
                $image_path = $row['image'];
            }
            
            echo '<td><img src="' . $image_path . '" alt="' . $row['nom'] . '"></td>';
            echo '<td>' . $row['categorie'] . '</td>';
            echo '<td class="table-actions">';
            echo '<a href="edit_product.php?id=' . $row['id'] . '" class="btn btn-edit">Modifier</a>';
            echo '<a href="delete_product.php?id=' . $row['id'] . '" class="btn btn-delete" onclick="return confirm(\'Supprimer ce produit ?\')">Supprimer</a>';
            echo '</td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<p style="text-align: center; margin-top: 30px;">Aucun produit trouvé.</p>';
    }
    ?>
</div>

<script>
function toggleView(view) {
    const gridView = document.getElementById('grid-view');
    const tableView = document.getElementById('table-view');
    const buttons = document.querySelectorAll('.view-btn');
    
    buttons.forEach(button => {
        button.classList.remove('active');
    });
    
    if (view === 'grid') {
        gridView.style.display = 'grid';
        tableView.style.display = 'none';
        buttons[0].classList.add('active');
    } else {
        gridView.style.display = 'none';
        tableView.style.display = 'block';
        buttons[1].classList.add('active');
    }
}

function toggleImageInput() {
    const imageType = document.getElementById('image-type').value;
    const uploadSection = document.getElementById('upload-section');
    const urlSection = document.getElementById('url-section');
    
    if (imageType === 'upload') {
        uploadSection.style.display = 'block';
        urlSection.style.display = 'none';
        document.getElementById('image_upload').setAttribute('required', 'required');
        document.getElementById('image_url').removeAttribute('required');
    } else {
        uploadSection.style.display = 'none';
        urlSection.style.display = 'block';
        document.getElementById('image_upload').removeAttribute('required');
        document.getElementById('image_url').setAttribute('required', 'required');
    }
}

// Appel initial pour configurer correctement l'affichage du formulaire
document.addEventListener('DOMContentLoaded', function() {
    toggleImageInput();
});
</script>

</body>
</html>