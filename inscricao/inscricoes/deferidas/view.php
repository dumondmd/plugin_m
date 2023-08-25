<?php
require_once('../../../../config.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/blocks/inscricao/inscricoes/deferidas/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Inscrições Realizadas - Deferidas');
$PAGE->set_title('Inscrições Realizadas - Deferidas');
$PAGE->set_context(\context_system::instance());

//Id curso
$idcurso = $_GET["idcurso"];

//Consulta nome de curso
$curso_db = $DB->get_record_sql(
  'SELECT c.id, c.fullname FROM {course} AS c WHERE c.id = ?',
  [$idcurso]
);

function formatarData($data)
{
  if ($data) {
    return date("d/m/Y H:i:s", $data);
  } else {
    return '';
  }
}

$localNomeZip = 'caderno/public/compactados/' . $curso_db->fullname . '.zip';

$inscricaodb = null;
$inscricaodb = $DB->get_records_sql(
  '
      SELECT u.id as iduser, u.username as cpf, u.firstname, u.lastname, u.email, u.city, ue.timecreated AS applydate,
      c.fullname as course, ue.timecreated as enroldate, c.shortname, insc.data_analise, insc.responsavel_analise

    from {user_enrolments} AS ue
    LEFT JOIN {enrol_apply_applicationinfo} ai ON ai.userenrolmentid = ue.id
    JOIN {user} u ON u.id = ue.userid
    JOIN {enrol} e ON e.id = ue.enrolid
    JOIN {course} c ON c.id = e.courseid
    JOIN {blocks_inscricao} insc ON insc.identificador_edital =  c.id AND insc.identificador_aluno = u.username
    where e.enrol = "apply"
    and c.id = ' . $idcurso . '
    and ue.status != 0
    and insc.situacao_inscricao  = "deferida"
    ORDER BY u.firstname
    ;'
);


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('Inscrições', new moodle_url('/blocks/inscricao/view.php?idcurso=' . $idcurso));
$editnode = $settingsnode->add('Confirmar Inscrição');
$editnode->make_active();
echo $OUTPUT->header();


echo '<h3 class="bg-success p-3">Deferidas</h3>';

echo '<a class="btn btn-primary m-2" href="pdf/index.php?idcurso=' . $idcurso . '" role="button">Exportar PDF sem CPF <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
echo '<a class="btn btn-primary m-2" href="xls/index.php?idcurso=' . $idcurso . '" role="button">Exportar XLS <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>';
echo '<a class="btn btn-primary m-2" href="xls/cpf.php?idcurso=' . $idcurso . '" role="button">Exportar XLS com CPF <i class="fa fa-file-excel-o" aria-hidden="true"></i></a>';
echo '<a class="btn btn-primary m-2" href="caderno/index.php?idcurso=' . $idcurso . '" role="button">Gerar provas em Lote <i class="fa fa-book" aria-hidden="true"></i></a>';

echo '<span class="btn btn-primary m-2"  role="button" id="btnAnalise">Mostrar/Ocultar Analise <i class="fa fa-book" aria-hidden="true"></i></span>';





if (file_exists($localNomeZip)) {
  echo '<a class="btn btn-success m-2" href="' . $localNomeZip . '" role="button">Download ZIP provas em Lote <i class="fa fa-file-archive-o" aria-hidden="true"></i></a>';
}


//Tabela de inscricao
echo '
     <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Número</th>
          <th scope="col">CPF</th>
          <th scope="col">NOME</th>
          <th scope="col">E-MAIL</th>
          <th scope="col">DATA INSCRIÇÃO</th>
          <th style="display:none;" class="analise" scope="col">ANALISTA</th>
          <th style="display:none;" class="analise" scope="col">DATA ANÁLISE</th>
          <th scope="col">CADERNO DE PROVA</th>
          <th scope="col">ANÁLISE DA INSCRIÇÃO</th>
        </tr>
      </thead>
      <tbody>
      ';
//Contador
$i = 0;
foreach ($inscricaodb as &$val) {
  global $idcurso;
  $i++;
  echo '<tr><td>' . $i . '</td>';
  echo '<td>' . $val->cpf . '</td>';
  echo '<td>' . $val->firstname . ' ' . $val->lastname . '</td>';
  echo '<td>' . $val->email . '</td>';
  echo '<td>' . formatarData($val->applydate) . '</td>';
  echo '<td style="display:none;" class="analise">' . $val->responsavel_analise . '</td>';
  echo '<td style="display:none;" class="analise">' . formatarData($val->data_analise) . '</td>';
  echo '<td><a class="btn btn-primary" href="caderno/index.php?idcurso=' . $idcurso . '&iduser=' . $val->iduser . '" role="button">Gerar</a></td>';
  echo '<td><form action="../buscar_usuario.php" method="post">
      <input type="hidden" name="id" value="' . $val->cpf . '">
      <input type="hidden" name="idcurso" value="' . $_GET["idcurso"] . '">

      <button type="submit" class="btn btn-primary">Editar</button></form></td></tr>';
}
echo '</tbody></table>';




//JAVA SCRIPT
echo '<script type="text/javascript" src="../../../../lib/jquery/jquery-3.6.0.js"></script>';

echo "
<script>
  $(document).ready(function() {
    var show = true;

    $('#btnAnalise').click(function() {
        if(show == true){
            $('.analise').show();
            show = false;
            console.log('Mostrando analise')
        } else {
            $('.analise').hide();
            show = true;
            console.log('Ocultando analise');
        }
    });
  });
</script>

";

echo $OUTPUT->footer();