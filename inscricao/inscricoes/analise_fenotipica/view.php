<?php
require_once('../../../../config.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/blocks/inscricao/inscricoes/analise_fenotipica/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Inscrições Realizadas - Análise Fenotípica');
$PAGE->set_heading('Inscrições Realizadas - Análise Fenotípica');
$PAGE->set_context(\context_system::instance());

//Id curso
$idcurso = $_GET["idcurso"];

// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('Inscrições', new moodle_url('/blocks/inscricao/view.php?idcurso='.$idcurso));
$editnode = $settingsnode->add('Confirmar Inscrição');
$editnode->make_active();
echo $OUTPUT->header();


echo '<h3 class="bg-primary p-3">Análise Fenotípica</h3>';
echo '<a class="btn btn-primary m-2" href="pdf/index.php?idcurso='.$idcurso.'" role="button">Exportar PDF <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
echo '<a class="btn btn-primary m-2" href="xls/index.php?idcurso='.$idcurso.'" role="button">Exportar XLS <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>';

function get_inscricoes($idcurso) {
    global $DB, $CFG;

    $inscricaodb = null;
    $inscricaodb = $DB->get_records_sql(
      '
      SELECT u.username as cpf, u.firstname, u.lastname, u.email, u.city, u.lastaccess, ue.timecreated AS applydate,
	    c.fullname as course, ue.timecreated as enroldate, c.shortname

    from {user_enrolments} AS ue
    LEFT JOIN {enrol_apply_applicationinfo} ai ON ai.userenrolmentid = ue.id
    JOIN {user} u ON u.id = ue.userid
    JOIN {enrol} e ON e.id = ue.enrolid
    JOIN {course} c ON c.id = e.courseid
    JOIN {blocks_inscricao} insc ON insc.identificador_edital =  c.id AND insc.identificador_aluno = u.username
    where e.enrol = "apply"
    and c.id = '.$idcurso.'
    and ue.status != 0
    and insc.situacao_inscricao  = "fenotipica"
    ORDER BY u.firstname
    ;
      '
    );




    //Tabela de inscricao
    echo '
     <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">CPF</th>
          <th scope="col">NOME</th>
          <th scope="col">E-MAIL</th>
          <th scope="col">DATA DE INSCRIÇÃO</th>
          <th scope="col">DATA ÚLTIMO ACESSO</th>
          <th scope="col">ANÁLISE DA INSCRIÇÃO</th>
        </tr>
      </thead>
      <tbody>
      ';

    foreach ($inscricaodb as &$val) {
      echo '<tr><td>'.$val->cpf.'</td>';
      echo '<td>'.$val->firstname.' '.$val->lastname.'</td>';
      echo '<td>'.$val->email.'</td>';
      echo '<td>'.date("d-m-Y", $val->applydate).'</td>';
      echo '<td>'.date("d-m-Y", $val->lastaccess).'</td>';
      echo '<td><form action="../buscar_usuario.php" method="post">
      <input type="hidden" name="id" value="'.$val->cpf.'">
      <input type="hidden" name="idcurso" value="'.$_GET["idcurso"].'">

      <button type="submit" class="btn btn-primary">Editar</button></form></td></tr>';
    }

    echo  '</tbody></table>';
}
get_inscricoes($idcurso);
echo $OUTPUT->footer();
