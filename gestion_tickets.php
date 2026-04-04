<?php 
include('auth_check.php'); 
include('config.php'); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Mes Tickets - L'Atelier des Jeux</title>
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
    <a href="user_ticket.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-dashboard fa-fw"></i> Nouveau Ticket</a>
    <a href="gestion_tickets.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-ticket fa-fw"></i> Mes tickets</a>
    <a href="profil.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i> Paramètres</a>
  </div>
</nav>


<div class="w3-main">

    <header class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-history"></i> Historique de mes demandes</b></h3>
    </header>


    <div class="w3-container">
        <div class="w3-responsive w3-card w3-white w3-round">
            <table class="w3-table w3-striped w3-hoverable">
                <thead>
    <tr class="w3-blue">
        <th>ID</th>
        <th>Objet</th>
        <th>Statut</th>
        <th>Date</th>
        <th>Action</th> </tr>
</thead>
<tbody>
    <?php
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id_auteur = ? ORDER BY date_creation DESC");
    $stmt->execute([$_SESSION['user_id']]);
    
    while($t = $stmt->fetch()) {
        $statusClass = ($t['statut'] == 'ouvert') ? "w3-red" : (($t['statut'] == 'en cours') ? "w3-orange" : "w3-green");

        echo "<tr>
                <td class='w3-text-grey'>#".htmlspecialchars($t['id_t'])."</td>
                <td>" . htmlspecialchars($t['titre']) . "</td>
                <td><span class='w3-tag $statusClass w3-round' style='font-size:12px;'>".htmlspecialchars($t['statut'])."</span></td>
                <td class='w3-text-grey'>".htmlspecialchars($t['date_creation'])."</td>
                <td>
                    <a href='details_ticket.php?id=".urlencode($t['id_t'])."' class='w3-button w3-light-grey w3-border w3-round w3-small'>
                        <i class='fa fa-eye'></i> Voir
                    </a>
                </td>
            </tr>";
    }
    ?>
</tbody>
            </table>
        </div>
<div style="padding-top: 24px;">
            <?php
            // Détection du rôle pour rediriger vers le bon dashboard
            $role = $_SESSION['role'] ?? 'utilisateur';
            
            if ($role === 'admin') {
                $back_url = "admin_dashboard.php";
            } elseif ($role === 'technicien') {
                $back_url = "tech_dashboard.php";
            } else {
                $back_url = "user_ticket.php";
            }
            ?>
            
           


        <div class="w3-padding-24">
            <a href="user_ticket.php" class="w3-button w3-blue w3-round w3-border">
                <i class="fa fa-plus"></i> Créer un nouveau ticket
            </a>
             <a href="<?php echo $back_url; ?>" class="w3-button w3-light-grey w3-round w3-border">
                <i class="fa fa-arrow-left"></i> Retour au Dashboard
            </a>
        </div>
        </div>
    </div>
</div>


</body>
</html>