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

$especializacoes = strtoupper(trim($_POST["especializacoesname"]));

$especializacoesdb = $DB->get_records_sql(
    'SELECT id, especializacoes from {blocks_especializacoes} where especializacoes = ?', array($especializacoes)
 );




if(isset($especializacoes) && $especializacoes !='' && empty($especializacoesdb)){

	global $DB;
	$record = new stdClass();
	$record->especializacoes = $especializacoes;
	try {
		$DB->insert_record('blocks_especializacoes', $record, false);
		redirect($CFG->wwwroot . '/blocks/especializacoes/view.php');
	} catch (dml_exception $e) {
		echo '<h3>Erro ao salvar especializações no banco de dados!</h3><br>'.$e;
	}
} else {
	redirect($CFG->wwwroot . '/blocks/especializacoes/view.php');
}

echo $OUTPUT->footer();
