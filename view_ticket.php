<?php
session_start();
include('config.php');
if(!isset($_SESSION['user_id'])) header('Location: login.php');

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id_t = ?");
$stmt->execute([$id]);
$ticket = $stmt->fetch();

if(isset($_POST['update_status'])){
    $new_status = $_POST['statut'];
    $stmt = $pdo->prepare("UPDATE tickets SET statut = ? WHERE id_t = ?");
    $stmt->execute([$new_status, $id]);
    header("Location: view_ticket.php?id=$id");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Vue des tickets</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="atelierlogo.png">
    <style>
        /* On s'assure que le fond de la page est gris clair comme sur ta capture */
        body { background-color: #f1f1f1; }
        @media screen and (max-width: 768px) {
            .w3-main { margin-left: 0; }
            .w3-card-4, select, button { width: 100%; box-sizing: border-box; }
            h3 { font-size: 1.3em; }
        }
    </style>
</head>
<body>

<nav class="w3-sidebar w3-collapse w3-white w3-card w3-animate-left" style="z-index:5; width:300px; top:0;" id="mySidebar">
  <div class="w3-container w3-padding-16 w3-blue w3-text-white">
    <h5><b>L'Atelier des Jeux</b></h5>
  </div>
  
  <div class="w3-container w3-padding-16">
    <h5>Bienvenue, <b><?php echo htmlspecialchars($_SESSION['nom']); ?></b></h5>
    <span class="w3-tag w3-orange w3-text-white w3-round">Technicien</span>
  </div>
  <hr>
  <div class="w3-bar-block">
    <a href="#" onclick="w3_close()" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey"><i class="fa fa-remove fa-fw"></i> Fermer</a>
    
    <!-- <a href="tech_dashboard.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-ticket fa-fw"></i> Tous les Tickets</a> -->
    <a href="profil.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i> Paramètres</a>
   
  </div>
</nav>

<div class="w3-main" style="margin-left:300px" class="w3-container w3-blue w3-padding-16">

    

    <div class="w3-container w3-padding-32">
        <div class="w3-card-4 w3-white">
            <header class="w3-container w3-blue">
                <h3>Ticket #<?= $ticket['id_t'] ?></h3>
            </header>
            
            <div class="w3-container w3-padding-16">
                <p><strong>Objet :</strong> <?= $ticket['titre'] ?></p>
                <p><strong>Catégorie :</strong> <?= $ticket['categorie'] ?></p>
                <hr>
                <p><strong>Description :</strong></p>
                <div class="w3-panel w3-light-grey w3-leftbar w3-padding">
                    <p><?= nl2br($ticket['description']) ?></p>
                </div>
                <hr>
                <p><strong>Statut actuel :</strong> <span class="w3-tag w3-blue w3-round"><?= $ticket['statut'] ?></span></p>
                
                <form method="POST" class="w3-padding-16">
                    <label>Modifier le statut :</label>
                    <select name="statut" class="w3-select w3-border w3-margin-top">
                        <option value="ouvert" <?= $ticket['statut'] == 'ouvert' ? 'selected' : '' ?>>Ouvert</option>
                        <option value="en cours" <?= $ticket['statut'] == 'en cours' ? 'selected' : '' ?>>En cours</option>
                        <option value="fermé" <?= $ticket['statut'] == 'fermé' ? 'selected' : '' ?>>Fermé</option>
                    </select>
                    <button name="update_status" class="w3-button w3-blue w3-margin-top w3-round">
                        <i class="fa fa-refresh"></i> Mettre à jour
                    </button>
                </form>
            </div>
        </div>

        <div class="w3-padding-16">
            <a href="tech_dashboard.php" class="w3-button w3-light-grey w3-round w3-border">
                <i class="fa fa-arrow-left"></i> Retour au Dashboard
            </a>
        </div>
    </div>
</div>

</body>
</html>