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



if(isset($_POST["id"])){
	$id = $_POST["id"];
	global $DB;

	try {
		$DB->delete_records('blocks_universidade', array('id'=>$id));
		redirect($CFG->wwwroot . '/blocks/universidade/view.php');
	} catch (dml_exception $e) {
		echo '<h3>Erro ao deletar universidade no banco de dados!</h3><br>'.$e;
	}
}

echo $OUTPUT->footer();
