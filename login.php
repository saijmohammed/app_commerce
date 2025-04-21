<?php
session_start();
include("conn.php");

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);

    if ($row) { 
        if (password_verify($password, $row['motDePasse'])) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['type'] = $row['type'];

            if (isset($_POST['remember_me'])) {
                $cookie_value = base64_encode($row['id']);
                setcookie("remember_me", $cookie_value, time() + (86400 * 30), "/");
            }

            // Redirections selon le type d'utilisateur
            if ($row['type'] === 'admin') {
                header("Location: admin_page.php");
            } elseif ($row['type'] === 'vendeur') {
                header("Location: vendeur_page.php");
            } elseif ($row['type'] === 'fournisseur') {
                header("Location: fournisseur_header.php");
            } else {
                header("Location: home.php");
            }
            exit();
        } else {
            $error = "Adresse e-mail ou mot de passe incorrect";
        }
    } else {
        $error = "Adresse e-mail ou mot de passe incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Bloom Parfums</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f5f5, #e0e0e0);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            background: white;
            border: none;
            border-radius: 12px;
            padding: 40px;
            max-width: 400px;
            width: 100%;
            color: #333;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .login-title {
            text-align: center;
            font-weight: 600;
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
        }

        .form-label {
            color: #333;
            font-weight: 500;
        }

        .form-control {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 14px;
            color: #333;
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: #9c27b0;
            box-shadow: 0 0 0 0.2rem rgba(156, 39, 176, 0.25);
        }

        .form-control::placeholder {
            color: #999;
        }

        .btn-primary {
            background: #9c27b0;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 8px;
            width: 100%;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: #7b1fa2;
            transform: translateY(-2px);
        }

        .text-center a {
            color: #9c27b0;
            font-weight: 500;
            text-decoration: none;
            transition: 0.3s;
        }

        .text-center a:hover {
            color: #7b1fa2;
            text-decoration: underline;
        }

        .alert {
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .form-check-label {
            color: #555;
        }

        .form-check-input:checked {
            background-color: #9c27b0;
            border-color: #9c27b0;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1 class="login-title">Connexion</h1>
        
        <?php if (!empty($error)) : ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="Entrez votre email" autocomplete="off" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Entrez votre mot de passe" autocomplete="off" required>
            </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>