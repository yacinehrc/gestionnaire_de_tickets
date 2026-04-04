<?php 
include('auth_check.php'); 

// Sécurité : Seul l'admin peut voir les logs
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Historique des Connexions - L'Atelier des Jeux</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="atelierlogo.png">
    <style>
        html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
        @media screen and (max-width: 768px) {
            .w3-main { margin-left: 0 !important; }
            .w3-table { font-size: 0.9em; }
        }
    </style>
</head>
<body class="w3-light-grey">

<nav class="w3-sidebar w3-collapse w3-white w3-card w3-animate-left" style="z-index:5; width:300px; top:0;" id="mySidebar">
  <div class="w3-container w3-padding-16 w3-blue">
    <h5><b>L'Atelier des Jeux</b></h5>
  </div>
  <div class="w3-container w3-padding-16">
    <h5>Bienvenue, <b>Admin</b></h5>
    <span class="w3-tag w3-blue w3-round">Administrateur</span>
  </div>
  <hr>
  <div class="w3-bar-block">
    <a href="admin_dashboard.php" class="w3-bar-item w3-button w3-padding "><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
    <a href="admin_user.php" class="w3-bar-item w3-button w3-padding "><i class="fa fa-users fa-fw"></i> Gestion Comptes</a>
    <a href="log_view.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-history fa-fw"></i> Logs Connexions</a>
    <a href="profil.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i> Paramètres</a>
    
  </div>
</nav>

<div class="w3-main" style="margin-left:300px;">
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-history"></i> Historique complet des connexions</b></h5>
  </header>

  <div class="w3-container w3-margin-bottom">
    <div class="w3-white w3-card-4 w3-round">
        <div class="w3-responsive">
            <table class="w3-table w3-striped w3-hoverable">
              <thead>
                <tr class="w3-blue">
                  <th>Utilisateur</th>
                  <th>Rôle</th>
                  <th>Dernière activité</th>
                  <th>Statut</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // On récupère tous les utilisateurs triés par la connexion la plus récente
                $stmt = $pdo->query("SELECT nom, role, derniere_connexion FROM utilisateurs ORDER BY derniere_connexion DESC");
                while ($user = $stmt->fetch()) {
                    $date = new DateTime($user['derniere_connexion']);
                    // Définition de la couleur selon le rôle ou l'état
                      if ($user['role'] == 'inactif') {
                          $color = 'w3-red';
                      } elseif ($user['role'] == 'admin') {
                          $color = 'w3-blue';
                      } elseif ($user['role'] == 'technicien') {
                          $color = 'w3-orange';
                      } else {
                          $color = 'w3-green'; // Pour 'utilisateur'
                      }
                    echo "<tr>
                            <td><b>" . htmlspecialchars($user['nom']) . "</b></td>
                            <td><span class='w3-tag $color w3-round'>" . ucfirst($user['role']) . "</span></td>
                            <td>" . $date->format('d/m/Y à H:i') . "</td>
                            <td><i class='fa fa-circle w3-text-green'></i> Enregistré</td>
                          </tr>";
                }
                ?>
              </tbody>
            </table>
        </div>
    </div>
    <div class="w3-padding-16">
            <a href="admin_dashboard.php" class="w3-button w3-light-grey w3-round w3-border">
                <i class="fa fa-arrow-left"></i> Retour au Dashboard
            </a>
        </div>
  </div>
</div>

</body>
</html>