<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Salle</title>
    <link rel="stylesheet" href="./../css/global.css">
    <link rel="stylesheet" href="./../css/ajoutsalle.css">
</head>
<body>
    <h2>Créer une nouvelle salle</h2>
    <form action="./../php/addsalle.php" method="POST">
        <label for="nom">Nom de la salle :</label>
        <input type="text" id="nom" name="nom" required><br><br>

        <label for="description">Description :</label>
        <input type="text" id="description" name="description" required><br><br>

        <label for="users">Utilisateurs (séparés par des virgules) :</label>
        <input type="text" id="users" name="users" required placeholder="ex: user1, user2, user3"><br><br>

        <button type="submit">Créer la salle</button>
        <button id="goback" onclick="window.location.href='./index.html.php'">Retour à l'accueil</button>
    </form>
</body>
</html>
