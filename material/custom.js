$(document).ready(function () {




    $("#formDocumentos").on('submit', function (e) {
        e.preventDefault();

        var form_data = new FormData();
        form_data.append('nomecurso', $("#nomecurso").val());
        form_data.append('documento', $('#documento').prop('files')[0]);


        $.ajax({
            type: 'POST',
            url: 'funcoes.php',
            data: form_data,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $('#msgAguardeDados').html('Aguarde, enviando dados do formulário...');
                console.log('Aguarde, enviando dados do formulário...');
            },
            success: function (response) {
                console.log(response);
                location.reload(true);

            },
            error: function (request, status, error) {
                $('#msgAguardeDados').html('Erro ao enviar formulário!');
                console.log(request.responseText);
            }
        });

    });





});
