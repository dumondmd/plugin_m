$(document).ready(function () {
    var cpf = $("#iduser").val();
    var idcurso = $("#idcurso").val();
    $.ajax({
        url: 'checa_status_inscricao.php',
        data: { 
            iduser: cpf,
            idcurso: idcurso,
         },
        dataType: "json",
        type: 'POST',
        beforeSend: function () {
            console.log("Buscando dados de inscricao");
        },
        success: function (data, textStatus) {

            console.log(data);
            $('#statusIscricao').val(data.situacao_inscricao);
            $('#statusIndeferimento').val(data.motivo_indeferimento);
            if(data.situacao_inscricao != 'indeferida'){
              $('#statusIndeferimento').val('');
              $("#formStatusInscricao").css("visibility", "hidden");
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


$('#statusIscricao').on('change', function () {
    if (this.value == 'indeferida') {
        $("#formStatusInscricao").css("visibility", "visible");
        $("#statusIndeferimento").attr("required", "req");
    } else {
        $('#statusIndeferimento').val('');
        $("#formStatusInscricao").css("visibility", "hidden");
        $("#statusIndeferimento").removeAttr("required");
    }

});

$('#formAnaliseInscricao').on('submit', function (e) {
    e.preventDefault();
    var form_data = new FormData();
    form_data.append('iduser', $("#iduser").val());
    form_data.append('idcurso', $("#idcurso").val());
    form_data.append('status_iscricao', $("#statusIscricao").val());
    form_data.append('status_indeferimento', $("#statusIndeferimento").val());
    form_data.append('responsavel_analise', $("#adminName").val());

    $.ajax({
        type: 'POST',
        url: 'atualiza_status_inscricao.php',
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
