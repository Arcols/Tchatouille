<?php
if(!isset($_SESSION)){
    session_start();
}
$auteur = "";
if(!isset($_SESSION['pseudo'])){
    header("Location: ./../index.html.php");
    exit;
}else{
    $auteur = $_SESSION['pseudo'];
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

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si tous les champs sont remplis
    if (isset($_POST['contenu']) && isset($_POST['salle'])) {
        // Récupérer les données du formulaire
        date_default_timezone_set('Europe/Paris');
        $horaire = (new DateTime())->format("Y-m-d H:i:s");
        $contenu = htmlspecialchars($_POST['contenu']);
        $salle = intval($_POST['salle']);

        try {
            // Requête préparée pour enregistrer le message
            $stmt = $pdo->prepare("INSERT INTO messages (horaire, auteur, contenu, salle)
                                   VALUES (:horaire, :auteur, :contenu, :salle)");
            $stmt->execute([
                ':horaire' => $horaire,
                ':auteur' => $auteur,
                ':contenu' => $contenu,
                ':salle' => $salle
            ]);
            exit;
        } catch (PDOException $e) {
            echo "<p>Erreur lors de l'enregistrement : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p>Veuillez remplir tous les champs.</p>";
    }
}
?>
