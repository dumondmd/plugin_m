<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_orgao.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/blocks/orgao/updatecpf_desenv.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Orgaos', $url);
$editnode->make_active();
echo $OUTPUT->header();

$dadoscpf = null;

$dadoscpf = $DB->get_records_sql(
    'SELECT userid, data from {user_info_data}'
);


if (!empty($dadoscpf)) {
    foreach ($dadoscpf as &$val) {
        if (validaCPF($val->data)) {
            atualizarCPF($val->userid, padronizarCPF($val->data));
        } else {
            echo "</br>CPF invalido, foi informado:  Id: " . $val->userid . " | CPF: " . $val->data . "</br>";
        }
    }
} else {
    echo "</br>Sem dados de CPF</br>";
}


function atualizarCPF($id, $cpf)
{
    global $DB;

    try {
        unset($record);
        $record = new stdClass();
        $record->id = $id;
        $record->username = $cpf;
        $DB->update_record('user', $record);
        echo "Sucesso id " . $id . " cpf " . $cpf . "</br>";
    } catch (dml_exception $e) {
        echo '</br><h3>Erro ao atualizar o banco de dados!</h3></br>' . $e;
    }
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

function padronizarCPF($valor)
{
    trim($valor);
    $valor = preg_replace('/[^0-9]/', '', $valor);
    $mask = "%s%s%s.%s%s%s.%s%s%s-%s%s";
    return  vsprintf($mask, str_split($valor));
}


echo $OUTPUT->footer();
