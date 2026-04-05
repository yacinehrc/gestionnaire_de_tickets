<?php 
// Démarrage de la session pour accéder aux informations de l'utilisateur connecté
session_start();
// Inclusion de la connexion à la base de données
include('config.php');

/**
 * 1. Sécurité : contrôle d'accès
 * On vérifie si l'utilisateur est connecté et s'il possède le rôle administrateur.
 * Sinon, il est redirigé vers le tableau de bord technicien.
 */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: tech_dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Historique Connexions - L'Atelier des Jeux</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="atelierlogo.png">
    <style>
        body { font-family: "Raleway", sans-serif; font-size: 16px; }
        
        /* Configuration de la barre latérale pour les grands écrans */
        .w3-sidebar { z-index: 5; width: 300px; height: 100%; position: fixed; }
        
        /* Style de l'en-tête mobile (caché par défaut) */
        .top-header { 
            display: none; position: fixed; top: 0; left: 0; right: 0; 
            width: 100%; z-index: 4; height: 50px; padding: 0; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); align-items: center; 
            background: #333 !important; color: white;
        }
        .top-header .header-title { flex: 1; text-align: right; font-weight: bold; font-size: 1.1em; padding-right: 15px; }

        /* Adaptabilité pour les appareils mobiles et tablettes */
        @media screen and (max-width: 768px) {
            .top-header { display: flex !important; }
            .w3-main { margin-left: 0 !important; margin-top: 50px !important; padding-top: 10px; }
            .w3-sidebar { width: 260px !important; display: none; position: fixed !important; box-shadow: 4px 0 10px rgba(0,0,0,0.3); }
            .w3-sidebar.w3-show { display: block !important; }
            .w3-overlay { z-index: 4; }
        }

        /* Affichage pour les écrans d'ordinateurs */
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
    <span class="w3-tag w3-blue w3-round">Administrateur</span>
  </div>
  <hr>
  <div class="w3-bar-block">
    <a href="admin_dashboard.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
    <a href="admin_user.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-users fa-fw"></i> Gestion Comptes</a>
    <a href="log_view.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-history fa-fw"></i> Logs Connexions</a>
    <a href="profil.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i> Paramètres</a>
  </div>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

<div class="top-header w3-bar w3-top">
  <button class="w3-button w3-hover-none" onclick="w3_open();" style="font-size:1.5em; padding:0 15px;">☰</button>
  <div class="header-title">Admin</div>
</div>

<div class="w3-main">
    <div class="w3-container" style="padding-top:22px">
        <h3 class="w3-left-align"><b><i class="fa fa-history"></i> Historique des connexions</b></h3>

        <div class="w3-card w3-white w3-round w3-margin-bottom">
            <header class="w3-container w3-light-grey">
                <h4 class="w3-opacity">Activités récentes</h4>
            </header>
            
            <div class="w3-responsive">
                <table class="w3-table-all w3-hoverable">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>Utilisateur</th>
                            <th>Rôle</th>
                            <th>Dernière activité</th>
                            <th class="w3-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Récupération de tous les utilisateurs triés par date de connexion décroissante
                        $stmt = $pdo->query("SELECT nom, role, derniere_connexion FROM utilisateurs ORDER BY derniere_connexion DESC");
                        
                        while ($user = $stmt->fetch()) {
                            // Transformation de la date SQL en format français (jour/mois/année à heure:minute)
                            $dateStr = ($user['derniere_connexion']) ? (new DateTime($user['derniere_connexion']))->format('d/m/Y à H:i') : "Jamais";
                            
                            // Attribution d'une couleur de badge selon le rôle pour une meilleure lisibilité
                            $badgeColor = match($user['role']) {
                                'admin'      => 'w3-blue',
                                'technicien' => 'w3-orange',
                                default      => 'w3-green',
                            };
                            
                            // Affichage de chaque ligne du tableau avec protection contre les failles XSS
                            echo "<tr>
                                    <td><b>" . htmlspecialchars($user['nom']) . "</b></td>
                                    <td><span class='w3-tag $badgeColor w3-round w3-small'>" . ucfirst($user['role']) . "</span></td>
                                    <td>" . $dateStr . "</td>
                                    <td class='w3-center'><span class='w3-text-green w3-small'><i class='fa fa-circle'></i> Actif</span></td>
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

<script>
var mySidebar = document.getElementById("mySidebar");
var overlayBg = document.getElementById("myOverlay");

// Ouvre la barre latérale
function w3_open() {
    if (mySidebar.className.indexOf("w3-show") == -1) {
        mySidebar.classList.add('w3-show');
        overlayBg.style.display = "block";
    }
}

// Ferme la barre latérale
function w3_close() {
    mySidebar.classList.remove('w3-show');
    overlayBg.style.display = "none";
}
</script>

</body>
</html>
