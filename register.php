<?php
// Inclusion du fichier de configuration pour la connexion à la base de données
include('config.php');

// Initialisation des variables pour le contrôle de l'affichage de la modale de succès
$show_modal = false;
$generated_username = "";

// Vérification si le formulaire d'inscription a été soumis
if(isset($_POST['reg'])){
    // Sécurisation des entrées utilisateur contre les failles XSS
    $nom = htmlspecialchars($_POST['nom_famille']);
    $prenom = htmlspecialchars($_POST['prenom']);
    
    /**
     * GÉNÉRATION AUTOMATIQUE DE L'IDENTIFIANT
     * Règle : Première lettre du prénom + nom de famille, le tout en minuscules.
     * Exemple : Alice Martin devient 'amartin'
     */
    $generated_username = strtolower(substr($prenom, 0, 1) . $nom);
    
    // Hachage du mot de passe (MD5 utilisé ici pour correspondre au système existant)
    $pass = md5($_POST['password']);
    
    // Préparation de la requête d'insertion (le rôle est fixé à 'utilisateur' par défaut)
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, password, role) VALUES (?, ?, ?, 'utilisateur')");
    
    // Si l'insertion réussit, on active l'affichage de la fenêtre modale
    if($stmt->execute([$generated_username, $_POST['email'], $pass])) {
        $show_modal = true; 
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Inscription - L'Atelier des Jeux</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="atelierlogo.png">
    <style>
        body, h1, h2, h3, h4 { font-family: "Raleway", sans-serif; }
        
        /* Animation visuelle pour mettre en avant l'identifiant généré */
        .pulse-username {
            display: inline-block;
            animation: pulse-animation 2s infinite;
            border: 2px solid #2196F3;
            padding: 10px 20px;
            background-color: #f1f1f1;
        }

        @keyframes pulse-animation {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(33, 150, 243, 0.4); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(33, 150, 243, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(33, 150, 243, 0); }
        }

        /* Responsive : Ajustements pour les petits écrans (mobiles) */
        @media screen and (max-width: 600px) {
            .w3-display-middle {
                position: relative;
                top: 20px;
                left: 0;
                transform: none;
                margin: auto;
                width: 95% !important;
            }
            .mobile-full {
                width: 100% !important;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body class="w3-dark-grey">

    <div class="w3-display-middle" style="width:100%; max-width:550px; padding:16px;">
        <div class="w3-card-4 w3-white w3-round-large">
            <header class="w3-container w3-blue w3-round-large w3-center w3-padding-16">
                <h3 style="margin:0"><b>Créer un compte</b></h3>
            </header>
            
            <form method="POST" class="w3-container w3-padding-24">
                <div class="w3-row-padding" style="margin:0 -16px;">
                    <div class="w3-half mobile-full">
                        <label><b>Prénom</b></label>
                        <input class="w3-input w3-border w3-round" type="text" name="prenom" placeholder="Alice" required>
                    </div>
                    <div class="w3-half mobile-full">
                        <label><b>Nom</b></label>
                        <input class="w3-input w3-border w3-round" type="text" name="nom_famille" placeholder="Martin" required>
                    </div>
                </div>

                <div class="w3-section">
                    <label><b>Email</b></label>
                    <input class="w3-input w3-border w3-round" type="email" name="email" required>
                </div>
                
                <div class="w3-section">
                    <label><b>Mot de passe</b></label>
                    <input class="w3-input w3-border w3-round" type="password" name="password" required>
                </div>
                
                <button class="w3-button w3-block w3-blue w3-round w3-padding-large" name="reg">
                    <b>S'INSCRIRE</b>
                </button>
                
                <div class="w3-center w3-padding-16">
                    <a href="login.php" class="w3-text-grey" style="text-decoration:none">Déjà inscrit ? Se connecter</a>
                </div>
            </form>
        </div>
    </div>

    <?php if($show_modal): ?>
    <div id="successModal" class="w3-modal" style="display:block">
        <div class="w3-modal-content w3-card-4 w3-animate-zoom w3-round-large" style="max-width:450px">
            <header class="w3-container w3-green w3-center w3-round-large w3-padding-16">
                <h3><i class="fa fa-check-circle"></i> Inscription réussie !</h3>
            </header>
            
            <div class="w3-container w3-padding-32 w3-center">
                <p>Bienvenue à L'Atelier des Jeux.</p>
                <p>Voici votre identifiant de connexion (à conserver) :</p>
                
                <div class="w3-margin-top w3-margin-bottom">
                    <h4 class="pulse-username w3-round w3-text-blue">
                        <b><?php echo $generated_username; ?></b>
                    </h4>
                </div>
                
                <div class="w3-padding-16">
                    <a href="login.php" class="w3-button w3-blue w3-round w3-block w3-large">
                        <b>C'EST PARTI !</b>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</body>
</html>
