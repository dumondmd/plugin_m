
$("#formCurso").on('submit', function (e) {
  e.preventDefault();
  var form_data = new FormData();
  form_data.append('id_curso', $("#idCurso").val());
  form_data.append('idCursoPeriodo', $("#idCursoPeriodo").val());
  form_data.append('tipo_formulario', 'curso');
  form_data.append('tipo_concurso', $("#tipo_concurso").val());
  form_data.append('dt_inicio_curso', $("#dt_inicio_curso").val());
  form_data.append('dt_fim_curso', $("#dt_fim_curso").val());

  console.log(form_data);

  $.ajax({
    type: 'POST',
    url: 'db_periodo.php',
    data: form_data,
    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,
    beforeSend: function () {
      $('#msgAguardeCurso').html('Aguarde, enviando dados do formulário...');
      console.log('Aguarde, enviando dados do formulário...');
    },
    success: function (response) {
      console.log(response);
      $('#msgAguardeCurso').html('Sucesso, dados enviados!');
    },
    error: function (request, status, error) {
      $('#msgAguardeCurso').html('Erro ao enviar formulário!');
      console.log(request.responseText);
    }
  });

});


$("#formRecurso").on('submit', function (e) {
  e.preventDefault();
  var form_data = new FormData();
  form_data.append('id_cursoR', $("#idCursoR").val());
  form_data.append('idRecursoPeriodo', $("#idRecursoPeriodo").val());
  form_data.append('tipo_formulario', 'recurso');
  form_data.append('dt_inicio_recurso', $("#dt_inicio_recurso").val());
  form_data.append('dt_fim_recurso', $("#dt_fim_recurso").val());

  console.log(form_data);

  $.ajax({
    type: 'POST',
    url: 'db_periodo.php',
    data: form_data,
    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,
    beforeSend: function () {
      $('#msgAguardeRecurso').html('Aguarde, enviando dados do formulário...');
      console.log('Aguarde, enviando dados do formulário...');
    },
    success: function (response) {
      console.log(response);
      $('#msgAguardeRecurso').html('Sucesso, dados enviados!');
    },
    error: function (request, status, error) {
      $('#msgAguardeRecurso').html('Erro ao enviar formulário!');
      console.log(request.responseText);
    }
  });
});  