<?php
session_start();
 
if (!isset($_SESSION['email'])) {
    header('location:login.php');
    exit;
} else {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profil.css">
    <link rel="icon" href="photo/7553408.jpg" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Profile - GamingPlanet</title>
</head>

<body>
    <?php
    require_once('conn.php');
    $userid = $_SESSION['id'];
    $messages = [];

    if (isset($_POST['submit'])) {
        // Use prepared statements to prevent SQL injection
        $newUsername = mysqli_real_escape_string($conn, $_POST['username']);
        $newAdresse = mysqli_real_escape_string($conn, $_POST['adresse']);
        $newPhoneNumber = mysqli_real_escape_string($conn, $_POST['numero_telephone']);
        $newEmail = mysqli_real_escape_string($conn, $_POST['email']);

        $updateQuery = "UPDATE users SET 
                        username=?, 
                        adresse=?, 
                        numero_telephone=?, 
                        email=? 
                        WHERE Id=?";
                        
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, 'ssssi', $newUsername, $newAdresse, $newPhoneNumber, $newEmail, $userid);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $_SESSION['email'] = $newEmail; // Update session with new email
            $messages[] = 'Vos informations ont été mises à jour avec succès!';
        } else {
            $messages[] = 'Erreur lors de la mise à jour des informations: ' . mysqli_error($conn);
        }
        
        mysqli_stmt_close($stmt);
    }

    // Retrieve user information from database using prepared statement
    $selectQuery = "SELECT * FROM users WHERE Id = ?";
    $stmt = mysqli_prepare($conn, $selectQuery);
    mysqli_stmt_bind_param($stmt, 'i', $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    ?>

    <div class="container">
        <?php
        // Display messages if there are any
        if (!empty($messages)) {
            foreach ($messages as $message) {
                echo '<div class="message">
                      <span>' . $message . '</span>
                      </div>';
            }
        }

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        ?>
            <div class="profile">
                <h1 class="gradient-title">PROFIL</h1>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" name="username" id="username" class="form-control" autocomplete="off" value="<?php echo htmlspecialchars($row['username']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" autocomplete="off" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" name="adresse" id="adresse" class="form-control" autocomplete="off" value="<?php echo htmlspecialchars($row['adresse']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="numero_telephone" class="form-label">Numéro de téléphone</label>
                        <input type="tel" name="numero_telephone" id="numero_telephone" class="form-control" autocomplete="off" value="<?php echo htmlspecialchars($row['numero_telephone']); ?>" required>
                    </div>
                    
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary" name="submit">Mettre à jour</button>
                    </div>
                </form>
                <div class="text-center">
                    <a href="admin_page.php" class="btn btn-outline">Retour à l'accueil</a>
                </div>
            </div>
        <?php
        } else {
            echo '<div class="message"><span>Impossible de récupérer les informations utilisateur.</span></div>';
        }
        mysqli_stmt_close($stmt);
        ?>
    </div>

    <script>
        const messages = document.querySelectorAll('.message');

        const removeMessage = (message) => {
            setTimeout(() => {
                message.classList.add('fade-out');
                setTimeout(() => {
                    message.remove();
                }, 1000);
            }, 4000);
        };

        messages.forEach((message) => {
            message.classList.add('show');
            removeMessage(message);
        });
    </script>
</body>

</html>
<?php
}
?>