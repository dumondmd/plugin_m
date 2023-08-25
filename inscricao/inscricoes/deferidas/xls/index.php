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


function ocultarCPF($cpf)
{
  $cpfOculto = "XXX.XXX";
  $cpfInicio = substr($cpf, 0, 4);
  $cpfFim = substr($cpf, 11, 3);
  $cpf = "{$cpfInicio}{$cpfOculto}{$cpfFim}";
  return $cpf;
}

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

function verificaPessoaPCD($val){
  if($val){
    return "SIM";
  } else {
    return "NÃO";
  }
}


function vericaDeferimento($val){
  if($val){
    return strtoupper($val);
  } else {
    return "ANALISANDO";
  }
}

$inscricaodb = null;
$inscricaodb = $DB->get_records_sql(
  '
  SELECT u.username as cpf, u.firstname, u.lastname, u.data_nacimento, u.rg, u.email, u.graduaco_local_exercicio,
  u.pcd_data, u.pcd_descricao,
  ue.timecreated AS applydate,
  c.fullname as course, ue.timecreated as enroldate, c.shortname,
  insc.situacao_inscricao

from {user_enrolments} AS ue
LEFT JOIN {enrol_apply_applicationinfo} ai ON ai.userenrolmentid = ue.id
JOIN {user} u ON u.id = ue.userid
JOIN {enrol} e ON e.id = ue.enrolid
JOIN {course} c ON c.id = e.courseid
JOIN {blocks_inscricao} insc ON insc.identificador_aluno = u.username
where e.enrol = "apply"
and c.id = ' . $idcurso . '
and ue.status != 0
and insc.situacao_inscricao  = "deferida"
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
  <title>INSCRIÇÕES REALIZADAS - DEFERIDAS</title>

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
        <td align="center"><strong>' . consultaCurso($idcurso) . '</strong></td>
    </tr>
  </table>


  <table class="cb" width="100%">
  <tr>
    <th class="tdb">***</th>
    <th class="tdb">CPF</th>
    <th class="tdb">NOME</th>
    <th class="tdb">Lotação</th>
    <th class="tdb">PNE</th>
    <th class="tdb">PRECISA DE TRATAMENTO ESPECIAL</th>
    <th class="tdb">DEF/IND</th>
  </tr>

';
$contador = 1;
foreach ($inscricaodb as &$val) {
  $html .=
  '<tr>
    <td class="tdb tdbnum" align="center">' . $contador++ . '</td>
    <td class="tdb tdbcpf" align="center">' . ocultarCPF($val->cpf) . '</td>
    <td class="tdb" align="center">' . $val->firstname . ' ' . $val->lastname . '</td>
    <td class="tdb" align="center">' . strtoupper($val->graduaco_local_exercicio) . '</td>
    <td class="tdb" align="center">' . verificaPessoaPCD($val->pcd_data) . '</td>
    <td class="tdb" align="center">' . verificaPessoaPCD($val->pcd_descricao) .'</td>
    <td class="tdb" align="center">' . vericaDeferimento($val->situacao_inscricao) . '</td>
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
