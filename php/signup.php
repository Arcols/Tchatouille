<?php

$host = "mysql-tchatouille.alwaysdata.net";
$dbname = "tchatouille_bdd";
$username = "400943";
$password = '$iutinfo';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connexion à la base de données
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("<p>Erreur de connexion à la base de données : " . $e->getMessage() . "</p>");
    }

    // Récupérer les données du formulaire
    if (isset($_POST['pseudo']) && isset($_POST['mdp'])) {
        $pseudo = $_POST['pseudo'];
        $mdp = $_POST['mdp'];

        $mdp = trim($mdp);

        $cle = "cleuwuchan";
        $mdp_hache = hash_hmac('sha256', $mdp, $cle);

        // Vérifier si le pseudo existe déjà
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE pseudo = :pseudo");
        $stmt->execute([':pseudo' => $pseudo]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // Rediriger vers la page principale avec un message d'erreur
            header("Location: ./../index.php?error=user_exists");
            exit();
        } else {
            // Insérer l'utilisateur dans la base de données
            $stmt = $pdo->prepare("INSERT INTO utilisateur (pseudo, mdp) VALUES (:pseudo, :mdp)");
            $stmt->execute([':pseudo' => $pseudo, ':mdp' => $mdp_hache]);

            // Ajouter l'utilisateur à la salle 1
            $stmt = $pdo->prepare("INSERT INTO acces (id_salle, user, role) VALUES (1, :pseudo, 'user')");
            $stmt->execute([':pseudo' => $pseudo]);

            // Rediriger vers la page principale avec un message de succès
            header("Location: ./../index.php?success=user_added");
            exit();
        }
    } else {
        echo "<p>Veuillez remplir tous les champs.</p>";
    }
}
?>