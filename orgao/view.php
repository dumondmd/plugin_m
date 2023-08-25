<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_orgao.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/blocks/orgao/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Orgaos', $url);
$editnode->make_active();
echo $OUTPUT->header();

      function get_cities() {
          global $DB, $CFG;
          $citiesdb = null;

          $url = new moodle_url("$CFG->wwwroot/blocks/orgao/neworgao.php"); 

          $citiesdb = $DB->get_records_sql(
            'SELECT id, orgao, responsable, servidor from {blocks_orgao_franchised} ORDER BY id ASC'                
          ); 


          $neworgao = 
            '<form action="neworgao.php" method="post">
            <div class="mb-3">
              <label for="orgaoname" class="form-label">Nome do orgao</label>               
                <input class="form-control" type="text" id="fname" name="orgaoname" id="orgaoname">
                
            </div>    
            <button type="submit" class="btn btn-primary">Adicionar orgao</button>
            </form>';
                    
            


          echo $neworgao;

          $tabela_html = '<table class="table table-striped">
            <thead>
              <tr>                  
                <th scope="col">Id</th>
                <th scope="col">Orgao</th>
                <th scope="col">Responsáveis</th>
                <th scope="col">Ação</th>
                <th scope="col">Responsável</th>                  
              </tr>
            </thead>
            <tbody>';

          $tabela_html_end = '              
            </tbody>
            </table>';

          echo  $tabela_html;

          foreach ($citiesdb as &$val) {                                                
            echo '<tr><td>'.$val->id.'</td>';
            echo '<td>'.$val->orgao.'</td>';                                                         
            echo '<td><form action="editorgao.php" method="POST"><input type="hidden" name="id" value="'.$val->id.'"><button type="submit"
              class="btn btn-warning">Editar</button></form></td>';

            if($val->servidor){
             echo '<td><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-lock-fill" viewBox="0 0 16 16">
              <path d="M7 7a1 1 0 0 1 2 0v1H7V7zM6 9.3c0-.042.02-.107.105-.175A.637.637 0 0 1 6.5 9h3a.64.64 0 0 1 .395.125c.085.068.105.133.105.175v2.4c0 .042-.02.107-.105.175A.637.637 0 0 1 9.5 12h-3a.637.637 0 0 1-.395-.125C6.02 11.807 6 11.742 6 11.7V9.3z"/>
              <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM10 7v1.076c.54.166 1 .597 1 1.224v2.4c0 .816-.781 1.3-1.5 1.3h-3c-.719 0-1.5-.484-1.5-1.3V9.3c0-.627.46-1.058 1-1.224V7a2 2 0 1 1 4 0z"/>
            </svg>RhNet</td>';
            } else {
              echo '<td><form action="deleteorgao.php" method="post"><input type="hidden" name="id" value="'.$val->id.'"><button type="submit"
              class="btn btn-danger">Excluir</button></form></td>'; 
            }            

            echo '<td>'.formatResponsable($val->responsable).'</td></tr>'; }
            echo  $tabela_html_end;
      }

    function formatResponsable($responsable){
      
      if(!empty($responsable)){
        $arrResponsable = explode(",", $responsable);

        if(sizeof($arrResponsable)>0){
          return sizeof($arrResponsable)." Responsáveis";
        } else{
          return "Sem Responsáveis";
        }
      } else {
        return "Sem Responsáveis";
      }
      
    }  




get_cities();


echo $OUTPUT->footer();
