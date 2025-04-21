<?php
require_once('fournisseur_header.php');
require_once('conn.php');

// Traitement des commandes
$query = "SELECT c.id, c.date_commande, c.statut, cp.quantite, p.nom as produit_nom 
          FROM commandes c
          JOIN commande_produits cp ON c.id = cp.commande_id
          JOIN produits p ON cp.produit_id = p.id
          WHERE p.fournisseur_id = ?
          ORDER BY c.date_commande DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['fournisseur_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="card">
    <h2>Commandes à Livrer</h2>
    
    <table class="order-table">
        <!-- Liste des commandes -->
    </table>
</div>

<?php
require_once('fournisseur_footer.php');
?>