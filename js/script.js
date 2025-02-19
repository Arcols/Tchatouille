window.onload = function() {
    fetchMessages();
    setInterval(fetchMessages, 2000); // Update messages every 2 seconds
};

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
            fetchMessages();
            inputMessage.val('');
        }
    });
}

function fetchMessages() {
    var requete = new XMLHttpRequest();
    requete.open("GET", "./php/recuperer.php", true);
    requete.onreadystatechange = function() {
        if (requete.readyState === 4 && requete.status === 200) {
            var messages = JSON.parse(requete.responseText);
            displayMessages(messages);
        }
    };
    requete.send();
}

function displayMessages(messages) {
    var chatDiv = $("#chat");
    chatDiv.empty(); // Clear previous messages
    messages.reverse().forEach(function(message) {
        var messageElement = $("<div>").addClass("message");
        messageElement.append($("<p>").html(`${message.auteur}`));
        messageElement.append($("<p>").text(message.contenu));
        messageElement.append($("<p>").html(`${message.horaire}`));
        chatDiv.append(messageElement);
    });
}

handleMessage();