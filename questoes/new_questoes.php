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


$questoesnome = trim($_POST["questoesnome"]);
$questoestexto = trim($_POST["questoestexto"]);



if (!empty($questoesnome) && !empty($questoestexto)) {

	global $DB;
	$record = new stdClass();
	$record->questao_nome = $questoesnome;
	$record->questao_texto = $questoestexto;
	try {
		$DB->insert_record('block_questoes', $record, false);
		redirect($CFG->wwwroot . '/blocks/questoes/view.php');
	} catch (dml_exception $e) {
		echo '<h3>Erro ao salvar no banco de dados!</h3><br>' . $e;
	}
} else {
	redirect($CFG->wwwroot . '/blocks/questoes/view.php');
}

echo $OUTPUT->footer();
