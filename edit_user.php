<?php
include 'conn.php';
session_start();

// Mise à jour utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $new_username = mysqli_real_escape_string($conn, $_POST['new_username']);
    $new_email = mysqli_real_escape_string($conn, $_POST['new_email']);
    $new_user_type = mysqli_real_escape_string($conn, $_POST['new_user_type']);

    $update_query = "UPDATE `users` SET username = '$new_username', email = '$new_email', type = '$new_user_type' WHERE id = '$user_id'";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        header('Location: admin_users.php');
        exit();
    } else {
        echo 'Update failed: ' . mysqli_error($conn);
    }
}

// Récupération données utilisateur
if (isset($_GET['id'])) {
    $user_id_to_edit = mysqli_real_escape_string($conn, $_GET['id']);
    $select_user_query = "SELECT * FROM `users` WHERE id = '$user_id_to_edit'";
    $select_user_result = mysqli_query($conn, $select_user_query);

    if ($select_user_result && mysqli_num_rows($select_user_result) > 0) {
        $user_data = mysqli_fetch_assoc($select_user_result);
    } else {
        echo 'Utilisateur introuvable.';
        exit();
    }
} else {
    echo 'ID utilisateur non fourni.';
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
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
        .edit-user {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .edit-user h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }
        .edit-user form {
            display: flex;
            flex-direction: column;
        }
        .edit-user label {
            margin-bottom: 8px;
            font-weight: bold;
            color: #34495e;
        }
        .edit-user input, .edit-user select {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
        .edit-user button, .edit-user .back-button {
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }
        .edit-user button {
            background-color: #3498db;
            color: white;
        }
        .edit-user button:hover {
            background-color: #2980b9;
        }
        .edit-user .back-button {
            background-color: #2ecc71;
            color: white;
            text-align: center;
            text-decoration: none;
            display: block;
        }
        .edit-user .back-button:hover {
            background-color: #27ae60;
        }
        @media(max-width: 600px) {
            .edit-user {
                margin: 20px;
                padding: 20px;
            }
            .edit-user h2 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

<?php include 'admin_header.php'; ?>

<div class="edit-user">
    <h2>Modifier l'utilisateur</h2>
    <form method="post" action="edit_user.php">
        <input type="hidden" name="user_id" value="<?php echo $user_data['id']; ?>">

        <label for="new_username">Nouveau Username :</label>
        <input type="text" id="new_username" name="new_username" value="<?php echo $user_data['username']; ?>" required>

        <label for="new_email">Nouvel Email :</label>
        <input type="email" id="new_email" name="new_email" value="<?php echo $user_data['email']; ?>" required>

        <label for="new_user_type">Nouveau Type :</label>
        <select id="new_user_type" name="new_user_type" required>
            <option value="user" <?php echo ($user_data['type'] == 'user') ? 'selected' : ''; ?>>User</option>
            <option value="admin" <?php echo ($user_data['type'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="vendeur" <?php echo ($user_data['type'] == 'vendeur') ? 'selected' : ''; ?>>Vendeur</option>
            <option value="fournisseur" <?php echo ($user_data['type'] == 'fournisseur') ? 'selected' : ''; ?>>Fournisseur</option>
        </select>

        <button type="submit" name="update_user">Enregistrer les modifications</button>
        <a href="admin_users.php" class="back-button">Retour</a>
    </form>
</div>

</body>
</html>
