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
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="admin_page.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #f0f2f5, #c9d6ff);
      min-height: 100vh;
      padding-top: 70px; /* Pour éviter que la navbar cache le contenu */
    }

    .header {
      background: linear-gradient(90deg, #141e30, #243b55);
      height: 70px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 30px;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
      box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .logo {
      color: #ffffff;
      text-decoration: none;
      font-size: 24px;
      font-weight: bold;
      display: flex;
      align-items: center;
    }

    .logo svg {
      margin-right: 8px;
      color: #00d2ff;
    }

    .navbar {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .navbar a {
      color: #f0f0f0;
      text-decoration: none;
      font-size: 16px;
      display: flex;
      align-items: center;
      transition: 0.3s;
    }

    .navbar a svg {
      margin-right: 5px;
    }

    .navbar a:hover {
      color: #00d2ff;
    }

    .profile {
      position: relative;
    }

    .profile-btn {
      background: none;
      border: none;
      color: #f0f0f0;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 16px;
      position: relative;
      z-index: 1;
    }

    .profile-btn:hover {
      color: #00d2ff;
    }

    .dropdown {
      position: absolute;
      top: 60px;
      right: 0;
      background: #2c3e50;
      border-radius: 8px;
      min-width: 160px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.3);
      overflow: hidden;
      display: none;
      flex-direction: column;
      animation: fadeIn 0.3s forwards;
    }

    .dropdown a {
      padding: 12px 16px;
      color: #ecf0f1;
      text-decoration: none;
      display: block;
      transition: background 0.3s;
    }

    .dropdown a:hover {
      background: #34495e;
    }

    .profile.open .dropdown {
      display: flex;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body>

<header class="header">
  <a href="admin_page.php" class="logo">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"/>
      <polyline points="9 22 9 12 15 12 15 22"/>
    </svg>
    Admin<span>Panel</span>
  </a>

  <nav class="navbar">
    <a href="admin_page.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"/>
        <polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
      Accueil
    </a>

    <a href="admin_produits.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="2" y="2" width="20" height="20" rx="2" ry="2"/>
        <path d="M6 2L6 8M6 12L6 18M12 6L12 18M18 6L18 12"/>
      </svg>
      Produits
    </a>

    <a href="admin_commande.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="9" cy="21" r="1"/>
        <circle cx="20" cy="21" r="1"/>
        <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 002-1.61L23 6H6"/>
      </svg>
      Commandes
    </a>

    <a href="admin_users.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M17 21H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2z"/>
        <circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2"/>
        <path d="M21 9l-2 2"/>
      </svg>
      Utilisateurs
    </a>

    <a href="admin_contact.php">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M1 1h22v22H1z"/>
        <path d="M4 5h16M4 12h16m-8 7h8"/>
      </svg>
      Messages
    </a>

    <div class="profile" id="profile">
      <button class="profile-btn" onclick="toggleDropdown()">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
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
</header>

<script>
function toggleDropdown() {
  document.getElementById('profile').classList.toggle('open');
}
</script>

</body>
</html>
