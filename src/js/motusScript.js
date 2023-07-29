// Formulaire principal du jeu
var wordForm = document.getElementById("wordForm");
var wordInput = document.getElementById("wordInput");
var wordDisplay = document.getElementById("wordDisplay");
var errorMessage = document.getElementById("errorMessage");
var attemptsCounter = document.getElementById("attemptsCounter");

if (wordForm) {
  wordForm.addEventListener("submit", function (e) {
    e.preventDefault();

    var word = wordInput ? wordInput.value : "";

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "src/php/motus.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("word=" + encodeURIComponent(word));

    xhr.onload = function () {
      if (this.status == 200) {
        console.log("Server response: ", this.responseText);  // Log the server response
        var response;
        try {
          var response = JSON.parse(this.responseText);
        } catch (e) {
          console.error("Failed to parse response as JSON: ", e);  // Log the error
          console.error("Server response: ", this.responseText);  // Log the server response
          return;
        }
        if (response.result && wordDisplay) {
          wordDisplay.innerHTML = "";
          for (var i = 0; i < response.result.length; i++) {
            var letterElement = document.createElement("span");
            letterElement.textContent = response.result[i].letter;
            letterElement.style.color = response.result[i].color;
            wordDisplay.appendChild(letterElement);
          }
        }
        if (response.error && errorMessage) {
          errorMessage.textContent = response.error;
        }
        if (response.attempts && attemptsCounter) {
          attemptsCounter.textContent = "Nombre de tentatives : " + response.attempts;
        }
      } else {
        // Gérer les erreurs de la requête
        alert("Une erreur s'est produite lors de la soumission du mot.");
      }
    };
  });
}

// Bouton changer de mot (ok)
var regenerateBtn = document.getElementById("regenerate");

if (regenerateBtn) {
  regenerateBtn.addEventListener("click", function (e) {
    e.preventDefault();

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "src/php/regenerate.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send();

    xhr.onload = function () {
      if (this.status == 200 && wordInput && attemptsCounter && errorMessage) {
        wordInput.value = "";
        attemptsCounter.textContent = "0 tentatives";
        errorMessage.textContent = "Mot changé avec succes !";
      } else {
        // Gérer les erreurs de la requête
        alert("Une erreur s'est produite lors du changement du mot.");
      }
    };
  });
}
