<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    die("Accès refusé. Veuillez vous connecter.");
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['nom']) || !isset($_POST['description']) || !isset($_POST['users'])) {
        die("Veuillez remplir tous les champs.");
    }

    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $users = array_map('trim', explode(',', $_POST['users']));
    $admin = $_SESSION['pseudo']; // L'utilisateur connecté est l'admin

    if (empty($nom) || empty($description)) {
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
        foreach ($users as $pseudo) {
            if (!empty($pseudo) && $pseudo !== $admin) { // Évite d'ajouter l'admin en double
                $stmt->execute([':id_salle' => $id_salle, ':pseudo' => $pseudo, ':role' => 'user']);
            }
        }

        $pdo->commit();
        header("Location: ./../pages/index.html.php");
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erreur lors de l'ajout de la salle : " . $e->getMessage());
    }
}
?>
