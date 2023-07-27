document.getElementById("wordForm").addEventListener("submit", function (e) {
    e.preventDefault();

    var word = document.getElementById("word").value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "src/php/motus.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("word=" + encodeURIComponent(word));

    xhr.onload = function () {
        if (this.status == 200) {
            try {
                var response = JSON.parse(this.responseText);
                var wordElement = document.getElementById("wordDisplay");
                wordElement.innerHTML = '';
                for (var i = 0; i < response.length; i++) {
                    var letterElement = document.createElement('span');
                    letterElement.textContent = response[i].letter;
                    letterElement.style.color = response[i].color;
                    wordElement.appendChild(letterElement);
                }
            } catch (error) {
                console.error("Error while parsing the JSON response: ", error);
            }
        }
    };

    xhr.onerror = function () {
        console.error("Error while making the XMLHttpRequest: ", xhr.status, xhr.statusText);
    };
});
