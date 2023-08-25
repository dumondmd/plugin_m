<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_orgao.php');
require_once($CFG->dirroot . '/vendor/nategood/httpful/bootstrap.php');

use \Httpful\Request;

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/blocks/orgao/updateorgao_desenv.php');
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
    'SELECT id, city, username from {user}'
);


if (!empty($users)) {
    foreach ($users as &$val) {
        if (validaCPF($val->username)) {
            consultaRHNet($val->id, $val->username);
        } else {
            echo "ID: " . $id . " Cpf inválido " . $val->username . "</br>";
        }
    }
} else {
    echo "</br>Sem dados de usuário</br>";
}




function consultaRHNet($id, $username)
{
    $autorization = "Bearer 21a59f130da35d2e62ebfbbefc2ccb50";
    $uri = "https://servicos.goias.gov.br/rhnet-rs/api/vinculo/consultarVinculo_v6/" . limpaCPF_CNPJ($username);
    $responseRest = \Httpful\Request::get($uri)
        ->addHeader('Accept', 'application/json')
        ->addHeader('Authorization', $autorization)
        ->send();

    $rhnet = new stdClass();
    $rhnet->id = null;
    $rhnet->orgao = null;
    $rhnet->cpf = null;

    $rhnet->cpf = $responseRest->body->cpf;

    $vinculos = $responseRest->body->vinculos;
    foreach ($vinculos[0]->vinculosVaga as &$vaga) {

        if ($vaga->statusVinculoVaga == 'S') {
            $rhnet->id = $vaga->orgaoOrigemCodg;
            $rhnet->orgao = $vaga->orgaoOrigemDesc;
        }
    }

    if (empty($rhnet->id)) {
        $rhnet->id = 0;
    }

    if (empty($rhnet->orgao)) {
        $rhnet->orgao = "Sem Órgão";
    }

    echo "<hr></br>";
    echo "Id Moodle: " . $id . " Id Orgao: " . $rhnet->id . " Cpf: " . $rhnet->cpf . " Orgao: " . $rhnet->orgao . "</br>";
    echo "<hr></br>";

    atualizarOrgao($id, $rhnet->orgao, $rhnet->id);

    criarOrgao($rhnet->orgao, $rhnet->id);
}




function atualizarOrgao($id, $orgao, $codrhnet)
{
    global $DB;

    try {
        unset($record);
        $record = new stdClass();
        $record->id = $id;
        $record->city = $orgao;
        $record->rhnet = $codrhnet;
        $DB->update_record('user', $record);
        echo "Sucesso id: " . $id . " órgão " . $orgao . "</br>";
    } catch (dml_exception $e) {
        echo "Erro id: " . $id . " órgão " . $orgao . "</br>";
    }
}


function criarOrgao($orgao, $id)
{
    global $DB;
    $orgaodb = null;
    $orgaodb = $DB->get_record('blocks_orgao_franchised', array('servidor' => $id), 'servidor');

    if (empty($orgaodb) && !empty($id)) {
        try {
            unset($record);
            $record = new stdClass();
            $record->orgao = $orgao;
            $record->servidor = $id;
            $DB->insert_record('blocks_orgao_franchised', $record, false);
            
            echo "Sucesso RhNet id: " . $id . " órgão " . $orgao . "</br>";
        } catch (dml_exception $e) {
            echo "Erro id: " . $id . " órgão " . $orgao . "</br>";
        }
    }
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
