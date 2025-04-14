<!-- edit_user.php -->

<?php
include 'conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $new_username = mysqli_real_escape_string($conn, $_POST['new_username']);
    $new_email = mysqli_real_escape_string($conn, $_POST['new_email']);
    $new_user_type = mysqli_real_escape_string($conn, $_POST['new_user_type']);

    // Mettez à jour les informations de l'utilisateur dans la base de données
    $update_query = "UPDATE `users` SET Username = '$new_username', Email = '$new_email', type = '$new_user_type' WHERE Id = '$user_id'";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        // Rediriger vers la page admin_users.php après la mise à jour
        header('Location: admin_users.php');
        exit();
    } else {
        echo 'Update failed: ' . mysqli_error($conn);
    }
}

// Récupérer les détails de l'utilisateur à éditer
if (isset($_GET['id'])) {
    $user_id_to_edit = mysqli_real_escape_string($conn, $_GET['id']);
    $select_user_query = "SELECT * FROM `users` WHERE Id = '$user_id_to_edit'";
    $select_user_result = mysqli_query($conn, $select_user_query);

    if ($select_user_result && mysqli_num_rows($select_user_result) > 0) {
        $user_data = mysqli_fetch_assoc($select_user_result);
    } else {
        echo 'User not found.';
        exit();
    }
} else {
    echo 'User ID not provided.';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom admin CSS file link -->
    <link rel="stylesheet" href="css/admin_style.css">

    <style>
       
    body {
        font-family: "Arial", sans-serif;
        background: linear-gradient(to right, #3498db, #2ecc71); /* Dégradé de bleu à vert */
        color: #fff;
        margin: 0;
        padding: 0;
    }

    .edit-user {
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
    }

    .edit-user h2 {
        text-align: center;
        color: #333;
    }

    .edit-user form {
        display: flex;
        flex-direction: column;
    }

    .edit-user label {
        margin-bottom: 8px;
        font-weight: bold;
    }

    .edit-user input,
    .edit-user select {
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 3px;
    }

    .edit-user button {
        background-color: #3498db;
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        margin: 3px;
    }

    .edit-user button:hover {
        background-color: #2980b9;
    }
    .edit-user .back-button {
            background-color:rgb(66, 231, 60);
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            width: 99%;
            margin: 3px;
           
        }

        .edit-user .back-button:hover {
            background-color: #c0392b;
        }
    </style>

</head>

<body>

    <?php include 'admin_header.php'; ?>

    <div class="edit-user">
        <h2>MODIFIER L'UTILISATEUR</h2>
        <form method="post" action="edit_user.php">
            <input type="hidden" name="user_id" value="<?php echo $user_data['id']; ?>">
            <label for="new_username">New Username:</label>
            <input type="text" id="new_username" name="new_username" value="<?php echo $user_data['username']; ?>" required>

            <label for="new_email">New Email:</label>
            <input type="email" id="new_email" name="new_email" value="<?php echo $user_data['email']; ?>" required>

            <label for="new_user_type">New User Type:</label>
            <select id="new_user_type" name="new_user_type" required>
                <option value="user" <?php echo ($user_data['type'] == 'user') ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo ($user_data['type'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="vendeur" <?php echo ($user_data['type'] == 'vendeur') ? 'selected' : ''; ?>>vendeur</option>
                <option value="fournisseur" <?php echo ($user_data['type'] == 'fournisseur') ? 'selected' : ''; ?>>fournisseur</option>
              </select>

            <button type="submit" name="update_user">Modifier l'utilisateur</button>
            <a href="admin_users.php">
                <button type="button" class="back-button">Retour</button>
            </a>
        </form>
    </div>

</body>

</html>