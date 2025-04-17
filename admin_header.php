<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
      
   }
}
$_SESSION['admin_name'] = $_SESSION['admin_name'] ?? 'Admin';
$admin_name = htmlspecialchars($_SESSION['admin_name']);
?>
<html>
 
<link rel="stylesheet" href="admin_page.css">
<style>
.profile {
            position: relative;
            display: inline-block;
        }

        .profile-btn {
            background: none;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .profile-btn:hover {
            color: #2ecc71;
        }

        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: #333;
            border-radius: 6px;
            min-width: 150px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            flex-direction: column;
            z-index: 1000;
        }

        .dropdown a {
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            display: block;
            transition: background 0.3s;
        }

        .dropdown a:hover {
            background-color: #444;
        }

        .profile:hover .dropdown {
            display: flex;
        }





</style>
<header class="header">
    <div class="flex">
        <a href="admin_page.php" class="logo">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            Admin<span>Panel</span>
        </a>

        <nav class="navbar">
            <a href="admin_page.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Accueil
            </a>
            <a href="admin_produits.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                    <rect x="2" y="2" width="20" height="20" rx="2" ry="2"></rect>
                    <path d="M6 2L6 8"></path>
                    <path d="M6 12L6 18"></path>
                    <path d="M12 6L12 18"></path>
                    <path d="M18 6L18 12"></path>
                </svg>
                Produits
            </a>
            <a href="admin_commande.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                Commandes
            </a>
            <a href="admin_users.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                    <path d="M17 21H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2z"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2"></path>
                    <path d="M21 9l-2 2"></path>
                </svg>
                Utilisateurs
            </a>
            <a href="admin_contact.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square">
                    <path d="M1 1h22v22H1z"></path>
                    <path d="M4 5h16M4 12h16m-8 7h8"></path>
                </svg>
                Messages
            </a>
            
            <div class="profile">
            <button class="profile-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                    <circle cx="12" cy="7" r="4"/>
                    <path d="M5.5 20a6.5 6.5 0 0113 0"/>
                </svg>
                <?php echo $admin_name; ?>
            </button>
            <div class="dropdown">
                <a href="admin_profil.php">Mon compte</a>

                <a href="logout.php">Déconnexion</a>
            </div>
        </div>
        </nav>
    </div>
</header>
<body>



</body>

<script src=" admin .js"></script>
</html>