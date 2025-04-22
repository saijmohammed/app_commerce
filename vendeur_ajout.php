<?php
session_start();
include 'conn.php';
require_once('tcpdf/tcpdf.php');

if (!isset($_SESSION['email']) || $_SESSION['type'] != 'vendeur') {
    header('Location: login.php');
    exit();
}

$vendeur_name = $_SESSION['email'];
$messages = [];

$produits = [];
$result = mysqli_query($conn, "SELECT * FROM produits ORDER BY nom ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $produits[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_vente'])) {
    $produit_id = mysqli_real_escape_string($conn, $_POST['produit_id']);
    $quantite = (int) $_POST['quantite'];
    $client = mysqli_real_escape_string($conn, $_POST['client']);

    $check_stock = mysqli_query($conn, "SELECT * FROM produits WHERE id = '$produit_id'");
    $produit = mysqli_fetch_assoc($check_stock);

    if ($produit && $produit['quantite_stock'] >= $quantite) {
        mysqli_query($conn, "INSERT INTO ventes (produit_id, vendeur_id, client, quantite, date_vente) VALUES ('$produit_id', '{$_SESSION['id']}', '$client', '$quantite', NOW())");
        mysqli_query($conn, "UPDATE produits SET quantite_stock = quantite_stock - $quantite WHERE id = '$produit_id'");

        $_SESSION['vente_data'] = [
            'client' => $client,
            'produit_nom' => $produit['nom'],
            'prix' => $produit['prix'],
            'quantite' => $quantite,
            'date' => date('d/m/Y H:i')
        ];

        $_SESSION['message'] = "✅ Vente enregistrée avec succès.";
        header('Location: vendeur_ajout.php');
        exit();
    } else {
        $_SESSION['message'] = "❌ Stock insuffisant pour cette vente.";
        header('Location: vendeur_ajout.php');
        exit();
    }
}

if (isset($_POST['generer_pdf']) && isset($_SESSION['vente_data'])) {
    $vente = $_SESSION['vente_data'];
    unset($_SESSION['vente_data']);

    $pdf = new TCPDF();
    $pdf->SetCreator('VendeurSpace');
    $pdf->SetAuthor('VendeurSpace');
    $pdf->SetTitle('Facture Vente');
    $pdf->SetMargins(15, 15, 15);
    $pdf->AddPage();

    $html = '
    <h1 style="text-align:center; color:#3498db;">Facture de Vente</h1>
    <hr><br>
    <table cellpadding="5">
        <tr><td><strong>Nom du Client :</strong></td><td>' . htmlspecialchars($vente['client']) . '</td></tr>
        <tr><td><strong>Produit :</strong></td><td>' . htmlspecialchars($vente['produit_nom']) . '</td></tr>
        <tr><td><strong>Prix Unitaire :</strong></td><td>' . number_format($vente['prix'], 2) . ' DH</td></tr>
        <tr><td><strong>Quantité :</strong></td><td>' . $vente['quantite'] . '</td></tr>
        <tr><td><strong>Total :</strong></td><td><strong>' . number_format($vente['prix'] * $vente['quantite'], 2) . ' DH</strong></td></tr>
        <tr><td><strong>Date :</strong></td><td>' . $vente['date'] . '</td></tr>
    </table>
    <br><br>
    <div style="text-align:center;">
        <p>Merci pour votre confiance !</p>
    </div>
    ';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('facture_vente.pdf', 'I');
    exit();
}

