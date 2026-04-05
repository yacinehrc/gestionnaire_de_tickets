<?php
/**
 * SYSTÈME DE VÉRIFICATION D'AUTHENTIFICATION ET DE SÉCURITÉ
 * Ce script est inclus en haut de chaque page nécessitant une connexion.
 */

// Initialisation ou récupération de la session actuelle
session_start();

// Inclusion de la connexion à la base de données via l'objet $pdo
include('config.php');

/**
 * ÉTAPE 1 : VÉRIFICATION DE LA CONNEXION
 * Si l'identifiant de l'utilisateur n'est pas présent en session, 
 * on le renvoie vers la page de login.
 */
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

/**
 * ÉTAPE 2 : RÈGLE DE SÉCURITÉ SUR L'INACTIVITÉ (1 MOIS)
 * Le système vérifie si l'utilisateur s'est connecté récemment.
 */

// On récupère la date de la dernière connexion enregistrée en base de données
$stmt = $pdo->prepare("SELECT derniere_connexion, role FROM utilisateurs WHERE id_u = ?");
$stmt->execute([$_SESSION['user_id']]);
$u = $stmt->fetch();

// Utilisation de l'objet DateTime de PHP pour calculer l'écart de temps
$now = new DateTime(); // Date actuelle
$last_co = new DateTime($u['derniere_connexion']); // Date de la dernière connexion en BDD
$interval = $last_co->diff($now); // Calcul de la différence

/**
 * ÉTAPE 3 : SANCTION SI INACTIF
 * Si l'intervalle est d'au moins 1 mois ou 1 an, le compte est désactivé.
 */
if ($interval->m >= 1 || $interval->y >= 1) { 
    
    // 1. Mise à jour du rôle en 'inactif' dans la base de données
    $update = $pdo->prepare("UPDATE utilisateurs SET role = 'inactif' WHERE id_u = ?");
    $update->execute([$_SESSION['user_id']]);
    
    // 2. Destruction de la session locale (déconnexion immédiate)
    session_destroy();
    
    // 3. Redirection vers le login avec un paramètre d'erreur spécifique
    header('Location: login.php?err=inactif');
    exit();
}
?>
