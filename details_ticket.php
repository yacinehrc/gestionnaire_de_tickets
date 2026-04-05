<?php 
// 1. SÉCURITÉ : Vérifie si l'utilisateur est bien connecté via le script de contrôle
include('auth_check.php'); 
// 2. CONNEXION : Initialise l'accès à la base de données via l'objet $pdo
include('config.php'); 

/**
 * RÉCUPÉRATION ET SÉCURISATION DES DONNÉES
 */
// Si l'ID du ticket n'est pas présent dans l'URL, redirection immédiate
if(!isset($_GET['id'])) { 
    header("Location: gestion_tickets.php"); 
    exit(); 
}

// Requête préparée pour éviter les injections SQL
// On filtre par ID du ticket ET par ID de l'auteur pour que l'utilisateur ne voie que ses propres tickets
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id_t = ? AND id_auteur = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
$ticket = $stmt->fetch();

// Si aucun ticket ne correspond (ID inexistant ou tentative d'accès au ticket d'autrui)
if(!$ticket) { 
    die("Ticket introuvable ou accès refusé."); 
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Détails Ticket #<?= $ticket['id_t'] ?> - L'Atelier des Jeux</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="atelierlogo.png">
    <style>
        body { font-family: "Raleway", sans-serif; font-size: 16px; }
        
        /* Sidebar : Fixée à gauche sur les écrans larges */
        .w3-sidebar { z-index: 5; width: 300px; height: 100%; position: fixed; }
        
        /* Header mobile : Caché par défaut, s'affiche uniquement sur petit écran */
        .top-header { 
            display: none; position: fixed; top: 0; left: 0; right: 0; 
            width: 100%; z-index: 4; height: 50px; padding: 0; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); align-items: center; 
            background: #333 !important; color: white;
        }
        .top-header .header-title { flex: 1; text-align: right; font-weight: bold; font-size: 1.1em; padding-right: 15px; }

        /* RESPONSIVE : Configuration pour mobiles (max 768px) */
        @media screen and (max-width: 768px) {
            .top-header { display: flex !important; }
            .w3-main { margin-left: 0 !important; margin-top: 50px !important; padding-top: 10px; }
            
            .w3-sidebar { 
                width: 260px !important; 
                display: none; 
                position: fixed !important; 
                box-shadow: 4px 0 10px rgba(0,0,0,0.3);
            }
            .w3-sidebar.w3-show { display: block !important; }
            .w3-overlay { z-index: 4; }
        }

        /* RESPONSIVE : Configuration pour ordinateurs (min 769px) */
        @media (min-width: 769px) {
            .top-header { display: none !important; }
            .w3-main { margin-left: 300px !important; padding-top: 0; }
            .w3-sidebar { display: block !important; width: 300px; }
        }
    </style>
</head>
<body class="w3-light-grey">

<nav class="w3-sidebar w3-collapse w3-white w3-card w3-animate-left" id="mySidebar">
  <div class="w3-container w3-padding-16 w3-blue">
    <h5><b>L'Atelier des Jeux</b></h5>
  </div>
  <div class="w3-container w3-padding-16">
    <h5>Bienvenue, <b><?php echo htmlspecialchars($_SESSION['nom']); ?></b></h5>
    <span class="w3-tag w3-green w3-round">Utilisateur</span>
  </div>
  <hr>
  <div class="w3-bar-block">
    <a href="user_ticket.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-plus fa-fw"></i> Nouveau Ticket</a>
    <a href="gestion_tickets.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-list fa-fw"></i> Mes tickets</a>
    <a href="profil.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i> Paramètres</a>
  </div>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

<div class="top-header w3-bar w3-top">
  <button class="w3-button w3-hover-none" onclick="w3_open();" style="font-size:1.5em; padding:0 15px;">☰</button>
  <div class="header-title">
    <?php echo htmlspecialchars($_SESSION['nom']); ?>
  </div>
</div>

<div class="w3-main">
    <header class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-info-circle"></i> Détails de la demande</b></h3>
    </header>

    <div class="w3-container">
        <div class="w3-card w3-white w3-round w3-margin-bottom">
            <header class="w3-container w3-light-grey w3-round">
                <h4>Ticket #<?= htmlspecialchars($ticket['id_t']) ?> - <?= htmlspecialchars($ticket['titre']) ?></h4>
            </header>
            
            <div class="w3-container w3-padding-16">
                <?php 
                    // Définition dynamique de la couleur du badge en fonction du statut
                    $statusClass = ($ticket['statut'] == 'ouvert') ? "w3-red" : (($ticket['statut'] == 'en cours') ? "w3-orange" : "w3-green");
                ?>
                <p><strong>Statut :</strong> <span class="w3-tag <?= $statusClass ?> w3-round"><?= htmlspecialchars($ticket['statut']) ?></span></p>
                <p><strong>Catégorie :</strong> <?= htmlspecialchars($ticket['categorie']) ?></p>
                <p><strong>Date de création :</strong> <?= htmlspecialchars($ticket['date_creation']) ?></p>
                <hr>
                <h5><b>Description :</b></h5>
                <div class="w3-panel w3-light-grey w3-leftbar w3-padding">
                    <?= nl2br(htmlspecialchars($ticket['description'])) ?>
                </div>
            </div>
        </div>

        <div class="w3-padding-16">
            <a href="gestion_tickets.php" class="w3-button w3-light-grey w3-round w3-border">
                <i class="fa fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>

<script>
// Gestion de l'interactivité de la sidebar sur mobile
var mySidebar = document.getElementById("mySidebar");
var overlayBg = document.getElementById("myOverlay");

function w3_open() {
    mySidebar.classList.add('w3-show');
    overlayBg.style.display = "block";
}

function w3_close() {
    mySidebar.classList.remove('w3-show');
    overlayBg.style.display = "none";
}
</script>

</body>
</html>
