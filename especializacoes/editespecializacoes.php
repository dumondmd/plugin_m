<?php
require_once('../../config.php');


$url = new moodle_url('/blocks/especializacoes/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());

require_login();

// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('especializacoes', $url);
$editnode->make_active();
echo $OUTPUT->header();

$id = $_POST["id"];

$especializacoesdb = $DB->get_record_sql(
	'SELECT id, especializacoes from {blocks_especializacoes} where id = ?', array($id)
);


echo '
<form action="updateespecializacoes.php" method="post">
	<div class="mb-3">
		<label for="id">ID</label>
		<input type="text" class="form-control" id="id" name="id" value="'.$especializacoesdb->id.'" readonly>
		<label for="especializacoesname" class="form-label">Nome da especializacoes</label>
		<input type="text" class="form-control" name="especializacoes" id="especializacoes" value="'.$especializacoesdb->especializacoes.'" required>
	</div>
	<button type="submit" class="btn btn-primary">Editar</button>
	</form>
	';















echo $OUTPUT->footer();
