<?php
session_start();
include('config.php');

// Sécurité : Seul l'admin peut accéder
if($_SESSION['role'] !== 'admin') { 
    header('Location: tech_dashboard.php'); 
    exit(); 
}

$message = "";

// --- LOGIQUE DE SUPPRESSION ---
if(isset($_GET['delete_id'])){
    $id_to_delete = $_GET['delete_id'];
    // On évite que l'admin se supprime lui-même par erreur
    if($id_to_delete == $_SESSION['id_u']){
        $message = "Erreur : Vous ne pouvez pas supprimer votre propre compte.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_u = ?");
        if($stmt->execute([$id_to_delete])){
            $message = "Utilisateur supprimé avec succès.";
        }
    }
}

// --- LOGIQUE DE CRÉATION ---
if(isset($_POST['create_user'])){
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $pass = md5($_POST['password']); 
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, password, role) VALUES (?, ?, ?, ?)");
    if($stmt->execute([$nom, $email, $pass, $role])) {
        $message = "Utilisateur créé avec succès !";
    }
}

// Récupération de la liste mise à jour
$users = $pdo->query("SELECT id_u, nom, email, role FROM utilisateurs ORDER BY role, nom")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Gestion Comptes - L'Atelier des Jeux</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            .w3-responsive table { font-size: 0.85em; }
            input, select { width: 100%; box-sizing: border-box; }
            .w3-quarter { width: 100%; }
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
    
  </div>
</nav>

<div class="w3-main" style="margin-left:300px;">
  <div class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-users"></i> Gestion des utilisateurs</b></h5>
    </header>

    <div class="w3-container">
        <?php if($message): ?>
            <div class="w3-panel w3-blue w3-round w3-display-container w3-card">
                <span onclick="this.parentElement.style.display='none'" class="w3-button w3-display-topright w3-round">&times;</span>
                <p><?= $message ?></p>
            </div>
        <?php endif; ?>

        <div class="w3-card w3-white w3-round w3-margin-bottom">
            <header class="w3-container w3-light-grey w3-round">
                <h4 class="w3-opacity">Créer un nouveau compte</h4>
            </header>
            <form method="POST" class="w3-container w3-padding-16">
                <div class="w3-row-padding">
                    <div class="w3-quarter">
                        <label class="w3-small"><b>Nom</b></label>
                        <input class="w3-input w3-border w3-round w3-light-grey" type="text" name="nom" required>
                    </div>
                    <div class="w3-quarter">
                        <label class="w3-small"><b>Email</b></label>
                        <input class="w3-input w3-border w3-round w3-light-grey" type="email" name="email" required>
                    </div>
                    <div class="w3-quarter">
                        <label class="w3-small"><b>Mot de passe</b></label>
                        <input class="w3-input w3-border w3-round w3-light-grey" type="password" name="password" required>
                    </div>
                    <div class="w3-quarter">
                        <label class="w3-small"><b>Rôle</b></label>
                        <select class="w3-select w3-border w3-round w3-light-grey" name="role">
                            <option value="utilisateur">Utilisateur</option>
                            <option value="technicien">Technicien</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                </div>
                <div class="w3-container w3-padding-16 w3-right-align">
                    <button class="w3-button color-soft-blue w3-round" name="create_user">
                        <i class="fa fa-plus"></i> Ajouter l'utilisateur
                    </button>
                </div>
            </form>
        </div>

        <div class="w3-card w3-white w3-round">
            <header class="w3-container w3-light-grey w3-round">
                <h4 class="w3-opacity">Liste des comptes</h4>
            </header>
            <div class="w3-responsive">
                <table class="w3-table-all w3-hoverable">
                    <thead>
                        <tr class="w3-light-grey">
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th class="w3-center">Actions</th>
                        </tr>
                    </thead>
                    <?php foreach($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['nom']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="w3-tag w3-round w3-small <?= $u['role'] == 'admin' ? 'w3-red' : ($u['role'] == 'technicien' ? 'w3-orange' : 'w3-blue') ?>">
                                <?= ucfirst($u['role']) ?>
                            </span>
                        </td>
                        <td class="w3-center">
                            <a href="edit_user.php?id=<?= $u['id_u'] ?>" class="w3-button w3-white w3-border w3-round-large w3-small" title="Modifier">
                                <i class="fa fa-pencil w3-text-blue"></i>
                            </a>
                            <a href="admin_users.php?delete_id=<?= $u['id_u'] ?>" 
                               class="w3-button w3-white w3-border w3-round-large w3-small" 
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');" 
                               title="Supprimer">
                                <i class="fa fa-trash w3-text-red"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
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

</body>
</html>