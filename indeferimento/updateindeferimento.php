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


$id = $_POST["id"];
$indeferimento = strtoupper(trim($_POST["indeferimentonome"]));


if(!empty($id) && !empty($indeferimento)){

	global $DB;

	try {
		$record = new stdClass();
		$record->id = $id;
		$record->indeferimento = $indeferimento;
		$DB->update_record('block_indeferimento', $record);

		redirect($CFG->wwwroot . '/blocks/indeferimento/view.php');
	} catch (dml_exception $e) {
		echo '<h3>Erro ao atualizar motivo de indeferimento!</h3><br>'.$e;
	}
} else {
	redirect($CFG->wwwroot . '/blocks/indeferimento/view.php');
}


echo $OUTPUT->footer();
