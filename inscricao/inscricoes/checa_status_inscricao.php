<?php
require_once('../../../config.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

$idcurso = trim($_POST["idcurso"]);
$iduser = trim($_POST["iduser"]);

//Consulta status inscricao
 $analisedb = $DB->get_record_sql(
    'SELECT * from {blocks_inscricao} where identificador_edital = ? and identificador_aluno = ?', [$idcurso, $iduser]
 );

echo json_encode($analisedb);
