<?php
require_once('../../config.php');


$url = new moodle_url('/blocks/universidade/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());

require_login();

// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Universidade', $url);
$editnode->make_active();
echo $OUTPUT->header();

$id = $_POST["id"];

$universidadedb = $DB->get_record_sql(
	'SELECT id, universidade from {blocks_universidade} where id = ?', array($id)
);


echo '
<form action="updateuniversidade.php" method="post">
	<div class="mb-3">
		<label for="id">ID</label>
		<input type="text" class="form-control" id="id" name="id" value="'.$universidadedb->id.'" readonly>
		<label for="universidadename" class="form-label">Nome da universidade</label>
		<input type="text" class="form-control" name="universidade" id="universidade" value="'.$universidadedb->universidade.'" required>
	</div>
	<button type="submit" class="btn btn-primary">Editar</button>
	</form>
	';















echo $OUTPUT->footer();
