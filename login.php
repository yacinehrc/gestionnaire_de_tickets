<?php
// Pour afficher les erreurs de connexion à la base de données ou autres problèmes, on active l'affichage des erreurs en développement
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('config.php');
$error = "";

if (isset($_POST['connexion'])) {
    // On utilise trim() pour enlever les espaces accidentels avant/après le texte
    $nom = trim($_POST['username']); 
    $pass = md5($_POST['password']); 

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE nom = ? AND password = ?");
    $stmt->execute([$nom, $pass]);
    $user = $stmt->fetch();

    if ($user) {
        if ($user['role'] == 'inactif') {
            $error = "Compte inactif (plus de 1 mois sans connexion).";
        } else {
            // Mise à jour de la date pour la règle de sécurité des 1 mois
            $pdo->prepare("UPDATE utilisateurs SET derniere_connexion = NOW() WHERE id_u = ?")->execute([$user['id_u']]);
            
            $_SESSION['user_id'] = $user['id_u'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nom'] = $user['nom'];
            
            header('Location: index.php');
            exit();
        }
    } else {
        // --- BLOC DE TEST : À SUPPRIMER UNE FOIS QUE ÇA MARCHE ---
        $check = $pdo->prepare("SELECT * FROM utilisateurs WHERE nom = ?");
        $check->execute([$nom]);
        if (!$check->fetch()) {
            $error = "L'utilisateur '$nom' n'existe pas dans la base.";
        } else {
            $error = "Mot de passe incorrect pour '$nom'.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion - L'Atelier des Jeux</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="icon" type="image/x-icon" href="atelierlogo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body, h1, h2 { font-family: "Raleway", sans-serif; }
        @media screen and (max-width: 768px) {
            .w3-display-middle { padding: 10px; }
            .w3-card-4 { margin: 10px; }
            .w3-input, .w3-button { width: 100%; box-sizing: border-box; margin: 5px 0; }
            h3 { font-size: 1.4em; }
        }
    </style>

</head>
<body class="w3-dark-grey">
    <div class="w3-display-middle" style="width:100%; max-width:400px; padding:16px;">
        <div class="w3-card-4 w3-white w3-round-large">
            <div class="w3-container w3-blue w3-round-large w3-center">
                <h3>Atelier des Jeux - Support Service</h3>
                <p>Connectez-vous pour accéder à votre espace de support</p>

            </div>
            <form class="w3-container w3-padding-24" method="POST">
                <?php if($error) echo "<p class='w3-text-red'>$error</p>"; ?>
                <label>Nom d'utilisateur</label>
                <input class="w3-input w3-border w3-round" type="text" name="username" required>
                <br>
                <label>Mot de passe</label>
                <input class="w3-input w3-border w3-round" type="password" name="password" required>
                <button class="w3-button w3-block w3-blue w3-section w3-round" name="connexion">Se connecter</button>
                <p class="w3-center w3-small"><a href="register.php">Créer un compte</a></p>
            </form>
        </div>
    </div>
</body>
</html>
