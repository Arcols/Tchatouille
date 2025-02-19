<?php
$host = "localhost";
$dbname = "messagerier4a10";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

try {
    // Récupérer les 10 derniers messages
    $query = "SELECT id, horaire, auteur, contenu FROM messages ORDER BY horaire DESC LIMIT 10";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les messages en JSON
    header("Content-Type: application/json");
    echo json_encode($messages, JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>