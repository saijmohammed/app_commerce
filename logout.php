<?php
session_start();

// Supprimer les données de session
$_SESSION = []; // Vide toutes les variables de session

// Détruire la session
session_destroy();

// Supprimer le cookie "remember_me" si il existe
if (isset($_COOKIE['remember_me'])) {
    setcookie("remember_me", "", time() - 3600, "/"); // Le cookie expire dans le passé
}

// Rediriger l'utilisateur vers la page de connexion
header("Location: login.php");
exit();
?>
