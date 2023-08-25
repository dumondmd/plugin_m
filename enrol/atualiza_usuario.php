<?php
require('../config.php');
require('funcoes_diretorio_upload.php');
//Uploads file

//Dados padrao para todos tipos de formulario
$id = $_POST["id"];
$cpf = limpaCPF_CNPJ($_POST["cpf"]);
$id_curso = $_POST["id_curso"];
$idInscricaoUsuario = consultaInscricaoUsuario($id_curso, $id);
$tipo_formulario = $_POST["tipo_formulario"];
$path_completo = criarPath('uploads', $id_curso, $cpf);
criarDiretorio($path_completo);



//Dados pessoais---------------------------------------------------------------
if ($tipo_formulario == 'dados_pessoais') {

  $firstname = separaNomeSobrenome($_POST["nome_completo"], 0);
  $lastname = separaNomeSobrenome($_POST["nome_completo"], 1);

  $data_nacimento = $_POST["data_nacimento"];
  $rg = $_POST["rg"];
  $orgao_expedidor = $_POST["orgao_expedidor"];
  $nome_mae = $_POST["nome_mae"];
  $nome_pai = $_POST["nome_pai"];



  //Verificar se tem arquivo para upload
  $notificacoes = null;
  $validacaoRG = true;

  if ($_FILES['file_rg_cpf']['tmp_name']) {

    $tmpRG = $_FILES['file_rg_cpf']['tmp_name'];
    $tipoRG = $_FILES['file_rg_cpf']['type'];
    $tamanho = $_FILES['file_rg_cpf']['size'];
    $path_completoRG = $path_completo . $cpf . '_RG.pdf';


    if ($tipoRG == 'application/pdf' && $tamanho <= 2010000 && move_uploaded_file($tmpRG, $path_completoRG)) {
      $validacaoRG = true;
      $notificacoes .= "Upload RG concluido!\n";
    } else {
      $validacaoRG = false;
      $notificacoes .= "Erro no upload de RG!\n";
    }

  } else {
    $notificacoes = "Sem documento de RG/CPF em anexo!";
  }


  //Banco de dados
  if ($id && $validacaoRG) {
    global $DB;
    try {
      $record = new stdClass();
      $record->id = $id;
      $record->firstname = $firstname;
      $record->lastname = $lastname;
      $record->data_nacimento = $data_nacimento;
      $record->rg = $rg;
      $record->orgao_expedidor = $orgao_expedidor;
      $record->nome_mae = $nome_mae;
      $record->nome_pai = $nome_pai;
      $record->link_upload_rg_cpf = $path_completoRG;
      $DB->update_record('user', $record);
    } catch (dml_exception $e) {
      echo 'Erro ao atualizar  no banco de dados!' . $e;
    }
    $record->upload = $notificacoes;
    echo json_encode($record);
  }
}
//Endereco---------------------------------------------------------------------
else if ($tipo_formulario == 'endereco') {


  $endereco_cep = $_POST["endereco_cep"];
  $endereco_numero = $_POST["endereco_numero"];
  $endereco_quadra = $_POST["endereco_quadra"];
  $endereco_lote = $_POST["endereco_lote"];
  $endereco_cidade = $_POST["endereco_cidade"];
  $endereco_complemento = $_POST["endereco_complemento"];
  $endereco_bairro = $_POST["endereco_bairro"];
  $endereco_logadouro = $_POST["endereco_logadouro"];
  $endereco_estado_uf = $_POST["endereco_estado_uf"];


  //Verificar se tem arquivo para upload
  $notificacoes = null;
  $validacao = true;

  if ($_FILES['file_comprovante_endereco']['tmp_name']) {

    $tmp = $_FILES['file_comprovante_endereco']['tmp_name'];
    $tipo = $_FILES['file_comprovante_endereco']['type'];
    $tamanho = $_FILES['file_comprovante_endereco']['size'];
    $path_completo = $path_completo . $cpf . '_ENDERECO.pdf';


    if ($tipo == 'application/pdf' && $tamanho <= 2010000 && move_uploaded_file($tmp, $path_completo)) {
      $validacao = true;
      $notificacoes = "Upload concluido";
    } else {
      $validacao = false;
      $notificacoes = "Erro no upload de arquivo";
    }

  } else {
    $notificacoes = "Sem arquivo para realizar o upload!";
  }



  //Atualiza
  if ($idInscricaoUsuario && $validacao) {
    //Banco de dados
    global $DB;
    try {
      $record = new stdClass();
      $record->id = $idInscricaoUsuario;
      $record->endereco_cep = $endereco_cep;
      $record->endereco_numero = $endereco_numero;
      $record->endereco_quadra = $endereco_quadra;
      $record->endereco_lote = $endereco_lote;
      $record->endereco_cidade = $endereco_cidade;
      $record->endereco_complemento = $endereco_complemento;
      $record->endereco_bairro = $endereco_bairro;
      $record->endereco_logadouro = $endereco_logadouro;
      $record->endereco_estado_uf = $endereco_estado_uf;
      $record->endereco_link_upload = $path_completo;
      $DB->update_record('blocks_inscricao_usuario', $record);
    } catch (dml_exception $e) {
      echo 'Erro ao atualizar no banco de dados!' . $e;
    }
    $record->upload = $notificacoes;
    echo json_encode($record);

    //Cria novo registro  
  } elseif ($validacao && $id && $id_curso) {
    $record = new stdClass();
    $record->id_curso = $id_curso;
    $record->id_usuario = $id;
    $record->endereco_cep = $endereco_cep;
    $record->endereco_numero = $endereco_numero;
    $record->endereco_quadra = $endereco_quadra;
    $record->endereco_lote = $endereco_lote;
    $record->endereco_cidade = $endereco_cidade;
    $record->endereco_complemento = $endereco_complemento;
    $record->endereco_bairro = $endereco_bairro;
    $record->endereco_logadouro = $endereco_logadouro;
    $record->endereco_estado_uf = $endereco_estado_uf;
    $record->endereco_link_upload = $path_completo;
    try {
      $DB->insert_record('blocks_inscricao_usuario', $record, false);
    } catch (dml_exception $e) {
      echo json_encode('Erro ao criar registro no banco de dados!' . $e);
    }
    $record->upload = $notificacoes;
    echo json_encode($record);
  }

}



