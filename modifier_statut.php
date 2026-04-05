<?php
/**
 * TRAITEMENT RAPIDE DU STATUT (ADMIN)
 */
session_start();
include('config.php');

// 1. SÉCURITÉ : Vérification de la session
// Si l'utilisateur n'est pas connecté, on arrête tout immédiatement
if(!isset($_SESSION['user_id'])) {
    exit();
}

/**
 * 2. RÉCUPÉRATION DES PARAMÈTRES
 * Les données passent par l'URL (ex: update_status_admin.php?id=5&s=fermé)
 */
$id = $_GET['id'];    // L'identifiant du ticket
$statut = $_GET['s']; // Le nouveau statut à appliquer

/**
 * 3. MISE À JOUR DU TICKET
 * Utilisation d'une requête préparée pour éviter les injections SQL
 */
$stmt = $pdo->prepare("UPDATE tickets SET statut = ? WHERE id_t = ?");
$stmt->execute([$statut, $id]);

/**
 * 4. TRAÇABILITÉ (LOGS)
 * On enregistre l'action dans une table 'logs' pour savoir quel admin 
 * a modifié quel ticket. C'est une preuve d'audit importante.
 */
$log = $pdo->prepare("INSERT INTO logs (action, id_u) VALUES (?, ?)");
$log->execute([
    "Changement statut ticket #$id vers $statut", 
    $_SESSION['user_id']
]);

/**
 * 5. REDIRECTION
 * Une fois le travail terminé, on renvoie l'admin vers son tableau de bord
 */
header('Location: admin_dashboard.php');
exit();
?>
