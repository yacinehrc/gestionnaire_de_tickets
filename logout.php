<?php
/**
 * GESTION DE LA DÉCONNEXION
 */

// 1. Récupère la session actuelle pour pouvoir la manipuler
session_start();

// 2. Vide toutes les variables de session ($_SESSION['user_id'], $_SESSION['role'], etc.)
$_SESSION = array();

// 3. Détruit physiquement le fichier de session sur le serveur
session_destroy();

/**
 * 4. REDIRECTION
 * Une fois la session détruite, l'utilisateur est renvoyé vers la page de connexion.
 * L'instruction exit() est indispensable pour stopper immédiatement l'exécution du script.
 */
header('Location: login.php');
exit();
?>
