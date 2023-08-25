 $(document).ready(function () {
    var cpf = $("#iduser").val();
    $.ajax({
        url: 'checa_status_recurso.php',
        data: { iduser: cpf },
        dataType: "json",
        type: 'POST',
        beforeSend: function () {
            console.log("Buscando dados de recurso");
        },
        success: function (data, textStatus) {

            console.log(data);

            $('#statusRecurso').val(data.situacao_recurso);
            $('#statusIndeferimento').val(data.motivo_indeferimento_recurso);
            if(data.situacao_recurso == 'deferido'){
              $("#formStatusRecurso").css("visibility", "hidden");
              $("#statusIndeferimento").removeAttr("required");
            }

        },
        complete: function () {

        },
        error: function (xhr, er) {
            alert("Erro ao consulta de dados " + xhr + ", " + er);
        }
    });
});


$('#statusRecurso').on('change', function () {
    if (this.value == 'indeferido') {
        $("#formStatusRecurso").css("visibility", "visible");
        $("#statusIndeferimento").attr("required", "req");
    } else {
        $("#formStatusRecurso").css("visibility", "hidden");
        $("#statusIndeferimento").removeAttr("required");
    }

});

$('#formAnaliseRecurso').on('submit', function (e) {
    e.preventDefault();
    var form_data = new FormData();
    form_data.append('iduser', $("#iduser").val());
    form_data.append('idcurso', $("#idcurso").val());
    form_data.append('situacao_recurso', $("#statusRecurso").val());
    form_data.append('motivo_indeferimento_recurso', $("#statusIndeferimento").val());

    $.ajax({
        type: 'POST',
        url: 'atualiza_status_recurso.php',
        data: form_data,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function () {
            $('#msgAguardeStatus').html('Aguarde, enviando dados do formulário...');
            console.log('Aguarde, enviando dados do formulário...');
        },
        success: function (response) {
            console.log(response);
            $('#msgAguardeStatus').html('Sucesso, dados enviados!');
        },
        error: function (request, status, error) {
            $('#msgAguardeStatus').html('Erro ao enviar formulário!');
            console.log(request.responseText);
        }
    });

});
