<?php
require_once('../../config.php');


$url = new moodle_url('/blocks/orgao/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Cidade', $url);
$editnode->make_active();
echo $OUTPUT->header();

$orgao = $_POST["orgaoname"];

$citiesdb = $DB->get_records_sql(
    'SELECT id, orgao from {blocks_orgao_franchised} where orgao = ?', array($orgao)                
 ); 




if(isset($orgao) && $orgao !='' && empty($citiesdb)){
    
    global $DB;
    $record = new stdClass();
    $record->orgao = $orgao;
    try {
        $DB->insert_record('blocks_orgao_franchised', $record, false);
        redirect($CFG->wwwroot . '/blocks/orgao/view.php');
    } catch (dml_exception $e) {
        echo '<h3>Erro ao salvar órgão no banco de dados!</h3><br>'.$e;
    }
} else {
    redirect($CFG->wwwroot . '/blocks/orgao/view.php');
}

echo $OUTPUT->footer();