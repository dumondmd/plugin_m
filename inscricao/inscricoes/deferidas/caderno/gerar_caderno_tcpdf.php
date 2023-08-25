<?php
require_once('../../../../../config.php');
require_once($CFG->libdir . '/tcpdf/tcpdf.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;
require_login();

//Id curso
$idcurso = $_GET["idcurso"];
$idcursodb = '';
if (empty(!$idcurso)) {
  $idcursodb = 'and c.id = ' . $idcurso;
}

//Id user
$iduser = $_GET["iduser"];
$iduserdb = '';
if (empty(!$iduser)) {
  $iduserdb = 'and u.id = ' . $iduser;
}

//Data de realização da prova
$dataRealisacaoProva = $_GET["dataRealizacaoProva"];
$dataRealisacaoProva = strtotime($dataRealisacaoProva);
$dataRealisacaoProva = date("d/m/Y", $dataRealisacaoProva);

//Consulta CPF de usuario quando for unico
$usuariodb = null;
if (!empty($iduser)) {
  $usuariodb = $DB->get_record_sql(
    'SELECT id, username as cpf from {user} where id = ?',
    array($iduser)
  );
}

//Consulta questão dinamica cadastrada
$questaoid = $_GET["idquestao"];
$questoesdb = null;
$questoesdb = $DB->get_record_sql(
  'SELECT id, questao_nome, questao_texto from {block_questoes} where id = ?',
  array($questaoid)
);
if (empty($questoesdb)) {
  $questoesdb->questao_texto = 'Questão não encontrada!';
}

//Qtd linhas
$qtd_linhas = 30;

//Codigo único inscricao //cpf+datainscricao
function gerarHash($cpf, $data)
{
  $cpf = LimpaCPF_CNPJ($cpf);
  $datainicio= substr($data, 5);
  $datafim = substr($data, 0, 5);
  $combinacao = "{$datainicio}{$cpf}{$datafim}";
  return $combinacao;
}

function limpaCPF_CNPJ($valor)
{
  $valor = trim($valor);
  $valor = str_replace(".", "", $valor);
  $valor = str_replace(",", "", $valor);
  $valor = str_replace("-", "", $valor);
  $valor = str_replace("/", "", $valor);
  return $valor;
}

//Codigo barras
global $char128asc, $char128charWidth;
$char128asc = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
$char128wid = array(
  '212222', '222122', '222221', '121223', '121322', '131222', '122213', '122312', '132212', '221213', // 0-9
  '221312', '231212', '112232', '122132', '122231', '113222', '123122', '123221', '223211', '221132', // 10-19
  '221231', '213212', '223112', '312131', '311222', '321122', '321221', '312212', '322112', '322211', // 20-29
  '212123', '212321', '232121', '111323', '131123', '131321', '112313', '132113', '132311', '211313', // 30-39
  '231113', '231311', '112133', '112331', '132131', '113123', '113321', '133121', '313121', '211331', // 40-49
  '231131', '213113', '213311', '213131', '311123', '311321', '331121', '312113', '312311', '332111', // 50-59
  '314111', '221411', '431111', '111224', '111422', '121124', '121421', '141122', '141221', '112214', // 60-69
  '112412', '122114', '122411', '142112', '142211', '241211', '221114', '413111', '241112', '134111', // 70-79
  '111242', '121142', '121241', '114212', '124112', '124211', '411212', '421112', '421211', '212141', // 80-89
  '214121', '412121', '111143', '111341', '131141', '114113', '114311', '411113', '411311', '113141', // 90-99
  '114131', '311141', '411131', '211412', '211214', '211232', '23311120'
); // 100-106

////Define Function
function bar128($text, $shownumber)
{ // Part 1, make list of widths
  global $char128asc, $char128wid;
  $w = $char128wid[$sum = 104]; // START symbol
  $onChar = 1;
  for ($x = 0; $x < strlen($text); $x++) // GO THRU TEXT GET LETTERS
    if (!(($pos = strpos($char128asc, $text[$x])) === false)) { // SKIP NOT FOUND CHARS
      $w .= $char128wid[$pos];
      $sum += $onChar++ * $pos;
    }
  $w .= $char128wid[$sum % 103] . $char128wid[106]; //Check Code, then END
  //Part 2, Write rows
  $html = "<table cellpadding=0 cellspacing=0><tr>";
  for ($x = 0; $x < strlen($w); $x += 2) // code 128 widths: black border, then white space
    $html .= "<td><div class=\"b128\" style=\"border-left-width:{$w[$x]};width:{$w[$x + 1]}\"></div></td>";
  if ($shownumber) {
    return "$html<tr><td colspan=" . strlen($w) . " align=left><font family=arial size=2>$text</td></tr></table>";
  } else {
    return "$html<tr><td colspan=" . strlen($w) . " align=left><font family=arial size=2></td></tr></table>";
  }
}

$inscricaodb = null;
$inscricaodb = $DB->get_records_sql(
  "
  SELECT u.id as userid, u.username as cpf, u.firstname, u.lastname, u.data_nacimento, u.rg, u.nome_mae,
  ue.timecreated AS applydate, c.id as cursoid,
  c.fullname as course, ue.timecreated as enroldate, c.shortname

from {user_enrolments} AS ue
LEFT JOIN {enrol_apply_applicationinfo} ai ON ai.userenrolmentid = ue.id
JOIN {user} u ON u.id = ue.userid
JOIN {enrol} e ON e.id = ue.enrolid
JOIN {course} c ON c.id = e.courseid
JOIN {blocks_inscricao} insc ON insc.identificador_aluno = u.username
where e.enrol = 'apply'
and ue.status != 0
and insc.situacao_inscricao  = 'deferida'
" . $iduserdb . "

ORDER BY ue.timecreated
;"
);

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);



