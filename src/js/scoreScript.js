document.getElementById("wordForm").addEventListener("submit", function (e) {
    e.preventDefault();

    var proposedWord = document.getElementById("wordInput").value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "src/php/motus.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (this.status === 200) {
            var response;
            try {
                response = this.responseText ? JSON.parse(this.responseText) : null;
                if (!response) {
                    throw new Error("Empty response received from server");
                }
            } catch (e) {
                console.error("Failed to parse response as JSON: ", e);
                return;
            }

            if (response.error) {
                alert(response.error);
                return;
            }

            var isGuessed = response.result.every(function(letter) {
                return letter.color === 'green';
            });

            if (isGuessed) {
                alert("Bravo ! Vous avez deviné le mot.");
                var score = (100 / response.attempts).toFixed(2);  // Affiche le score avec 2 chiffres après la virgule
                alert("Votre score est : " + score);
            } else {
                alert("Dommage ! Réessayez.");
            }
        } else {
            alert("Une erreur s'est produite lors de la soumission du mot.");
        }
    };

    xhr.onerror = function() {
        alert("Request failed");
    };

    xhr.send("word=" + encodeURIComponent(proposedWord));
});
