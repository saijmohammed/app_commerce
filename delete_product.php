<?php
require_once('conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $product_id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "DELETE FROM produits WHERE id = '$product_id'";
    $result = mysqli_query($conn, $query);

    echo '<!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Produit supprimé</title>
        <style>
            body {
                font-family: "Segoe UI", sans-serif;
                background: linear-gradient(to right, #6a11cb, #2575fc);
                margin: 0;
                padding: 0;
                color: #fff;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                text-align: center;
            }

            .container {
                background-color: rgba(255, 255, 255, 0.2);
                padding: 30px 40px;
                border-radius: 15px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                backdrop-filter: blur(10px);
                max-width: 400px;
                width: 100%;
            }

            p {
                font-size: 18px;
                font-weight: bold;
                margin-top: 20px;
            }

            .success {
                color: #2ecc71;
            }

            .error {
                color: #e74c3c;
            }

            .btn-back {
                display: inline-block;
                margin-top: 30px;
                padding: 12px 30px;
                background-color: #34495e;
                color: #fff;
                font-size: 18px;
                text-decoration: none;
                border-radius: 10px;
                transition: background-color 0.3s ease;
            }

            .btn-back:hover {
                background-color: #27ae60;
            }
        </style>
    </head>
    <body>
        <div class="container">';
    
    if ($result) {
        echo '<p class="success">Produit supprimé avec succès.</p>';
    } else {
        echo '<p class="error">Erreur lors de la suppression du produit : ' . mysqli_error($conn) . '</p>';
    }
    
    echo '<a href="admin_produits.php" class="btn-back">Retour à la liste des produits</a>
        </div>
    </body>
    </html>';
} else {
    echo '<p>Mauvaise requête.</p>';
}
?>
