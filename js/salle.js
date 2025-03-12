window.onload = function() {
    recupererSalles();
    // Initialiser avec la première salle
    setTimeout(() => {
        var firstSalle = $(".salle").first();
        if (firstSalle.length) {
            changerSalle(firstSalle.data("id"));
        }
    }, 500);
    scrollToBottom();
    setInterval(() => {
        var activeSalleId = $(".salle.active").data("id");
        if (activeSalleId) {
            recupererMessages(activeSalleId);
        }
        actualiserDerniersMessages();
    }, 2000); // Actualiser les messages et les derniers messages toutes les 2 secondes
};

function scrollToBottom() {
    var chatDiv = $("#chat");
    setTimeout(() => {
        chatDiv.scrollTop(chatDiv.prop("scrollHeight") + 10);
    }, 200); // Petit délai pour attendre la mise à jour du DOM
}

function getPseudo() {
    return pseudo;
}

function getMessage() {
    return $('#message').val();
}

function envoyerMessage(inputMessage){
    var auteur = getPseudo();
    if (auteur === '') {
        alert('Veuillez saisir un pseudo');
        return;
    }
    var contenu = getMessage();
    console.log(contenu);
    if (contenu === '') {
        return;
    }
    var salleId = $(".salle.active").data("id");
    var requete = new XMLHttpRequest();
    requete.open("POST", "./../php/enregistrer.php", true);
    requete.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
    requete.onreadystatechange = function() {
        if (requete.readyState === 4 && requete.status === 200) {
            recupererMessages(salleId);
            actualiserDerniersMessages(); // Actualiser les derniers messages après l'envoi d'un message
        }
    };
    requete.send("auteur=" + encodeURIComponent(auteur) + "&contenu=" + encodeURIComponent(contenu) + "&salle=" + salleId);
    inputMessage.val('');
    scrollToBottom();
}

function gererMessage() {
    var inputMessage = $('#message');
    inputMessage.keypress(function(e) {
        if (e.which === 13) {
            envoyerMessage(inputMessage);
        }
    });
    $('#envoyerMessage').click(function() {
        envoyerMessage(inputMessage);
    });
}

function recupererMessages(salleId) {
    var requete = new XMLHttpRequest();
    requete.open("GET", "./../php/recuperer.php?salle=" + salleId, true);
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
    messages.reverse().forEach(function(message) {
        var messageElement = $("<div>").addClass("message").attr("data-id", message.id);
        var messageHeader = $("<div>").addClass("message-header");
        messageHeader.append($("<p>").text(message.auteur));
        messageHeader.append($("<p>").addClass("time").text(message.horaire));
        messageElement.append(messageHeader);
        var messageEncode = htmlspecialchars_decode(message.contenu);
        messageElement.append($("<p>").addClass("message-content").text(insertRetourLigne(messageEncode, 50)));
        if (message.auteur === getPseudo()) {
            messageElement.addClass("right");
        }
        if (!chatDiv.find(".message[data-id='" + message.id + "']").length) {
            chatDiv.append(messageElement);
        }
    });
}


function recupererSalles() {
    var requete = new XMLHttpRequest();
    requete.open("GET", "./../php/salles.php", true);
    requete.onreadystatechange = function() {
        if (requete.readyState === 4 && requete.status === 200) {
            var salles = JSON.parse(requete.responseText);
            afficherSalles(salles);
        } else if (requete.readyState === 4) {
            console.error("Erreur lors de la récupération des salles : " + requete.status);
        }
    };
    requete.send();
}

function afficherSalles(salles) {
    var sallesDiv = $("#salles");
    sallesDiv.empty();
    salles.forEach(function(salle) {
        var salleElement = $("<div>").addClass("salle").attr("data-id", salle.id);
        salleElement.append($("<h3>").text(salle.nom));
        salleElement.append($("<p>").addClass("dernier-message").text(htmlspecialchars_decode(salle.dernier_message)));
        salleElement.on("click", function() {
            changerSalle(salle.id);
        });
        sallesDiv.append(salleElement);
    });
}

function changerSalle(salleId) {
    // Mettre à jour l'interface utilisateur pour indiquer la salle sélectionnée
    var chatDiv = $("#chat");
    chatDiv.empty();
    $(".salle").removeClass("active");
    $(".salle[data-id='" + salleId + "']").addClass("active");
    $(".salle[data-id='" + salleId + "'] .dernier-message").removeClass("new");

    // Récupérer les messages de la salle sélectionnée
    recupererMessages(salleId);
    setTimeout(() => {
        scrollToBottom();
    }, 500);

}

function actualiserDerniersMessages() {
    var requete = new XMLHttpRequest();
    requete.open("GET", "./../php/salles.php", true);
    requete.onreadystatechange = function() {
        if (requete.readyState === 4 && requete.status === 200) {
            var salles = JSON.parse(requete.responseText);
            salles.forEach(function(salle) {
                var salleElement = $(".salle[data-id='" + salle.id + "']");
                var dernierMessageElement = salleElement.find(".dernier-message");
                var dernierMessage = dernierMessageElement.text();
                if (dernierMessage !== salle.dernier_message) {
                    dernierMessageElement.text(htmlspecialchars_decode(salle.dernier_message));
                    if (!salleElement.hasClass("active")) {
                        dernierMessageElement.addClass("new");
                    }
                }
            });
        }
    };
    requete.send();
}

/**
 * htmlspecialchars_decode mais en javascript
 * @param str à décoder
 * @returns str décodé
 */
function htmlspecialchars_decode(str) {
    if (str === null || str === undefined) {
        return '';
    }
    var map = {
        '&amp;': '&',
        '&lt;': '<',
        '&gt;': '>',
        '&quot;': '"',
        '&#039;': "'"
    };
    return str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function(m) { return map[m]; });
}

/**
 * Insère des sauts de ligne dans une chaîne de caractères
 * @param str
 * @param maxLength
 * @returns {string}
 */
function insertRetourLigne(str, maxLength) {
    var result = '';
    while (str.length > maxLength) {
        result += str.substring(0, maxLength) + '\n';
        str = str.substring(maxLength);
    }
    return result + str;
}

gererMessage();