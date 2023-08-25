<?php
require_once('../../config.php');


$url = new moodle_url('/blocks/questoes/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Questões', $url);
$editnode->make_active();
echo $OUTPUT->header();


$id = $_POST["id"];
$questoesnome = trim($_POST["questoesnome"]);
$questoestexto = trim($_POST["questoestexto"]);




if(!empty($id) && !empty($questoesnome) && !empty($questoestexto)){

	global $DB;

	try {
		$record = new stdClass();
		$record->id = $id;
		$record->questao_nome = $questoesnome;
		$record->questao_texto = $questoestexto;
		$DB->update_record('block_questoes', $record);

		redirect($CFG->wwwroot . '/blocks/questoes/view.php');
	} catch (dml_exception $e) {
		echo '<h3>Erro ao atualizar banco de dados!</h3><br>'.$e;
	}
} else {
	redirect($CFG->wwwroot . '/blocks/questoes/view.php');
}


echo $OUTPUT->footer();
