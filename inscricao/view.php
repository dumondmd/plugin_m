<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_inscricao.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();

//Id curso
$idcurso = $_GET["idcurso"];

$url = new moodle_url('/blocks/inscricao/view.php?idcurso='.$idcurso);
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Gerenciamento de Inscrições e Recursos');
$PAGE->set_heading('Gerenciamento de Inscrições e Recursos');
$PAGE->set_context(\context_system::instance());

$url_l = new moodle_url('/blocks/inscricao');

// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('Gerenciamento');
$editnode = $settingsnode->add('Inscrições e Recursos', $url);
$editnode->make_active();
echo $OUTPUT->header();



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


function consultaTipoCursoId($idCurso)
{
    global $CFG, $DB;
    $curso = null;
    $curso = $DB->get_record_sql('
        SELECT p.tipo_concurso FROM {blocks_periodo_curso} AS p
        WHERE p.id_curso = '.$idCurso
        );
    return $curso->tipo_concurso;
}




//Inscricoes qtd

function qtdInscNaoAnalisadas($idcurso) {
    global $CFG, $DB;
        $qtd = null;
        $qtd = $DB->count_records_sql('
        SELECT COUNT(u.username) as cpf

        from {user_enrolments} AS ue
        LEFT JOIN {enrol_apply_applicationinfo} ai ON ai.userenrolmentid = ue.id
        JOIN {user} u ON u.id = ue.userid
        JOIN {enrol} e ON e.id = ue.enrolid
        JOIN {course} c ON c.id = e.courseid
        LEFT JOIN mdl_blocks_inscricao insc ON insc.identificador_edital =  c.id AND insc.identificador_aluno = u.username
        where e.enrol = "apply"
        and c.id = '.$idcurso.'
        and ue.status != 0
        and insc.situacao_inscricao  IS NULL
        ;
        ');
    return $qtd;
}
function qtd_insc($idcurso, $status_inscricao) {
    $status_inscricao = '"'.$status_inscricao.'"';   
   global $CFG, $DB;
       $qtd = null;
       $qtd = $DB->count_records_sql('
       SELECT COUNT(u.username) as cpf

       from {user_enrolments} AS ue
       LEFT JOIN {enrol_apply_applicationinfo} ai ON ai.userenrolmentid = ue.id
       JOIN {user} u ON u.id = ue.userid
       JOIN {enrol} e ON e.id = ue.enrolid
       JOIN {course} c ON c.id = e.courseid
       JOIN {blocks_inscricao} insc ON insc.identificador_edital =  c.id AND insc.identificador_aluno = u.username
       where e.enrol = "apply"
       and c.id = '.$idcurso.'
       and ue.status != 0
       and insc.situacao_inscricao = '.$status_inscricao.'
       ;
       ');
   return $qtd;
}



//Recursos qtd-------------------------------------------------------------------------------------

function qtd_recursos_n_anlisados($idcurso) {
   global $CFG, $DB;
       $qtd = null;
       $qtd = $DB->count_records_sql('
       SELECT COUNT(u.username) as cpf, u.firstname, u.lastname, u.email, u.city, ue.timecreated AS applydate,
       c.fullname as course, ue.timecreated as enroldate, c.shortname

       from {user_enrolments} AS ue
       LEFT JOIN {enrol_apply_applicationinfo} ai ON ai.userenrolmentid = ue.id
       JOIN {user} u ON u.id = ue.userid
       JOIN {enrol} e ON e.id = ue.enrolid
       JOIN {course} c ON c.id = e.courseid
       LEFT JOIN {blocks_inscricao} insc ON insc.identificador_aluno = u.username
       where e.enrol = "apply"
       and c.id = '.$idcurso.'
       and ue.status != 0
       and insc.situacao_inscricao  = "indeferida"
       and insc.situacao_recurso IS NULL
       and insc.link_upload_recurso IS NOT NULL
       ;
       ');
   return $qtd;
}

function qtd_recursos_deferidos($idcurso) {
   global $CFG, $DB;
       $qtd = null;
       $qtd = $DB->count_records_sql('
       SELECT COUNT(u.username) as cpf, u.firstname, u.lastname, u.email, u.city, ue.timecreated AS applydate,
       c.fullname as course, ue.timecreated as enroldate, c.shortname

       from {user_enrolments} AS ue
       LEFT JOIN {enrol_apply_applicationinfo} ai ON ai.userenrolmentid = ue.id
       JOIN {user} u ON u.id = ue.userid
       JOIN {enrol} e ON e.id = ue.enrolid
       JOIN {course} c ON c.id = e.courseid
       JOIN {blocks_inscricao} insc ON insc.identificador_aluno = u.username
       where e.enrol = "apply"
       and c.id = '.$idcurso.'
       and ue.status != 0
       and insc.situacao_inscricao  = "indeferida"
       and insc.situacao_recurso = "deferido"
       and insc.link_upload_recurso IS NOT NULL
       ;
       ');
   return $qtd;
}


function qtd_recursos_indeferidos($idcurso) {
   global $CFG, $DB;
       $qtd = null;
       $qtd = $DB->count_records_sql('
       SELECT COUNT(u.username) as cpf, u.firstname, u.lastname, u.email, u.city, ue.timecreated AS applydate,
       c.fullname as course, ue.timecreated as enroldate, c.shortname

       from {user_enrolments} AS ue
       LEFT JOIN {enrol_apply_applicationinfo} ai ON ai.userenrolmentid = ue.id
       JOIN {user} u ON u.id = ue.userid
       JOIN {enrol} e ON e.id = ue.enrolid
       JOIN {course} c ON c.id = e.courseid
       JOIN {blocks_inscricao} insc ON insc.identificador_aluno = u.username
       where e.enrol = "apply"
       and c.id = '.$idcurso.'
       and ue.status != 0
       and insc.situacao_inscricao  = "indeferida"
       and insc.situacao_recurso = "indeferido"
       and insc.link_upload_recurso IS NOT NULL
       ;
       ');
   return $qtd;
}




echo '

<h3>'.consultaCursoId($idcurso).'</h3>
<hr>
<a class="btn btn-primary m-2" href="editalpdf/index.php?idcurso=' . $idcurso . '" role="button">Exportar INSCRITOS PDF <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
<hr>

<div class="container">

    <div class="row">
        <div class="col-sm">
            <div class="card">
                <h5 class="card-header bg-warning">Não Analisadas</h5>
                <div class="card-body">
                    <h5 class="card-title">'.qtdInscNaoAnalisadas($idcurso).'</h5>
                    <p class="card-text">Realizadas e não analisadas</p>
                    <a href="'.$url_l.'/inscricoes/nao_analisadas/view.php?idcurso='.$idcurso.'" class="btn btn-primary">Selecionar</a>
                </div>
            </div>
        </div>';

        //Análise fenotípica só aparece para estudos dirigidos externos
        if(consultaTipoCursoId($idcurso) == "est_dir_ext"){
            echo '            
            <div class="col-sm">
                <div class="card">
                    <h5 class="card-header bg-primary">Análise Fenotípica</h5>
                    <div class="card-body">
                        <h5 class="card-title">'.qtd_insc($idcurso, 'fenotipica').'</h5>
                        <p class="card-text">Convocação para análise</p>
                        <a href="'.$url_l.'/inscricoes/analise_fenotipica/view.php?idcurso='.$idcurso.'" class="btn btn-primary">Selecionar</a>
                    </div>
                </div>
            </div>
            ';
        }


echo '
        <div class="col-sm">
            <div class="card">
                <h5 class="card-header bg-success">Deferidas</h5>
                <div class="card-body">
                    <h5 class="card-title">'.qtd_insc($idcurso, 'deferida').'</h5>
                    <p class="card-text">Inscrições deferidas</p>
                    <a href="'.$url_l.'/inscricoes/deferidas/view.php?idcurso='.$idcurso.'" class="btn btn-primary">Selecionar</a>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="card">
                <h5 class="card-header bg-danger">Indeferidas</h5>
                <div class="card-body">
                    <h5 class="card-title">'.qtd_insc($idcurso, 'indeferida').'</h5>
                    <p class="card-text">Inscrições indeferidas</p>
                    <a href="'.$url_l.'/inscricoes/indeferidas/view.php?idcurso='.$idcurso.'" class="btn btn-primary">Selecionar</a>
                </div>
            </div>
        </div>
    </div>

</div>



<h3 class="mt-4">Recursos</h3>
<hr>


<div class="container">
    <div class="row">
        <div class="col-sm">
            <div class="card">
                <h5 class="card-header bg-warning">Não Analisados</h5>
                <div class="card-body">
                    <h5 class="card-title">'.qtd_recursos_n_anlisados($idcurso).'</h5>
                    <p class="card-text">Recursos não anlizados</p>
                    <a href="'.$url_l.'/recursos/nao_analisados/view.php?idcurso='.$idcurso.'" class="btn btn-primary">Selecionar</a>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="card">
                <h5 class="card-header bg-success">Deferidos</h5>
                <div class="card-body">
                    <h5 class="card-title">'.qtd_recursos_deferidos($idcurso).'</h5>
                    <p class="card-text">Recursos deferidos</p>
                    <a href="'.$url_l.'/recursos/deferidos/view.php?idcurso='.$idcurso.'" class="btn btn-primary">Selecionar</a>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="card">
                <h5 class="card-header bg-danger">Indeferidos</h5>
                <div class="card-body">
                    <h5 class="card-title">'.qtd_recursos_indeferidos($idcurso).'</h5>
                    <p class="card-text">Recursos indeferidos</p>
                    <a href="'.$url_l.'/recursos/indeferidos/view.php?idcurso='.$idcurso.'" class="btn btn-primary">Selecionar</a>
                </div>
            </div>
        </div>
    </div>
</div>



';

echo $OUTPUT->footer();
