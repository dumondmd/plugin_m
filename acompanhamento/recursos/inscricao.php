<?php
require_once('../../../config.php');


global $CFG, $DB, $USER;

require_login();
$idcurso = $_GET["idcurso"];
$url = new moodle_url("$CFG->wwwroot/blocks/acompanhamento/recursos/");

$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Recurso Dados');
$PAGE->set_heading('Recursos Dados');
$PAGE->set_context(\context_system::instance());

// Bread Crums
$settingsnode = $PAGE->settingsnav->add('Status Recurso', new moodle_url("$CFG->wwwroot/blocks/acompanhamento/view.php?idcurso=".$idcurso));
$editnode = $settingsnode->add('Recurso');
$editnode->make_active();

echo $OUTPUT->header();


//Buscar
$inscricao = null;
$inscricao = getInscricaoRecurso($USER->username);






echo '
<h3>Dados pessoais</h3></br>
<div class="form-row">
   <div class="form-group col-md-8">
      <label for="nomeCompleto">Nome completo</label>
     <input type="text" class="form-control" id="nomeCompleto" value="'.strtoupper($USER->firstname) .' '.strtoupper($USER->lastname).'" readonly>
</div>
   <div class="form-group col-md-4">
      <label for="cpf">CPF</label>
      <input type="text" class="form-control" id="cpf" value="'.$USER->username.'" readonly>
   </div>
</div>

<div class="form-row">
   <div class="form-group col-md-4">
      <label for="dataNacimento">Data de nascimento</label>
      <input type="date" class="form-control" id="dataNacimento" value="'.$USER->data_nacimento.'" readonly>
   </div>
   <div class="form-group col-md-4">
      <label for="estadoCivil">Estado civil</label>
      <input type="text" class="form-control" id="estadoCivil" value="'.strtoupper($USER->estado_civil).'" readonly>
   </div>
   <div class="form-group col-md-4">
      <label for="dispEstagio">Disponibilidade para estágio</label>
      <input type="text" class="form-control" id="dispEstagio" value="'.strtoupper($USER->disponibilidade_estagio).'" readonly>
   </div>
</div>

<div class="form-row">
   <div class="form-group col-md-2">
      <label for="rg">RG</label>
      <input type="text" class="form-control" id="rg" value="'.$USER->rg.'" readonly>
   </div>
   <div class="form-group col-md-2">
      <label for="orgaoExpedidor">Órgão expedidor</label>
      <input type="text" class="form-control" id="orgaoExpedidor" value="'.strtoupper($USER->orgao_expedidor).'" readonly>
   </div>
   <div class="form-group col-md-4">
      <label for="nomeMae">Nome da Mãe</label>
      <input type="text" class="form-control" id="nomeMae" value="'.strtoupper($USER->nome_mae).'" readonly>
   </div>
   <div class="form-group col-md-4">
      <label for="nomePai">Nome do pai</label>
      <input type="text" class="form-control" id="nomePai" value="'.strtoupper($USER->nome_pai).'" readonly>
   </div>
</div>

';


echo '
<hr>
<h3>Recurso da inscrição</h3></br>

<form action="atualizar_recurso.php" method="post" enctype="multipart/form-data">

  <input type="hidden" id="cpf" name="cpf" value="'.$USER->username.'">
  <input type="hidden" id="id_curso" name="id_curso" value="'.$idcurso.'">




  <div class="form-row">
     <div class="form-group col-md-12">
        <label for="dadosUpload">Upload do arquivo unificado com os dados do recurso</label>
        <input type="file" accept="application/pdf" class="form-control p-1" id="file_recurso" name="file_recurso" required>
     </div>
  </div>

  <div class="form-row">
     <div class="form-group col-md-12">
        <div id="msgAguardeStatus"></div>
        <input class="btn btn-success" id="btnVoltar" type="button" onclick="window.history.back()" value="Voltar">
        <input class="btn btn-primary" id="btnAlterarStatus" type="submit" value="Confirmar" style="float:right;">
     </div>
  </div>
</form>';


if(empty($inscricao->link_upload_recurso)){
  echo '
  <div class="form-row">
    <div class="mx-auto alert alert-danger" role="alert">
      <h4>Ainda não foi enviado nenhum arquivo!</h4>
        <p>Os arquivos devem ser unificados em um único PDF.</p>
    </div>
  </div>';
} else {
  echo '
  <div class="form-row">
    <div class="mx-auto alert alert-success" role="alert">
      <h4>Arquivo enviado com sucesso!</h4>
        <a href="'.$url.''.$inscricao->link_upload_recurso.'" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Downlod do recurso enviado</strong></a>
    </div>
  </div>';
}


function getInscricaoRecurso($cpf)
{
  global $DB;
  try {
    $user = $DB->get_record_sql(
        'SELECT
        id,
        link_upload_recurso
         FROM {blocks_inscricao} WHERE identificador_aluno = ?', [$cpf]
    );
  } catch (dml_exception $e) {
      return 'Erro consulta banco de dados. '.$e;
  }
    return $user;
}

echo $OUTPUT->footer();
