<?php
require_once('../../../../../config.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;
require_login();

//Id curso
$idcurso = $_GET["idcurso"];

//Nome arquivo
$arquivo = 'Inscrições Realizadas - Deferidas.xls';

//Logo
$path_l = '../../../logo.png';
$type_l = pathinfo($path_l, PATHINFO_EXTENSION);
$data_l = file_get_contents($path_l);
$logoencode = 'data:image/' . $type_l . ';base64,' . base64_encode($data_l);



$inscricaodb = null;
$inscricaodb = $DB->get_records_sql(
  '
  SELECT u.username as cpf, u.firstname, u.lastname, u.data_nacimento, u.rg, u.email,
  ue.timecreated AS applydate,
  c.fullname as course, ue.timecreated as enroldate, c.shortname, insc.motivo_indeferimento

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
ORDER BY u.firstname
;'
);

//Template html
$html = '';


$html .= '
<!doctype html>
  <html lang="en">
  <head>
  <meta charset="UTF-8">
  <title>Caderno de prova</title>

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

  </style>

  </head>
  <body>


  <table width="100%">
    <tr>
        <td align="left"><img src="'.$logoencode.'" alt="Logo" width="100"/></td>
        <td align="center"><strong>Inscrições Realizadas - Indeferidas</strong></td>
    </tr>
  </table>


  <table class="cb" width="100%">
  <tr>
    <th class="tdb">CPF</th>
    <th class="tdb">NOME</th>
    <th class="tdb">E-MAIL</th>
    <th class="tdb">DATA</th>
    <th class="tdb">Motivo indeferimento</th>
  </tr>

';

foreach ($inscricaodb as &$val) {
  $html .=
  '<tr>
    <td class="tdb">'.$val->cpf.'</td>
    <td class="tdb">'.$val->firstname.' '.$val->lastname.'</td>
    <td class="tdb">'.$val->email.'</td>
    <td class="tdb">'.date("d-m-Y", $val->applydate).'</td>
    <td class="tdb">'.$val->motivo_indeferimento.'</td>
  </tr>';

}

$html .= '
</table>
</body>
</html>';

// Configurações header para forçar o download
		header ("Expires: Mon, 07 Jul 2030 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/x-msexcel");
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
		header ("Content-Description: PHP Generated Data" );

echo $html;
exit;
