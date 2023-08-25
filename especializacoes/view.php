<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_especializacoes.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/blocks/especializacoes/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('especializacoes', $url);
$editnode->make_active();
echo $OUTPUT->header();

      function get_especializacoess() {
        	global $DB, $CFG;
          $especializacoesdb = null;

          $url = new moodle_url("$CFG->wwwroot/blocks/especializacoes/newespecializacoes.php");

          $especializacoesdb = $DB->get_records_sql(
            'SELECT id, especializacoes from {blocks_especializacoes}'
          );

          //Criar nova especializacoes
          echo '
          <form action="newespecializacoes.php" method="post">
      			<div class="mb-3">
      				<label for="especializacoesname" class="form-label">Nome da especialização</label>
              <input type="text" class="form-control" name="especializacoesname" id="especializacoesname" required>
      			</div>
      			<button type="submit" class="btn btn-primary">Adicionar especialização</button>
      			</form>
            ';

          //Tabela de especializacoes
          echo '
           <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Id</th>
                <th scope="col">especializacoes</th>
                <th scope="col">Editar</th>
                <th scope="col">Excluir</th>
              </tr>
            </thead>
            <tbody>
            ';

          foreach ($especializacoesdb as &$val) {
            echo '<tr><td>'.$val->id.'</td>';
            echo '<td>'.$val->especializacoes.'</td>';
            echo '<td><form action="editespecializacoes.php" method="post"><input type="hidden" name="id" value="'.$val->id.'"><button type="submit" class="btn btn-warning">Editar</button></form></td>';
            echo '<td><form action="deleteespecializacoes.php" method="post"><input type="hidden" name="id" value="'.$val->id.'"><button type="submit" class="btn btn-danger">Excluir</button></form></td></tr>';
          }

          echo  '</tbody></table>';
      }
get_especializacoess();




echo $OUTPUT->footer();
