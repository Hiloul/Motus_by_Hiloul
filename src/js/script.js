// $(document).ready(function(){
//     $('#formUsername').on('submit', function(e) {
//         e.preventDefault();
//         var username = $('#username').val();
//         if (username.trim() === "") { // Si le nom d'utilisateur est vide
//             alert("Veuillez entrer un nom d'utilisateur.");
//         } else {
//             $('#formUsername').hide();
//             $('#formMotus').show();
//         }
//     });

//     $('#formMotus').on('submit', function(e) {
//         e.preventDefault();
//         $.ajax({
//             url: 'motus.php',
//             type: 'post',
//             data: {username: $('#username').val(), motSaisi: $('#motSaisi').val()},
//             success: function(reponse) {
//                 $('#reponse').text(reponse);
//             }
//         });
//     });
// });
document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();

    var username = document.getElementById('username').value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", 'saveUser.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send('username=' + encodeURIComponent(username));

    xhr.onload = function() {
        if (this.status == 200) {
            var response = JSON.parse(this.responseText);
            var errorMessageElement = document.getElementById('errorMessage');
            var successMessageElement = document.getElementById('successMessage');
            if (response.error) {
                // Afficher le message d'erreur
                errorMessageElement.textContent = response.error;
                // Effacer le message de succès s'il y en a un
                successMessageElement.textContent = '';
            } else {
                // Effacer le message d'erreur s'il y en a un
                errorMessageElement.textContent = '';
                // Afficher le message de succès
                successMessageElement.textContent = response.success;
                // Rediriger l'utilisateur vers la page de jeu après 2 secondes
                setTimeout(function() {
                    window.location.href = 'homeGame.html';
                }, 2000);
            }
        }
    }
});
