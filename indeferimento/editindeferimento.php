<?php
require_once('../../config.php');


$url = new moodle_url('/blocks/indeferimento/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());

require_login();

// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Indeferimento', $url);
$editnode->make_active();
echo $OUTPUT->header();

$id = $_POST["id"];

$indeferimentodb = $DB->get_record_sql(
	'SELECT id, indeferimento from {block_indeferimento} where id = ?', array($id)
);


echo '
<form action="updateindeferimento.php" method="post">
	<div class="mb-3">
		<label for="id">ID</label>
		<input type="text" class="form-control" id="id" name="id" value="'.$indeferimentodb->id.'" readonly>
		<label for="indeferimento" class="form-label">Nome do indeferimento</label>
		<input type="text" class="form-control" name="indeferimentonome" id="indeferimentonome" value="'.$indeferimentodb->indeferimento.'" required>
	</div>
	<button type="submit" class="btn btn-primary">Editar</button>
	</form>
	';















echo $OUTPUT->footer();
