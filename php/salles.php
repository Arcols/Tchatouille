<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header("Content-Type: application/json");
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

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

$pseudo = $_SESSION['pseudo'];

try {
    $query = "SELECT salles.id, salles.nom, salles.description, 
              (SELECT contenu FROM messages WHERE messages.salle = salles.id ORDER BY horaire DESC LIMIT 1) AS dernier_message 
              FROM salles 
              JOIN acces ON salles.id = acces.id_salle 
              WHERE acces.user = :pseudo";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $stmt->execute();

    $salles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header("Content-Type: application/json");
    echo json_encode($salles, JSON_PRETTY_PRINT);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>