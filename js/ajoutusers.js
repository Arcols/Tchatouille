document.addEventListener("DOMContentLoaded", function() {
    chargerUtilisateurs();
    ajouterEvenementSelection();
    // Evenement quand on enverra le formulaire
    document.getElementById("formSalle").addEventListener("submit", function (event) {
        mettreAJourUtilisateurs();  // Mets à jour les utilisateurs sélectionnés
    });
});

/**
 * Charge les utilisateurs depuis la base de données et les insère dans le <select>
 */
function chargerUtilisateurs() {
    var requete = new XMLHttpRequest();
    requete.open("GET", "./../php/getusers.php", true);
    requete.onreadystatechange = function() {
        if (requete.readyState === 4 && requete.status === 200) {
            var users = JSON.parse(requete.responseText);
            users.forEach(function(user) {
                ajouterLigneSelect(user);
            });
        }
    };
    requete.send();
}

/**
 * Ajoute un événement pour gérer la sélection d'un utilisateur
 */
function ajouterEvenementSelection() {
    $("#userSelect").change(function() {
        var userSelectionne = $(this).val();
        if (userSelectionne) {
            ajouterUtilisateurTable(userSelectionne);
            supprimerLigneSelect(userSelectionne);
        }
    });
}

/**
 * Ajoute un utilisateur sélectionné à la table avec une croix pour suppression
 */
function ajouterUtilisateurTable(user) {
    var row = $("<tr></tr>");

    // Colonne avec l'icône de suppression
    var deleteCell = $("<td></td>");
    var deleteIcon = $("<img>").attr("src", "./../anex/croix.svg")
        .attr("alt", "Supprimer")
        .addClass("delete-icon")
        .click(function() {
            supprimerUtilisateurTable(row, user);
        });
    deleteCell.append(deleteIcon);

    // Colonne avec le pseudo
    var userCell = $("<td></td>").text(user);

    row.append(userCell);
    row.append(deleteCell);
    $("#userTable").append(row);
}

/**
 * Supprime un utilisateur de la table et le remet dans la liste déroulante
 */
function supprimerUtilisateurTable(row, user) {
    row.remove(); // Supprime la ligne du tableau
    ajouterLigneSelect(user); // Remet l'utilisateur dans la liste
}

/**
 * Ajoute une ligne dans le <select>
 */
function ajouterLigneSelect(user) {
    var ligne = $("<option></option>").val(user).text(user);
    $("#userSelect").append(ligne);
}

/**
 * Supprime une ligne du <select>
 */
function supprimerLigneSelect(user) {
    $("#userSelect option[value='" + user + "']").remove();
}

/**
 * Met à jour le champ caché avec les utilisateurs sélectionnés en les mettant en value
 * usersHiddenInput va servir à ce que addsalle.php récupère tous les utilisateurs rentrés en paramètres
 */
function mettreAJourUtilisateurs() {
    var userTable = $("#userTable");
    var usersHiddenInput = $("#usersHidden");
    console.log(userTable);
    var user = [];
    userTable.find('tr').each(function(index,element){
        var usertr = $(element).clone();
        user.push(usertr.find('td:first').text());
    });

    usersHiddenInput.val(user.join(","));  // Si plusieurs utilisateurs, ils seront séparés par une virgule
}
