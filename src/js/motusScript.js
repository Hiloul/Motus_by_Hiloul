document.getElementById("wordForm").addEventListener("submit", function (e) {
    e.preventDefault();
  
    var word = document.getElementById("word").value;
  
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "src/php/motus.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("word=" + encodeURIComponent(word));
  
    xhr.onload = function () {
      if (this.status == 200) {
        var response = JSON.parse(this.responseText);
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
            // message d'erreur sur la page
            var errorElement = document.getElementById("errorMessage");
            errorElement.textContent = response.error;
        }
        if (response.attempts) {
            // maj des tentatives sur la page
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
        // reset
        document.getElementById("wordDisplay").innerHTML = '';
        document.getElementById("attemptsCounter").textContent = "0 tentatives";
        document.getElementById("errorMessage").textContent = "Mot changé avec succes !";
      }
    };
});


