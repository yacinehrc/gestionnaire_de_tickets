<?php include('auth_check.php'); ?>
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
        body, h1, h2, h3, h4, h5, h6 { font-family: "Raleway", sans-serif; }
        .w3-sidebar { z-index: 3; width: 300px; position: fixed; }
        
        /* Correction de la marge gauche pour PC */
        @media (min-width:993px) { 
            .w3-main { margin-left: 300px !important; } 
        }

        @media screen and (max-width: 768px) {
            .w3-sidebar { width: 100%; position: relative; }
            .w3-main { margin-left: 0 !important; }
            .flex-container { flex-direction: column; }
        }
        
        /* Conteneur flex pour aligner les deux cartes côte à côte */
        .flex-container { 
            display: flex; 
            gap: 20px; 
            width: 100%; 
            align-items: stretch; 
        }
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
      // Détermination de la couleur et du texte selon le rôle
      $role = $_SESSION['role'] ?? 'utilisateur';
      $colorClass = "w3-green"; // Par défaut Vert (Utilisateur)
      
      if ($role === 'admin') {
          $colorClass = "w3-blue"; // Bleu pour Admin
      } elseif ($role === 'technicien') {
          $colorClass = "w3-orange"; // Orange pour Technicien
      }
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

<div class="w3-main">
    <header class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-cog"></i> Gestion de votre compte</b></h3>
    </header>

    <div class="w3-container">
        <div class="flex-container">
            <div style="flex: 1; display: flex;"> 
                <div class="w3-card-4 w3-white w3-round" style="width: 100%; display: flex; flex-direction: column; overflow: hidden;">
                    <header class="w3-container w3-blue w3-center">
                        <h4>Modifier le mot de passe</h4>
                    </header>
                    <form class="w3-container w3-padding-24 w3-center" method="POST" style="flex: 1;">
                        <label><b>Nouveau mot de passe</b></label>
                        <input class="w3-input w3-border w3-round w3-light-grey w3-margin-top" type="password" name="new_pass" required>
                        <button class="w3-button w3-blue w3-round w3-margin-top" name="update" style="width:100%">Enregistrer</button>
                    </form>
                </div>
            </div>

            <div style="flex: 1; display: flex;">
                <div class="w3-card-4 w3-white w3-round" style="width: 100%; display: flex; flex-direction: column; overflow: hidden;">
                    <header class="w3-container w3-red w3-center">
                        <h4>Attention !</h4>
                    </header>
                    <div class="w3-container w3-padding-24 w3-center" style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <p>La suppression de votre compte est définitive. Tous vos tickets resteront anonymisés.</p>
                        <form method="POST" onsubmit="return confirm('Sûr ?');">
                            <button class="w3-button w3-red w3-round" name="delete_account" style="width:100%">Supprimer mon compte</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding-top: 24px;">
            <?php
            $role = $_SESSION['role'] ?? 'utilisateur';
            $back_url = ($role === 'admin') ? "admin_dashboard.php" : (($role === 'technicien') ? "tech_dashboard.php" : "user_ticket.php");
            ?>
            <a href="<?= $back_url ?>" class="w3-button w3-light-grey w3-round w3-border">
                <i class="fa fa-arrow-left"></i> Retour au Dashboard
            </a>
        </div>

        <?php
        if(isset($_POST['update'])){
            $pass = md5($_POST['new_pass']); 
            $stmt = $pdo->prepare("UPDATE utilisateurs SET password = ? WHERE id_u = ?");
            if($stmt->execute([$pass, $_SESSION['user_id']])) {
                echo "<div class='w3-panel w3-green w3-round'><p>Mot de passe mis à jour !</p></div>";
            }
        }
        ?>
    </div>
</div>

</body>
</html>
