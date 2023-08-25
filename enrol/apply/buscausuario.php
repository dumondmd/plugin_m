<?php
require_once('../../config.php');
//require_once('../moodleblock.class.php');
//require_once('block_orgao.php');

global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/enrol/apply/buscausuario.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Análise da inscrição');

$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Busca de dados de usuário', $url);
$editnode->make_active();
echo $OUTPUT->header();



$idUser = $_GET["id"];

$usr = null;
$usr = getUser($idUser);



function getUser($id)
{
  global $DB;
  try {
    $user = $DB->get_record_sql(
        'SELECT
        firstname,
        lastname,
        username,
        data_nacimento,
        estado_civil,
        disponibilidade_estagio,
        rg,
        orgao_expedidor,
        nome_mae,
        nome_pai,
        link_upload_rg_cpf,
        logadouro,
        endereco_numero,
        endereco_quadra,
        endereco_lote,
        city,
        endereco_complemento,
        endereco_bairro,
        endereco_cep,
        estado_uf,
        link_upload_comprovante_endereco,
        phone1,
        phone2,
        graduaco_instituicao_ensino,
        graduaco_data_inicio,
        graduaco_previsao_termino,
        graduacao_numero_matricula,
        graduaco_modalidade_ensino,
        link_upload_comprovante_ensino_medio,
        link_upload_comprovante_matricula,
        numero_pis,
        link_upload_pis,
        pcd_descricao,
        pcd_data,
        link_upload_comprovante_atestado_medico
         FROM {user} WHERE id = ?', [$id]
    );
  } catch (dml_exception $e) {
      return 'Erro consulta banco de dados. '.$e;
  }

  if(empty($user->nome_pai)){
    $user->nome_pai = "NÃO INFORMADO";
  }

  if(empty($user->endereco_numero)){
    $user->endereco_numero = "NÃO INFORMADO";
  }

  if(empty($user->endereco_quadra)){
    $user->endereco_quadra = "NÃO INFORMADO";
  }

  if(empty($user->endereco_lote)){
    $user->endereco_lote = "NÃO INFORMADO";
  }

  if(empty($user->endereco_complemento)){
    $user->endereco_complemento = "NÃO INFORMADO";
  }

  if(empty($user->phone1)){
    $user->phone1 = "NÃO INFORMADO";
  }

  if(empty($user->numero_pis)){
    $user->numero_pis = 'NÃO INFORMADO';
  }

  if(empty($user->pcd_data)){
    $user->pcdPossuiDeficiencia = 'NÃO';
  } else {
    $user->pcdPossuiDeficiencia = 'SIM';
  }

  if(empty($user->pcd_descricao)){
    $user->pcdPossuiDeficiencia = 'NÃO';
  } else {
    $user->pcdPossuiDeficiencia = 'SIM';
  }

  return $user;
}



//Lista de motivos de indeferimento---------------------------------------------
$indeferimento_db = $DB->get_records_sql(
  'SELECT id, indeferimento from {block_indeferimento} ORDER BY indeferimento'
);

$opcoesIndeferimentos = '';
if(!empty($indeferimento_db)){
  foreach ($indeferimento_db as $key => $value) {
    $opcoesIndeferimentos .= '<option value="'.$value->indeferimento.'">'.$value->indeferimento.'</option>';
  }
} else {
  $opcoesIndeferimentos = '<option value="sem_indeferimentos">Sem indeferimentos cadastrados</option>';
}





echo '
<h3>Dados pessoais</h3></br>
<div class="form-row">
   <div class="form-group col-md-8">
      <label for="nomeCompleto">Nome completo</label>
     <input type="text" class="form-control" id="nomeCompleto" value="'.strtoupper($usr->firstname) .' '.strtoupper($usr->lastname).'" readonly>
</div>
   <div class="form-group col-md-4">
      <label for="cpf">CPF</label>
      <input type="text" class="form-control" id="cpf" value="'.$usr->username.'" readonly>
   </div>
</div>

<div class="form-row">
   <div class="form-group col-md-4">
      <label for="dataNacimento">Data de nascimento</label>
      <input type="date" class="form-control" id="dataNacimento" value="'.$usr->data_nacimento.'" readonly>
   </div>
   <div class="form-group col-md-4">
      <label for="estadoCivil">Estado civil</label>
      <input type="text" class="form-control" id="estadoCivil" value="'.strtoupper($usr->estado_civil).'" readonly>
   </div>
   <div class="form-group col-md-4">
      <label for="dispEstagio">Disponibilidade para estágio</label>
      <input type="text" class="form-control" id="dispEstagio" value="'.strtoupper($usr->disponibilidade_estagio).'" readonly>
   </div>
</div>

