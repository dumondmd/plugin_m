<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_periodo.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();

$idcurso = $_GET["idcurso"];

//Consulta nome de curso
$curso_db = $DB->get_record_sql(
    'SELECT c.id, c.fullname FROM {course} AS c WHERE c.id = ?',
    [$idcurso]
);

if (empty($curso_db)) {
    header('location: /moodle/?redirect=0');
    die;
}

//Curso
$curso_periodo_db = $DB->get_record_sql(
    'SELECT c.id, c.tipo_concurso, c.data_inicio, c.data_fim FROM {blocks_periodo_curso} AS c WHERE c.id_curso = ?',
    [$idcurso]
);

//Recurso
$recurso_periodo_db = $DB->get_record_sql(
    'SELECT r.id, r.data_inicio, r.data_fim FROM {blocks_periodo_recurso} AS r WHERE r.id_curso = ?',
    [$idcurso]
);



$url = new moodle_url('/blocks/periodo/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Periodo', $url);
$editnode->make_active();
echo $OUTPUT->header();


echo '
<div class="container">

    <h1>' . $curso_db->fullname . '</h1>

    <hr>
    <h3>Tempo de inscrição no curso</h3>
    <div class="row">
        <form id="formCurso">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="tipo_concurso">Tipo do curso:</label>
                    <select id="tipo_concurso" required>
                        <option value="graduacao" '.verificarSelecao('graduacao', $curso_periodo_db->tipo_concurso).'>Graduação</option>
                        <option value="pos_graduacao" '.verificarSelecao('pos_graduacao', $curso_periodo_db->tipo_concurso).'>Pós-Graduação</option>
	     		<option value="est_dir_int" '.verificarSelecao('est_dir_int', $curso_periodo_db->tipo_concurso).'>Estudos Dirigidos Interno</option>
                        <option value="est_dir_ext" '.verificarSelecao('est_dir_ext', $curso_periodo_db->tipo_concurso).'>Estudos Dirigidos Externo</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-5">
                    <input type="hidden" class="form-control" id="idCursoPeriodo" value="' . $curso_periodo_db->id . '" readonly>
                    <input type="hidden" class="form-control" id="idCurso" value="' . $_GET['idcurso'] . '" readonly>
                    <label for="dt_inicio_curso">Início</label>
                    <input type="datetime-local" class="form-control" id="dt_inicio_curso" value="' . formatarData($curso_periodo_db->data_inicio) . '" required>
                </div>
                <div class="form-group col-md-5">
                    <label for="dt_fim_curso">Fim</label>
                    <input type="datetime-local" class="form-control" id="dt_fim_curso" value="' . formatarData($curso_periodo_db->data_fim) . '" required>
                </div>
                <div class="form-group col-md-2">
                    <input class="btn btn-primary mt-5" type="submit" value="Salvar">
                </div>
            </div>

        </form>
    </div>
    <div id="msgAguardeCurso"></div>

    <hr>
    <h3>Tempo de inscrição de recursos</h3>

    <div class="row">
        <form id="formRecurso">
            <div class="form-row">
                <div class="form-group col-md-5">
                    <input type="hidden" class="form-control" id="idRecursoPeriodo" value="' . $recurso_periodo_db->id . '" readonly>
                    <input type="hidden" class="form-control" id="idCursoR" value="' . $_GET['idcurso'] . '" readonly>
                    <label for="dt_inicio_recurso">Início</label>
                    <input type="datetime-local" class="form-control" id="dt_inicio_recurso" value="' . formatarData($recurso_periodo_db->data_inicio) . '" required>
                </div>
                <div class="form-group col-md-5">
                    <label for="dt_fim_recurso">Fim</label>
                    <input type="datetime-local" class="form-control" id="dt_fim_recurso" value="' . formatarData($recurso_periodo_db->data_fim) . '" required>
                </div>
                <div class="form-group col-md-2">
                    <input class="btn btn-primary mt-5" type="submit" value="Salvar">
                </div>
        </form>
    </div>
    <div id="msgAguardeRecurso"></div>
</div>
';

//JAVA SCRIPT
echo '<script type="text/javascript" src="../../lib/jquery/jquery-3.6.0.js"></script>';
echo '<script type="text/javascript" src="js/custom.js?ver=1.0"></script>';

echo $OUTPUT->footer();


function formatarData($date)
{
    if ($date) {
        return date('Y-m-d H:i', $date);
    } else {
        return null;
    }
}


function verificarSelecao($value, $valuedb){

    if($value == $valuedb){
        return 'selected';
    }
}
