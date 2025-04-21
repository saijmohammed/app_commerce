<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Fournisseur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
        }

        .dashboard {
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            margin: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .title {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 15px;
        }

        .box {
            background-color: #3498db;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 10px;
            flex: 1 1 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-decoration: none;
            color: white;
        }

        .box:hover {
            transform: scale(1.05);
            background-color: #2980b9;
        }

        .box h3 {
            margin-bottom: 10px;
            font-size: 28px;
        }

        .box p {
            margin: 0;
            font-size: 16px;
        }
        
        .text-primary {
            color: #3498db;
        }
    </style>
</head>

<body>
    <?php 
    include 'fournisseur_header.php'; 
    include('conn.php');
    
    // Récupérer l'ID du fournisseur connecté
    $fournisseur_id = $_SESSION['fournisseur_id'];
    ?>

    <section class="dashboard">
        <div class="panel3">
            <h1 class="title">TABLEAU <span class="text-primary">DE BORD</span></h1>
        </div>

        <div class="box-container">
            <!-- Mes Produits -->
            <a href="fournisseur_produits.php" class="box">
                <?php
                $select_products = mysqli_query($conn, "SELECT * FROM `produits` WHERE fournisseur_id = '$fournisseur_id'") or die('Erreur de requête');
                $number_of_products = mysqli_num_rows($select_products);
                ?>
                <h3><?php echo $number_of_products; ?></h3>
                <p>Mes Produits</p>
            </a>

            <!-- Commandes en attente -->
            <a href="fournisseur_commandes.php" class="box">
                <?php
                $select_orders = mysqli_query($conn, "SELECT DISTINCT c.id 
                                                    FROM commandes c
                                                    JOIN commande_produits cp ON c.id = cp.commande_id
                                                    JOIN produits p ON cp.produit_id = p.id
                                                    WHERE p.fournisseur_id = '$fournisseur_id' 
                                                    AND c.statut = 'en attente'") or die('Erreur de requête');
                $number_of_orders = mysqli_num_rows($select_orders);
                ?>
                <h3><?php echo $number_of_orders; ?></h3>
                <p>Commandes en attente</p>
            </a>

            <!-- Produits en stock faible -->
            <a href="fournisseur_produits.php?filter=low_stock" class="box">
                <?php
                $select_low_stock = mysqli_query($conn, "SELECT * FROM `produits` 
                                                        WHERE fournisseur_id = '$fournisseur_id' 
                                                        AND quantite_stock < 5") or die('Erreur de requête');
                $number_of_low_stock = mysqli_num_rows($select_low_stock);
                ?>
                <h3><?php echo $number_of_low_stock; ?></h3>
                <p>Produits en stock faible</p>
            </a>

            <!-- Ventes du mois -->
            <a href="fournisseur_ventes.php" class="box">
                <?php
                $select_sales = mysqli_query($conn, "SELECT SUM(cp.quantite) as total 
                                                    FROM commande_produits cp
                                                    JOIN produits p ON cp.produit_id = p.id
                                                    JOIN commandes c ON cp.commande_id = c.id
                                                    WHERE p.fournisseur_id = '$fournisseur_id'
                                                    AND c.statut = 'livré'
                                                    AND MONTH(c.date_commande) = MONTH(CURRENT_DATE())") or die('Erreur de requête');
                $sales_data = mysqli_fetch_assoc($select_sales);
                $total_sales = $sales_data['total'] ?? 0;
                ?>
                <h3><?php echo $total_sales; ?></h3>
                <p>Ventes ce mois</p>
            </a>

            <!-- Revenus totaux -->
            <a href="fournisseur_finances.php" class="box">
                <?php
                $select_revenue = mysqli_query($conn, "SELECT SUM(cp.quantite * p.prix) as revenue 
                                                      FROM commande_produits cp
                                                      JOIN produits p ON cp.produit_id = p.id
                                                      JOIN commandes c ON cp.commande_id = c.id
                                                      WHERE p.fournisseur_id = '$fournisseur_id'
                                                      AND c.statut = 'livré'") or die('Erreur de requête');
                $revenue_data = mysqli_fetch_assoc($select_revenue);
                $total_revenue = $revenue_data['revenue'] ?? 0;
                ?>
                <h3><?php echo number_format($total_revenue, 2); ?> MAD</h3>
                <p>Revenus totaux</p>
            </a>

            <!-- Messages non lus -->
            <a href="fournisseur_messagerie.php" class="box">
                <?php
                $select_messages = mysqli_query($conn, "SELECT COUNT(*) as unread 
                                                       FROM messages 
                                                       WHERE destinataire_id = '$fournisseur_id'
                                                       AND lu = 0") or die('Erreur de requête');
                $messages_data = mysqli_fetch_assoc($select_messages);
                $unread_messages = $messages_data['unread'] ?? 0;
                ?>
                <h3><?php echo $unread_messages; ?></h3>
                <p>Messages non lus</p>
            </a>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation des boîtes au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const boxes = document.querySelectorAll('.box');
            boxes.forEach((box, index) => {
                box.style.animation = `fadeIn 0.5s ease-out ${index * 0.1}s forwards`;
                box.style.opacity = '0';
            });
        });
    </script>
</body>
</html>

