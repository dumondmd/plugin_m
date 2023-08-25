<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_questoes.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/blocks/questoes/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Gerenciar Questões');
$PAGE->set_heading('Gerenciar Questões');
$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Questões', $url);
$editnode->make_active();
echo $OUTPUT->header();

      function get_questoess() {
        	global $DB, $CFG;
          $questoesdb = null;


          $questoesdb = $DB->get_records_sql(
            'SELECT id, questao_nome from {block_questoes}'
          );

          //Criar nova questoes
          $url = new moodle_url("$CFG->wwwroot/blocks/questoes/edit_questoes.php");
          echo '<a href="'.$url.'"><button data-filteraction="apply" type="button" class="btn btn-primary mb-2">Adicionar Questão</button></a>';

          //Tabela de questoes
          echo '
           <table class="table table-striped">
            <thead>
              <tr>
                <th scope="col">Id</th>
                <th scope="col">Nome</th>
                <th scope="col">Editar</th>
              </tr>
            </thead>
            <tbody>
            ';

          foreach ($questoesdb as &$val) {
            echo '<tr><td>'.$val->id.'</td>';
            echo '<td>'.$val->questao_nome.'</td>';
            echo '<td><form action="edit_questoes.php" method="post"><input type="hidden" name="id" value="'.$val->id.'"><button type="submit" class="btn btn-warning">Editar</button></form></td>';
          }

          echo  '</tbody></table>';
      }
get_questoess();




echo $OUTPUT->footer();
