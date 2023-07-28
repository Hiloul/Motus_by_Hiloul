// Formulaire principale du jeu
document.getElementById("wordForm").addEventListener("submit", function (e) {
  e.preventDefault();

  var word = document.getElementById("wordInput").value;

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "src/php/motus.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("word=" + encodeURIComponent(word));

  xhr.onload = function () {
    if (this.status == 200) {
      var response;
      try {
        var response = JSON.parse(this.responseText);
      } catch (e) {
        console.error("Failed to parse response as JSON: ", this.responseText);
        return;
      }
      if (response.result) {
        var wordElement = document.getElementById("wordDisplay");
        wordElement.innerHTML = "";
        for (var i = 0; i < response.result.length; i++) {
          var letterElement = document.createElement("span");
          letterElement.textContent = response.result[i].letter;
          letterElement.style.color = response.result[i].color;
          wordElement.appendChild(letterElement);
        }
      }
      if (response.error) {
        var errorElement = document.getElementById("errorMessage");
        errorElement.textContent = response.error;
      }
      if (response.attempts) {
        var attemptsElement = document.getElementById("attemptsCounter");
        attemptsElement.textContent =
          "Nombre de tentatives : " + response.attempts;
      }
    }
  };

  xhr.onerror = function () {
    var errorElement = document.getElementById("errorMessage");
    errorElement.textContent =
      "Une erreur s'est produite lors de l'envoi de la requête.";
  };
});

// Bouton changer de mot
document.getElementById("regenerate").addEventListener("click", function (e) {
  e.preventDefault();

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "src/php/regenerate.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send();

  xhr.onload = function () {
    if (this.status == 200) {
      document.getElementById("wordInput").value = "";
      document.getElementById("attemptsCounter").textContent = "0 tentatives";
      document.getElementById("errorMessage").textContent =
        "Mot changé avec succes !";
    }
  };

  xhr.onerror = function () {
    var errorElement = document.getElementById("errorMessage");
    errorElement.textContent =
      "Une erreur s'est produite lors de l'envoi de la requête.";
  };
});
