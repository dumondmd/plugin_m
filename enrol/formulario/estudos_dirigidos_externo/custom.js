//Mostrar botao de incricao apenas quando o usuario preencher tudo corretamente
var f_dados_pessoais = false;
var f_endereco = false;
var f_contato = false;
var f_cota = false;


function showSubmissao() {
  if (f_dados_pessoais == true && f_endereco == true && f_contato == true && f_cota == true) {
    $("#id_submitbutton").css("display", "block");
  } else {
    $("#id_submitbutton").css("display", "none");
  }
}

function showPublicoExterno()  {

  //Remoção autodeclaracao
  $("#formAutodeclaracao").css("display", "none");
  $("#autoriDecaracaoDwl").css("display", "none");
  $("#fileAutodeclaracao").removeAttr("required");

  //Remoção laudo médico
  $("#formLaudoMedico").css("display", "none");
  $("#fileLaudoMedico").removeAttr("required");

  //Remoção comprovante matricula/diploma
  $("#formCertificadoEnsino").css("display", "none");
  $("#fileCertificadoEnsino").removeAttr("required");

  //Remoção de curriculo
  $("#formCurriculo").css("display", "none");
  $("#fileCurriculo").removeAttr("required");


  console.log("Mostrando Publico Externo");
}

function showGraduacaoAfrodescedentes() {

  //Adição autodeclaracao
  $("#formAutodeclaracao").css("display", "block");
  $("#autoriDecaracaoDwl").css("display", "block");
  $("#fileAutodeclaracao").attr("required", "req");

  //Remoção laudo médico
  $("#formLaudoMedico").css("display", "none");
  $("#fileLaudoMedico").removeAttr("required");

  //Adição comprovante de matricula/diploma
  $("#formCertificadoEnsino").css("display", "block");
  $("#fileCertificadoEnsino").attr("required", "req");

  //Adição de curriculo
  $("#formCurriculo").css("display", "block");
  $("#fileCurriculo").attr("required", "req");

  console.log("Mostrando GraduacaoAfrodescedentes");
}

function showBacharelAfrodecendente() {

  //Adição autodeclaracao
  $("#formAutodeclaracao").css("display", "block");
  $("#autoriDecaracaoDwl").css("display", "block");
  $("#fileAutodeclaracao").attr("required", "req");

  //Remoção laudo médico
  $("#formLaudoMedico").css("display", "none");
  $("#fileLaudoMedico").removeAttr("required");

  //Adição comprovante de matricula/diploma
  $("#formCertificadoEnsino").css("display", "block");
  $("#fileCertificadoEnsino").attr("required", "req");

  //Adição de curriculo
  $("#formCurriculo").css("display", "block");
  $("#fileCurriculo").attr("required", "req");

  console.log("Mostrando BacharelAfrodecendente");
}

function showGraduacaoPCD() {

  //Adição autodeclaracao
  $("#formAutodeclaracao").css("display", "block");
  $("#autoriDecaracaoDwl").css("display", "block");
  $("#fileAutodeclaracao").attr("required", "req");

  //Adição laudo médico
  $("#formLaudoMedico").css("display", "block");
  $("#fileLaudoMedico").attr("required", "req");

  //Adição comprovante de matricula/diploma
  $("#formCertificadoEnsino").css("display", "block");
  $("#fileCertificadoEnsino").attr("required", "req");

  //Adição de curriculo
  $("#formCurriculo").css("display", "block");
  $("#fileCurriculo").attr("required", "req");

  console.log("Mostrando GraduacaoPCD");
}

function showBacharelPCD() {

  //Adição autodeclaracao
  $("#formAutodeclaracao").css("display", "block");
  $("#autoriDecaracaoDwl").css("display", "block");
  $("#fileAutodeclaracao").attr("required", "req");

  //Adição laudo médico
  $("#formLaudoMedico").css("display", "block");
  $("#fileLaudoMedico").attr("required", "req");

  //Adição comprovante de matricula/diploma
  $("#formCertificadoEnsino").css("display", "block");
  $("#fileCertificadoEnsino").attr("required", "req");

  //Adição de curriculo
  $("#formCurriculo").css("display", "block");
  $("#fileCurriculo").attr("required", "req");

  console.log("Mostrando BacharelPCD");
}

