<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Salle</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <link rel="stylesheet" href="./../css/global.css">
    <link rel="stylesheet" href="./../css/ajoutsalle.css">
</head>
<body>
    <h2>Créer une nouvelle salle</h2>
    <form id="formSalle" action="./../php/addsalle.php" method="POST">
        <label for="nom">Nom de la salle :</label>
        <input type="text" id="nom" name="nom" required><br><br>

        <label for="description">Description :</label>
        <input type="text" id="description" name="description"><br><br>

        <label for="users">Utilisateurs :</label>
        <select id="userSelect">
            <option value="">Sélectionnez un utilisateur</option>
        </select>
        <br><br>

        <table>
            <thead>
                <tr>
                    <th><label>Personnes ajoutées à votre salle :</label></th>
                </tr>
            </thead>
            <tbody id="userTable">
            </tbody>
        </table>

        <input type="hidden" name="users" id="usersHidden">
        <button id="submit" type="submit">Créer la salle</button>
        <button id="goback" onclick="window.location.href='./index.html.php'">Retour à l'accueil</button>
    </form>
    <script src="../js/ajoutusers.js"></script>
</body>
</html>
