<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panneau d’administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin_page.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #3498db;
            color: #ecf0f1;
            margin: 0;
            padding: 0;
        }

        .DASHBOARD {
            padding: 20px;
            background-color: #2c3e50;
            border-radius: 10px;
            margin: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        .title {
            color: #2ecc71;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .box {
         background-color: #87ceeb;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin: 10px;
            flex: 1 1 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: scaleIn 1s ease-in-out;
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
            text-decoration: none;  
            color: #ecf0f1;  
        }

        .box:hover {
            transform: scale(1.05);
            color: green;
        }

        .box h3 {
            margin-bottom: 10px;
            font-size: 28px;
        }

        .box p {
            margin: 0;
            font-size: 16px;
        }
        .DASHBOARD .text{
            font-weight: bold;
            color: #60dea1;
        }
        .DASHBOARD .title{
            font-weight: bold;
            color: black;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }

            to {
                transform: scale(1);
            }
        }
    </style>
</head>

<body>

    <?php include 'admin_header.php'; ?>
    <?php include('conn.php'); ?>

    <section class="DASHBOARD">

    <div class="panel3">
        <h1 class="title">TABLEAU <span class="text">DE BORD</span></h1>
    </div>

        <div class="box-container">

             
            <a href="admin_produits.php" class="box">
                <?php
                $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
                $number_of_products = mysqli_num_rows($select_products);
                ?>
                <h3><?php echo $number_of_products; ?></h3>
                <p>Produits ajoutés</p>
            </a>

            <a href="admin_users.php" class="box">
                <?php
                $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE type = 'user'") or die('query failed');
                $number_of_users = mysqli_num_rows($select_users);
                ?>
                <h3><?php echo $number_of_users; ?></h3>
                <p>Utilisateurs normaux</p>
            </a>

            <a href="admin_users.php" class="box">
                <?php
                $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE type = 'admin'") or die('query failed');
                $number_of_admins = mysqli_num_rows($select_admins);
                ?>
                <h3><?php echo $number_of_admins; ?></h3>
                <p>Utilisateurs Admin</p>
            </a>
            <a href="admin_users.php" class="box">
                <?php
                $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE type = 'vendeur'") or die('query failed');
                $number_of_admins = mysqli_num_rows($select_admins);
                ?>
                <h3><?php echo $number_of_admins; ?></h3>
                <p>Utilisateurs vendeurs</p>
            </a>
            <a href="admin_users.php" class="box">
                <?php
                $select_admins = mysqli_query($conn, "SELECT * FROM `users` WHERE type = 'fournisseur'") or die('query failed');
                $number_of_admins = mysqli_num_rows($select_admins);
                ?>
                <h3><?php echo $number_of_admins; ?></h3>
                <p>Utilisateurs Fournisseurs</p>
            </a>

            <a href="admin_users.php" class="box">
                <?php
                $select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
                $number_of_account = mysqli_num_rows($select_account);
                ?>
                <h3><?php echo $number_of_account; ?></h3>
                <p>Totals des comptes</p>
            </a>

         
       
        </div>

    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/admin_script.js"></script>

    <script>
        function handleBoxClick(boxType) {
            alert("Clicked on " + boxType);
             
        }
    </script>

</body>

</html>
