<?php
session_start();
if (!isset($_SESSION['fournisseur_id'])) {
    header('Location: fournisseur_header.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Fournisseur</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Styles communs */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4; 
        }
        
        .navbar {
            background: #1e3a8a;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 1rem;
            font-weight: 500;
        }
        
        .navbar a:hover {
            text-decoration: underline;
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div>
            <a href="fournisseur_dashboard.php">Accueil</a>
            <a href="fournisseur_produits.php">Produits</a>
            <a href="fournisseur_commandes.php">Commandes</a>
            <a href="fournisseur_messagerie.php">Messagerie</a>
            <a href="fournisseur_compte.php">Mon Compte</a>
        </div>
        <div>
            <a href="logout.php">Déconnexion</a>
        </div>
    </div>
    <div class="container"></div>