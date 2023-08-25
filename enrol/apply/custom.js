$('#statusIscricao').on('change', function() {
  if(this.value == 'indeferida'){
    $("#formStatusInscricao").css("visibility", "visible");
    $("#statusIndeferimento").attr("required", "req");
  } else {
    $("#formStatusInscricao").css("visibility", "hidden");
    $("#statusIndeferimento").removeAttr("required");
  }

});

$('#formAnaliseInscricao').on('submit', function(e){
  e.preventDefault();
  var form_data = new FormData();
  form_data.append('id', 'x');
  form_data.append('status_iscricao', $("#statusIscricao").val());
  form_data.append('status_indeferimento', $("#statusIndeferimento").val());

  $.ajax({
      type: 'POST',
      url: 'atualiza_status_inscricao.php',
      data: form_data,
      dataType: 'json',
      contentType: false,
      cache: false,
      processData:false,
      beforeSend: function(){
        $('#msgAguardeStatus').html('Aguarde, enviando dados do formulário...');
        console.log('Aguarde, enviando dados do formulário...');
      },
      success: function(response){
          console.log(response);
          $('#msgAguardeStatus').html('Sucesso, dados enviados!');
          f_contato = true;
          showSubmissao();
      },
      error: function(request, status, error) {
          $('#msgAguardeStatus').html('Erro ao enviar formulário!');
          console.log(request.responseText);
      }
  });

});
