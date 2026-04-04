<?php
$host = 'localhost';
$dbname = 'atelier_des_jeux';
$user = 'root';
$pass = '';

try {
    // Connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    
    // Active l'affichage des erreurs (très important pour ton BTS en phase de test)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // --- LA LIGNE POUR L'HEURE FRANCAISE ---
    // Elle force la base de données à passer en heure française (UTC +2)
    $pdo->exec("SET time_zone = '+02:00'");
    
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