//Contato---------------------------------------------------------------------
else if ($tipo_formulario == 'contato') {


  $telefone_contato = $_POST["telefone_contato"];
  $whatsapp_numero = $_POST["whatsapp_numero"];

  //Banco de dados
  //Atualiza
  if ($idInscricaoUsuario) {
    global $DB;
    try {
      $record = new stdClass();
      $record->id = $idInscricaoUsuario;
      $record->contato_whatsapp = $whatsapp_numero;
      $record->contato_telefone = $telefone_contato;
      $DB->update_record('blocks_inscricao_usuario', $record);
    } catch (dml_exception $e) {
      echo json_encode('Erro ao atualizar Órgão no banco de dados!' . $e);
    }
    echo json_encode($record);

    //Insere novo registro
  } else {
    $record = new stdClass();
    $record->id_curso = $id_curso;
    $record->id_usuario = $id;
    $record->contato_whatsapp = $telefone_contato;
    $record->contato_telefone = $whatsapp_numero;
    try {
      $DB->insert_record('blocks_inscricao_usuario', $record, false);
    } catch (dml_exception $e) {
      echo json_encode('Erro ao criar registro no banco de dados!' . $e);
    }
    echo json_encode($record);

  }


}



//Lotação---------------------------------------------------------------------
else if ($tipo_formulario == 'lotacao') {

  //Verificar se tem arquivo para upload
  $notificacoes = null;
  $validacao = true;


  if ($_FILES['fileAutorizacaoSupHierar']['tmp_name']) {

    $tmp = $_FILES['fileAutorizacaoSupHierar']['tmp_name'];
    $tipo = $_FILES['fileAutorizacaoSupHierar']['type'];
    $tamanho = $_FILES['fileAutorizacaoSupHierar']['size'];


    if ($tipo == 'application/pdf' && $tamanho <= 2010000) {
      $validacao = true;
    } else {
      $validacao = false;
    }

    $path_completoAutorizacaoSupHier = $path_completo . $cpf . '_AutorizacaoSuperiorHierarquico.pdf';
    if ($validacao && move_uploaded_file($tmp, $path_completoAutorizacaoSupHier)) {
      $notificacoes = "Upload concluido";
    } else {
      $notificacoes = "Erro no upload de arquivo";
    }


  } else {
    $notificacoes = "Sem arquivo para realizar upload!";
  }


  $lotacao_nucleo = $_POST["lotacao_nucleo"];
  $lotacao_cargo = $_POST["lotacao_cargo"];
  $lotacao_data_inicio = $_POST["lotacao_data_inicio"];
  $lotacao_data_fim = $_POST["lotacao_data_fim"];

  //Banco de dados
  //Atualiza
  if ($validacao && $idInscricaoUsuario) {
    global $DB;
    try {
      $record = new stdClass();
      $record->id = $idInscricaoUsuario;
      $record->lotacao_nucleo = $lotacao_nucleo;
      $record->lotacao_cargo = $lotacao_cargo;
      $record->lotacao_data_inicio = $lotacao_data_inicio;
      $record->lotacao_data_fim = $lotacao_data_fim;
      $record->lotacao_link_autori = $path_completoAutorizacaoSupHier;
      $DB->update_record('blocks_inscricao_usuario', $record);
    } catch (dml_exception $e) {
      echo json_encode('Erro ao criar registro no banco de dados!' . $e);
    }
    $record->upload = $notificacoes;
    echo json_encode($record);

    //Insere novo registro
  } else if ($validacao && $id && $id_curso) {
    $record = new stdClass();
    $record->id_curso = $id_curso;
    $record->id_usuario = $id;
    $record->lotacao_nucleo = $lotacao_nucleo;
    $record->lotacao_cargo = $lotacao_cargo;
    $record->lotacao_data_inicio = $lotacao_data_inicio;
    $record->lotacao_data_fim = $lotacao_data_fim;
    $record->lotacao_link_autori = $path_completo;
    try {
      $DB->insert_record('blocks_inscricao_usuario', $record, false);
    } catch (dml_exception $e) {
      echo json_encode('Erro ao criar registro no banco de dados!' . $e);
    }
    $record->upload = $notificacoes;
    echo json_encode($record);

  }


}




