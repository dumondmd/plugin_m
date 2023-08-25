<?php
require_once('../../config.php');


$url = new moodle_url('/blocks/orgao/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Órgão', $url);
$editnode->make_active();
echo $OUTPUT->header();


$id = $_POST["id"];
$responsable = $_POST["responsable"];


if(isset($id) && $id !='' && !empty($id)){
    
    global $DB;
    
    try {
        $record = new stdClass();
        $record->id = $id;
        $record->responsable = $responsable;  
        $DB->update_record('blocks_orgao_franchised', $record);

        redirect($CFG->wwwroot . '/blocks/orgao/view.php');
    } catch (dml_exception $e) {
        echo '<h3>Erro ao atualizar Órgão no banco de dados!</h3><br>'.$e;
    }
} else {
    redirect($CFG->wwwroot . '/blocks/orgao/view.php');
}

echo $OUTPUT->footer();