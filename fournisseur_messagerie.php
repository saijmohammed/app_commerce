<?php
require_once('fournisseur_header.php');
require_once('conn.php');

// Envoi de message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    // Votre code de traitement ici...
}

// Récupération des messages
$query = "SELECT m.*, u.Username as expediteur 
          FROM messages m
          JOIN users u ON m.expediteur_id = u.id
          WHERE m.destinataire_id = ? OR m.expediteur_id = ?
          ORDER BY m.date_envoi DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $_SESSION['fournisseur_id'], $_SESSION['fournisseur_id']);
$stmt->execute();
$messages = $stmt->get_result();
?>

<div class="card">
    <h2>Messagerie</h2>
    
    <form method="post">
        <!-- Formulaire d'envoi de message -->
    </form>
    
    <div class="message-list">
        <!-- Liste des messages -->
    </div>
</div>

<?php
require_once('fournisseur_footer.php');
?>