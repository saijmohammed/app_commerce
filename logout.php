<?php
session_start();
include("conn.php"); // ← très important !

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    // Mettre status à 0 pour cet utilisateur
    mysqli_query($conn, "UPDATE users SET status = 0 WHERE id = '$user_id'");
}

// Supprimer toutes les variables de session
$_SESSION = [];
session_unset();
session_destroy();

// Supprimer aussi le cookie "remember_me" s'il existe
if (isset($_COOKIE['remember_me'])) {
    setcookie("remember_me", "", time() - 3600, "/"); // Le cookie expire dans le passé
}

// Rediriger vers la page de connexion
header("Location: login.php");
exit();
?>
