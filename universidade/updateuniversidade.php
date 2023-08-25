<?php
require_once('../../config.php');


$url = new moodle_url('/blocks/universidade/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Universidade', $url);
$editnode->make_active();
echo $OUTPUT->header();


$id = $_POST["id"];
$universidade = strtoupper(trim($_POST["universidade"]));


if(!empty($id) && !empty($universidade)){

	global $DB;

	try {
		$record = new stdClass();
		$record->id = $id;
		$record->universidade = $universidade;
		$DB->update_record('blocks_universidade', $record);

		redirect($CFG->wwwroot . '/blocks/universidade/view.php');
	} catch (dml_exception $e) {
		echo '<h3>Erro ao atualizar universidade no banco de dados!</h3><br>'.$e;
	}
} else {
	redirect($CFG->wwwroot . '/blocks/universidade/view.php');
}


echo $OUTPUT->footer();
