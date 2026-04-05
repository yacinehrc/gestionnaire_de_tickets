<?php 
// 1. SÉCURITÉ : Vérifie si l'utilisateur est bien connecté via auth_check.php
include('auth_check.php'); 
// 2. CONNEXION : Inclusion de la configuration PDO pour les requêtes SQL
include('config.php'); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Mes Tickets - L'Atelier des Jeux</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="atelierlogo.png">
    <style>
        /* Styles globaux */
        body, h1, h2, h3, h4, h5, h6 { 
            font-family: "Raleway", sans-serif; 
            font-size: 16px;
        }

        /* Masquage de la barre mobile sur desktop */
        .w3-bar.w3-top { display: none; }
        @media screen and (max-width: 768px) {
            .w3-bar.w3-top { display: flex !important; }
        }

        /* Configuration Sidebar */
        .w3-sidebar { z-index: 3; width: 300px; }
        .w3-sidebar.w3-show { display: block !important; }
        .w3-overlay { z-index: 2; display: none; }
        
        /* En-tête mobile noir */
        .top-header { background: #333 !important; color: white; }

        /* RESPONSIVE : Desktop */
        @media (min-width:993px) { 
            .w3-main { margin-left: 300px !important; } 
        }
        
        /* RESPONSIVE : Mobile/Tablette */
        @media screen and (max-width: 768px) {
            .w3-main { margin-left: 0 !important; margin-top: 50px; }
            .w3-sidebar { width: 100%; position: relative; margin-bottom: 20px; }
        }
    </style>
</head>
<body class="w3-light-grey">

<nav class="w3-sidebar w3-collapse w3-white w3-card w3-animate-left" id="mySidebar">
  <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

  <div class="top-header w3-bar w3-top w3-dark-grey">
    <button class="w3-button w3-hover-none" onclick="w3_open();" aria-label="Ouvrir le menu" style="font-size:1.5em;">☰</button>
    <div class="w3-bar-item header-title">
        <?php
          // Affichage dynamique du nom ou du rôle dans la barre mobile
          echo (isset($_SESSION['role']) && $_SESSION['role'] === 'utilisateur') ? htmlspecialchars($_SESSION['nom']) : htmlspecialchars(ucfirst($_SESSION['role'] ?? 'Invité'));
        ?>
    </div>
  </div>

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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // 3. RÉCUPÉRATION : On filtre les tickets pour n'afficher que ceux de l'utilisateur connecté
                    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id_auteur = ? ORDER BY date_creation DESC");
                    $stmt->execute([$_SESSION['user_id']]);
                    
                    while($t = $stmt->fetch()) {
                        // 4. LOGIQUE DE COULEUR : Définit la couleur du badge selon l'état
                        $statusClass = match($t['statut']) {
                            'ouvert' => 'w3-red',
                            'en cours' => 'w3-orange',
                            'fermé' => 'w3-green',
                            default => 'w3-grey'
                        };

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

        <div class="w3-padding-24">
            <?php
            // 5. REDIRECTION DYNAMIQUE : Le bouton retour s'adapte au rôle stocké en session
            $role = $_SESSION['role'] ?? 'utilisateur';
            $back_url = match($role) {
                'admin' => "admin_dashboard.php",
                'technicien' => "tech_dashboard.php",
                default => "user_ticket.php"
            };
            ?>
            
            <a href="user_ticket.php" class="w3-button w3-blue w3-round w3-border">
                <i class="fa fa-plus"></i> Créer un nouveau ticket
            </a>
            <a href="<?php echo $back_url; ?>" class="w3-button w3-light-grey w3-round w3-border">
                <i class="fa fa-arrow-left"></i> Retour au Dashboard
            </a>
        </div>
    </div>
</div>

<script>
/**
 * JAVASCRIPT : Gestion de l'ouverture/fermeture du menu sur mobile
 */
var mySidebar = document.getElementById("mySidebar");
var overlayBg = document.getElementById("myOverlay");

function w3_open() {
    if (!mySidebar || !overlayBg) return;
    mySidebar.classList.add('w3-show');
    overlayBg.style.display = "block";
}

function w3_close() {
    if (!mySidebar || !overlayBg) return;
    mySidebar.classList.remove('w3-show');
    overlayBg.style.display = "none";
}
</script>
</body>
</html>
