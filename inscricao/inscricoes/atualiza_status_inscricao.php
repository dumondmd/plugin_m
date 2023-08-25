<?php
require_once('../../../config.php');


global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/blocks/inscricao/inscricoes/atualiza_status_inscricao.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Análise da inscrição - Atualização');

$PAGE->set_context(\context_system::instance());



$iduser = trim($_POST["iduser"]);
$idcurso = trim($_POST["idcurso"]);
$status_iscricao = trim($_POST["status_iscricao"]);
$status_indeferimento = trim($_POST["status_indeferimento"]);
$responsavel_analise = trim($_POST["responsavel_analise"]);



$analisedb = $DB->get_record_sql(
    'SELECT * from {blocks_inscricao} where identificador_edital = ? and identificador_aluno = ?', [$idcurso, $iduser]
 );




if(empty($analisedb)){
  //Create
  createStatusInscricao($iduser, $idcurso, $status_iscricao, $status_indeferimento, $responsavel_analise);
} else {
  //Update
  updateStatusInscricao($analisedb->id, $iduser, $idcurso, $status_iscricao, $status_indeferimento, $responsavel_analise);
}



function createStatusInscricao($iduser, $idcurso, $status_iscricao, $status_indeferimento, $responsavel_analise){

    global $DB;
    $record = new stdClass();
    $record->identificador_edital = $idcurso;
    $record->identificador_aluno = $iduser;
    $record->situacao_inscricao = $status_iscricao;
    $record->motivo_indeferimento = $status_indeferimento;
    $record->data_analise = time();
    $record->responsavel_analise = $responsavel_analise;
  	try {
  		$DB->insert_record('blocks_inscricao', $record, false);
      echo json_encode($record);
  	} catch (dml_exception $e) {
  		echo 'Erro ao salvar no banco de dados!'.$e;
  	}

}



function updateStatusInscricao($id, $iduser, $idcurso, $status_iscricao, $status_indeferimento, $responsavel_analise){

  global $DB;
  try {
    $record = new stdClass();
    $record->id = $id;
    $record->identificador_edital = $idcurso;
    $record->identificador_aluno = $iduser;
    $record->situacao_inscricao = $status_iscricao;
    $record->motivo_indeferimento = $status_indeferimento;
    $record->data_analise = time();
    $record->responsavel_analise = $responsavel_analise;
    $DB->update_record('blocks_inscricao', $record);
    echo json_encode($record);
  } catch (dml_exception $e) {
    echo 'Erro ao atualizar banco de dados!'.$e;
  }

}