<div class="form-row">
   <div class="form-group col-md-2">
      <label for="rg">RG</label>
      <input type="text" class="form-control" id="rg" value="'.$usr->rg.'" readonly>
   </div>
   <div class="form-group col-md-2">
      <label for="orgaoExpedidor">Órgão expedidor</label>
      <input type="text" class="form-control" id="orgaoExpedidor" value="'.strtoupper($usr->orgao_expedidor).'" readonly>
   </div>
   <div class="form-group col-md-4">
      <label for="nomeMae">Nome da Mãe</label>
      <input type="text" class="form-control" id="nomeMae" value="'.strtoupper($usr->nome_mae).'" readonly>
   </div>
   <div class="form-group col-md-4">
      <label for="nomePai">Nome do pai</label>
      <input type="text" class="form-control" id="nomePai" value="'.strtoupper($usr->nome_pai).'" readonly>
   </div>
</div>

<div class="form-row">
   <div class="form-group col-md-12">
      <a href="../'.$usr->link_upload_rg_cpf.'" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Downlod documento oficial com foto e CPF (arquivo único)</strong></a>
   </div>
</div>

<hr>
<h3>Endereço</h3></br>

<div class="form-row">
   <div class="form-group col-md-3">
      <label for="enderecoCep">CEP</label>
      <input type="text" class="form-control" id="enderecoCep" value="'.$usr->endereco_cep.'" readonly>
   </div>
   <div class="form-group col-md-2">
      <label for="enderecoNumero">Número</label>
      <input type="text" class="form-control" id="enderecoNumero" value="'.$usr->endereco_numero.'" readonly>
   </div>
   <div class="form-group col-md-2">
      <label for="enderecoQuadra">Quadra</label>
      <input type="text" class="form-control" id="enderecoQuadra" value="'.$usr->endereco_quadra.'" readonly>
   </div>
   <div class="form-group col-md-2">
      <label for="enderecoLote">Lote</label>
      <input type="text" class="form-control" id="enderecoLote" value="'.$usr->endereco_lote.'" readonly>
   </div>
   <div class="form-group col-md-3">
      <label for="enderecoCidade">Cidade</label>
      <input type="text" class="form-control" id="enderecoCidade" value="'.strtoupper($usr->city).'" readonly>
   </div>
</div>

<div class="form-row">
   <div class="form-group col-md-2">
      <label for="logadouro">Logadouro</label>
      <input type="text" class="form-control" id="logadouro" value="'.strtoupper($usr->logadouro).'" readonly>
   </div>
   <div class="form-group col-md-4">
      <label for="enderecoBairro">Bairro</label>
      <input type="text" class="form-control" id="enderecoBairro" value="'.strtoupper($usr->endereco_bairro).'" readonly>
   </div>
   <div class="form-group col-md-4">
      <label for="enderecoComplemento">Complemento</label>
      <input type="text" class="form-control" id="enderecoComplemento" value="'.strtoupper($usr->endereco_complemento).'" readonly>
   </div>
   <div class="form-group col-md-2">
      <label for="estadoUf">Estado (UF)</label>
      <input type="text" class="form-control" id="estadoUf" value="'.$usr->estado_uf.'" readonly>
   </div>
</div>

<div class="form-row">
   <div class="form-group col-md-12">
      <a href="../'.$usr->link_upload_comprovante_endereco.'" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Downlod comprovante de endereço</strong></a>
   </div>
</div>




<hr>
<h3>Contato</h3></br>




<div class="form-row">
   <div class="form-group col-md-6">
      <label for="telefoneContato">Telefone de contato</label>
      <input type="text" class="form-control" id="telefoneContato" value="'.$usr->phone1.'" readonly>
   </div>
   <div class="form-group col-md-6">
      <label for="whatsappNumero">Whatsapp</label>
      <input type="text" class="form-control" id="whatsappNumero" value="'.$usr->phone2.'" readonly>
   </div>
</div>


<hr>
<h3>Curso</h3></br>


<div class="form-row">
   <div class="form-group col-md-6">
      <label for="graducaoTitulo">Título</label>
      <input type="text" class="form-control" id="graducaoTitulo" value="Direito" readonly>
   </div>
   <div class="form-group col-md-6">
      <label for="graduacoInstituicaoEnsino">Instituição de Ensino Superior - IES</label>
      <input type="text" class="form-control" id="graducaoTitulo" value="'.$usr->graduaco_instituicao_ensino.'" readonly>
   </div>
</div>

