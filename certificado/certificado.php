<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_certificado.php');

require_login();

$url = new moodle_url('/blocks/certificado/certificado.php');
$urlview = new moodle_url('/blocks/certificado/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Certificado de inscrição realizada');
$PAGE->set_title('Certificado de inscrição realizada');
$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Certificado', $url);
$editnode->make_active();
echo $OUTPUT->header();

function limpaCPF_CNPJ($valor)
{
  $valor = trim($valor);
  $valor = str_replace(".", "", $valor);
  $valor = str_replace(",", "", $valor);
  $valor = str_replace("-", "", $valor);
  $valor = str_replace("/", "", $valor);
  return $valor;
}


//Codigo único inscricao //cpf+datainscricao
function gerarHash($cpf, $data)
{
  $cpf = LimpaCPF_CNPJ($cpf);
  $datainicio = substr($data, 5);
  $datafim = substr($data, 0, 5);
  $combinacao = "{$datainicio}{$cpf}{$datafim}";
  return $combinacao;
}


//Id curso
$idcurso = $_GET["idcurso"];


function get_name_certificates($idcurso)
{
  global $CFG, $DB, $USER;
  $enrolsname = null;

  $enrol = 'apply';
  $status = 0;
  $userid = $USER->id;


  $enrolsname = $DB->get_records_sql(
    'SELECT u.username as cpf, u.firstname, u.lastname, u.email, u.city, c.id as courseid, c.fullname as course, ue.timecreated as enroldate, c.shortname ' .

      'from {user_enrolments} AS ue ' .
      'LEFT JOIN {enrol_apply_applicationinfo} ai ON ai.userenrolmentid = ue.id ' .
      'JOIN {user} u ON u.id = ue.userid ' .
      'JOIN {enrol} e ON e.id = ue.enrolid ' .
      'JOIN {course} c ON c.id = e.courseid ' .

      'where e.enrol = ? ' .
      'and ue.status != ? ' .
      'and u.id = ?' .
      'and c.id = ?',
    array($enrol, $status, $userid, $idcurso)
  );



  foreach ($enrolsname as &$val) {

    echo '<div class="card" id="print_comprovante">
                <div class="card-header">
                  Inscrição
                </div>
                <div class="card-body">
                  <p class="card-text">Sr(a). ' . $val->firstname . ' ' . $val->lastname . ',</p>
                  <p class="card-text">Sua inscrição no  ' . $val->course . ' foi confirmado em nosso sistema com os seguintes dados:</p>
                  <p class="card-text">CPF: ' . $val->cpf . '</p>
                  <p class="card-text">Data: ' . date("d/m/Y", $val->enroldate) . '</p>
                  <p class="card-text">Código de Inscrição: ' . gerarHash($val->cpf, $val->enroldate) . '</p>
                  <p class="card-text">Aviso: Acompanhe as publicações no site da PGE www.procuradoria.go.gov.br</p>

                  <img src="https://www.procuradoria.go.gov.br/images/institucional/logo_pge.png" width="20%" class="rounded mx-auto d-block" alt="logo_pge">
                </div>
              </div>
              <input class="btn btn-success mt-3" type="button" onclick="window.history.back()" value="Voltar">
              <button class="btn btn-primary mt-3" id="btnimprimir" type="submit">Imprimir seu comprovante</button>
              ';
  }
}
if (isset($idcurso)) {
  get_name_certificates($idcurso);
} else {
  echo '<a href="' . $urlview . '"><input class="btn btn-success" type="button"  value="Voltar"></a>';
}



echo "

<script>
      document.getElementById('btnimprimir').onclick = function() {
          var conteudo = document.getElementById('print_comprovante').innerHTML,
              tela_impressao = window.open('about:blank');

          tela_impressao.document.write(conteudo);
          tela_impressao.window.print();
          tela_impressao.window.close();
      };
</script>

";





echo $OUTPUT->footer();
