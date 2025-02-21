window.onload = function() {
    recupererMessages();
    setInterval(recupererMessages, 2000); // Mettre à jour les messages toutes les 2 secondes
};

function getPseudo(){
    return pseudo;
}

function getMessage(){
    return $('#message').val();
}

function gererMessage(){
    var inputMessage = $('#message');
    inputMessage.keypress(function (e) {
        if (e.which === 13) {
            var auteur = getPseudo();
            if(auteur === ''){
                alert('Veuillez saisir un pseudo');
                return;
            }
            var contenu = getMessage();
            var requete = new XMLHttpRequest();
            requete.open("POST", "./../php/enregistrer.php", true);
            requete.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            requete.onreadystatechange = function() {
                if (requete.readyState === 4 && requete.status === 200) {
                    console.log("Message enregistré avec succès");
                }
            };
            requete.send("auteur=" + encodeURIComponent(auteur) + "&contenu=" + encodeURIComponent(contenu));
            recupererMessages();
            inputMessage.val('');
        }
    });
}

function recupererMessages() {
    var requete = new XMLHttpRequest();
    requete.open("GET", "./../php/recuperer.php", true);
    requete.onreadystatechange = function() {
        if (requete.readyState === 4 && requete.status === 200) {
            var messages = JSON.parse(requete.responseText);
            afficherMessages(messages);
        }
    };
    requete.send();
}

function afficherMessages(messages) {
    var chatDiv = $("#chat");
    chatDiv.empty();
    messages.reverse().forEach(function(message) {
        var messageElement = $("<div>").addClass("message");
        messageElement.append($("<p>").html(`${message.auteur}`));
        messageElement.append($("<p>").text(message.contenu));
        messageElement.append($("<p>").html(`${message.horaire}`));
        chatDiv.append(messageElement);
    });
}

gererMessage();