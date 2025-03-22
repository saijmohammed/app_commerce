<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - GamingPlanet</title>
    <link rel="icon" href="photo/7553408.jpg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Arrière-plan avec effet néon */
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

        /* Image en arrière-plan */
        body::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('https://i.imgur.com/dM7Thhn.jpg') no-repeat center center/cover;
            opacity: 0.4;
            z-index: -1;
        }

        /* Formulaire en mode Glassmorphism */
        .register-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            padding: 30px;
            box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        /* Effet de titre dégradé */
        .gradient-title {
            background: linear-gradient(90deg, #ff00ff, #00ffff, #ffcc00);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: bold;
            font-size: 28px;
        }

        /* Champs de saisie avec effet lumineux */
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

        /* Messages d'erreur */
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }

        /* Boutons */
        .btn-custom {
            background: linear-gradient(90deg,rgb(195, 0, 255), #00ffff);
            border: none;
            color: white;
            font-weight: bold;
            padding: 10px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: linear-gradient(90deg, #00ffff,rgb(255, 0, 0));
            transform: scale(1.05);
        }

        /* Alignement des boutons */
        #but {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        #but button, #but a {
            flex: 1;
            height: 50px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <div class="register-container">
        <h1 class="gradient-title">INSCRIPTION</h1>
        
        <?php
        session_start();
        include("conn.php");

        $usernameErr = $emailErr = "";

        if (isset($_POST['submit'])) {
            $nom_utilisateur = $_POST['username'];
            $email = $_POST['email'];
            $motDePasse = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $adresse = $_POST['adresse'];
            $numero_telephone = $_POST['numero_telephone'];
            $type = 'user'; 

            // Vérifier si le nom d'utilisateur existe déjà
            $resultUsername = mysqli_query($conn, "SELECT * FROM users WHERE Username='$nom_utilisateur'");
            if (mysqli_num_rows($resultUsername) > 0) {
                $usernameErr = "Le nom d'utilisateur existe déjà.";
            }

            // Vérifier si l'email est valide et n'existe pas déjà
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

                echo "<div class='alert alert-success'>Inscription réussie !</div>";
            }
        }
        ?>

        <form action="" method="post">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Nom d'utilisateur" required>
                <span class="error-message"><?php echo $usernameErr; ?></span>
            </div>
            <div class="mb-3">
                <input type="text" name="adresse" class="form-control" placeholder="Adresse" required>
            </div>
            <div class="mb-3">
                <input type="number" name="numero_telephone" class="form-control" value="212" required>
            </div>
            <div class="mb-3">
                <input type="text" name="email" class="form-control" placeholder="E-mail" required>
                <span class="error-message"><?php echo $emailErr; ?></span>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
            </div>

            <div class="mb-3" id="but">
                <button type="submit" class="btn-custom w-100" name="submit">S'inscrire</button>
                <a href="login.php" class="btn-custom">Se connecter</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
