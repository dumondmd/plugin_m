<?php
require_once('../../../../../config.php');
require_once('../../../dompdf/autoload.inc.php');

use Dompdf\Dompdf;


global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;
require_login();

//Id curso
$idcurso = $_GET["idcurso"];


//Logo
$path_l = '../../../logo.png';
$type_l = pathinfo($path_l, PATHINFO_EXTENSION);
$data_l = file_get_contents($path_l);
$logoencode = 'data:image/' . $type_l . ';base64,' . base64_encode($data_l);



$inscricaodb = null;
$inscricaodb = $DB->get_records_sql(
  '
  SELECT u.id, u.username as cpf, u.firstname, u.lastname, u.email, ue.timecreated AS applydate,
  c.fullname as course, ue.timecreated as enroldate, c.shortname, inscuser.contato_telefone, inscuser.contato_whatsapp, inscuser.cota_pretendida

from {user_enrolments} AS ue
LEFT JOIN {enrol_apply_applicationinfo} ai ON ai.userenrolmentid = ue.id
JOIN {user} u ON u.id = ue.userid
JOIN {enrol} e ON e.id = ue.enrolid
JOIN {course} c ON c.id = e.courseid
JOIN {blocks_inscricao} insc ON insc.identificador_edital =  c.id AND insc.identificador_aluno = u.username
JOIN {blocks_inscricao_usuario} inscuser ON inscuser.id_curso =  c.id  AND  inscuser.id_usuario = u.id
where e.enrol = "apply"
and c.id = '.$idcurso.'
and ue.status != 0
and insc.situacao_inscricao  = "fenotipica"
ORDER BY u.firstname
;
'
);

//Template html
$html = '';


$html .= '
<!doctype html>
  <html lang="en">
  <head>
  <meta charset="UTF-8">
  <title>INSCRIÇÕES REALIZADAS - ANÁLISE FENOTÍPICA</title>

  <style type="text/css">
      * {
          font-family: Verdana, Arial, sans-serif;
      }
      table{
          font-size: x-small;
      }
      tfoot tr td{
          font-weight: bold;
          font-size: x-small;
      }
      .gray {
          background-color: lightgray
      }
      hr {
        page-break-after: always;
        border: 0;
      }

      .cb, .tdb {
        border: 1px solid black;
        border-collapse: collapse;
      }

      .tdb {
        padding:12px;
      }

      .tdbnum {
        width:8%;
      }

      .tdbcpf {
        width:18%;
      }

  </style>

  </head>
  <body>

  <table class="cb" width="100%">
      <tr>
          <td class="tdb" align="center"><img src="' . $logoencode . '" alt="Logo" width="100px" /></td>
      </tr>
  
      <tr>
        <td align="center"><strong>ESTADO DE GOIÁS</strong></td>
      </tr>
      <tr>
        <td align="center"><strong>PROCURADORIA-GERAL DO ESTADO</strong></td>
      </tr>
      <tr>
        <td class="tdb" align="center"><strong>' . consultaCurso($idcurso) . '</strong></td>
      </tr>
  </table>

  <table class="cb" width="100%">
    <tr>
        <td align="center"><strong>INSCRIÇÕES REALIZADAS - ANÁLISE FENOTÍPICA</strong></td>
    </tr>
  </table>


  <table class="cb" width="100%">
  <tr>
    <th class="tdb">***</th>
    <th class="tdb">CPF</th>
    <th class="tdb">NOME</th>
    <th class="tdb">E-MAIL</th>
    <th class="tdb">TELEFONE</th>
    <th class="tdb">WHATSAPP</th>
    <th class="tdb">COTA ESCOLHIDA</th>
  </tr>

';
$contador = 1;
foreach ($inscricaodb as &$val) {
  $html .= '
    <tr>
      <td class="tdb tdbnum" align="center">' . $contador++ . '</td>
      <td class="tdb tdbcpf" align="center">' . $val->cpf . '</td>
      <td class="tdb" align="center">' . $val->firstname . ' ' . $val->lastname . '</td>
      <td class="tdb" align="center">' . $val->email . '</td>
      <td class="tdb" align="center">' . $val->contato_telefone . '</td>
      <td class="tdb" align="center">' . $val->contato_whatsapp .'</td>
      <td class="tdb" align="center">' . $val->cota_pretendida . '</td>
    </tr>
    ';
}

$html .= '
</table>
</body>
</html>';

//Gerador pdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('Inscrições Realizadas - ANÁLISE FENOTÍPICA');




function consultaCurso($idcurso)
{
  global $DB;
  $questoesdb = $DB->get_record_sql(
    'SELECT id, shortname, fullname from {course} where id = ?',
    array($idcurso)
  );
  $nomeCurso = strtoupper($questoesdb->fullname);
  return $nomeCurso;
}




