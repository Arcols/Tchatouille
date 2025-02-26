<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    die("Accès refusé. Veuillez vous connecter.");
}

$host = "mysql-tchatouille.alwaysdata.net";
$dbname = "tchatouille_bdd";
$username = "400943";
$password = '$iutinfo';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['users']) || empty($_POST['users'])) {
        die("Veuillez sélectionner au moins un utilisateur.");
    }

    $nom = trim($_POST['nom']);
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;
    $users = array_map('trim', explode(',', $_POST['users']));
    $admin = $_SESSION['pseudo']; // L'utilisateur connecté est l'admin

    if (empty($nom)) {
        die("Données invalides.");
    }

    try {
        $pdo->beginTransaction();

        // Insérer la salle
        $stmt = $pdo->prepare("INSERT INTO salles (nom, description) VALUES (:nom, :description)");
        $stmt->execute([':nom' => $nom, ':description' => $description]);

        // Récupérer l'ID de la salle insérée
        $id_salle = $pdo->lastInsertId();

        // Ajouter l'admin dans la table "acces"
        $stmt = $pdo->prepare("INSERT INTO acces (id_salle, user, role) VALUES (:id_salle, :pseudo, :role)");
        $stmt->execute([':id_salle' => $id_salle, ':pseudo' => $admin, ':role' => 'admin']);

        // Ajouter les autres utilisateurs en tant que "user"
        $stmt = $pdo->prepare("INSERT INTO acces (id_salle, user, role) VALUES (:id_salle, :pseudo, :role)");
        foreach ($users as $pseudo) {
            if (!empty($pseudo) && $pseudo !== $admin) { // Évite d'ajouter l'admin en double
                $stmt->execute([':id_salle' => $id_salle, ':pseudo' => $pseudo, ':role' => 'user']);
            }
        }

        $pdo->commit();
        header("Location: ./../pages/index.html.php");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erreur lors de l'ajout de la salle : " . $e->getMessage());
    }
}
?>
