<?php 
include('auth_check.php'); 
include('config.php'); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Mon Support - L'Atelier des Jeux</title>
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
    <a href="user_ticket.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-dashboard fa-fw"></i> Nouveau Ticket</a>
    <a href="gestion_tickets.php" class="w3-bar-item w3-button w3-padding "><i class="fa fa-ticket fa-fw"></i> Mes tickets</a></a>
    <a href="profil.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i> Paramètres</a>
  </div>
</nav>

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
                    <label class="w3-text-grey"><b>Objet du problème</b></label>
                    <input class="w3-input w3-border w3-round" name="objet" type="text" placeholder="Ex: Problème de connexion" required>
                </div>

                <div class="w3-section">
                    <label class="w3-text-grey"><b>Catégorie</b></label>
                    <select class="w3-select w3-border w3-round" name="categorie">
                        <option value="Logiciel">Logiciel</option>
                        <option value="Panne">Panne</option>
                        <option value="Matériel">Matériel</option>
                        <option value="Compte">Compte</option>
                        <option value="Réseau">Réseau</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>

                <div class="w3-section">
                    <label class="w3-text-grey"><b>Description détaillée</b></label>
                    <textarea class="w3-input w3-border w3-round" name="description" style="height:150px; resize:none;" required></textarea>
                </div>

                <button type="submit" name="envoyer" class="w3-button color-soft-blue w3-block w3-round w3-padding-large">
                    <i class="fa fa-paper-plane"></i> Envoyer le ticket
                </button>
            </form>
        </div>
        
        <?php
        if(isset($_POST['envoyer'])){
            // RÉPARATION DES ERREURS
            
            // 1. Correction de la colonne 'objet' en 'titre' (selon ton message d'erreur SQL)
            $titre = $_POST['objet']; 
            $description = $_POST['description'];
            $categorie = $_POST['categorie'];
            
            // 2. Correction de 'id_u' en 'user_id' (selon ton message d'erreur Undefined key)
            $id_auteur = $_SESSION['user_id']; 

            try {
                // Utilisation de 'titre' au lieu de 'objet' car SQL dit que 'objet' n'existe pas
                $sql = "INSERT INTO tickets (titre, description, categorie, id_auteur) VALUES (?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$titre, $description, $categorie, $id_auteur]);
                echo "<div class='w3-panel w3-green w3-round'><p>Ticket envoyé avec succès !</p></div>";
            } catch (PDOException $e) {
                echo "<div class='w3-panel w3-red w3-round'><p>Erreur : " . $e->getMessage() . "</p></div>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>