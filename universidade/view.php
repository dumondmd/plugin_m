<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_universidade.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/blocks/universidade/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Universidade', $url);
$editnode->make_active();
echo $OUTPUT->header();

      function get_universidades() {
        	global $DB, $CFG;
          $universidadedb = null;

          $url = new moodle_url("$CFG->wwwroot/blocks/universidade/newuniversidade.php");

          $universidadedb = $DB->get_records_sql(
            'SELECT id, universidade from {blocks_universidade}'
          );

          //Criar nova universidade
          echo '
          <form action="newuniversidade.php" method="post">
      			<div class="mb-3">
      				<label for="universidadename" class="form-label">Nome da universidade</label>
              <input type="text" class="form-control" name="universidadename" id="universidadename" required>
      			</div>
      			<button type="submit" class="btn btn-primary">Adicionar universidade</button>
      			</form>
            ';

          //Tabela de universidades
          echo '
           <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Id</th>
                <th scope="col">Universidade</th>
                <th scope="col">Editar</th>
                <th scope="col">Excluir</th>
              </tr>
            </thead>
            <tbody>
            ';

          foreach ($universidadedb as &$val) {
            echo '<tr><td>'.$val->id.'</td>';
            echo '<td>'.$val->universidade.'</td>';
            echo '<td><form action="edituniversidade.php" method="post"><input type="hidden" name="id" value="'.$val->id.'"><button type="submit" class="btn btn-warning">Editar</button></form></td>';
            echo '<td><form action="deleteuniversidade.php" method="post"><input type="hidden" name="id" value="'.$val->id.'"><button type="submit" class="btn btn-danger">Excluir</button></form></td></tr>';
          }

          echo  '</tbody></table>';
      }
get_universidades();




echo $OUTPUT->footer();
