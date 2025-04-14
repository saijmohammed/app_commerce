<?php
include 'conn.php';
session_start();

// Processus de suppression d'utilisateur
if (isset($_GET['delete'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    $delete_query = "DELETE FROM `users` WHERE Id = '$delete_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {
        session_destroy();
        header('Location: admin_users.php');
        exit();
    } else {
        echo 'Delete failed: ' . mysqli_error($conn);
    }
}

// Processus de modification d'utilisateur
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMPTES D’UTILISATEURS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #333;
        }

        .users {
            padding: 20px;
        }

        .title {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 30px;
            color: #333;
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .box {
            width: 280px;
            background: #fff;
            color: #333;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin: 15px;
            padding: 20px;
            transition: transform 0.3s ease;
        }

        .box:hover {
            transform: translateY(-5px);
        }

        .box p {
            margin: 8px 0;
            font-size: 1rem;
        }

        .box .delete-btn,
        .box form input[type="submit"] {
            background-color: #3498db;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .box .delete-btn:hover,
        .box form input[type="submit"]:hover {
            background-color: #2980b9;
        }

        .box .delete-btn {
            background-color: #e74c3c;
        }

        .box .delete-btn:hover {
            background-color: #c0392b;
        }

        .panel {
            text-align: center;
            margin-bottom: 20px;
        }

        .user-text {
            color: #3498db;
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
                    <p> User ID: <span><?php echo $fetch_users['id']; ?></span> </p>
                    <p> Username: <span><?php echo $fetch_users['username']; ?></span> </p>
                    <p> Email: <span><?php echo $fetch_users['email']; ?></span> </p>
                    <p> User type: <span style="color:<?php echo ($fetch_users['type'] == 'admin') ? '#e67e22' : '#3498db'; ?>"><?php echo $fetch_users['type']; ?></span> </p>

                    <form method="post" action="admin_users.php">
                        <input type="hidden" name="edit_user_id" value="<?php echo $fetch_users['id']; ?>">
                        <input type="submit" name="edit_user" value="Modifier l'utilisateur">
                    </form>

                    <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Supprimer cet utilisateur ?');" class="delete-btn">Supprimer l'utilisateur</a>
                </div>
            <?php
            };
            ?>
        </div>
    </section>

</body>

</html>