if (isset($_SESSION['message'])) {
    $messages[] = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Vente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #74ebd5, #acb6e5); min-height: 100vh; padding-top: 100px; display: flex; flex-direction: column; align-items: center; }
        header { width: 100%; height: 70px; background: linear-gradient(135deg, #3498db, #9b59b6); color: white; position: fixed; top: 0; left: 0; display: flex; justify-content: space-between; align-items: center; padding: 0 30px; z-index: 100; box-shadow: 0 4px 6px rgba(0,0,0,0.2);}
        .logo { font-weight: bold; font-size: 1.5rem; }
        nav { display: flex; gap: 20px; align-items: center; }
        nav a, .email-text { color: white; text-decoration: none; font-weight: 500; font-size: 1rem; }
        .container { background: white; width: 90%; max-width: 800px; padding: 30px; border-radius: 16px; box-shadow: 0 10px 20px rgba(0,0,0,0.2); margin-top: 20px; }
        h1 { text-align: center; color: #333; margin-bottom: 20px; }
        .message, .error-message { padding: 10px; border-radius: 8px; text-align: center; margin-bottom: 20px; color: white; font-weight: bold; }
        .message { background: #2ecc71; }
        .error-message { background: #e74c3c; }
        form { display: flex; flex-direction: column; gap: 15px; }
        select, input[type="number"], input[type="text"] { padding: 10px; border: 1px solid #ccc; border-radius: 8px; width: 100%; font-size: 1rem; }
        button { background: #3498db; border: none; color: white; padding: 12px; border-radius: 8px; font-size: 16px; cursor: pointer; transition: 0.3s; }
        button:hover { background: #2980b9; }
        .product-preview { margin-top: 20px; display: none; background: #f9f9f9; padding: 20px; border-radius: 12px; box-shadow: 0 5px 10px rgba(0,0,0,0.1); text-align: center; }
        .product-preview img { width: 220px; height: 220px; object-fit: cover; border-radius: 12px; margin-bottom: 10px; transition: 0.4s; }
        .product-preview img:hover { transform: scale(1.05); }
        .product-preview h3 { margin-top: 10px; color: #3498db; }
        .product-preview p { margin: 6px 0; font-size: 1rem; color: #666; }
        .pdf-btn { margin-top: 25px; display: flex; justify-content: center; }
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

    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $msg): ?>
            <div class="<?php echo strpos($msg, '✅') !== false ? 'message' : 'error-message'; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <form method="post" action="">
        <label for="produit_id">Sélectionnez un Produit :</label>
        <select name="produit_id" id="produit_id" onchange="showProduct()" required>
            <option value="">-- Choisir un produit --</option>
            <?php foreach ($produits as $produit): ?>
                <option value="<?php echo $produit['id']; ?>"
                    data-nom="<?php echo htmlspecialchars($produit['nom']); ?>"
                    data-prix="<?php echo htmlspecialchars($produit['prix']); ?>"
                    data-stock="<?php echo (int)$produit['quantite_stock']; ?>"
                    data-image="<?php echo ($produit['is_external_image'] ? htmlspecialchars($produit['image']) : 'photo/' . htmlspecialchars($produit['image'])); ?>">
                    <?php echo htmlspecialchars($produit['nom']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div id="preview" class="product-preview">
            <img id="preview-img" src="" alt="Produit">
            <h3 id="preview-name"></h3>
            <p id="preview-price"></p>
            <p id="preview-stock"></p>
        </div>

        <label for="quantite">Quantité :</label>
        <input type="number" name="quantite" id="quantite" min="1" required>

        <label for="client">Nom du Client :</label>
        <input type="text" name="client" id="client" required>

        <button type="submit" name="ajouter_vente">Valider la Vente</button>
    </form>

    <?php if (isset($_SESSION['vente_data'])): ?>
        <div class="pdf-btn">
            <form method="post" action="">
                <button type="submit" name="generer_pdf">📄 Télécharger la Facture en PDF</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<script>
function showProduct() {
    const select = document.getElementById('produit_id');
    const selected = select.options[select.selectedIndex];
    if (selected.value !== "") {
        document.getElementById('preview').style.display = 'block';
        document.getElementById('preview-img').src = selected.getAttribute('data-image');
        document.getElementById('preview-name').textContent = selected.getAttribute('data-nom');
        document.getElementById('preview-price').textContent = "Prix : " + selected.getAttribute('data-prix') + " DH";
        document.getElementById('preview-stock').textContent = "Stock disponible : " + selected.getAttribute('data-stock') + " pièces";
    } else {
        document.getElementById('preview').style.display = 'none';
    }
}
</script>

</body>
</html>
