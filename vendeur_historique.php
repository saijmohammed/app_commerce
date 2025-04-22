<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['email']) || $_SESSION['type'] != 'vendeur') {
    header('Location: login.php');
    exit();
}

$vendeur_id = $_SESSION['id'];
$vendeur_name = $_SESSION['email'];

$search_client = '';
$search_date = '';

$sql = "SELECT ventes.*, produits.nom AS produit_nom 
        FROM ventes 
        JOIN produits ON ventes.produit_id = produits.id 
        WHERE ventes.vendeur_id = '$vendeur_id'";

// Recherche par client
if (isset($_GET['search_client']) && !empty(trim($_GET['search_client']))) {
    $search_client = mysqli_real_escape_string($conn, $_GET['search_client']);
    $sql .= " AND ventes.client LIKE '%$search_client%'";
}

// Recherche par date
if (isset($_GET['search_date']) && !empty($_GET['search_date'])) {
    $search_date = mysqli_real_escape_string($conn, $_GET['search_date']);
    $sql .= " AND DATE(ventes.date_vente) = '$search_date'";
}

$sql .= " ORDER BY ventes.date_vente DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Ventes</title>
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
            gap: 20px;
            align-items: center;
        }
        nav a, .email-text {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            transition: 0.3s;
        }
        nav a:hover {
            color: #f1c40f;
        }
        .email-text {
            margin-right: 10px;
            opacity: 0.9;
            font-size: 0.95rem;
        }
        .logout-btn {
            background: #e74c3c;
            padding: 8px 16px;
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
            width: 90%;
            max-width: 1000px;
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            margin-top: 20px;
            overflow-x: auto;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #3498db;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .no-sales {
            text-align: center;
            font-size: 1.2rem;
            color: #777;
            margin-top: 50px;
        }
        .search-bar {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .search-bar input {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }
        .search-bar button {
            background: #3498db;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
        }
        .search-bar button:hover {
            background: #2980b9;
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
        <span class="email-text">👤 <?php echo htmlspecialchars($vendeur_name); ?></span>
        <a href="logout.php" class="logout-btn">Déconnexion</a>
    </nav>
</header>

<div class="container">
    <h1>Historique des Ventes</h1>

    <form class="search-bar" method="get" action="">
        <input type="text" name="search_client" placeholder="🔎 Recherche par client" value="<?php echo htmlspecialchars($search_client); ?>">
        <input type="date" name="search_date" value="<?php echo htmlspecialchars($search_date); ?>">
        <button type="submit">Rechercher</button>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Client</th>
                <th>Date de Vente</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($vente = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($vente['produit_nom']); ?></td>
                <td><?php echo (int)$vente['quantite']; ?></td>
                <td><?php echo htmlspecialchars($vente['client']); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($vente['date_vente'])); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <p class="no-sales">Aucune vente trouvée avec votre recherche.</p>
    <?php endif; ?>
</div>

</body>
</html>
