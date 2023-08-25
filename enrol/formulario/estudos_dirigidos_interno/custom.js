//Mostrar botao de incricao apenas quando o usuario preencher tudo corretamente
var f_dados_pessoais = false;
var f_endereco = false;
var f_contato = false;
var f_lotacao = false;



function showSubmissao() {
  if (f_dados_pessoais == true && f_endereco == true && f_contato == true && f_lotacao == true) {
    $("#id_submitbutton").css("visibility", "visible");
  } else {
    $("#id_submitbutton").css("visibility", "hidden");
  }
}



function showDataEstagio() {
  $("#datasEstagio").css("visibility", "visible");
  $("#estagioDataInicio").attr("required", "req");
  $("#estagioPrevisaoTermino").attr("required", "req");
  console.log("Mostrando data de estágio");
}

function hiddenDataEstagio() {
  $("#datasEstagio").css("visibility", "hidden");
  $("#estagioDataInicio").removeAttr("required");
  $("#estagioPrevisaoTermino").removeAttr("required");
  console.log("Ocultando data de estágio")
}


$(document).ready(function () {
  //Ocultar não preenchidos
  // $("#g_endereco").css("visibility", "hidden");
  // $("#g_contato").css("visibility", "hidden");
  // $("#g_lotacao").css("visibility", "hidden");

  //Ocultar o btn de atualizar dados
  $("#id_submitbutton").css("visibility", "hidden");


  //Pegar os dados do banco e preencher o formulario na tela para usuario
  var id = $('#id').val();
  var idUser = $('#idUser').val();
  var idCurso = $('#idCurso').val();
  var idCPF = $('#idCPF').val();

  $.ajax({
    url: 'buscar_usuario.php',
    data: {
      id: id,
      idUser: idUser,
      idCurso: idCurso,
      idCPF: idCPF
    },
    dataType: "json",
    type: 'POST',
    beforeSend: function () {
      console.log("Buscando dados de usuario");
    },
    success: function (data, textStatus) {

      console.log(data);
      $('#nomeCompleto').val(data.firstname + ' ' + data.lastname);
      $('#dataNacimento').val(data.data_nacimento);
      $('#nomeMae').val(data.nome_mae);
      $('#nomePai').val(data.nome_pai);
      $('#rg').val(data.rg);
      $('#orgaoExpedidor').val(data.orgao_expedidor);

      $('#enderecoCep').val(data.endereco_cep);
      $('#enderecoNumero').val(data.endereco_numero);
      $('#enderecoQuadra').val(data.endereco_quadra);
      $('#enderecoLote').val(data.endereco_lote);
      $('#enderecoCidade').val(data.endereco_cidade);
      $('#logadouro').val(data.endereco_logadouro);
      $('#enderecoBairro').val(data.endereco_bairro);
      $('#enderecoComplemento').val(data.endereco_complemento);
      $('#estadoUf').val(data.endereco_estado_uf);

      $('#telefoneContato').val(data.contato_telefone);
      $('#whatsappNumero').val(data.contato_whatsapp);

      $('#lotacaoNucleo').val(data.lotacao_nucleo);
      $('#lotacaoCargo').val(data.lotacao_cargo);
      $('#estagioDataInicio').val(data.lotacao_data_inicio);
      $('#estagioPrevisaoTermino').val(data.lotacao_data_fim);
      showLinkDownloadSupHie(data.lotacao_link_autori);


    },
    complete: function () {

    },
    error: function (xhr, er) {
      alert("Erro ao consultaro o usuario " + xhr + ", " + er);
    }
  });

  //Seletor Cargo
  $('#lotacaoCargo').on('change', function () {
    if (this.value == "EstagiarioGraduacao" || this.value == "EstagiarioPosGraduacao") {
      showDataEstagio();
    } else {
      hiddenDataEstagio();
    }

  });

//Consulta o endereço

  $('#enderecoCep').keyup(function() {
    $(this).val(this.value.replace(/\D/g, ''));
  });


  $('#enderecoCep').blur(function () {
    var form_data = new FormData();
    form_data.append('cep', $("#enderecoCep").val().trim());
    $.ajax({
      type: 'POST',
      url: 'consulta_cep.php',
      data: form_data,
      dataType: 'json',
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function () {
        $('#enderecoCep').val($("#enderecoCep").val().trim());
        console.log('Aguarde, consultando CEP...');
      },
      success: function (response) {
        $('#enderecoCidade').val(response.localidade);
        $('#logadouro').val(response.logradouro);
        $('#enderecoBairro').val(response.bairro);
        $('#estadoUf').val(response.uf);
        console.log(response);

      },
      error: function (request, status, error) {
        console.log(request.responseText);
      }
    });
  });

  //Dados pessoais-----------------------------------------------------
  $("#formDadosPessoais").on('submit', function (e) {
    e.preventDefault();

    var form_data = new FormData();
    form_data.append('id', $("#idUser").val());
    form_data.append('id_curso', $("#idCurso").val());
    form_data.append('cpf', $("#idCPF").val());
    form_data.append('tipo_formulario', 'dados_pessoais');
    form_data.append('nome_completo', $("#nomeCompleto").val());
    form_data.append('data_nacimento', $("#dataNacimento").val());
    form_data.append('rg', $("#rg").val());
    form_data.append('orgao_expedidor', $("#orgaoExpedidor").val());
    form_data.append('nome_mae', $("#nomeMae").val());
    form_data.append('nome_pai', $("#nomePai").val());
    $.ajax({
      type: 'POST',
      url: 'atualiza_usuario.php',
      data: form_data,
      dataType: 'json',
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function () {
        $('#msgAguardeDadosPessoais').html('Aguarde, enviando dados do formulário...');
        console.log('Aguarde, enviando dados do formulário...');
      },
      success: function (response) {
        console.log(response);
        $('#msgAguardeDadosPessoais').html('Sucesso, dados enviados!');
        $('#collapseOne').removeClass('show');
        $('#collapseTwo').addClass("show");
        $("#g_endereco").css("visibility", "visible");
        f_dados_pessoais = true;
        showSubmissao();
      },
      error: function (request, status, error) {
        $('#msgAguardeDadosPessoais').html('Erro ao enviar formulário!');
        console.log(request.responseText);
      }
    });

  });




//Endereço-----------------------------------------------------------

$("#formEndereco").on('submit', function (e) {
  e.preventDefault();

  var form_data = new FormData();
  form_data.append('id', $("#idUser").val());
  form_data.append('id_curso', $("#idCurso").val());
  form_data.append('cpf', $("#idCPF").val());
  form_data.append('tipo_formulario', 'endereco');
  form_data.append('endereco_cep', $("#enderecoCep").val());
  form_data.append('endereco_numero', $("#enderecoNumero").val());
  form_data.append('endereco_quadra', $("#enderecoQuadra").val());
  form_data.append('endereco_lote', $("#enderecoLote").val());
  form_data.append('endereco_cidade', $("#enderecoCidade").val());
  form_data.append('endereco_complemento', $("#enderecoComplemento").val());
  form_data.append('endereco_bairro', $("#enderecoBairro").val());
  form_data.append('endereco_logadouro', $("#logadouro").val());
  form_data.append('endereco_estado_uf', $('#estadoUf').val());


  $.ajax({
    type: 'POST',
    url: 'atualiza_usuario.php',
    data: form_data,
    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,
    beforeSend: function () {
      $('#msgAguardeEndereco').html('Aguarde, enviando dados do formulário...');
      console.log('Aguarde, enviando dados do formulário...');
    },
    success: function (response) {
      console.log(response);
      $('#msgAguardeEndereco').html('Sucesso, dados enviados!');
      $('#collapseTwo').removeClass('show');
      $('#collapseThree').addClass("show");
      $("#g_contato").css("visibility", "visible");
      f_endereco = true;
      showSubmissao();
    },
    error: function (request, status, error) {
      $('#msgAguardeEndereco').html('Erro ao enviar formulário!');
      console.log(request.responseText);
    }
  });



});



//Contato------------------------------------------------------------

$("#formContato").on('submit', function (e) {
  e.preventDefault();
  var form_data = new FormData();
  form_data.append('id', $("#idUser").val());
  form_data.append('id_curso', $("#idCurso").val());
  form_data.append('cpf', $("#idCPF").val());
  form_data.append('tipo_formulario', 'contato');
  form_data.append('telefone_contato', $("#telefoneContato").val());
  form_data.append('whatsapp_numero', $("#whatsappNumero").val());

  $.ajax({
    type: 'POST',
    url: 'atualiza_usuario.php',
    data: form_data,
    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,
    beforeSend: function () {
      $('#msgAguardeContato').html('Aguarde, enviando dados do formulário...');
      console.log('Aguarde, enviando dados do formulário...');
    },
    success: function (response) {
      console.log(response);
      $('#msgAguardeContato').html('Sucesso, dados enviados!');
      $('#collapseThree').removeClass('show');
      $('#collapseFour').addClass("show");
      $("#g_lotacao").css("visibility", "visible");
      f_contato = true;
      showSubmissao();
    },
    error: function (request, status, error) {
      $('#msgAguardeContato').html('Erro ao enviar formulário!');
      console.log(request.responseText);
    }
  });
});


//Lotação------------------------------------------------------------


$("#formLotacao").on('submit', function (e) {
  e.preventDefault();
  var form_data = new FormData();
  form_data.append('id', $("#idUser").val());
  form_data.append('id_curso', $("#idCurso").val());
  form_data.append('cpf', $("#idCPF").val());
  form_data.append('tipo_formulario', 'lotacao');
  form_data.append('lotacao_nucleo', $("#lotacaoNucleo").val());
  form_data.append('lotacao_cargo', $("#lotacaoCargo").val());
  form_data.append('lotacao_data_inicio', $("#estagioDataInicio").val());
  form_data.append('lotacao_data_fim', $("#estagioPrevisaoTermino").val());
  form_data.append('fileAutorizacaoSupHierar', $('#fileAutorizacaoSupHierar').prop('files')[0]);

  $.ajax({
    type: 'POST',
    url: 'atualiza_usuario.php',
    data: form_data,
    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,
    beforeSend: function () {
      $('#msgAguardeLotacao').html('Aguarde, enviando dados do formulário...');
      console.log('Aguarde, enviando dados do formulário...');
    },
    success: function (response) {
      console.log(response);
      $('#msgAguardeLotacao').html('Sucesso, dados enviados!');
      $('#collapseFour').removeClass('show');
      showLinkDownloadSupHie(response.lotacao_link_autori);
      f_lotacao = true;
      showSubmissao();
    },
    error: function (request, status, error) {
      $('#msgAguardeLotacao').html('Erro ao enviar formulário!');
      console.log(request.responseText);
    }
  });
});

//Link download
function showLinkDownloadSupHie(link) {
  if (link) {
    $("#downAutoririzacaoSupHierar").replaceWith(' <a href="' + link + '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Arquivo já enviado, um novo envio sobrescreverá o arquivo anterior.</strong></a>');
  }
}










});







