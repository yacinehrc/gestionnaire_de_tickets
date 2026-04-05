<?php
// Vérification de la session et de l'inactivité (Inclusion des scripts de sécurité et connexion BDD)
include('auth_check.php');
include('config.php');

/**
 * 1. SÉCURITÉ : RESTRICTION D'ACCÈS
 * Empêche les utilisateurs avec le rôle 'utilisateur' d'accéder à cette page réservée aux techniciens.
 */
if ($_SESSION['role'] == 'utilisateur') {
    header('Location: user_ticket.php');
    exit();
}

/**
 * 2. CALCUL DES STATISTIQUES DE TICKETS
 * Récupération du nombre de tickets par statut via des requêtes SQL COUNT.
 */
$ouvert   = $pdo->query("SELECT COUNT(*) FROM tickets WHERE statut='ouvert'")->fetchColumn();
$en_cours = $pdo->query("SELECT COUNT(*) FROM tickets WHERE statut='en cours'")->fetchColumn();
$ferme    = $pdo->query("SELECT COUNT(*) FROM tickets WHERE statut='fermé'")->fetchColumn();

// Calcul du total pour éviter une division par zéro lors du calcul des pourcentages
$total = $ouvert + $en_cours + $ferme;
$total_div = ($total == 0) ? 1 : $total;

// Calcul des pourcentages pour l'affichage des barres de progression
$p_ouvert  = ($ouvert / $total_div) * 100;
$p_encours = ($en_cours / $total_div) * 100;
$p_ferme   = ($ferme / $total_div) * 100;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Technicien - Support</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="atelierlogo.png">
    <style>
        /* Styles personnalisés pour la typographie et la structure Sidebar/Main */
        body { font-family: "Raleway", sans-serif; font-size: 16px; }
        .w3-sidebar { z-index: 5; width: 300px; height: 100%; position: fixed; }
        
        /* Header spécifique pour l'affichage mobile */
        .top-header { 
            display: none; position: fixed; top: 0; left: 0; right: 0; 
            width: 100%; z-index: 4; height: 50px; padding: 0; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); align-items: center; 
            background: #333 !important; color: white;
        }
        .top-header .header-title { flex: 1; text-align: right; font-weight: bold; font-size: 1.1em; padding-right: 15px; }

        /* Responsive : Gestion de l'affichage sur tablettes et mobiles */
        @media screen and (max-width: 768px) {
            .top-header { display: flex !important; }
            .w3-main { margin-left: 0 !important; margin-top: 50px !important; padding-top: 10px; }
            .w3-sidebar { width: 260px !important; display: none; position: fixed !important; box-shadow: 4px 0 10px rgba(0,0,0,0.3); }
            .w3-sidebar.w3-show { display: block !important; }
        }

        /* Responsive : Gestion de l'affichage sur ordinateurs */
        @media (min-width: 769px) {
            .w3-main { margin-left: 300px !important; }
            .w3-sidebar { display: block !important; }
        }
    </style>
</head>
<body class="w3-light-grey">

<nav class="w3-sidebar w3-collapse w3-white w3-card w3-animate-left" id="mySidebar">
  <div class="w3-container w3-padding-16 w3-blue w3-text-white">
    <h5><b>L'Atelier des Jeux</b></h5>
  </div>
  <div class="w3-container w3-padding-16">
    <h5>Bienvenue, <b><?php echo htmlspecialchars($_SESSION['nom']); ?></b></h5>
    <span class="w3-tag w3-orange w3-text-white w3-round">Technicien</span>
  </div>
  <hr>
  <div class="w3-bar-block">
    <a href="tech_dashboard.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
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
        <h3 class="w3-left-align"><b><i class="fa fa-dashboard"></i> Gestion des tickets</b></h3>
        
        <div class="w3-card w3-white w3-round w3-margin-bottom">
            <header class="w3-container w3-light-grey">
                <h4 class="w3-opacity">Aperçu des tickets</h4>
            </header>
            <div class="w3-row-padding w3-padding-16">
                <div class="w3-col s12 m4">
                    <p>Ouverts (<?php echo $ouvert; ?>)</p>
                    <div class="w3-light-grey w3-round w3-border w3-small" style="height:14px;">
                        <div class="w3-container w3-round w3-red" style="width:<?php echo $p_ouvert; ?>%; height:12px; padding:0;"></div>
                    </div>
                </div>
                <div class="w3-col s12 m4">
                    <p>En cours (<?php echo $en_cours; ?>)</p>
                    <div class="w3-light-grey w3-round w3-border w3-small" style="height:14px;">
                        <div class="w3-container w3-round w3-orange" style="width:<?php echo $p_encours; ?>%; height:12px; padding:0;"></div>
                    </div>
                </div>
                <div class="w3-col s12 m4">
                    <p>Fermés (<?php echo $ferme; ?>)</p>
                    <div class="w3-light-grey w3-round w3-border w3-small" style="height:14px;">
                        <div class="w3-container w3-round w3-green" style="width:<?php echo $p_ferme; ?>%; height:12px; padding:0;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w3-card w3-white w3-round">
            <header class="w3-container w3-light-grey">
                <h4 class="w3-opacity">Tickets à traiter</h4>
            </header>
            <div class="w3-responsive">
                <table class="w3-table-all w3-hoverable">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>ID</th><th>Objet</th><th>Catégorie</th><th>Statut</th><th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Récupération de tous les tickets triés par date décroissante
                        $stmt = $pdo->query("SELECT * FROM tickets ORDER BY date_creation DESC");
                        while($t = $stmt->fetch()) {
                            // Détermination de la couleur du badge selon le statut (PHP 8 match)
                            $color = match($t['statut']) {
                                'ouvert'   => 'w3-red',
                                'en cours' => 'w3-orange',
                                'fermé'    => 'w3-green',
                                default    => 'w3-grey'
                            };
                            
                            // Affichage de la ligne du tableau avec sécurisation XSS
                            echo "<tr>
                                    <td>#{$t['id_t']}</td>
                                    <td>" . htmlspecialchars($t['titre']) . "</td>
                                    <td>" . htmlspecialchars($t['categorie']) . "</td>
                                    <td><span class='w3-tag $color w3-round w3-small'>{$t['statut']}</span></td>
                                    <td><a href='view_ticket.php?id={$t['id_t']}' class='w3-button w3-blue w3-round w3-small'>Traiter</a></td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        
    </div>
</div>

<script>
// Fonctions JavaScript pour ouvrir/fermer le menu latéral sur mobile
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