<div class="form-row">
   <div class="form-group col-md-3">
      <label for="graduacoDataInicio">Data início</label>
      <input type="date" class="form-control" id="graduacoDataInicio" value="'.$usr->graduaco_data_inicio.'" readonly>
   </div>
   <div class="form-group col-md-3">
      <label for="graduacoPrevisaoTermino">Previsão de término</label>
      <input type="date" class="form-control" id="graduacoPrevisaoTermino" value="'.$usr->graduaco_previsao_termino.'" readonly>
   </div>
   <div class="form-group col-md-3">
      <label for="graduacaoNumeroMatricula">Nº de matrícula</label>
      <input type="text" class="form-control" id="graduacaoNumeroMatricula" value="'.$usr->graduacao_numero_matricula.'" readonly>
   </div>
   <div class="form-group col-md-3">
      <label for="graduacoModalidadeEnsino">Modalidade de ensino</label>
      <input type="text" class="form-control" id="graduacoModalidadeEnsino" value="'.strtoupper($usr->graduaco_modalidade_ensino).'" readonly>
   </div>
</div>

<div class="form-row">
   <div class="form-group col-md-6">
      <a href="../'.$usr->link_upload_comprovante_ensino_medio.'" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Downlod comprovante de conclusão do ensino médio com histórico</strong></a>
   </div>
   <div class="form-group col-md-6">
      <a href="../'.$usr->link_upload_comprovante_matricula.'" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Downlod comprovante de matrícula emitido pela IES</strong></a>
   </div>
</div>



<hr>
<h3>PIS</h3></br>
<div class="form-row" id="descPis">
  <div class="form-group col-md-6" id="numPISDesc">
    <label for="numeroPis">Número do PIS</label>
    <input type="text" class="form-control" id="numeroPis" value="'.$usr->numero_pis.'" readonly>
  </div>';
  if(empty(!$usr->link_upload_pis)){
    echo '
      <div class="form-group col-md-6 mt-5" id="numPISDesc">
        <a href="../'.$usr->link_upload_pis.'" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Downlod comprovante do PIS</strong></a>
      </div>
    ';
  }
echo '
</div>




<hr>
<h3>PCD</h3></br>

<div class="form-row">
   <div class="form-group col-md-12">
      <label for="pcdPossuiDeficiencia">Possui alguma deficiência</label>
      <input type="text" class="form-control" id="pcdPossuiDeficiencia" value="'.$usr->pcdPossuiDeficiencia.'" readonly>
   </div>
</div>';

if(!empty($usr->pcd_data)){

  echo '
  <div class="form-row">
     <div class="form-group col-md-6">
        <label for="pcdData">Data do documento</label>
        <input type="date" class="form-control" id="pcdData" value="'.$usr->pcd_data.'" readonly>
     </div>
       <div class="form-group col-md-6 mt-5">
          <a href="../'.$usr->link_upload_comprovante_atestado_medico.'" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Downlod atestado médico</strong></a>
       </div>
  </div>
  ';
}




echo '

<div class="form-row">
   <div class="form-group col-md-12">
      <label for="pcdTratamentoEspecial">Necessita de tratamento especial</label>
      <input type="text" class="form-control" id="pcdPossuiDeficiencia" value="'.$usr->pcdPossuiDeficiencia.'" readonly>
   </div>
</div>';

if(!empty($usr->pcd_descricao)){
  echo '
    <div class="form-row" id="pcdDescricaoDesc">
       <div class="form-group col-md-12">
          <label for="pcdDescricao">Condições especiais que necessita para a a realização de prova</label>
          <textarea class="form-control" id="pcdDescricao" rows="3" readonly>'.strtoupper($usr->pcd_descricao).'</textarea>
       </div>
    </div>
  ';
}



echo '
<hr>
<h3>Análise da inscrição</h3></br>

<form method="post" id="formAnaliseInscricao" enctype="multipart/form-data">
  <div class="form-row">
     <div class="form-group col-md-6">
        <label for="pcdDescricao">Situação da inscrição</label>
        <select id="statusIscricao" class="form-control" required>
           <option value="indeferida">INSCRIÇÃO INDEFERIDA</option>
           <option value="deferida">INSCRIÇÃO DEFERIDA</option>
        </select>
     </div>
     <div id="formStatusInscricao" class="form-group col-md-6">
        <label for="statusIndeferimento">Motivo do indeferimento</label>
        <select id="statusIndeferimento" class="form-control" required>
        '.$opcoesIndeferimentos.'
        </select>
     </div>
  </div>
  <div class="form-row">
     <div class="form-group col-md-12">
        <div id="msgAguardeStatus"></div>
        <input class="btn btn-primary" id="btnAlterarStatus" type="submit" value="Confirmar" style="float:right;">
     </div>
  </div>
</form>

';

//JAVA SCRIPT
echo '<script type="text/javascript" src="jquery.js?ver=1.0"></script>';
echo '<script type="text/javascript" src="custom.js?ver=1.0"></script>';