function showIndigenaQuilombola() {

  //Adição autodeclaracao
  $("#formAutodeclaracao").css("display", "block");
  $("#autoriDecaracaoDwl").css("display", "block");
  $("#fileAutodeclaracao").attr("required", "req");

  //Remoção laudo médico
  $("#formLaudoMedico").css("display", "none");
  $("#fileLaudoMedico").removeAttr("required");

  //Adição comprovante de matricula/diploma
  $("#formCertificadoEnsino").css("display", "block");
  $("#fileCertificadoEnsino").attr("required", "req");

  //Adição de curriculo
  $("#formCurriculo").css("display", "block");
  $("#fileCurriculo").attr("required", "req");

  console.log("Mostrando IndigenaQuilombola");
}

function showTravestisTransexuais() {

  //Adição autodeclaracao
  $("#formAutodeclaracao").css("display", "block");
  $("#autoriDecaracaoDwl").css("display", "block");
  $("#fileAutodeclaracao").attr("required", "req");

  //Remoção laudo médico
  $("#formLaudoMedico").css("display", "none");
  $("#fileLaudoMedico").removeAttr("required");

  //Adição comprovante de matricula/diploma
  $("#formCertificadoEnsino").css("display", "block");
  $("#fileCertificadoEnsino").attr("required", "req");

  //Adição de curriculo
  $("#formCurriculo").css("display", "block");
  $("#fileCurriculo").attr("required", "req");

  console.log("Mostrando TravestisTransexuais");
}


