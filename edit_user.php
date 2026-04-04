<?php
session_start();
include('config.php');

// Sécurité : Seul l'admin peut accéder
if($_SESSION['role'] !== 'admin') { 
    header('Location: login.php'); 
    exit(); 
}

$message = "";
$error = "";

// 1. Récupération de l'utilisateur
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_u = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if(!$user) {
        header('Location: admin_users.php');
        exit();
    }
} else {
    header('Location: admin_users.php');
    exit();
}

// 2. Traitement de la modification
if(isset($_POST['update_user'])) {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $new_pass = $_POST['new_password'];

    try {
        if(!empty($new_pass)) {
            // Si un nouveau mot de passe est saisi
            $pass_hash = md5($new_pass);
            $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ?, password = ?, role = ? WHERE id_u = ?");
            $stmt->execute([$nom, $email, $pass_hash, $role, $id]);
        } else {
            // Sinon on ne touche pas au mot de passe
            $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, email = ?, role = ? WHERE id_u = ?");
            $stmt->execute([$nom, $email, $role, $id]);
        }
        $message = "Profil mis à jour avec succès.";
        // Rafraîchir les données affichées
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_u = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
    } catch(Exception $e) {
        $error = "Erreur lors de la mise à jour.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Modifier Utilisateur - L'Atelier des Jeux</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body, h1, h2, h3, h4, h5, h6 { font-family: "Raleway", sans-serif; }
        .w3-sidebar { z-index: 3; width: 300px; }
        @media (min-width:993px) { .w3-main { margin-left: 300px !important; } }
        .color-soft-blue { background-color: #2196F3 !important; color: white !important; }
        @media screen and (max-width: 768px) {
            .w3-main { margin-left: 0 !important; }
            input, select { width: 100%; box-sizing: border-box; margin: 5px 0; }
            button { width: 100%; }
        }
    </style>
</head>
<body class="w3-light-grey">

<nav class="w3-sidebar w3-collapse w3-white w3-card w3-animate-left" id="mySidebar">
  <div class="w3-container w3-padding-16 color-soft-blue">
    <h5><b>L'Atelier des Jeux</b></h5>
  </div>
  <div class="w3-container w3-padding-16">
    <h5>Bienvenue, <b>Admin</b></h5>
    <span class="w3-tag color-soft-blue w3-round">Administrateur</span>
  </div>
  <hr>
  <div class="w3-bar-block">
    <a href="admin_dashboard.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
    <a href="admin_user.php" class="w3-bar-item w3-button w3-padding color-soft-blue"><i class="fa fa-users fa-fw"></i> Gestion Comptes</a>
    <a href="log_view.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-history fa-fw"></i> Logs Connexions</a>
    <a href="profil.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-cog fa-fw"></i> Paramètres</a>
    <a href="logout.php" class="w3-bar-item w3-button w3-padding w3-text-red"><i class="fa fa-sign-out fa-fw"></i> Déconnexion</a>
  </div>
</nav>

<div class="w3-main" style="margin-left:300px;">
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-pencil"></i> Modifier les utilisateurs</b></h5>
    <div class="w3-container">
        <?php if($message): ?>
            <div class="w3-panel w3-green w3-round w3-card"><p><?= $message ?></p></div>
        <?php endif; ?>

        <div class="w3-card w3-white w3-round w3-margin-bottom">
            <header class="w3-container w3-light-grey w3-round">
                <h4 class="w3-opacity">Informations de <?= htmlspecialchars($user['nom']) ?></h4>
            </header>
            
            <form method="POST" class="w3-container w3-padding-24">
                <div class="w3-section">
                    <label><b>Nom complet</b></label>
                    <input class="w3-input w3-border w3-round w3-light-grey" type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                </div>

                <div class="w3-section">
                    <label><b>Email</b></label>
                    <input class="w3-input w3-border w3-round w3-light-grey" type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="w3-section">
                    <label><b>Rôle</b></label>
                    <select class="w3-select w3-border w3-round w3-light-grey" name="role">
                        <option value="utilisateur" <?= $user['role'] == 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
                        <option value="technicien" <?= $user['role'] == 'technicien' ? 'selected' : '' ?>>Technicien</option>
                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Administrateur</option>
                    </select>
                </div>

                <div class="w3-section">
                    <label><b>Nouveau mot de passe</b> (laisser vide pour ne pas changer)</label>
                    <!-- <input class="w3-input w3-border w3-round w3-white" type="password" name="new_password" placeholder="********">-->
                    <input class="w3-input w3-border w3-round w3-light-grey" type="password" name="new_password" placeholder="Confirmer le mot de passe">
                </div>

                <div class="w3-padding-16">
                    <button class="w3-button color-soft-blue w3-round w3-block" name="update_user">
                        <i class="fa fa-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>

        <div class="w3-padding-16">
            <a href="admin_user.php" class="w3-button w3-light-grey w3-round w3-border">
                <i class="fa fa-arrow-left"></i> Retour à la gestion des utilisateurs
            </a>
        </div>
    </div>
</div>

</body>
</html>