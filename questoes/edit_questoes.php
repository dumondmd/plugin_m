<?php
require_once('../../config.php');


$url = new moodle_url('/blocks/questoes/view.php');
$PAGE->set_url($url);
$PAGE->set_title('Gerenciar Questões');
$PAGE->set_heading('Gerenciar Questões');
$PAGE->set_pagelayout('standard');
$PAGE->set_context(\context_system::instance());

require_login();

// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Questões', $url);
$editnode->make_active();
echo $OUTPUT->header();

$id = $_POST["id"];

$questoesdb = $DB->get_record_sql(
	'SELECT id, questao_nome, questao_texto from {block_questoes} where id = ?', array($id)
);





if(empty($questoesdb)){
	//Criar
	echo '
	<form action="new_questoes.php" method="post">
		<div class="mb-3">					
			<label for="questoesnome" class="form-label">Nome</label>
			<input type="text" class="form-control" name="questoesnome" id="questoesnome"  required>

			<label for="questoestexto" class="form-label mt-4">Questão</label>
			<textarea class="form-control" id="questoestexto" name="questoestexto" rows="4" required>'.$questoesdb->questao_texto.'</textarea>
		</div>
		<button type="submit" class="btn btn-primary">Salvar</button>
	</form>';


} else {
	//Atualizar
	echo '
	<form action="update_questoes.php" method="post">
		<div class="mb-3">
			<label for="id">ID</label>
			<input type="text" class="form-control" id="id" name="id" value="'.$questoesdb->id.'" readonly>
			
			<label for="questoesnome" class="form-label mt-4">Nome</label>
			<input type="text" class="form-control" name="questoesnome" id="questoesnome" value="'.$questoesdb->questao_nome.'" required>

			<label for="questoestexto" class="form-label mt-4">Questão</label>
			<textarea class="form-control" id="questoestexto" name="questoestexto" rows="4" required>'.$questoesdb->questao_texto.'</textarea>
		</div>
		<button type="submit" class="btn btn-primary">Salvar</button>
	</form>';
}






echo $OUTPUT->footer();
