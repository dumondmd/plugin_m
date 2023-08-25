<?php
require_once('../../config.php');


$url = new moodle_url('/blocks/especializacoes/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('especializacoes', $url);
$editnode->make_active();
echo $OUTPUT->header();


$id = $_POST["id"];
$especializacoes = strtoupper(trim($_POST["especializacoes"]));


if(!empty($id) && !empty($especializacoes)){

	global $DB;

	try {
		$record = new stdClass();
		$record->id = $id;
		$record->especializacoes = $especializacoes;
		$DB->update_record('blocks_especializacoes', $record);

		redirect($CFG->wwwroot . '/blocks/especializacoes/view.php');
	} catch (dml_exception $e) {
		echo '<h3>Erro ao atualizar especialização no banco de dados!</h3><br>'.$e;
	}
} else {
	redirect($CFG->wwwroot . '/blocks/especializacoes/view.php');
}


echo $OUTPUT->footer();
