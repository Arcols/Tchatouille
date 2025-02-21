<?php
session_start();
if (!isset($_SESSION['pseudo'])) {
    header("Location: ./../index.html.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MessagerieChat</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <link rel="stylesheet" href="./../css/global.css">
    <link rel="stylesheet" href="./../css/chat.css">
</head>
<body>
    <div class="container">
        <div id="chat">
            <!-- Le contenu du chat sera ici -->
        </div>
        <div id="messageDiv">
            <label for="message">Entrez votre message :</label>
            <input type="text" id="message" name="message" value="">
        </div>
    </div>
    <script>
        var pseudo = "<?php echo isset($_SESSION['pseudo']) ? $_SESSION['pseudo'] : ''; ?>";
    </script>
    <script src="../js/script.js"></script>
</body>
</html>