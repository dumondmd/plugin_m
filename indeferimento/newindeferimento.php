<?php
require_once('../../config.php');


$url = new moodle_url('/blocks/indeferimento/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Indeferimento', $url);
$editnode->make_active();
echo $OUTPUT->header();

$indeferimento = strtoupper(trim($_POST["indeferimentonome"]));

$indeferimentodb = $DB->get_records_sql(
    'SELECT id, indeferimento from {block_indeferimento} where indeferimento = ?', array($indeferimento)
 );




if(isset($indeferimento) && $indeferimento !='' && empty($indeferimentodb)){

	global $DB;
	$record = new stdClass();
	$record->indeferimento = $indeferimento;
	try {
		$DB->insert_record('block_indeferimento', $record, false);
		redirect($CFG->wwwroot . '/blocks/indeferimento/view.php');
	} catch (dml_exception $e) {
		echo '<h3>Erro ao salvar motivos de indeferimento!</h3><br>'.$e;
	}
} else {
	redirect($CFG->wwwroot . '/blocks/indeferimento/view.php');
}

echo $OUTPUT->footer();
