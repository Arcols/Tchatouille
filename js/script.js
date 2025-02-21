window.onload = function() {
    recupererMessages();
    setInterval(recupererMessages, 2000); // 2 secondes
    scrollToBottom();
};

function scrollToBottom() {
    var chatDiv = $("#chat");
    setTimeout(() => {
        chatDiv.scrollTop(chatDiv.prop("scrollHeight")+10);
    }, 100); // Petit délai pour attendre la mise à jour du DOM
}

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
            scrollToBottom();
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
        var messageHeader = $("<div>").addClass("message-header");
        messageHeader.append($("<p>").text(message.auteur));
        messageHeader.append($("<p>").addClass("time").text(message.horaire));
        messageElement.append(messageHeader);
        messageElement.append($("<p>").addClass("message-content").text(message.contenu));
        chatDiv.append(messageElement);
    });
}

gererMessage();