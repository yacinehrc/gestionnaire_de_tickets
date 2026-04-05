<?php 
// 1. SÉCURITÉ : Vérifie si l'utilisateur est authentifié avant d'afficher la page
include('auth_check.php'); 
// 2. CONNEXION : Accès à la base de données via l'objet $pdo
include('config.php'); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Mon Support - L'Atelier des Jeux</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="atelierlogo.png">
    <style>
        body { font-family: "Raleway", sans-serif; font-size: 16px; }
        .color-soft-blue { background-color: #2196F3 !important; color: white !important; }
        
        /* Sidebar (Desktop) : Toujours visible à gauche */
        .w3-sidebar { z-index: 5; width: 300px; height: 100%; position: fixed; display: block; }
        
        /* Header Mobile : Barre supérieure noire masquée sur PC */
        .top-header { 
            display: none; position: fixed; top: 0; left: 0; right: 0; 
            width: 100%; z-index: 4; height: 50px; padding: 0; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); align-items: center; 
            background: #333 !important; color: white;
        }
        .top-header .header-title { flex: 1; text-align: right; font-weight: bold; font-size: 1.1em; padding-right: 15px; }

        /* RESPONSIVE : Adaptations pour smartphones/tablettes */
        @media screen and (max-width: 768px) {
            .top-header { display: flex !important; }
            .w3-main { margin-left: 0 !important; margin-top: 50px !important; }
            
            .w3-sidebar { 
                width: 260px !important; 
                display: none; 
                position: fixed !important; 
                box-shadow: 4px 0 10px rgba(0,0,0,0.3);
            }
            .w3-sidebar.w3-show { display: block !important; }
            .w3-overlay { z-index: 4; }
        }

        /* RESPONSIVE : Adaptations pour ordinateurs */
        @media (min-width: 769px) {
            .top-header { display: none !important; }
            .w3-main { margin-left: 300px !important; }
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
    <span class="w3-tag w3-green w3-round">Utilisateur</span>
  </div>
  <hr>
  <div class="w3-bar-block">
    <a href="user_ticket.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-dashboard fa-fw"></i> Nouveau Ticket</a>
    <a href="gestion_tickets.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-ticket fa-fw"></i> Mes tickets</a>
    <a href="profil.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i> Paramètres</a>
  </div>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

<div class="top-header w3-bar w3-top">
  <button class="w3-button w3-hover-none" onclick="w3_open();" style="font-size:1.5em; padding:0 15px;">☰</button>
  <div class="header-title">
    <?php echo htmlspecialchars($_SESSION['nom']); ?>
  </div>
</div>

<div class="w3-main">
    <header class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-plus-circle"></i> Créer une demande d'assistance</b></h3>
    </header>

    <div class="w3-container">
        <div class="w3-card w3-white w3-round w3-margin-bottom">
            <header class="w3-container w3-light-grey w3-round">
                <h4 class="w3-opacity">Détails du ticket</h4>
            </header>

            <form method="POST" class="w3-container w3-padding-24">
                <div class="w3-section">
                    <label><b>Objet du problème</b></label>
                    <input class="w3-input w3-border w3-round" name="objet" type="text" placeholder="Ex: Problème de connexion" required>
                </div>

                <div class="w3-section">
                    <label><b>Catégorie</b></label>
                    <select class="w3-select w3-border w3-round" name="categorie">
                        <option value="Logiciel">Logiciel</option>
                        <option value="Panne">Panne</option>
                        <option value="Matériel">Matériel</option>
                        <option value="Compte">Compte</option>
                    </select>
                </div>

                <div class="w3-section">
                    <label><b>Description</b></label>
                    <textarea class="w3-input w3-border w3-round" name="description" style="height:120px; resize:none;" required></textarea>
                </div>

                <button type="submit" name="envoyer" class="w3-button color-soft-blue w3-block w3-round w3-padding-large">
                    <i class="fa fa-paper-plane"></i> Envoyer le ticket
                </button>
            </form>
        </div>

        <?php
        /**
         * TRAITEMENT PHP : Insertion du ticket en BDD après soumission
         */
        if(isset($_POST['envoyer'])){
            $titre = htmlspecialchars($_POST['objet']); 
            $description = htmlspecialchars($_POST['description']);
            $categorie = $_POST['categorie'];
            $id_auteur = $_SESSION['user_id']; // Récupération de l'ID utilisateur connecté

            try {
                // Requête SQL d'insertion (statut par défaut 'ouvert' en BDD)
                $sql = "INSERT INTO tickets (titre, description, categorie, id_auteur) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$titre, $description, $categorie, $id_auteur]);
                echo "<div class='w3-panel w3-green w3-round w3-padding-16'><p><i class='fa fa-check'></i> Ticket envoyé avec succès !</p></div>";
            } catch (PDOException $e) {
                // Message d'erreur en cas de problème technique avec la base de données
                echo "<div class='w3-panel w3-red w3-round w3-padding-16'><p>Erreur lors de l'envoi : " . $e->getMessage() . "</p></div>";
            }
        }
        ?>
    </div>
</div>

<script>
/**
 * JAVASCRIPT : Fonctions pour ouvrir/fermer le menu sur mobile
 */
var mySidebar = document.getElementById("mySidebar");
var overlayBg = document.getElementById("myOverlay");

function w3_open() {
    mySidebar.style.display = "block";
    mySidebar.classList.add('w3-show');
    overlayBg.style.display = "block";
}

function w3_close() {
    mySidebar.style.display = "none";
    mySidebar.classList.remove('w3-show');
    overlayBg.style.display = "none";
}
</script>

</body>
</html>
