<?php
// Vérification de l'authentification et de la connexion à la base de données
include('auth_check.php');
include('config.php');

// Vérification que l'utilisateur est bien un administrateur
// Si ce n'est pas le cas, il est redirigé vers le tableau de bord technicien
if ($_SESSION['role'] !== 'admin') {
    header('Location: tech_dashboard.php');
    exit();
}

// Récupération du nombre d'utilisateurs par rôle via des requêtes SQL
$nb_admin = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role='admin'")->fetchColumn();
$nb_tech  = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role='technicien'")->fetchColumn();
$nb_user  = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role='utilisateur'")->fetchColumn();

// Calcul du total des utilisateurs enregistrés
$total_users = $nb_admin + $nb_tech + $nb_user;

// Prévention de la division par zéro pour le calcul des pourcentages
$total_div = ($total_users == 0) ? 1 : $total_users;

// Calcul des pourcentages de répartition pour les barres de progression
$p_admin = ($nb_admin / $total_div) * 100;
$p_tech  = ($nb_tech / $total_div) * 100;
$p_user  = ($nb_user / $total_div) * 100;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Admin - Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="atelierlogo.png">
    <style>
        body { font-family: "Raleway", sans-serif; font-size: 16px; }
        
        /* Configuration de la barre latérale fixe */
        .w3-sidebar { z-index: 5; width: 300px; height: 100%; position: fixed; }
        
        /* Style de l'en-tête pour les appareils mobiles */
        .top-header { 
            display: none; position: fixed; top: 0; left: 0; right: 0; 
            width: 100%; z-index: 4; height: 50px; padding: 0; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); align-items: center; 
            background: #333 !important; color: white;
        }
        .top-header .header-title { flex: 1; text-align: right; font-weight: bold; font-size: 1.1em; padding-right: 15px; }

        /* Adaptabilité pour tablettes et smartphones */
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

        /* Affichage standard pour les écrans d'ordinateurs */
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
    <span class="w3-tag w3-blue w3-round">Administrateur</span>
  </div>
  <hr>
  <div class="w3-bar-block">
    <a href="admin_dashboard.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
    <a href="admin_user.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-users fa-fw"></i> Gestion Comptes</a>
    <a href="log_view.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-history fa-fw"></i> Logs Connexions</a>
    <a href="profil.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i> Paramètres</a>
  </div>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

<div class="top-header w3-bar w3-top">
  <button class="w3-button w3-hover-none" onclick="w3_open();" style="font-size:1.5em; padding:0 15px;">☰</button>
  <div class="header-title">Admin</div>
</div>

<div class="w3-main">
    <header class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-dashboard"></i> Administration du Système</b></h5>
    </header>
    
    <div class="w3-row-padding">
        <div class="w3-half w3-margin-bottom">
            <a href="admin_user.php" style="text-decoration:none">
                <div class="w3-container w3-blue w3-padding-16 w3-card w3-round w3-hover-opacity">
                    <div class="w3-left"><i class="fa fa-user-plus w3-xxlarge"></i></div>
                    <div class="w3-right"><h3><?php echo $total_users; ?></h3></div>
                    <div class="w3-clear"></div>
                    <h4>Gérer les comptes</h4>
                </div>
            </a>
        </div>
        <div class="w3-half w3-margin-bottom">
            <a href="log_view.php" style="text-decoration:none">
                <div class="w3-container w3-dark-grey w3-padding-16 w3-card w3-round w3-hover-opacity">
                    <div class="w3-left"><i class="fa fa-eye w3-xxlarge"></i></div>
                    <div class="w3-right"><h3>Logs</h3></div>
                    <div class="w3-clear"></div>
                    <h4>Historique connexions</h4>
                </div>
            </a>
        </div>
    </div>

    <div class="w3-container w3-margin-bottom">
        <div class="w3-white w3-card w3-round w3-padding-16">
            <h5 class="w3-padding-small"><b><i class="fa fa-users"></i> Répartition des utilisateurs</b></h5>
            <div class="w3-row-padding">
                <div class="w3-col s12 m4">
                    <p>Admins</p>
                    <div class="w3-grey w3-round">
                        <div class="w3-container w3-round w3-indigo" style="width:<?php echo $p_admin; ?>%">&nbsp;</div>
                    </div>
                </div>
                <div class="w3-col s12 m4">
                    <p>Techniciens</p>
                    <div class="w3-grey w3-round">
                        <div class="w3-container w3-round w3-orange" style="width:<?php echo $p_tech; ?>%">&nbsp;</div>
                    </div>
                </div>
                <div class="w3-col s12 m4">
                    <p>Utilisateurs</p>
                    <div class="w3-grey w3-round">
                        <div class="w3-container w3-round w3-green" style="width:<?php echo $p_user; ?>%">&nbsp;</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w3-panel">
        <div class="w3-card w3-white w3-round">
            <header class="w3-container w3-light-grey w3-round">
                <h5><i class="fa fa-clock-o"></i> Activité récente</h5>
            </header>
            <ul class="w3-ul w3-hoverable">
                <?php
                // Requête pour récupérer les derniers utilisateurs connectés
                $stmt = $pdo->query("SELECT nom, role, derniere_connexion FROM utilisateurs ORDER BY derniere_connexion DESC LIMIT 5");
                while ($user = $stmt->fetch()) {
                    // Formattage de l'heure ou affichage d'un tiret si aucune donnée
                    $heure = $user['derniere_connexion'] ? date('H:i', strtotime($user['derniere_connexion'])) : "--:--";
                    echo "<li class='w3-padding-16'>
                            <span class='w3-large'>" . htmlspecialchars($user['nom']) . "</span>
                            <span class='w3-tag w3-small w3-light-grey w3-margin-left'>" . htmlspecialchars($user['role']) . "</span>
                            <span class='w3-right w3-text-grey'>$heure</span>
                          </li>";
                }
                ?>
            </ul>
        </div>
    </div>
</div> 

<script>
var mySidebar = document.getElementById("mySidebar");
var overlayBg = document.getElementById("myOverlay");

// Fonction pour afficher la barre latérale
function w3_open() {
    if (mySidebar.className.indexOf("w3-show") == -1) {
        mySidebar.classList.add('w3-show');
        overlayBg.style.display = "block";
    }
}

// Fonction pour masquer la barre latérale
function w3_close() {
    mySidebar.classList.remove('w3-show');
    overlayBg.style.display = "none";
}
</script>

</body>
</html>
