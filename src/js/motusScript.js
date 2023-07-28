document.getElementById("wordForm").addEventListener("submit", function (e) {
  e.preventDefault();

  var word = document.getElementById("wordInput").value; // Change "word" to "wordInput"

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "src/php/motus.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("word=" + encodeURIComponent(word));

  xhr.onload = function () {
    if (this.status == 200) {
      var response;
      try {
          response = JSON.parse(this.responseText);
      } catch(e) {
          console.log('Erreur de parsing JSON: ', e);
          return;
      }
      if (response.result) {
          var wordElement = document.getElementById("wordDisplay");
          wordElement.innerHTML = '';
          for (var i = 0; i < response.result.length; i++) {
              var letterElement = document.createElement('span');
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
          attemptsElement.textContent = "Nombre de tentatives : " + response.attempts;
      }
    }
  };
});

document.getElementById("regenerate").addEventListener("click", function(e) {
  e.preventDefault();

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "src/php/regenerate.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send();

  xhr.onload = function () {
    if (this.status == 200) {
      document.getElementById("wordInput").value = ''; // Use value instead of innerHTML for input elements
      document.getElementById("attemptsCounter").textContent = "0 tentatives";
      document.getElementById("errorMessage").textContent = "Mot changé avec succes !";
    }
  };
});