//Cota---------------------------------------------------------------------
else if ($tipo_formulario == 'cota') {


  $notificacoes = null;


  //Autodeclaração
  $validaAutodeclaracao = true;
  if ($_FILES['cota_link_autodeclaracao']['tmp_name']) {

    $tmpAutoDeclaracao = $_FILES['cota_link_autodeclaracao']['tmp_name'];
    $tipoAutodeclaracao = $_FILES['cota_link_autodeclaracao']['type'];
    $tamanhoAutoDeclaracao = $_FILES['cota_link_autodeclaracao']['size'];
    $path_completoAutodeclaracao = $path_completo . $cpf . '_AUTODECLARACAO.pdf';


    if ($tipoAutodeclaracao == 'application/pdf' && $tamanhoAutoDeclaracao <= 2010000 && move_uploaded_file($tmpAutoDeclaracao, $path_completoAutodeclaracao)) {
      $validaAutodeclaracao = true;
      $notificacoes .= "Upload autodeclaração concluido!\n";
    } else {
      $validaAutodeclaracao = false;
      $notificacoes .= "Erro no upload de autodeclaração!\n";
    }

  }

  //Laudo Médico
  $validacaoLaudoMedico = true;
  if ($_FILES['cota_link_laudo_medico']['tmp_name']) {

    $tmpLaudoMedico = $_FILES['cota_link_laudo_medico']['tmp_name'];
    $tipoLaudoMedico = $_FILES['cota_link_laudo_medico']['type'];
    $tamanhoLaudoMedico = $_FILES['cota_link_laudo_medico']['size'];
    $path_completoLaudoMedico = $path_completo . $cpf . '_LAUDO_MEDICO.pdf';


    if ($tipoLaudoMedico == 'application/pdf' && $tamanhoLaudoMedico <= 2010000 && move_uploaded_file($tmpLaudoMedico, $path_completoLaudoMedico)) {
      $validacaoLaudoMedico = true;
      $notificacoes .= "Upload laudo médico concluido!\n";
    } else {
      $validacaoLaudoMedico = false;
      $notificacoes .= "Erro no upload de laudo médico!\n";
    }

  }

  //Declaração ou comprovante de matricula
  $validacaoDeclaracao = true;
  if ($_FILES['cota_link_declaracao']['tmp_name']) {

    $tmpDeclaracao = $_FILES['cota_link_declaracao']['tmp_name'];
    $tipoDeclaracao = $_FILES['cota_link_declaracao']['type'];
    $tamanhoDeclaracao = $_FILES['cota_link_declaracao']['size'];
    $path_declaracao = $path_completo . $cpf . '_DECLARACAO.pdf';


    if ($tipoDeclaracao == 'application/pdf' && $tamanhoDeclaracao <= 2010000 && move_uploaded_file($tmpDeclaracao, $path_declaracao)) {
      $validacaoDeclaracao = true;
      $notificacoes .= "Upload declaração concluído!\n";
    } else {
      $validacaoDeclaracao = false;
      $notificacoes .= "Erro no upload de declaração!\n";
    }

  }



  //Curriculo
  $validacaoCurriculo = true;
  if ($_FILES['cota_link_curriculo']['tmp_name']) {

    $tmpCurriculo = $_FILES['cota_link_curriculo']['tmp_name'];
    $tipoCurriculo = $_FILES['cota_link_curriculo']['type'];
    $tamanhoCurriculo = $_FILES['cota_link_curriculo']['size'];
    $path_completoCurriculo = $path_completo . $cpf . '_CURRICULO.pdf';


    if ($tipoCurriculo == 'application/pdf' && $tamanhoCurriculo <= 2010000 && move_uploaded_file($tmpCurriculo, $path_completoCurriculo)) {
      $validacaoCurriculo = true;
      $notificacoes .= "Upload curriculo concluido!\n";
    } else {
      $validacaoCurriculo = false;
      $notificacoes .= "Erro no upload de curriculo!\n";
    }

  }








  $cota_pretendida = $_POST["cota_pretendida"];


  //Banco de dados
  //Atualiza
  if ($validaAutodeclaracao && $validacaoLaudoMedico && $validacaoCurriculo && $validacaoDeclaracao && $idInscricaoUsuario) {
    global $DB;
    try {
      $record = new stdClass();
      $record->id = $idInscricaoUsuario;
      $record->cota_pretendida = $cota_pretendida;
      $record->cota_link_autodeclaracao = $path_completoAutodeclaracao;
      $record->cota_link_laudo_medico = $path_completoLaudoMedico;
      $record->cota_link_declaracao = $path_declaracao;
      $record->cota_link_curriculo = $path_completoCurriculo;
      $DB->update_record('blocks_inscricao_usuario', $record);
    } catch (dml_exception $e) {
      echo json_encode('Erro ao atualizar Órgão no banco de dados!' . $e);
    }
    $record->upload = $notificacoes;
    echo json_encode($record);

    //Insere novo registro
  } else if ($validacaoCurriculo && $id && $id_curso) {
    $record = new stdClass();
    $record->id_curso = $id_curso;
    $record->id_usuario = $id;
    $record->cota_pretendida = $cota_pretendida;

    try {
      $DB->insert_record('blocks_inscricao_usuario', $record, false);
    } catch (dml_exception $e) {
      echo json_encode('Erro ao criar registro no banco de dados!' . $e);
    }
    $record->upload = $notificacoes;
    echo json_encode($record);

  }


}












