<?php
session_start();

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
        $cle = "cleuwuchan";
        $mdp_hache = hash_hmac('sha256', $mdp, $cle);

        // Vérifier l'utilisateur dans la base de données
        $stmt = $pdo->prepare("SELECT mdp FROM utilisateur WHERE pseudo = :pseudo");
        $stmt->execute([':pseudo' => $pseudo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($mdp_hache === $user['mdp']) {
                // Connexion réussie
                session_start();
                $_SESSION['pseudo'] = $pseudo; // Stocke le pseudo dans la session
                header("Location: ./../pages/index.html.php"); // Redirige vers la page principale
                echo "<p>Connexion réussie.</p>";
                exit;
            } else {
            header("Location: ./../index.php?error=user_notexists");
                exit;
            }
        } else {
            header("Location: ./../index.php?error=user_notexists");
            exit;
        }
    } else {
        echo "<p>Veuillez remplir tous les champs.</p>";
    }
}
?>