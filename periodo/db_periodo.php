<?php
require_once('../../config.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;


$tipo_formulario = $_POST["tipo_formulario"];

//Curso
$idCursoPeriodo = $_POST["idCursoPeriodo"];
$id_curso = $_POST["id_curso"];
$tipo_concurso = $_POST["tipo_concurso"];
$dt_inicio_curso = $_POST["dt_inicio_curso"];
$dt_fim_curso = $_POST["dt_fim_curso"];

//Recurso
$idRecursoPeriodo = $_POST["idRecursoPeriodo"];
$id_cursoR = $_POST["id_cursoR"];
$dt_inicio_recurso = $_POST["dt_inicio_recurso"];
$dt_fim_recurso = $_POST["dt_fim_recurso"];



if ($tipo_formulario == 'curso') {

    //Atualiza periodo de curso
    if ($idCursoPeriodo) {
        try {
            $record = new stdClass();
            $record->id = $idCursoPeriodo;
            $record->id_curso = $id_curso;
            $record->tipo_concurso = $tipo_concurso;
            $record->data_inicio = formarTimeStamp($dt_inicio_curso);
            $record->data_fim = formarTimeStamp($dt_fim_curso);
            $DB->update_record('blocks_periodo_curso', $record);
        } catch (dml_exception $e) {
            echo json_encode('Erro ao atualizar Órgão no banco de dados!' . $e);
        }
        echo json_encode($record);

        //Cria periodo de curso
    } else {
        $record = new stdClass();
        $record->id_curso = $id_curso;
        $record->tipo_concurso = $tipo_concurso;
        $record->data_inicio = formarTimeStamp($dt_inicio_curso);
        $record->data_fim = formarTimeStamp($dt_fim_curso);
        try {
            $DB->insert_record('blocks_periodo_curso', $record, false);
        } catch (dml_exception $e) {
            echo json_encode('Erro ao criar registro no banco de dados!' . $e);
        }
        echo json_encode($record);
    }
} elseif ($tipo_formulario == 'recurso') {





    //Atualiza periodo de recurso
    if ($idRecursoPeriodo) {
        try {
            $record = new stdClass();
            $record->id = $idRecursoPeriodo;
            $record->id_curso = $id_cursoR;
            $record->data_inicio = formarTimeStamp($dt_inicio_recurso);
            $record->data_fim = formarTimeStamp($dt_fim_recurso);
            $DB->update_record('blocks_periodo_recurso', $record);
        } catch (dml_exception $e) {
            echo json_encode('Erro ao atualizar Órgão no banco de dados!' . $e);
        }
        echo json_encode($record);

        //Cria periodo de recurso
    } else {
        $record = new stdClass();
        $record->id_curso = $id_cursoR;
        $record->data_inicio = formarTimeStamp($dt_inicio_recurso);
        $record->data_fim = formarTimeStamp($dt_fim_recurso);
        try {
            $DB->insert_record('blocks_periodo_recurso', $record, false);
        } catch (dml_exception $e) {
            echo json_encode('Erro ao criar registro no banco de dados!' . $e);
        }
        echo json_encode($record);
    }
}





function formarTimeStamp($date)
{
    $date = new DateTime($date);
    return $date->getTimestamp();
}
