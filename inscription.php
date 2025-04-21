<?php
session_start();
include("conn.php");

$usernameErr = $emailErr = "";
$successMessage = "";

if (isset($_POST['submit'])) {
    $nom_utilisateur = $_POST['username'];
    $email = $_POST['email'];
    $motDePasse = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $adresse = $_POST['adresse'];
    $numero_telephone = $_POST['numero_telephone'];
    $type = 'user';

    $resultUsername = mysqli_query($conn, "SELECT * FROM users WHERE Username='$nom_utilisateur'");
    if (mysqli_num_rows($resultUsername) > 0) {
        $usernameErr = "Le nom d'utilisateur existe déjà.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Format d'e-mail invalide.";
    } else {
        $resultEmail = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($resultEmail) > 0) {
            $emailErr = "L'e-mail existe déjà.";
        }
    }

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
    <title>Inscription - ParfumPlanet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #05365f, #ff4d88);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px;
            max-width: 350px;
            width: 80%;
            color: white;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .gradient-title {
            background-image: linear-gradient(to right, #ff9f00,rgb(238, 87, 137));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 600;
            font-size: 30px;
        }

        .form-label {
            color: #f8f9fa;
            font-weight: 150;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 10px;
            padding: 10px;
            color: white;
            margin-bottom: 5px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .btn-primary {
            background: linear-gradient(to right, #ff9f00, #ff4d88);
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 25px;
            width: 100%;
            transition: 0.3s;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }

        .text-center a {
            color: #ff9f00;
            font-weight: 500;
            text-decoration: none;
            transition: 0.3s;
        }

        .text-center a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #ff8080;
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .alert-success {
            background-color: rgba(0, 255, 100, 0.2);
            color: #00ff99;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1 class="text-center gradient-title">Inscription</h1>

        <?php if (!empty($successMessage)) : ?>
            <div class="alert alert-success text-center"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-2">
                <label class="form-label" for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Nom d'utilisateur" required>
                <div class="error-message"><?php echo $usernameErr; ?></div>
            </div>

            <div class="mb-2">
                <label class="form-label" for="adresse">Adresse</label>
                <input type="text" name="adresse" id="adresse" class="form-control" placeholder="Adresse" required>
            </div>

            <div class="mb-2">
                <label class="form-label" for="numero_telephone">Numéro de téléphone</label>
                <input type="text" name="numero_telephone" id="numero_telephone" class="form-control" value="212" required>
            </div>

            <div class="mb-2">
                <label class="form-label" for="email">E-mail</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="Adresse e-mail" required>
                <div class="error-message"><?php echo $emailErr; ?></div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Mot de passe" required>
            </div>

            <button type="submit" class="btn btn-primary" name="submit">S'inscrire</button>

            <div class="text-center mt-3">
                <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
