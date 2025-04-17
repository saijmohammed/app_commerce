<?php
session_start();
include("conn.php");

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // Ne pas échapper ici car password_verify n'utilise pas SQL

    // Vérifier si l'utilisateur existe
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);

    if ($row) { 
        $motDePass = $row['motDePasse']; // Mot de passe haché depuis la base de données
        // Comparer le mot de passe saisi avec le haché
        if (password_verify($password, $motDePass)) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['type'] = $row['type']; 

            // Redirection selon le type d'utilisateur
            if ($row['type'] === 'admin') {
                header("Location: admin_page.php");
            } elseif ($row['type'] === 'vendeur') {
                header("Location: vendeur_page.php");
            } elseif ($row['type'] === 'fournisseur') {
                $_SESSION['fournisseur_id'] = $row['id']; // Ajoutez cette ligne pour définir la session du fournisseur
                header("Location: fournisseur.php");
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
    background: linear-gradient(135deg,rgb(6, 22, 85),rgb(90, 24, 156));
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Poppins', sans-serif;
}

.container {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

.card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    box-shadow: 0px 10px 30px rgba(180, 65, 65, 0.3);
    padding: 30px;
    max-width: 400px;
    width: 100%;
    color: white;
    text-align: center;
}

.gradient-title {
    background-image: linear-gradient(to right,rgb(63, 160, 79), #2BD2FF, #2BFF88);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-weight: bold;
}

.form-label {
    color: white;
    font-weight: 500;
}

.form-control {
    background: rgba(94, 17, 17, 0.2);
    border: none;
    border-radius: 10px;
    color: white;
    padding: 12px;
}

.form-control::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.btn-primary {
    background: linear-gradient(to right,rgb(112, 79, 87), #FF4B2B);
    border: none;
    padding: 12px;
    font-size: 16px;
    border-radius: 25px;
    transition: 0.3s;
}

.btn-primary:hover {
    opacity: 0.8;
    transform: scale(1.05);
}

.text-center a {
    color: #FF8C00;
    font-weight: 500;
    text-decoration: none;
    transition: 0.3s;
}

.text-center a:hover {
    text-decoration: underline;
}

    </style>
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="container">
        <div class="card mx-auto p-4 custom-form">
            <div class="card-body">
            <?php
session_start();
include("conn.php");

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // Ne pas échapper ici car password_verify n'utilise pas SQL

    // Vérifier si l'utilisateur existe
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);

    if ($row) { 
        $motDePass = $row['motDePasse']; // Mot de passe haché depuis la base de données
        // Comparer le mot de passe saisi avec le haché
        if (password_verify($password, $motDePass)) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['type'] = $row['type']; 

            // Redirection selon le type d'utilisateur
            if ($row['type'] === 'admin') {
                header("Location: admin_page.php");
           }elseif($row['type'] === 'vendeur'){
                header("Location: vendeur_page.php");
            }elseif($row['type'] === 'fournisseur'){
                header("Location: fournisseur.php");
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

                <h1 class="card-title text-center mb-4 gradient-title">CONNEXION</h1>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" name="email" id="email" class="form-control" autocomplete="off" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control" autocomplete="off" required>
                    </div>

                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary btn-lg" name="submit">connexion</button>
                    </div>
                    <div class="text-center">
                        Pas encore de compte ? <a href="inscription.php">Inscrivez-vous</a>
                    </div>
                    <div class="text-center mt-3">
                        <a href="reset_password.php">Mot de passe oublié ?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>