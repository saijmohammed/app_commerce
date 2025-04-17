<?php
session_start();
include("conn.php");

// Vérifier si un cookie "remember_me" existe
if (isset($_COOKIE['remember_me'])) {
    $cookie_value = base64_decode($_COOKIE['remember_me']); // Décoder la valeur du cookie

    // Vérifier si l'ID existe dans la base de données
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id='$cookie_value'") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['type'] = $row['type'];

        // Redirection selon le type d'utilisateur
        if ($row['type'] === 'admin') {
            header("Location: admin_page.php");
        } elseif ($row['type'] === 'vendeur') {
            header("Location: vendeur_page.php");
        } else {
            header("Location: home.php");
        }
        exit();
    } else {
        // Si l'utilisateur n'existe pas, détruire le cookie
        setcookie("remember_me", "", time() - 3600, "/");
    }
}

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Vérifier si l'utilisateur existe
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $motDePass = $row['motDePasse']; // Mot de passe haché
        // Comparer le mot de passe saisi avec le haché
        if (password_verify($password, $motDePass)) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['type'] = $row['type'];

            // Créer un cookie pour garder l'utilisateur connecté
            if (isset($_POST['remember_me'])) {
                $cookie_value = base64_encode($row['id']); // Encode l'ID de l'utilisateur
                setcookie("remember_me", $cookie_value, time() + (86400 * 30), "/"); // Expiration après 30 jours
            }

            // Redirection selon le type d'utilisateur
            if ($row['type'] === 'admin') {
                header("Location: admin_page.php");
            } elseif ($row['type'] === 'vendeur') {
                header("Location: vendeur_page.php");
            } else {
                header("Location: home.php");
            }
            exit();
        } else {
            echo "<div class='alert alert-danger'>
                    <p>Adresse e-mail ou mot de passe incorrect</p>
                  </div>";
        }
    } else {
        echo "<div class='alert alert-danger'>
                <p>Adresse e-mail ou mot de passe incorrect</p>
              </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ParfumPlanet</title>
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
            padding: 40px;
            max-width: 380px;
            width: 100%;
            color: white;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .gradient-title {
            background-image: linear-gradient(to right, #ff9f00, #ff4d88);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 600;
            font-size: 30px;
        }

        .form-label {
            color: #f8f9fa;
            font-weight: 500;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 10px;
            padding: 14px;
            color: white;
            margin-bottom: 20px;
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

        .alert {
            background-color: rgba(255, 0, 0, 0.6);
            color: white;
            font-weight: 600;
        }

        .form-check-label {
            color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1 class="text-center gradient-title">Connexion</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="Entrez votre email" autocomplete="off" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Entrez votre mot de passe" autocomplete="off" required>
            </div>

            <!-- Case à cocher pour se souvenir de l'utilisateur -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                <label class="form-check-label" for="remember_me">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn btn-primary" name="submit">Se connecter</button>

            <div class="text-center mt-3">
                <p>Pas encore de compte ? <a href="inscription.php">Inscrivez-vous</a></p>
                <p><a href="reset_password.php">Mot de passe oublié ?</a></p>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
