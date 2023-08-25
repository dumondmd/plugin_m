<?php
require_once('../../config.php');


$url = new moodle_url('/blocks/universidade/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('universidade', $url);
$editnode->make_active();
echo $OUTPUT->header();

$universidade = strtoupper(trim($_POST["universidadename"]));

$universidadedb = $DB->get_records_sql(
    'SELECT id, universidade from {blocks_universidade} where universidade = ?', array($universidade)
 );




if(isset($universidade) && $universidade !='' && empty($universidadedb)){

	global $DB;
	$record = new stdClass();
	$record->universidade = $universidade;
	try {
		$DB->insert_record('blocks_universidade', $record, false);
		redirect($CFG->wwwroot . '/blocks/universidade/view.php');
	} catch (dml_exception $e) {
		echo '<h3>Erro ao salvar universidade no banco de dados!</h3><br>'.$e;
	}
} else {
	redirect($CFG->wwwroot . '/blocks/universidade/view.php');
}

echo $OUTPUT->footer();
