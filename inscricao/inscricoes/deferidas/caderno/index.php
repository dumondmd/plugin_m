<?php
require_once('../../../../../config.php');


global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/blocks/inscricao/inscricoes/deferidas/index.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Inscrições Realizadas - Deferidas - Escolha de Questão');
$PAGE->set_title('Inscrições Realizadas - Deferidas - Escolha de Questão');
$PAGE->set_context(\context_system::instance());

//Id curso
$idcurso = $_GET["idcurso"];

//Id usuario
$idusuario = $_GET["iduser"];

//Limite (qtd de provas)
$limite = $_GET["limite"];

//Tipo de geracao lote/unitario, sem id de usuario = lote
$tipogeracao = null;

if ($idusuario) {
  $tipogeracao = "gera_caderno_unico.php";
} else {
  $tipogeracao = "gera_caderno_lote.php";
}




//Data atual
$data_atual = date('Y-m-d');


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('Inscrições', new moodle_url('/blocks/inscricao/view.php?idcurso=' . $idcurso));
$editnode = $settingsnode->add('Escolha de Questão');
$editnode->make_active();
echo $OUTPUT->header();



//Lista de questoes--------------------------------------------------------
$questoes_db = null;
$questoes_db = $DB->get_records_sql(
  'SELECT id, questao_nome, questao_texto from {block_questoes} '
);

$opcoesQuestoes = '';
if (!empty($questoes_db)) {
  foreach ($questoes_db as $key => $value) {
    $opcoesQuestoes .= '<option value="' . $value->id . '">' . $value->questao_nome . '</option>';
  }
} else {
  $opcoesQuestoes = '<option value="0">Sem questão</option>';
}


$html = '';

$html = '
  <div class="card-body">
      <form action="' . $tipogeracao . '" method="get">
          <div class="form-row">
              <div class="form-group col-md-8">
                  <input type="hidden" id="idcurso" name="idcurso" value="' . $idcurso . '">
                  <input type="hidden" id="iduser" name="iduser" value="' . $idusuario . '">
                  <input type="hidden" id="limite" name="limite" value="' . $limite . '">
                  <label for="idquestao">Nome Questão</label>
                  <select id="idquestao" name="idquestao" class="form-control" required>
                  <option value="0">Sem questão</option>
                  ' . $opcoesQuestoes . '
                  </select>
              </div>
              <div class="form-group col-md-4">
                <label for="dataRealizacaoProva">Data de Realização da Prova<span class="text-danger font-weight-bold">*</span></label>
                <input type="date" class="form-control" id="dataRealizacaoProva" name="dataRealizacaoProva" value="' . $data_atual . '"   required>
              </div>
          </div>
          <div class="form-row">
              <div class="form-group col-md-12">                
                <input class="btn btn-success" type="button" onclick="window.history.back()" value="Voltar" style="float:left;">
                <input class="btn btn-primary" type="submit" value="Gerar Caderno" style="float:right;">
              </div>              
          </div>
      </form>
  </div>

';
echo $html;



echo $OUTPUT->footer();
