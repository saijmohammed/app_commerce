<?php
require_once('fournisseur_header.php');
?>

<h2>Tableau de Bord</h2>
<div class="card">
    <h3>Bienvenue, <?= htmlspecialchars($_SESSION['fournisseur_nom']) ?></h3>
    <p>Vous avez X produits en stock et Y commandes à traiter.</p>
</div>

<?php
require_once('fournisseur_footer.php');
?>