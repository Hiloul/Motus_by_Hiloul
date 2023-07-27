$(document).ready(function(){
    $('#formMotus').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'motus.php',
            type: 'post',
            data: {motSaisi: $('#motSaisi').val()},
            success: function(reponse) {
                $('#reponse').text(reponse);
            }
        });
    });
});
