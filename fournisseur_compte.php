<?php
require_once('fournisseur_header.php');
require_once('conn.php');

// Mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Votre code de traitement ici...
}

// Récupération des infos
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['fournisseur_id']);
$stmt->execute();
$fournisseur = $stmt->get_result()->fetch_assoc();
?>

<div class="card">
    <h2>Mon Compte</h2>
    
    <form method="post">
        <!-- Formulaire de mise à jour -->
    </form>
</div>

<div class="card">
    <h3>Changer le mot de passe</h3>
    <form action="update_password.php" method="post">
        <!-- Formulaire de changement de mot de passe -->
    </form>
</div>

<?php
require_once('fournisseur_footer.php');
?>