<?php
include 'conn.php';
session_start();

// Suppression d'utilisateur
if (isset($_GET['delete'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    $delete_query = "DELETE FROM `users` WHERE id = '$delete_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        session_destroy();
        header('Location: admin_users.php');
        exit();
    } else {
        echo 'Delete failed: ' . mysqli_error($conn);
    }
}

// Redirection pour modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $edit_id = mysqli_real_escape_string($conn, $_POST['edit_user_id']);
    header("Location: edit_user.php?id=$edit_id");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0; padding: 0; box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            min-height: 100vh;
            padding-top: 80px;
        }
        .header {
            background: linear-gradient(90deg, #141e30, #243b55);
            color: white;
            padding: 20px 30px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 999;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header a {
            text-decoration: none;
            color: #00d2ff;
            font-weight: bold;
            font-size: 24px;
        }
        .users {
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }
        .panel {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
        }
        .user-text {
            color: #3498db;
        }
        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .box {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
        .box:hover {
            transform: translateY(-5px);
        }
        .box p {
            margin: 10px 0;
            font-size: 1rem;
            color: #555;
        }
        .box p span {
            font-weight: bold;
            color: #111;
        }
        .box .delete-btn,
        .box form input[type="submit"] {
            margin-top: 10px;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        .box form input[type="submit"] {
            background: #2980b9;
            color: white;
        }
        .box form input[type="submit"]:hover {
            background: #3498db;
        }
        .box .delete-btn {
            background: #c0392b;
            color: white;
            display: block;
            text-align: center;
            text-decoration: none;
        }
        .box .delete-btn:hover {
            background: #e74c3c;
        }
        @media(max-width: 600px) {
            .title {
                font-size: 2rem;
            }
            .header {
                flex-direction: column;
                text-align: center;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<?php include 'admin_header.php'; ?>

<section class="users">
    <div class="panel">
        <h1 class="title">COMPTES <span class="user-text">D'UTILISATEURS</span></h1>
    </div>

    <div class="box-container">
        <?php
        $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
        while ($fetch_users = mysqli_fetch_assoc($select_users)) {
        ?>
            <div class="box">
                <p> <i class="fas fa-id-badge"></i> ID: <span><?php echo $fetch_users['id']; ?></span> </p>
                <p> <i class="fas fa-user"></i> Username: <span><?php echo $fetch_users['username']; ?></span> </p>
                <p> <i class="fas fa-envelope"></i> Email: <span><?php echo $fetch_users['email']; ?></span> </p>
                <p> <i class="fas fa-user-tag"></i> Type: 
                    <span style="color:<?php echo ($fetch_users['type'] == 'admin') ? '#e67e22' : '#3498db'; ?>">
                        <?php echo $fetch_users['type']; ?>
                    </span>
                </p>

                <form method="post" action="admin_users.php">
                    <input type="hidden" name="edit_user_id" value="<?php echo $fetch_users['id']; ?>">
                    <input type="submit" name="edit_user" value="Modifier l'utilisateur">
                </form>

                <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Supprimer cet utilisateur ?');" class="delete-btn">
                    Supprimer l'utilisateur
                </a>
            </div>
        <?php
        };
        ?>
    </div>
</section>

</body>
</html>
