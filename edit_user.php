<?php 
// 1. DÉMARRAGE ET SÉCURITÉ : Initialise la session et établit la connexion BDD via $pdo
session_start();
include('config.php');

// Vérification de sécurité : Si l'utilisateur n'est pas connecté, retour à la page de login
if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

/**
 * RÉCUPÉRATION DU TICKET
 */
// Vérifie si l'identifiant du ticket est présent dans l'URL (ex: view_ticket.php?id=5)
if(!isset($_GET['id'])) {
    header('Location: tech_dashboard.php');
    exit();
}

$id = $_GET['id'];
// Préparation de la requête pour récupérer les informations du ticket correspondant à l'ID
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id_t = ?");
$stmt->execute([$id]);
$ticket = $stmt->fetch();

// Si le ticket n'existe pas en base de données, redirection vers le dashboard
if(!$ticket) {
    header('Location: tech_dashboard.php');
    exit();
}

/**
 * MISE À JOUR DU STATUT (Traitement du formulaire)
 */
if(isset($_POST['update_status'])){
    $new_status = $_POST['statut'];
    // Mise à jour du champ 'statut' dans la table tickets pour cet identifiant précis
    $stmt = $pdo->prepare("UPDATE tickets SET statut = ? WHERE id_t = ?");
    $stmt->execute([$new_status, $id]);
    
    // Rafraîchissement de la page pour afficher le nouveau statut mis à jour
    header("Location: view_ticket.php?id=$id");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Détails du Ticket - L'Atelier des Jeux</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="atelierlogo.png">
    <style>
        body { font-family: "Raleway", sans-serif; font-size: 16px; }
        .w3-sidebar { z-index: 5; width: 300px; height: 100%; position: fixed; }
        
        /* Styles spécifiques pour l'en-tête mobile (masqué sur bureau) */
        .top-header { 
            display: none; position: fixed; top: 0; left: 0; right: 0; 
            width: 100%; z-index: 4; height: 50px; padding: 0; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); align-items: center; 
            background: #333 !important; color: white;
        }
        .top-header .header-title { flex: 1; text-align: right; font-weight: bold; font-size: 1.1em; padding-right: 15px; }

        /* Configuration Responsive : Tablettes et Mobiles */
        @media screen and (max-width: 768px) {
            .top-header { display: flex !important; }
            .w3-main { margin-left: 0 !important; margin-top: 50px !important; padding-top: 10px; }
            .w3-sidebar { width: 260px !important; display: none; position: fixed !important; box-shadow: 4px 0 10px rgba(0,0,0,0.3); }
            .w3-sidebar.w3-show { display: block !important; }
            .w3-overlay { z-index: 4; }
        }

        /* Configuration Responsive : Écrans larges */
        @media (min-width: 769px) {
            .w3-main { margin-left: 300px !important; }
            .w3-sidebar { display: block !important; }
        }
    </style>
</head>
<body class="w3-light-grey">

<nav class="w3-sidebar w3-collapse w3-white w3-card w3-animate-left" id="mySidebar">
  <div class="w3-container w3-padding-16 w3-blue"><h5><b>L'Atelier des Jeux</b></h5></div>
  <div class="w3-container w3-padding-16">
    <h5>Bienvenue, <b><?php echo htmlspecialchars($_SESSION['nom']); ?></b></h5>
    <span class="w3-tag w3-orange w3-text-white w3-round"><?php echo htmlspecialchars(ucfirst($_SESSION['role'])); ?></span>
  </div>
  <hr>
  <div class="w3-bar-block">
    <a href="tech_dashboard.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
    <a href="profil.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i> Paramètres</a>
  </div>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

<div class="top-header w3-bar w3-top">
  <button class="w3-button w3-hover-none" onclick="w3_open();" style="font-size:1.5em; padding:0 15px;">☰</button>
  <div class="header-title">Technicien</div>
</div>

<div class="w3-main">
    <div class="w3-container" style="padding-top:22px">
        <h3 class="w3-left-align"><b><i class="fa fa-tag"></i> Ticket #<?= htmlspecialchars($ticket['id_t']) ?></b></h3>

        <div class="w3-card w3-white w3-round w3-margin-bottom">
            <header class="w3-container w3-light-grey">
                <h4 class="w3-opacity">Détails de l'incident</h4>
            </header>
            
            <div class="w3-container w3-padding-16">
                <p><strong>Objet :</strong> <?= htmlspecialchars($ticket['titre']) ?></p>
                <p><strong>Catégorie :</strong> <span class="w3-tag w3-light-grey w3-border w3-round"><?= htmlspecialchars($ticket['categorie']) ?></span></p>
                <hr>
                <p><strong>Description :</strong></p>
                <div class="w3-panel w3-pale-blue w3-leftbar w3-border-blue w3-padding">
                    <p><?= nl2br(htmlspecialchars($ticket['description'])) ?></p>
                </div>
                <hr>
                
                <p><strong>Statut actuel :</strong> 
                    <?php 
                        // Logique de coloration du badge selon le statut (match PHP 8)
                        $color = match($ticket['statut']) {
                            'ouvert' => 'w3-red',
                            'en cours' => 'w3-orange',
                            'fermé' => 'w3-green',
                            default => 'w3-blue'
                        };
                    ?>
                    <span class="w3-tag <?= $color ?> w3-round"><?= htmlspecialchars($ticket['statut']) ?></span>
                </p>
                
                <form method="POST" class="w3-padding-16">
                    <div class="w3-section">
                        <label><b>Modifier le statut :</b></label>
                        <select name="statut" class="w3-select w3-border w3-round w3-light-grey">
                            <option value="ouvert" <?= $ticket['statut'] == 'ouvert' ? 'selected' : '' ?>>Ouvert</option>
                            <option value="en cours" <?= $ticket['statut'] == 'en cours' ? 'selected' : '' ?>>En cours</option>
                            <option value="fermé" <?= $ticket['statut'] == 'fermé' ? 'selected' : '' ?>>Fermé</option>
                        </select>
                    </div>
                    <button name="update_status" class="w3-button w3-blue w3-round w3-block">
                        <i class="fa fa-refresh"></i> Mettre à jour le statut
                    </button>
                </form>
            </div>
        </div>

        <a href="tech_dashboard.php" class="w3-button w3-light-grey w3-round w3-border">
            <i class="fa fa-arrow-left"></i> Retour au Dashboard
        </a>
    </div>
</div>

<script>
// Fonctions de contrôle de l'affichage de la barre latérale sur mobile
var mySidebar = document.getElementById("mySidebar");
var overlayBg = document.getElementById("myOverlay");

function w3_open() {
    if (mySidebar.className.indexOf("w3-show") == -1) {
        mySidebar.classList.add('w3-show');
        overlayBg.style.display = "block";
    }
}

function w3_close() {
    mySidebar.classList.remove('w3-show');
    overlayBg.style.display = "none";
}
</script>

</body>
</html>
