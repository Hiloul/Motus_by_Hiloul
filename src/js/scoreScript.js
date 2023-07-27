document.addEventListener("DOMContentLoaded", function() {
    var scoreButton = document.getElementById("scoreButton");

    if(scoreButton) {
        scoreButton.addEventListener("click", function() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "src/php/score.php", true);
            xhr.onload = function() {
                if (this.status == 200) {
                    var scores = JSON.parse(this.responseText);
                    var scoreList = document.getElementById("scoreList");
                    scoreList.innerHTML = ''; // clear existing scores
                    for (var i = 0; i < scores.length; i++) {
                        var scoreItem = document.createElement("li");
                        scoreItem.textContent = "Score: " + scores[i].score;
                        scoreList.appendChild(scoreItem);
                    }
                }
            };
            xhr.send();
        });
    }
});
