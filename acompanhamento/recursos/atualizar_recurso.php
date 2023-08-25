<?php
require_once('../../../config.php');
require('funcoes_diretorio_upload.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;



//Dados padrao para todos tipos de formulario
$cpf = limpaCPF_CNPJ($_POST["cpf"]);
$id_curso = $_POST["id_curso"];
$path_completo = criarPath('uploads', $id_curso, $cpf);
criarDiretorio($path_completo);


//Buscar
$inscricao = null;
$inscricao = getInscricaoStatus($_POST["cpf"]);



//Atualizar
if(!empty($inscricao)){

  $tmp = $_FILES['file_recurso']['tmp_name'];
  $tipo = $_FILES['file_recurso']['type'];
  $path_completo = $path_completo . $cpf . '_RECURSO.pdf';
  $notificacoes = null;
  if ($tipo == 'application/pdf' && move_uploaded_file($tmp, $path_completo))
  {
    $notificacoes = "Upload concluido";
  } else {
    $notificacoes = "Erro no upload de arquivo";
  }

  //Banco de dados
  if(!empty($inscricao) && $tipo == 'application/pdf'){
      global $DB;
      try {
        $record = new stdClass();
        $record->id = $inscricao->id;
        $record->link_upload_recurso = $path_completo;
        $DB->update_record('blocks_inscricao', $record);
      } catch (dml_exception $e) {
          echo 'Erro ao atualizar no banco de dados!'.$e;
      }
      $record->upload = $notificacoes;
      redirect($CFG->wwwroot . '/blocks/acompanhamento/recursos/inscricao.php?idcurso='.$id_curso);
  }
}



function getInscricaoStatus($cpf)
{
  global $DB;
  try {
    $user = $DB->get_record_sql(
        'SELECT
        id,
        identificador_edital,
        identificador_aluno,
        situacao_inscricao,
        motivo_indeferimento,
        situacao_recurso,
        link_upload_recurso
         FROM {blocks_inscricao} WHERE identificador_aluno = ?', [$cpf]
    );
  } catch (dml_exception $e) {
      return 'Erro consulta banco de dados. '.$e;
  }
    return $user;
}
