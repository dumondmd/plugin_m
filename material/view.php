<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_material.php');

global $CFG, $DB, $USER;


require_login();
$url = new moodle_url("$CFG->wwwroot/blocks/material/");


$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Material de Apoio');
$PAGE->set_heading('Material de Apoio');
$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('Material');
$editnode = $settingsnode->add('Arquivos');
$editnode->make_active();
echo $OUTPUT->header();


$idcurso = $_GET["idcurso"];
$userid = $USER->id;


//Consulta usuario se está deferido
global $CFG, $DB;
$inscricao = null;
$inscricao = $DB->get_record_sql('
    SELECT u.id as iduser, u.username as cpf, u.firstname, u.lastname, c.fullname as course, c.shortname

  from {user_enrolments} AS ue
  LEFT JOIN {enrol_apply_applicationinfo} ai ON ai.userenrolmentid = ue.id
  JOIN {user} u ON u.id = ue.userid
  JOIN {enrol} e ON e.id = ue.enrolid
  JOIN {course} c ON c.id = e.courseid
  JOIN {blocks_inscricao} insc ON insc.identificador_edital =  c.id AND insc.identificador_aluno = u.username
  where e.enrol = "apply"
  and c.id = ' . $idcurso . '
  and u.id = ' . $userid . '
  and ue.status != 0
  and insc.situacao_inscricao  = "deferida"
  ORDER BY u.firstname
  ;'
);



if ($inscricao) {
    echo '

    <div class="row mt-4 p-3">
    <div class="card w-100">
        <div class="card-header">
            Arquivos do material de apoio
        </div>
        <div class="card-body">
            <blockquote class="blockquote mb-0">
            <div class="alert alert-success" role="alert">
                ' . $inscricao->course . '
            </div>';

    //Listar arquivos-----------------------------------------------------------------------
    $url_uploads = $url . 'uploads/' . $inscricao->shortname;
    $arquivos = scandir('uploads/' . $inscricao->shortname . '/');
    unset($arquivos[0], $arquivos[1]);


    echo '<hr><div class="form-row"><div class="form-group col-md-12"><h4>Download Material de Apoio:</h4><div class="list-group">';

    foreach ($arquivos as &$arquivo) {
        echo '<a href="' . $url_uploads . '/' . $arquivo . '" target="_blank"><button type="button" class="list-group-item list-group-item-action">' . $arquivo . '</button></a>';
    }
    echo '
            </blockquote>
        </div>
    </div>
    </div>
    
    
    
    ';
} else {
    echo '

    <div class="row mt-4 p-3">
    <div class="card w-100">
        <div class="card-header">
            Arquivos do material de apoio
        </div>
        <div class="card-body">
            <blockquote class="blockquote mb-0">
            <div class="alert alert-danger" role="alert">
                Sua inscrição não foi deferida !
            </div>
            </blockquote>
        </div>
    </div>
    </div>
    
    
    
    ';
}






echo $OUTPUT->footer();
