<?php

session_start();
if (!isset($_SESSION['pseudo'])) {
    header("Content-Type: application/json");
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$host = "mysql-tchatouille.alwaysdata.net";
$dbname = "tchatouille_bdd";
$username = "400943";
$password = '$iutinfo';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer tous les usernames sauf l'utilisateur connecté
    $stmt = $pdo->prepare("SELECT pseudo FROM utilisateur WHERE pseudo != :pseudo");
    $stmt->execute(['pseudo' => $_SESSION['pseudo']]);
    $users = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode($users);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion"]);
}
?>
