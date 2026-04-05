<?php 
/**
 * 1. SÉCURITÉ ET SESSION
 * On inclut 'auth_check.php' qui vérifie si l'utilisateur est bien connecté.
 * Si la session a expiré, l'utilisateur est redirigé automatiquement.
 */
include('auth_check.php'); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Paramètres - L'Atelier des Jeux</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="atelierlogo.png">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { font-family: "Raleway", sans-serif; font-size: 16px; }
        
        /* Configuration Sidebar (Desktop) : Fixée à gauche */
        .w3-sidebar { z-index: 5; width: 300px; height: 100%; position: fixed; }
        
        /* Header Mobile : Masqué sur PC, s'affiche en haut sur mobile */
        .top-header { 
            display: none; position: fixed; top: 0; left: 0; right: 0; 
            width: 100%; z-index: 4; height: 50px; padding: 0; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); align-items: center; 
            background: #333 !important; color: white;
        }
        .top-header .header-title { flex: 1; text-align: right; font-weight: bold; font-size: 1.1em; padding-right: 15px; }

        /* RESPONSIVE : Adaptation pour tablettes et smartphones */
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
            /* Les cartes passent les unes sous les autres sur mobile */
            .flex-container { flex-direction: column; }
        }

        /* RESPONSIVE : Configuration pour les grands écrans */
        @media (min-width: 769px) {
            .top-header { display: none !important; }
            .w3-main { margin-left: 300px !important; padding-top: 0; }
            .w3-sidebar { display: block !important; width: 300px; }
            /* Alignement horizontal des cartes sur PC */
            .flex-container { display: flex; gap: 20px; }
        }

        .flex-item { flex: 1; display: flex; margin-bottom: 20px; }
    </style>
</head>
<body class="w3-light-grey">

<nav class="w3-sidebar w3-collapse w3-white w3-card w3-animate-left" id="mySidebar">
  <div class="w3-container w3-padding-16 w3-blue">
    <h5><b>L'Atelier des Jeux</b></h5>
  </div>
  <div class="w3-container w3-padding-16">
    <h5><b><?php echo htmlspecialchars($_SESSION['nom']); ?></b></h5>
    <?php 
      // Attribution dynamique de la couleur du badge selon le rôle en session
      $role = $_SESSION['role'] ?? 'utilisateur';
      $colorClass = ($role === 'admin') ? "w3-blue" : (($role === 'technicien') ? "w3-orange" : "w3-green");
    ?>
    <span class="w3-tag <?php echo $colorClass; ?> w3-round">
      <?php echo ucfirst(htmlspecialchars($role)); ?>
    </span>
  </div>
  <hr>
  <div class="w3-bar-block">
    <a href="profil.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-cog fa-fw"></i> Paramètres</a>
    <a href="logout.php" class="w3-bar-item w3-button w3-padding w3-text-red"><i class="fa fa-sign-out fa-fw"></i> Déconnexion</a>
  </div>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

<div class="top-header w3-bar w3-top">
  <button class="w3-button w3-hover-none" onclick="w3_open();" style="font-size:1.5em; padding:0 15px;">☰</button>
  <div class="header-title">
    <?php echo htmlspecialchars(ucfirst($role)); ?>
  </div>
</div>

<div class="w3-main">
    <header class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-cog"></i> Gestion de votre compte</b></h3>
    </header>

    <div class="w3-container">
        <div class="flex-container">
            
            <div class="flex-item"> 
                <div class="w3-card w3-white w3-round" style="width:100%">
                    <header class="w3-container w3-blue">
                        <h4>Modifier le mot de passe</h4>
                    </header>
                    <form class="w3-container w3-padding-24" method="POST">
                        <label><b>Nouveau mot de passe</b></label>
                        <input class="w3-input w3-border w3-round w3-light-grey w3-margin-top" type="password" name="new_pass" required>
                        <button class="w3-button w3-blue w3-round w3-margin-top w3-block" name="update">Enregistrer</button>
                    </form>
                </div>
            </div>

            <div class="flex-item">
                <div class="w3-card w3-white w3-round" style="width:100%">
                    <header class="w3-container w3-red">
                        <h4>Attention !</h4>
                    </header>
                    <div class="w3-container w3-padding-24">
                        <p>La suppression de votre compte est définitive. Vos tickets seront anonymisés.</p>
                        <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');">
                            <button class="w3-button w3-red w3-round w3-block" name="delete_account">Supprimer mon compte</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding-top: 10px;">
            <?php
            $back_url = ($role === 'admin') ? "admin_dashboard.php" : (($role === 'technicien') ? "tech_dashboard.php" : "user_ticket.php");
            ?>
            <a href="<?= $back_url ?>" class="w3-button w3-light-grey w3-round w3-border">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>

        <?php
        /**
         * TRAITEMENT PHP : Mise à jour du mot de passe
         * Ce bloc intercepte la soumission du formulaire et met à jour la base.
         */
        if(isset($_POST['update'])){
            include('config.php');
            // Hachage du mot de passe en MD5 (standard pour ce projet)
            $pass = md5($_POST['new_pass']); 
            $stmt = $pdo->prepare("UPDATE utilisateurs SET password = ? WHERE id_u = ?");
            if($stmt->execute([$pass, $_SESSION['user_id']])) {
                echo "<div class='w3-panel w3-green w3-round'><p><i class='fa fa-check'></i> Mot de passe mis à jour !</p></div>";
            }
        }
        ?>
    </div>
</div>

<script>
/**
 * Fonctions JavaScript pour ouvrir/fermer la barre latérale sur mobile
 */
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
