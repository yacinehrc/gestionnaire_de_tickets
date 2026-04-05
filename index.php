<?php
// Démarrage de la session pour accéder aux données de l'utilisateur connecté (ID, rôle, nom)
session_start();

// Connexion à la base de données via le fichier de configuration centralisé
include('config.php');

/**
 * ÉTAPE 1 : VÉRIFICATION DE L'AUTHENTIFICATION
 * Si la variable de session 'user_id' n'existe pas, l'utilisateur n'est pas connecté.
 * On le redirige immédiatement vers la page de connexion (login.php) par sécurité.
 */
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit(); // On arrête l'exécution du script pour forcer la redirection
}

/**
 * ÉTAPE 2 : AIGUILLAGE SELON LE RÔLE (RBAC - Role Based Access Control)
 * Une fois connecté, le système vérifie le rang de l'utilisateur pour l'envoyer 
 * vers l'interface correspondante à ses droits.
 */

if ($_SESSION['role'] == 'admin') {
    // Si l'utilisateur est un Administrateur, il va vers son tableau de bord de gestion globale
    header('Location: admin_dashboard.php'); 
} elseif ($_SESSION['role'] == 'technicien') {
    // Si c'est un Technicien, il est dirigé vers l'interface de traitement des tickets assignés
    header('Location: tech_dashboard.php');  
} else {
    // Par défaut (rôle 'utilisateur'), il est envoyé vers le formulaire de création de ticket
    header('Location: user_ticket.php');     
}

// Sécurité finale : on s'assure que rien d'autre ne s'exécute après la redirection
exit();
?>
