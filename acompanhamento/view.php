<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_acompanhamento.php');

global $CFG, $DB, $USER;


require_login();
$url = new moodle_url("$CFG->wwwroot/blocks/acompanhamento/");
$urlcomprovante = new moodle_url("$CFG->wwwroot/blocks/certificado/view.php");
$urlinscricao = new moodle_url("$CFG->wwwroot/enrol/index.php");

$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Inscrições Status');
$PAGE->set_heading('Inscrição Status');
$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('Recursos');
$editnode = $settingsnode->add('Status');
$editnode->make_active();
echo $OUTPUT->header();


$idcurso = $_GET["idcurso"];
$enrol = 'apply';
$status = 0;
$userid = $USER->id;
$dataAtual = date('d-m-Y H:i');

//Consulta nome do curso
function consultaCursoId($idCurso)
{
    global $CFG, $DB;
    $curso = null;
    $curso = $DB->get_record_sql('
        SELECT c.fullname FROM {course} AS c
        WHERE c.id = '.$idCurso
        );
    return $curso->fullname;
}

$dadosuser = null;
$dadosuser = $DB->get_record_sql(
    'SELECT insc.*, u.username as cpf, u.firstname, u.lastname, c.fullname as course, ue.timecreated as enroldate, c.shortname ' .
    'from {user_enrolments} AS ue ' .
    'LEFT JOIN {enrol_apply_applicationinfo} ai ON ai.userenrolmentid = ue.id ' .
    'JOIN {user} u ON u.id = ue.userid ' .
    'JOIN {enrol} e ON e.id = ue.enrolid ' .
    'JOIN {course} c ON c.id = e.courseid ' .
    'LEFT JOIN {blocks_inscricao} insc ON insc.identificador_edital =  c.id AND insc.identificador_aluno = u.username ' .
    'where e.enrol = ? ' .
    'and ue.status != ? ' .
    'and u.id = ?' .
    'and c.id = ?',
    array($enrol, $status, $userid, $idcurso)
);


//Consulta de período de recurso
$recurso_periodo_db = $DB->get_record_sql(
    'SELECT r.id, r.data_inicio, r.data_fim FROM {blocks_periodo_recurso} AS r WHERE r.id_curso = ?',
    [$idcurso]
);


if (empty($dadosuser)) {
    echo '

  <div class="row mt-4 p-3">
      <div class="card w-100">
          <div class="card-header">
              Status de inscrição
          </div>
          <div class="card-body">
              <blockquote class="blockquote mb-0">
              <div class="alert alert-danger" role="alert">
                  Você não fez inscrição !
              </div>
              <a href="' . $urlinscricao . '?id='.$idcurso.'"><button data-filteraction="apply" type="button" class="btn btn-primary float-right">Inscrição no '.consultaCursoId($idcurso).'</button></a>
              </blockquote>
          </div>
      </div>
  </div>


  ';
} else {



    echo '

  <div class="row p-3">
      <div class="card w-100">
          <div class="card-header">
              Inscrição realizada em ' . date("d/m/Y", $dadosuser->enroldate) . '
              <a href="' . $urlcomprovante . '"><button data-filteraction="apply" type="button" class="btn btn-success float-right">Comprovantes de inscrição</button></a>
          </div>
          <div class="card-body">
              <blockquote class="blockquote mb-0">';

    if (empty($dadosuser->situacao_inscricao)) {
        echo '
                <div class="alert alert-success" role="alert">
                  Inscrição em análise!
                </div>';

    } elseif ($dadosuser->situacao_inscricao == 'deferida') {
        echo '
                <div class="alert alert-success" role="alert">
                  Inscrição Deferida!
                </div>';

    } elseif ($dadosuser->situacao_inscricao == 'fenotipica') {
        echo '
            <div class="alert alert-success" role="alert">
              Análise Fenotípica!
            </div>';

    } else {
        echo '
                <div class="alert alert-danger" role="alert">
                    <h4>Inscrição Indeferida!</h4>
                    <p class="mt-3">Período de envio de recurso das ' . date('d/m/Y H:i', $recurso_periodo_db->data_inicio) . ' às ' . date('d/m/Y H:i', $recurso_periodo_db->data_fim) . ', observando o horário oficial de Brasília-DF</p>
                </div>';

        if (strtotime($dataAtual) > $recurso_periodo_db->data_inicio && strtotime($dataAtual) < $recurso_periodo_db->data_fim && !empty($recurso_periodo_db)) {
            echo '<a href="' . $url . 'recursos/inscricao.php?idcurso=' . $idcurso . '"><button data-filteraction="apply" type="button" class="btn btn-primary  float-right">Upload de recursos</button></a>';
        }
    }


    echo '
              </blockquote>
          </div>
      </div>
  </div>';
}






echo $OUTPUT->footer();