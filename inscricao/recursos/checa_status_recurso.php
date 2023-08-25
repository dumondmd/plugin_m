<?php
require_once('../../../config.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

$iduser = trim($_POST["iduser"]);

//Consulta status recurso
$analisedb = null;
$analisedb = $DB->get_record_sql(
    "SELECT id,
     identificador_edital,
     identificador_aluno,
     situacao_recurso,
     motivo_indeferimento_recurso
     from {blocks_inscricao}
     WHERE identificador_aluno = '".$iduser."'
    "
 );
echo json_encode($analisedb);
