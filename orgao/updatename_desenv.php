<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_orgao.php');
require_once($CFG->dirroot . '/vendor/nategood/httpful/bootstrap.php');

use \Httpful\Request;

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/blocks/orgao/updatename_desenv.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Orgaos', $url);
$editnode->make_active();
echo $OUTPUT->header();

$users = null;

$users = $DB->get_records_sql(
    'SELECT id, username, firstname, lastname from {user}'
);


if (!empty($users)) {
    foreach ($users as &$val) {
        if (validaCPF($val->username)) {
            consultaRHNet($val->id, $val->username, $val->firstname, $val->lastname);
        }
    }
} else {
    echo "</br>Sem dados de usuário</br>";
}




function consultaRHNet($id, $username, $nome, $sobrenome)
{
    $autorization = "Bearer 21a59f130da35d2e62ebfbbefc2ccb50";
    $uri = "https://servicos.goias.gov.br/rhnet-rs/api/vinculo/consultarVinculo_v6/" . limpaCPF_CNPJ($username);
    $responseRest = \Httpful\Request::get($uri)
        ->addHeader('Accept', 'application/json')
        ->addHeader('Authorization', $autorization)
        ->send();

    $rhnet = new stdClass();
    $rhnet->firstname = null;
    $rhnet->lastname = null;
    $nomeCompleto = null;


    $nomeCompleto = formatName($responseRest->body->nome);

    $rhnet->firstname = $nomeCompleto->fname;
    $rhnet->lastname = $nomeCompleto->lname;



    if (!empty($rhnet->firstname) && !empty($rhnet->lastname)) {
        renderHtml($id, $username, $nome, $sobrenome, $rhnet->firstname, $rhnet->lastname);
        atualizarNome($id, $rhnet->firstname, $rhnet->lastname);
    }
}

function renderHtml($id, $username, $nome, $sobrenome, $nomeRhnet, $sobrenomeRhnet)
{
    echo '
    <div class="container-fluid">
    <h3>ID: ' . $id . ' CPF: ' . $username . '</h3>
    </br>
    <div class="row">
        <div class="col" style="background-color:rgb(208, 233, 212);">
            <h5>Moodle - Ava</h5>
            <ul>
                <li>Nome: ' . $nome . '</li>
                <li>Sobrenome: ' . $sobrenome . '</li>
            </ul>
        </div>
        <div class="col" style="background-color:rgb(96, 255, 136);">
            <h5>RhNet</h5>
            <ul>
                <li>Nome: ' . $nomeRhnet . '</li>
                <li>Sobrenome: ' . $sobrenomeRhnet . '</li>
            </ul>
        </div>
    </div>
    </div>
    <br> 
    ';
}




function atualizarNome($id, $fname, $lname)
{
    global $DB;

    try {
        unset($record);
        $record = new stdClass();
        $record->id = $id;
        $record->firstname = $fname;
        $record->lastname = $lname;
        $DB->update_record('user', $record);
        echo "Sucesso, Id: " . $id . " Nome: " . $fname . " " . $lname . "</br><hr>";
    } catch (dml_exception $e) {
        echo "Erro, Id: " . $id . " Nome: " . $fname . " " . $lname . "</br><hr>";
    }
}


function formatName($name)
{
    $name = trim($name);
    $fname = explode(' ', $name);
    $lname = substr($name, strlen($fname[0]) + 1);

    $nameObj = new stdClass();
    $nameObj->fname = $fname[0];
    $nameObj->lname = $lname;

    return $nameObj;
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


function validaCPF($cpf)
{

    // Extrai somente os números
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);

    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}


echo $OUTPUT->footer();
