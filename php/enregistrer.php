<?php
$host = "localhost";
$dbname = "messagerier4a10";
$username = "root";
$password = "";
try {
    $linkpdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si tous les champs sont remplis
    if (isset($_POST['auteur'], $_POST['contenu'])) {
        // Récupérer les données du formulaire
        date_default_timezone_set('Europe/Paris');
        $horaire = (new DateTime())->format("Y-m-d H:i:s");
        $auteur = htmlspecialchars($_POST['auteur']);
        $contenu = htmlspecialchars($_POST['contenu']);
        try {
            // Requête préparée pour enregistrer le message
            $stmt = $pdo->prepare("INSERT INTO messages (horaire, auteur, contenu) 
                                   VALUES (:horaire, :auteur, :contenu)");

            $stmt->execute([
                ':horaire' => $horaire,
                ':auteur' => $auteur,
                ':contenu' => $contenu                
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
