function getUsername(){
    return $('#pseudo').val();
}

function getMessage(){
    return $('#message').val();
}

function handleMessage(){
    var inputMessage = $('#message');
    inputMessage.keypress(function (e) {
        if (e.which == 13) {
            var auteur = getUsername();
            if(auteur === ''){
                alert('Veuillez saisir un pseudo');
                return;
            }
            var contenu = getMessage();
            var requete = new XMLHttpRequest();
            requete.open("POST", "./php/enregistrer.php", true);
            requete.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            requete.onreadystatechange = function() {
                if (requete.readyState === 4 && requete.status === 200) {
                    console.log("Message enregistré avec succès");
                }
            };
            requete.send("auteur=" + encodeURIComponent(auteur) + "&contenu=" + encodeURIComponent(contenu));
            inputMessage.val('');
        }
    });
}

handleMessage();