$(document).ready(function () {


  //Ocultar o btn de atualizar dados
  $("#id_submitbutton").css("display", "none");


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
      showLinkDownloadDocOficial(data.link_upload_rg_cpf);

      $('#enderecoCep').val(data.endereco_cep);
      $('#enderecoNumero').val(data.endereco_numero);
      $('#enderecoQuadra').val(data.endereco_quadra);
      $('#enderecoLote').val(data.endereco_lote);
      $('#enderecoCidade').val(data.endereco_cidade);
      $('#logadouro').val(data.endereco_logadouro);
      $('#enderecoBairro').val(data.endereco_bairro);
      $('#enderecoComplemento').val(data.endereco_complemento);
      $('#estadoUf').val(data.endereco_estado_uf);
      showLinkDownloadComprovanteEndereco(data.endereco_link_upload);


      $('#telefoneContato').val(data.contato_telefone);
      $('#whatsappNumero').val(data.contato_whatsapp);

      $('#cotaPretendida').val(data.cota_pretendida);
      showLinkAutoDeclaracao(data.cota_link_autodeclaracao);
      showLinkLaudoMedico(data.cota_link_laudo_medico);
      showLinkDeclaracao(data.cota_link_declaracao);
      showLinkCurriculo(data.cota_link_curriculo);



    },
    complete: function () {

    },
    error: function (xhr, er) {
      alert("Erro ao consultaro o usuario " + xhr + ", " + er);
    }
  });


  //Seletor Cargo
  $('#cotaPretendida').on('change', function () {
    if (this.value == "GraduacaoAfrodescedentes") {
      showGraduacaoAfrodescedentes();
    } else if (this.value == "BacharelAfrodecendente") {
      showBacharelAfrodecendente();
    } else if (this.value == "GraduacaoPCD") {
      showGraduacaoPCD();
    } else if (this.value == "BacharelPCD") {
      showBacharelPCD();
    } else if (this.value == "IndigenaQuilombola") {
      showIndigenaQuilombola();
    } else if (this.value == "TravestisTransexuais") {
      showTravestisTransexuais();
    } else if(this.value == "PublicoExterno"){
      showPublicoExterno();
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
    //Verifica tamanho arquivo
    var fileRGCPF_f = document.getElementById("fileRGCPF");
    if (fileRGCPF_f.files.length > 0) {
      const fsize = fileRGCPF_f.files.item(0).size;
      const file = Math.round((fsize / 1024));
      //Tamanho permitido
      if (file <= 2048) {
        console.log("OK, arquivo menor ou igual 2MB");
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
        form_data.append('file_rg_cpf', $('#fileRGCPF').prop('files')[0]);
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
            showLinkDownloadDocOficial(response.link_upload_rg_cpf);
            $('#collapseOne').removeClass('show');
            $('#collapseTwo').addClass("show");
            $("#g_endereco").css("display", "block");
            f_dados_pessoais = true;
            showSubmissao();
          },
          error: function (request, status, error) {
            $('#msgAguardeDadosPessoais').html('Erro ao enviar formulário!');
            console.log(request.responseText);
          }
        });
      } else {
        $('#msgAguardeDadosPessoais').html('Erro, arquivo maior do que 2MB, ou tipo de arquivo diferente de .PDF');
      }
    }
  });
  //Link download
  function showLinkDownloadDocOficial(link) {
    if (link) {
      $("#downDocOficial").replaceWith('<a href="' + link + '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Arquivo já enviado, um novo envio sobrescreverá o arquivo anterior.</strong></a>');
    }
  }

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
    form_data.append('file_comprovante_endereco', $('#fileComprovanteEndereco').prop('files')[0]);


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
        showLinkDownloadComprovanteEndereco(response.endereco_link_upload);
        $('#collapseTwo').removeClass('show');
        $('#collapseThree').addClass("show");
        $("#g_contato").css("display", "block");
        f_endereco = true;
        showSubmissao();
      },
      error: function (request, status, error) {
        $('#msgAguardeEndereco').html('Erro ao enviar formulário!');
        console.log(request.responseText);
      }
    });

  });

  //Link download
  function showLinkDownloadComprovanteEndereco(link) {
    if (link) {
      $("#downComprovanteEndereco").replaceWith(' <a href="' + link + '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Arquivo já enviado, um novo envio sobrescreverá o arquivo anterior.</strong></a>');
    }
  }


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
        f_contato = true;
        showSubmissao();
      },
      error: function (request, status, error) {
        $('#msgAguardeContato').html('Erro ao enviar formulário!');
        console.log(request.responseText);
      }
    });
  });



  //Cota------------------------------------------------------------

  $("#formCota").on('submit', function (e) {
    e.preventDefault();
    var form_data = new FormData();
    form_data.append('id', $("#idUser").val());
    form_data.append('id_curso', $("#idCurso").val());
    form_data.append('cpf', $("#idCPF").val());
    form_data.append('tipo_formulario', 'cota');

    form_data.append('cota_link_autodeclaracao', $('#fileAutodeclaracao').prop('files')[0]);
    form_data.append('cota_link_laudo_medico', $('#fileLaudoMedico').prop('files')[0]);
    form_data.append('cota_link_declaracao', $('#fileCertificadoEnsino').prop('files')[0]);
    form_data.append('cota_link_curriculo', $('#fileCurriculo').prop('files')[0]);
    form_data.append('cota_pretendida', $("#cotaPretendida").val());


    $.ajax({
      type: 'POST',
      url: 'atualiza_usuario.php',
      data: form_data,
      dataType: 'json',
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function () {
        $('#msgAguardeCota').html('Aguarde, enviando dados do formulário...');
        console.log('Aguarde, enviando dados do formulário...');
      },
      success: function (response) {
        console.log(response);
        $('#msgAguardeCota').html('Sucesso, dados enviados!');
        showLinkAutoDeclaracao(response.cota_link_autodeclaracao);
        showLinkLaudoMedico(response.cota_link_laudo_medico);
        showLinkDeclaracao(response.cota_link_declaracao);
        showLinkCurriculo(response.cota_link_curriculo);
        $('#collapseThree').removeClass('show');
        $('#collapseFour').addClass("show");
        f_cota = true;
        showSubmissao();
      },
      error: function (request, status, error) {
        $('#msgAguardeCota').html('Erro ao enviar formulário!');
        console.log(request.responseText);
      }
    });
  });

  //Link download
  function showLinkAutoDeclaracao(link) {
    if (link) {
      $("#downAutodeclaracao").replaceWith(' <a href="' + link + '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Arquivo já enviado, um novo envio sobrescreverá o arquivo anterior.</strong></a>');
    }
  }

  //Link download
  function showLinkLaudoMedico(link) {
    if (link) {
      $("#downLaudoMedico").replaceWith(' <a href="' + link + '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Arquivo já enviado, um novo envio sobrescreverá o arquivo anterior.</strong></a>');
    }
  }

  //Link download
  function showLinkDeclaracao(link) {
    if (link) {
      $("#downCertificadoEnsino").replaceWith(' <a href="' + link + '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Arquivo já enviado, um novo envio sobrescreverá o arquivo anterior.</strong></a>');
    }
  }

  //Link download
  function showLinkCurriculo(link) {
    if (link) {
      $("#downCurriculo").replaceWith(' <a href="' + link + '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Arquivo já enviado, um novo envio sobrescreverá o arquivo anterior.</strong></a>');
    }
  }





});
