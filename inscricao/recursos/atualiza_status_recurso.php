<?php
require_once('../../../config.php');


global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/blocks/inscricao/recursos/atualiza_status_recurso.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Análise da inscrição - Atualização');

$PAGE->set_context(\context_system::instance());



$iduser = trim($_POST["iduser"]);
$idcurso = trim($_POST["idcurso"]);
$situacao_recurso = trim($_POST["situacao_recurso"]);
$motivo_indeferimento_recurso = trim($_POST["motivo_indeferimento_recurso"]);




$analisedb = null;

$analisedb = $DB->get_record_sql(
    'SELECT id,
     identificador_edital,
     identificador_aluno,
     situacao_inscricao,
     motivo_indeferimento from {blocks_inscricao}
     where identificador_aluno = ?', array($iduser)
 );



if(empty($analisedb)){
  //Nao encontrado
  echo json_encode('Erro! Registro não encontrado');
} else {
  //Update
  updateStatusInscricao($analisedb->id, $situacao_recurso, $motivo_indeferimento_recurso);
}



function updateStatusInscricao($id, $situacao_recurso, $motivo_indeferimento_recurso){

  global $DB;
  try {
    $record = new stdClass();
    $record->id = $id;
    $record->situacao_recurso = $situacao_recurso;
    $record->motivo_indeferimento_recurso	 = $motivo_indeferimento_recurso;
    $DB->update_record('blocks_inscricao', $record);
    echo json_encode($record);
  } catch (dml_exception $e) {
    echo 'Erro ao atualizar banco de dados!'.$e;
  }

}
