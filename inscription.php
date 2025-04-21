<?php
session_start();
include("conn.php");

// Variables pour les erreurs et messages
$usernameErr = $emailErr = "";
$successMessage = "";

// Traitement de l'inscription
if (isset($_POST['register'])) {
    $nom_utilisateur = $_POST['username'];
    $email = $_POST['email'];
    $motDePasse = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $adresse = $_POST['adresse'];
    $numero_telephone = $_POST['numero_telephone'];
    $type = 'user';

    // Validation du nom d'utilisateur
    $resultUsername = mysqli_query($conn, "SELECT * FROM users WHERE Username='$nom_utilisateur'");
    if (mysqli_num_rows($resultUsername) > 0) {
        $usernameErr = "Le nom d'utilisateur existe déjà.";
    }

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Format d'e-mail invalide.";
    } else {
        $resultEmail = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($resultEmail) > 0) {
            $emailErr = "L'e-mail existe déjà.";
        }
    }

    // Si pas d'erreurs, insertion dans la base
    if (empty($usernameErr) && empty($emailErr)) {
        mysqli_query($conn, "INSERT INTO users(Username, numero_telephone, adresse, email, motDePasse, type) 
            VALUES('$nom_utilisateur','$numero_telephone','$adresse','$email','$motDePasse','$type')") or die("Erreur");
        $successMessage = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Bloom Parfums</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('login_pic.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 400px;
            width: 90%;
            color: #333;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .title {
            text-align: center;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #6a11cb;
        }

        .form-label {
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 15px;
            color: #333;
            margin-bottom: 15px;
        }

        .form-control:focus {
            border-color: #9c27b0;
            box-shadow: 0 0 0 0.2rem rgba(156, 39, 176, 0.25);
        }

        .btn-register {
            background: #9c27b0;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            width: 100%;
            color: white;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-register:hover {
            background: #7b1fa2;
            transform: translateY(-2px);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .login-link a {
            color: #9c27b0;
            font-weight: 500;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 15px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1 class="title">Inscription</h1>

        <?php if (!empty($successMessage)) : ?>
            <div class="alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label" for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Nom d'utilisateur" required>
                <div class="error-message"><?php echo $usernameErr; ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="adresse">Adresse</label>
                <input type="text" name="adresse" id="adresse" class="form-control" placeholder="Adresse" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="numero_telephone">Numéro de téléphone</label>
                <input type="text" name="numero_telephone" id="numero_telephone" class="form-control" value="212" required>
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">E-mail</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="Adresse e-mail" required>
                <div class="error-message"><?php echo $emailErr; ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Mot de passe" required>
            </div>

            <button type="submit" class="btn btn-register" name="register">S'inscrire</button>

            <div class="login-link">
                <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
