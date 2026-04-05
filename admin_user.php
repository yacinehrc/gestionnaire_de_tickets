<?php
// Démarrage de la session pour accéder aux variables utilisateur
session_start();
// Inclusion du fichier de configuration pour la connexion à la base de données
include('config.php');

// Vérification des droits d'accès
// Si l'utilisateur n'est pas administrateur, il est renvoyé vers le tableau de bord technicien
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header('Location: tech_dashboard.php'); 
    exit(); 
}

// Variable pour stocker les messages de confirmation ou d'erreur
$message = "";

// Traitement de la suppression d'un utilisateur
if(isset($_GET['delete_id'])){
    $id_to_delete = $_GET['delete_id'];
    // Empêcher l'administrateur de supprimer son propre compte par erreur
    if($id_to_delete == $_SESSION['user_id']){
        $message = "Erreur : Vous ne pouvez pas supprimer votre propre compte.";
    } else {
        // Préparation et exécution de la requête de suppression
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_u = ?");
        if($stmt->execute([$id_to_delete])){
            $message = "Utilisateur supprimé avec succès.";
        }
    }
}

// Traitement de la création d'un nouvel utilisateur
if(isset($_POST['create_user'])){
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    // Hachage du mot de passe en md5 (attention : md5 est peu sécurisé pour une mise en production)
    $pass = md5($_POST['password']); 
    $role = $_POST['role'];
    
    // Insertion des données dans la base de données
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, password, role) VALUES (?, ?, ?, ?)");
    if($stmt->execute([$nom, $email, $pass, $role])) {
        $message = "Utilisateur créé avec succès !";
    }
}

// Récupération de la liste complète des utilisateurs classée par rôle et par nom
$users = $pdo->query("SELECT id_u, nom, email, role FROM utilisateurs ORDER BY role, nom")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Gestion Comptes</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="atelierlogo.png">
    <style>
        body { font-family: "Raleway", sans-serif; font-size: 16px; }
        /* Style pour la barre latérale sur grand écran */
        .w3-sidebar { z-index: 5; width: 300px; height: 100%; position: fixed; }
        
        /* Style pour l'en-tête mobile */
        .top-header { 
            display: none; position: fixed; top: 0; left: 0; right: 0; 
            width: 100%; z-index: 4; height: 50px; padding: 0; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); align-items: center; 
            background: #333 !important; color: white;
        }
        .top-header .header-title { flex: 1; text-align: right; font-weight: bold; font-size: 1.1em; padding-right: 15px; }

        /* Ajustements pour les écrans de taille moyenne et petite */
        @media screen and (max-width: 768px) {
            .top-header { display: flex !important; }
            .w3-main { margin-left: 0 !important; margin-top: 50px !important; padding-top: 10px; }
            .w3-sidebar { width: 260px !important; display: none; position: fixed !important; box-shadow: 4px 0 10px rgba(0,0,0,0.3); }
            .w3-sidebar.w3-show { display: block !important; }
        }

        /* Ajustements pour les grands écrans */
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
    <a href="admin_user.php" class="w3-bar-item w3-button w3-padding w3-blue"><i class="fa fa-users fa-fw"></i> Gestion Comptes</a>
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
    <div class="w3-container" style="padding-top:22px">
        <h3 class="w3-left-align"><b><i class="fa fa-users"></i> Gestion des utilisateurs</b></h3>

        <?php if($message): ?>
            <div class="w3-panel w3-blue w3-round w3-card w3-display-container">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-display-topright">&times;</span>
                <p><?= $message ?></p>
            </div>
        <?php endif; ?>

        <div class="w3-card w3-white w3-round w3-margin-bottom">
            <header class="w3-container w3-light-grey">
                <h4 class="w3-opacity">Créer un nouveau compte</h4>
            </header>
            <form method="POST" class="w3-container w3-padding-16">
                <label><b>Nom</b></label>
                <input class="w3-input w3-border w3-round w3-light-grey w3-margin-bottom" type="text" name="nom" required>
                
                <label><b>Email</b></label>
                <input class="w3-input w3-border w3-round w3-light-grey w3-margin-bottom" type="email" name="email" required>
                
                <label><b>Mot de passe</b></label>
                <input class="w3-input w3-border w3-round w3-light-grey w3-margin-bottom" type="password" name="password" required>
                
                <label><b>Rôle</b></label>
                <select class="w3-select w3-border w3-round w3-light-grey w3-margin-bottom" name="role">
                    <option value="utilisateur">Utilisateur</option>
                    <option value="technicien">Technicien</option>
                    <option value="admin">Administrateur</option>
                </select>
                
                <button class="w3-button w3-blue w3-round" name="create_user"><i class="fa fa-plus"></i> Ajouter l'utilisateur</button>
            </form>
        </div>

        <div class="w3-card w3-white w3-round w3-margin-bottom">
            <header class="w3-container w3-light-grey">
                <h4 class="w3-opacity">Liste des comptes</h4>
            </header>
            <div class="w3-responsive">
                <table class="w3-table-all w3-hoverable">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>Nom</th><th>Email</th><th>Rôle</th><th class="w3-center">Actions</th>
                        </tr>
                    </thead>
                    <?php foreach($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['nom']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="w3-tag w3-round w3-small <?php echo ($u['role']=='admin')?'w3-blue':(($u['role']=='technicien')?'w3-orange':'w3-green'); ?>">
                                <?= ucfirst($u['role']) ?>
                            </span>
                        </td>
                        <td class="w3-center">
                            <a href="edit_user.php?id=<?= $u['id_u'] ?>" class="w3-button w3-white w3-border w3-round-large w3-small"><i class="fa fa-pencil w3-text-blue"></i></a>
                            <a href="admin_user.php?delete_id=<?= $u['id_u'] ?>" class="w3-button w3-white w3-border w3-round-large w3-small" onclick="return confirm('Supprimer ?');"><i class="fa fa-trash w3-text-red"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <a href="admin_dashboard.php" class="w3-button w3-light-grey w3-round w3-border w3-margin-bottom">
            <i class="fa fa-arrow-left"></i> Retour au Dashboard
        </a>
    </div>
</div>

<script>
// Gestion de l'affichage du menu latéral pour mobile
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
