<?php
session_start();
include 'conn.php';

// Vérification si TCPDF est bien présent
if (!file_exists(__DIR__ . '/tcpdf/tcpdf.php')) {
    die('<h2 style="color:red;text-align:center;margin-top:100px;">Erreur : TCPDF n\'est pas installé. Télécharge TCPDF sur <a href="https://tcpdf.org/" target="_blank">tcpdf.org</a> et place le dossier "tcpdf/" dans ton projet !</h2>');
}

require_once(__DIR__ . '/tcpdf/tcpdf.php');

if (!isset($_SESSION['email']) || $_SESSION['type'] != 'vendeur') {
    header('Location: login.php');
    exit();
}

$vendeur_name = $_SESSION['email'];
$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_vente'])) {
    $produit_id = mysqli_real_escape_string($conn, $_POST['produit_id']);
    $quantite = (int) $_POST['quantite'];
    $client = mysqli_real_escape_string($conn, $_POST['client']);

    $check_stock = mysqli_query($conn, "SELECT * FROM produits WHERE id = '$produit_id'");
    $produit = mysqli_fetch_assoc($check_stock);

    if ($produit && $produit['quantite_stock'] >= $quantite) {
        mysqli_query($conn, "INSERT INTO ventes (produit_id, vendeur_id, client, quantite, date_vente) 
        VALUES ('$produit_id', '{$_SESSION['id']}', '$client', '$quantite', NOW())");
        mysqli_query($conn, "UPDATE produits SET quantite_stock = quantite_stock - $quantite WHERE id = '$produit_id'");

        // Génération du PDF de facture
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('VendeurSpace');
        $pdf->SetTitle('Facture Client');
        $pdf->SetMargins(20, 20, 20);
        $pdf->AddPage();

        $html = '
        <h1 style="text-align:center;">Facture de Vente</h1><br><br>
        <h3>Informations Client :</h3>
        <p><strong>Client :</strong> ' . htmlspecialchars($client) . '</p>

        <h3>Détails :</h3>
        <p><strong>Produit :</strong> ' . htmlspecialchars($produit['nom']) . '</p>
        <p><strong>Prix Unitaire :</strong> ' . number_format($produit['prix'], 2) . ' DH</p>
        <p><strong>Quantité :</strong> ' . $quantite . '</p>
        <p><strong>Total :</strong> ' . number_format($produit['prix'] * $quantite, 2) . ' DH</p>

        <br><br>
        <p><strong>Date :</strong> ' . date('d/m/Y H:i') . '</p>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('facture_client.pdf', 'I');
        exit();

    } else {
        $_SESSION['message'] = "❌ Stock insuffisant pour cette vente.";
        header('Location: vendeur_ajout.php');
        exit();
    }
}

$produits = [];
$result = mysqli_query($conn, "SELECT * FROM produits ORDER BY nom ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $produits[] = $row;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Vente</title>
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
        }
        nav a, .email-text {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
        }
        .container {
            background: white;
            width: 90%;
            max-width: 800px;
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
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        select, input[type="number"], input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 100%;
            font-size: 1rem;
        }
        button {
            background: #3498db;
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
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
    <h1>Ajouter une Vente</h1>

    <form method="post" action="">
        <label for="produit_id">Produit :</label>
        <select name="produit_id" id="produit_id" required>
            <option value="">-- Sélectionner un produit --</option>
            <?php foreach ($produits as $produit): ?>
                <option value="<?= $produit['id']; ?>">
                    <?= htmlspecialchars($produit['nom']) . " (Stock: " . $produit['quantite_stock'] . ")" ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="quantite">Quantité :</label>
        <input type="number" name="quantite" id="quantite" min="1" required>

        <label for="client">Nom du Client :</label>
        <input type="text" name="client" id="client" required>

        <button type="submit" name="ajouter_vente">Valider la Vente</button>
    </form>
</div>

</body>
</html>
