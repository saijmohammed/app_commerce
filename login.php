<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GamingPlanet</title>
    <link rel="icon" href="photo/7553408.jpg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Arrière-plan avec couleurs et effet néon */
        body {
            background: linear-gradient(45deg, #1a1a2e, #16213e, #0f3460);
            background-attachment: fixed;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        /* Effet de néon en arrière-plan */
        body::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('https://i.imgur.com/dM7Thhn.jpg') no-repeat center center/cover;
            opacity: 0.4; /* Opacité pour éviter trop de contraste */
            z-index: -1;
        }

        /* Formulaire en mode "Glassmorphism" */
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            padding: 30px;
            box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        /* Effet de titre en dégradé */
        .gradient-title {
            background: linear-gradient(90deg, #ff00ff, #00ffff, #ffcc00);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: bold;
            font-size: 28px;
        }

        /* Champs de saisie avec bord lumineux */
        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            border-radius: 5px;
        }

        .form-control:focus {
            border: 2px solid #ff00ff;
            background: rgba(255, 255, 255, 0.3);
            color: white;
            box-shadow: none;
        }

        /* Bouton lumineux */
        .btn-login {
            background: linear-gradient(90deg,rgb(0, 255, 200),rgb(153, 0, 255));
            border: none;
            color: white;
            font-weight: bold;
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: linear-gradient(90deg, #00ffff,rgb(255, 0, 0));
            transform: scale(1.05);
        }

        /* Liens */
        .link {
            color: #00ffff;
            text-decoration: none;
        }

        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h1 class="gradient-title">CONNEXION</h1>
        <?php
        session_start();
        include("conn.php");
        if (isset($_POST['submit'])) {
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);

            $result = mysqli_query($conn, "SELECT * FROM users WHERE Email='$email'") or die(mysqli_error($conn));
            $row = mysqli_fetch_assoc($result);

            if ($row && password_verify($password, $row['motDePasse'])) {
                $_SESSION['id'] = $row['Id'];
                $_SESSION['email'] = $row['Email'];
                $_SESSION['type'] = $row['type'];

                if ($row['type'] === 'admin') {
                    header("Location: admin_page.php");
                } else {
                    header("Location: home.php");
                }
                exit();
            } else {
                echo "<div class='alert alert-danger'>Adresse e-mail ou mot de passe incorrect</div>";
            }
        }
        ?>

        <form action="" method="post">
            <div class="mb-3">
                <input type="text" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
            </div>

            <button type="submit" class="btn btn-login w-100" name="submit">Connexion</button>

            <div class="mt-3">
                <a href="#" class="link">Mot de passe oublié ?</a>
            </div>
            <div class="mt-2">
                Pas encore de compte ? <a href="inscription.php" class="link">Inscrivez-vous</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
