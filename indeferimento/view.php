<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_indeferimento.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/blocks/indeferimento/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Motivos de indeferimento');
$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Indeferimento', $url);
$editnode->make_active();
echo $OUTPUT->header();

      function get_indeferimentos() {
        	global $DB, $CFG;
          $indeferimentodb = null;


          $indeferimentodb = $DB->get_records_sql(
            'SELECT id, indeferimento from {block_indeferimento}'
          );

          //Criar nova indeferimento
          echo '
          <form action="newindeferimento.php" method="post">
      			<div class="mb-3">
      				<label for="indeferimentonome" class="form-label">Nome do indeferimento</label>
              <input type="text" class="form-control" name="indeferimentonome" id="indeferimentonome" required>
      			</div>
      			<button type="submit" class="btn btn-primary">Adicionar Indeferimento</button>
      			</form>
            ';

          //Tabela de indeferimento
          echo '
           <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Id</th>
                <th scope="col">Indeferimento</th>
                <th scope="col">Editar</th>
                <th scope="col">Excluir</th>
              </tr>
            </thead>
            <tbody>
            ';

          foreach ($indeferimentodb as &$val) {
            echo '<tr><td>'.$val->id.'</td>';
            echo '<td>'.$val->indeferimento.'</td>';
            echo '<td><form action="editindeferimento.php" method="post"><input type="hidden" name="id" value="'.$val->id.'"><button type="submit" class="btn btn-warning">Editar</button></form></td>';
            echo '<td><form action="deleteindeferimento.php" method="post"><input type="hidden" name="id" value="'.$val->id.'"><button type="submit" class="btn btn-danger">Excluir</button></form></td></tr>';
          }

          echo  '</tbody></table>';
      }
get_indeferimentos();




echo $OUTPUT->footer();