//Curso---------------------------------------------------------------------
else if ($tipo_formulario == 'curso') {
  $graduaco_local_exercicio = $_POST["graduaco_local_exercicio"];


  //Banco de dados
  if (!empty($id)) {
    global $DB;
    try {
      $record = new stdClass();
      $record->id = $id;
      $record->graduaco_local_exercicio = $graduaco_local_exercicio;
      $DB->update_record('user', $record);
    } catch (dml_exception $e) {
      echo 'Erro ao atualizar  no banco de dados!' . $e;
    }
    echo json_encode($record);
  }
}

//Pis---------------------------------------------------------------------------
else if ($tipo_formulario == 'pis') {

  $numero_pis = $_POST["numero_pis"];


  $tmp = $_FILES['file_doc_pis']['tmp_name'];
  $tipo = $_FILES['file_doc_pis']['type'];
  $tamanho = $_FILES['file_doc_pis']['size'];
  $validacao = null;

  if (empty($numero_pis)) {
    $validacao = true;
  } else {
    if ($tipo == 'application/pdf' && $tamanho <= 2010000) {
      $validacao = true;
    } else {
      $validacao = false;
    }
  }

  $path_completo = $path_completo . $cpf . '_PIS.pdf';
  $notificacoes = null;
  if ($validacao && move_uploaded_file($tmp, $path_completo)) {
    $notificacoes = "Upload concluido";
  } else {
    $notificacoes = "Erro no upload de arquivo";
  }

  //Banco de dados

  if (!empty($id) && $validacao) {
    global $DB;
    try {
      $record = new stdClass();
      $record->id = $id;
      $record->numero_pis = $numero_pis;
      $record->link_upload_pis = $path_completo;
      $DB->update_record('user', $record);
    } catch (dml_exception $e) {
      echo json_encode('Erro ao atualizar no banco de dados!' . $e);
    }
    $record->upload = $notificacoes;
    echo json_encode($record);
  }
}



