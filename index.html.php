<!DOCTYPE html>
<html>
<head>
    <title>Page de connection</title>
	<link rel="stylesheet" type="text/css" href="./css/global.css">
    <link rel="stylesheet" type="text/css" href="./css/connection.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">
        <div class="signup">
            <form method="POST" action="php/signup.php">
                <label for="chk" aria-hidden="true">Inscription</label>
                <input type="text" name="pseudo" placeholder="Identifiant" required="">
                <input type="password" name="mdp" placeholder="Mot de passe" required="">
                <button>S'inscrire</button>
            </form>
            <?php
            if (isset($_GET['error']) && $_GET['error'] === 'user_exists') {
                echo "<p style='color:red; text-align:center'>L'utilisateur existe déjà.</p>";
            }
            if (isset($_GET['success']) && $_GET['success'] === 'user_added') {
                echo "<p style='color:white; text-align:center'>Inscription réussie ! <br> Bienvenue !</p>";
            }
            ?>
        </div>

        <div class="login">
            <form method="POST" action="php/login.php">
                <label for="chk" aria-hidden="true">Connexion</label>
                <input type="text" name="pseudo" placeholder="Identifiant" required="">
                <input type="password" name="mdp" placeholder="Mot de passe" required="">
                <button>Se connecter</button>
            </form>
            <?php
            if (isset($_GET['error']) && $_GET['error'] === 'user_notexists') {
                echo "<p style='color:red; text-align:center'>L'utilisateur ou le mot de passe n'existe pas.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>