$pdf->SetTitle('Caderno de prova');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
//$pdf->SetFont('arial', '', 10);

// add a page
$pdf->AddPage();


// Frente do caderno

$html = '

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

.cb, .tdb, .tdbnum {
  border: 1px solid black;
  border-collapse: collapse;
}

.tdb {
  padding:12px;
}

.tdbnum {
  width:2%;
}

div.b128{
 border-left: 1px black solid;
 height: 30px;
}

</style>

';

foreach ($inscricaodb as &$val) {
    $html .= '
  
    <table width="100%">
        <tr>
            <td align="center"><img src="images/logo_example.png" alt="Logo" width="100px" /></td>
        </tr>
    </table>
  
    <table width="100%">
        <tr>
            <td align="center"><strong>' . $val->course . '</strong></td>
        </tr>
    </table>
  
  
    <table width="100%">
        <tr>
            <td align="left"><strong>Nome:</strong> ' . strtoupper($val->firstname) . ' ' . strtoupper($val->lastname) . '</td>
            <td align="left"><strong>CPF:</strong> ' . $val->cpf . '</td>
        </tr>
        <tr>
            <td align="left"><strong>RG:</strong> ' . $val->rg . '</td>
            <td align="left"><strong>Data Realização da Prova:</strong> ' . $dataRealisacaoProva . '</td>
        </tr>
        <tr>
            <td align="right">Codigo de barras</td>
        </tr>
    </table>
  
    <table class="linhacorte" width="100%">
    <tr>
        <td align="center">--------------------------------------------------------------------------------------------------------------------------------
        </td>
    </tr>
    </table>
  

  
    <table width="100%">
        <tr>
            <td align="justify"><strong>' . $questoesdb->questao_texto . '</strong></td>
        </tr>
    </table>
  
    <td align="center"><img src="images/watermark.png" alt="Logo" width="100px" height="100px"/></td>
  
    <hr />
  
  
  
    <table width="100%">
        <tr>
            <td align="center"><img src="' . $logoencode . '" alt="Logo" width="100px" /></td>
        </tr>
    </table>
  
    <table width="100%">
        <tr>
            <td align="center"><strong>' . $val->course . '</strong></td>
        </tr>
    </table>
  
  
    <table width="100%">
        <tr>
            <td align="left"><strong>Nome:</strong> ' . $val->firstname . ' ' . $val->lastname . '</td>
            <td align="left"><strong>CPF:</strong> ' . $val->cpf . '</td>
        </tr>
        <tr>
            <td align="left"><strong>RG:</strong> ' . $val->rg . '</td>
            <td align="left"><strong>Data Realização da Prova:</strong> ' . $dataRealisacaoProva . '</td>
        </tr>
        
    </table>
  
  
    <table class="linhacorte" width="100%">
      <tr>
      <td align="center">--------------------------------------------------------------------------------------------------------------------------------
          </td>
      </tr>
    </table>
    
   
  
  
  
    <table class="cb" style="width:100%">';
  
    //Criacao linhas em branco
  
    for ($i = 0; $i < $qtd_linhas; $i++) {
      $html .= '
           <tr>
             <td class="tdbnum">'.($i+1).'</td>
             <td class="tdb"></td>
           </tr>
        ';
    }
  
    $html .= '</table><hr/>';
  }


// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');






// reset pointer to the last page
$pdf->lastPage();


$pdf->lastPage();





// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Caderno_de_prova.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
