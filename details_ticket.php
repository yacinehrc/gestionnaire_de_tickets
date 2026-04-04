<?php 
include('auth_check.php'); 
include('config.php'); 

// Récupération et vérification de l'ID du ticket
if(!isset($_GET['id'])) { header("Location: gestion_tickets.php"); exit(); }

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id_t = ? AND id_auteur = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
$ticket = $stmt->fetch();

if(!$ticket) { die("Ticket introuvable ou accès refusé."); }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Détails Ticket #<?= $ticket['id_t'] ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="atelierlogo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body, h1, h2, h3, h4, h5, h6 { font-family: "Raleway", sans-serif; }
        .w3-sidebar { z-index: 3; width: 300px; }
        .color-soft-blue { background-color: #2196F3 !important; color: white !important; }
        
        @media (min-width:993px) { 
            .w3-main { margin-left: 300px !important; } 
        }
        
        @media screen and (max-width: 768px) {
            .w3-sidebar { width: 100%; position: relative; margin-bottom: 20px; }
            .w3-main { margin-left: 0 !important; }
            .w3-input, textarea, select, button { width: 100%; box-sizing: border-box; margin: 5px 0; }
        }
    </style>
</head>


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
    <a href="user_ticket.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-dashboard fa-fw"></i> Nouveau Ticket</a>
    <a href="gestion_tickets.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-dashboard fa-fw"></i> Mes tickets</a>
    <a href="profil.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i> Paramètres</a>
  </div>
</nav>


<body class="w3-light-grey">

<div class="w3-main">
        <div class="w3-container w3-padding-32">
            <div class="w3-card-4 w3-white">
                <header class="w3-container w3-blue">
                    <h3>Ticket #<?= htmlspecialchars($ticket['id_t']) ?> - <?= htmlspecialchars($ticket['titre']) ?></h3>
                </header>
                
                <div class="w3-container w3-padding-16">
                    <p><strong>Statut :</strong> <span class="w3-tag w3-round <?= ($ticket['statut'] == 'ouvert') ? 'w3-red' : 'w3-green' ?>"><?= $ticket['statut'] ?></span></p>
                    <p><strong>Catégorie :</strong> <?= htmlspecialchars($ticket['categorie']) ?></p>
                    <p><strong>Date de création :</strong> <?= $ticket['date_creation'] ?></p>
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


</body>
</html>

