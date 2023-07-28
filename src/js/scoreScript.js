document.addEventListener("DOMContentLoaded", function() {
    var scoreButton = document.getElementById("scoreButton");

    if(scoreButton) {
        scoreButton.addEventListener("click", function() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "src/php/score.php", true);
            xhr.onload = function() {
                if (this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    var scoreList = document.getElementById("scoreList");
                    scoreList.innerHTML = ''; // clear existing scores
                    if (response.error) {
                        var errorItem = document.createElement("li");
                        errorItem.textContent = response.error;
                        scoreList.appendChild(errorItem);
                    } else {
                        for (var i = 0; i < response.length; i++) {
                            var scoreItem = document.createElement("li");
                            scoreItem.textContent = "Score: " + response[i].score;
                            scoreList.appendChild(scoreItem);
                        }
                    }
                }
            };
            xhr.send();
        });
    }
});
