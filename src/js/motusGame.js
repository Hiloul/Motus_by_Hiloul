document.getElementById('gameForm').addEventListener('submit', function(e) {
    e.preventDefault();

    var guess = document.getElementById('guess').value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", 'check_guess.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send('guess=' + encodeURIComponent(guess));

    xhr.onload = function() {
        if (this.status == 200) {
            var response = JSON.parse(this.responseText);
            var result = '';

            for (var i = 0; i < response.length; i++) {
                if (response[i] == 'red') {
                    result += '<span style="color: red;">' + guess[i] + '</span>';
                } else if (response[i] == 'yellow') {
                    result += '<span style="color: yellow;">' + guess[i] + '</span>';
                } else {
                    result += '<span>' + guess[i] + '</span>';
                }
            }

            document.getElementById('result').innerHTML = result;
        }
    }
});