//Pcd---------------------------------------------------------------------------
else if ($tipo_formulario == 'pcd') {

  $pcd_tratamento_especial = $_POST["pcd_tratamento_especial"];
  $pcd_descricao = $_POST["pcd_descricao"];
  $pcd_data = $_POST["pcd_data"];


  //Upload
  $tmp = $_FILES['file_atestado_medico']['tmp_name'];
  $tipo = $_FILES['file_atestado_medico']['type'];
  $tamanho = $_FILES['file_atestado_medico']['size'];
  $validacao = null;
  if (empty($pcd_data)) {
    $validacao = true;
  } else {
    if ($tipo == 'application/pdf' && $tamanho <= 2010000) {
      $validacao = true;
    } else {
      $validacao = false;
    }
  }

  $path_completo = $path_completo . $cpf . '_PCD.pdf';
  $notificacoes = null;
  if ($validacao && move_uploaded_file($tmp, $path_completo)) {
    $notificacoes = "Upload concluido";
  } else {
    $notificacoes = "Erro no upload de arquivo";
  }

  //Banco de dados
  if (!empty($id) && $validacao) {
    global $DB;
    try {
      $record = new stdClass();
      $record->id = $id;
      $record->pcd_descricao = $pcd_descricao;
      $record->pcd_data = $pcd_data;
      $record->link_upload_atestado_medico = $path_completo;
      $DB->update_record('user', $record);
    } catch (dml_exception $e) {
      echo 'Erro ao atualizar no banco de dados!' . $e;
    }
    $record->upload = $notificacoes;
    echo json_encode($record);
  }
}