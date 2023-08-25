<?php
require_once('../../../config.php');


global $DB, $COURSE, $OUTPUT, $PAGE, $USER, $CFG;

require_login();


$url = new moodle_url('/blocks/inscricao/inscricoes/buscausuario.php');
$url_upload = new moodle_url('/enrol/');
$url_l = new moodle_url('/blocks/inscricao');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Análise da inscrição');

$PAGE->set_context(\context_system::instance());

//Id user
$idUser = $_POST["id"];

//Id curso
$idCurso = $_POST["idcurso"];

//Admin Nome
$adminName = $USER->firstname . ' ' . $USER->lastname;

// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('Inscrições', new moodle_url('/blocks/inscricao/view.php?idcurso=' . $idCurso));
$editnode = $settingsnode->add('Busca de dados de usuário');


$editnode->make_active();
echo $OUTPUT->header();

//Lista de motivos de indeferimento---------------------------------------------
$indeferimento_db = $DB->get_records_sql(
   'SELECT id, indeferimento from {block_indeferimento} ORDER BY indeferimento'
);

//Consulta nome de curso--------------------------------------------------------
$curso_db = $DB->get_record_sql(
   'SELECT c.id, c.shortname FROM {course} AS c WHERE c.id = ?',
   [$idCurso]
);

function consultaTipoCursoId($idCurso)
{
   global $CFG, $DB;
   $curso = null;
   $curso = $DB->get_record_sql('
        SELECT p.tipo_concurso FROM {blocks_periodo_curso} AS p
        WHERE p.id_curso = ' . $idCurso
   );
   return $curso->tipo_concurso;
}

$opcoesIndeferimentos = '';
if (!empty($indeferimento_db)) {
   foreach ($indeferimento_db as $key => $value) {
      $opcoesIndeferimentos .= '<option value="' . $value->indeferimento . '">' . $value->indeferimento . '</option>';
   }
} else {
   $opcoesIndeferimentos = '<option value="sem_indeferimentos">Sem indeferimentos cadastrados</option>';
}

function limpaCPF_CNPJ($valor)
{
   $valor = trim($valor);
   $valor = str_replace(".", "", $valor);
   $valor = str_replace(",", "", $valor);
   $valor = str_replace("-", "", $valor);
   $valor = str_replace("/", "", $valor);
   return $valor;
}


//Listagem de usuario do Moodle
$usr = null;
$usr = getUser($idUser);

function getUser($id)
{
   global $DB;
   try {
      $user = $DB->get_record_sql(
         'SELECT *
         FROM {user} WHERE username = ?',
         [$id]
      );
   } catch (dml_exception $e) {
      return 'Erro consulta banco de dados. ' . $e;
   }


   if (empty($user->nome_pai)) {
      $user->nome_pai = "NÃO INFORMADO";
   }

   if (empty($user->endereco_numero)) {
      $user->endereco_numero = "NÃO INFORMADO";
   }

   if (empty($user->endereco_quadra)) {
      $user->endereco_quadra = "NÃO INFORMADO";
   }

   if (empty($user->endereco_lote)) {
      $user->endereco_lote = "NÃO INFORMADO";
   }

   if (empty($user->endereco_complemento)) {
      $user->endereco_complemento = "NÃO INFORMADO";
   }

   if (empty($user->phone1)) {
      $user->phone1 = "NÃO INFORMADO";
   }

   if (empty($user->numero_pis)) {
      $user->numero_pis = 'NÃO INFORMADO';
   }

   if (empty($user->pcd_data)) {
      $user->pcdPossuiDeficiencia = 'NÃO';
   } else {
      $user->pcdPossuiDeficiencia = 'SIM';
   }

   if (empty($user->pcd_descricao)) {
      $user->pcdPossuiDeficiencia = 'NÃO';
   } else {
      $user->pcdPossuiDeficiencia = 'SIM';
   }

   return $user;
}


//Listagem usuario Moodle por INSCRICAO
$usrInscricao = null;
$usrInscricao = getInscricaoUsuario($idCurso, $usr->id);

function getInscricaoUsuario($id_curso, $id_usuario)
{
   global $DB;
   try {
      $result = $DB->get_record_sql(
         'SELECT
         *
         FROM {blocks_inscricao_usuario} WHERE id_curso = ? AND id_usuario = ?',
         [$id_curso, $id_usuario]
      );
   } catch (dml_exception $e) {
      return ('Erro consulta banco de dados. ' . $e);
   }
   return $result;
}



//Junção de dados da tabela 'user' com a 'mdl_blocks_inscricao_usuario'
$result = new stdClass();

$result->nomeCompleto = strtoupper($usr->firstname) . ' ' . strtoupper($usr->lastname);
$result->cpf = $usr->username;
$result->dataNacimento = $usr->data_nacimento;
$result->rg = $usr->rg;
$result->orgaoExpedidor = strtoupper($usr->orgao_expedidor);
$result->nomeMae = strtoupper($usr->nome_mae);
$result->nomePai = strtoupper($usr->nome_pai);
$result->linkRgCpf = $usr->link_upload_rg_cpf;

$result->enderecoCep = $usrInscricao->endereco_cep ? $usrInscricao->endereco_cep : $usr->endereco_cep; //Modelo
$result->enderecoNumero = $usrInscricao->endereco_numero ? $usrInscricao->endereco_numero : $usr->endereco_numero;
$result->enderecoQuadra = $usrInscricao->endereco_quadra ? $usrInscricao->endereco_quadra : $usr->endereco_quadra;
$result->enderecoLote = $usrInscricao->endereco_lote ? $usrInscricao->endereco_lote : $usr->endereco_lote;
$result->enderecoCidade = $usrInscricao->endereco_cidade ? $usrInscricao->endereco_cidade : $usr->city;
$result->enderecoLogadouro = $usrInscricao->endereco_logadouro ? $usrInscricao->endereco_logadouro : $usr->logadouro;
$result->enderecoBairro = $usrInscricao->endereco_bairro ? $usrInscricao->endereco_bairro : $usr->endereco_bairro;
$result->enderecoComplemento = $usrInscricao->endereco_complemento ? $usrInscricao->endereco_complemento : $usr->endereco_complemento;
$result->enderecoUf = $usrInscricao->endereco_estado_uf ? $usrInscricao->endereco_estado_uf : $usr->estado_uf;
$result->linkEndereco = $usrInscricao->endereco_link_upload ? $usrInscricao->endereco_link_upload : $usr->link_upload_comprovante_endereco;

$result->contatoTelefone = $usrInscricao->contato_telefone ? $usrInscricao->contato_telefone : $usr->phone1;
$result->contatoWhatsapp = $usrInscricao->contato_whatsapp ? $usrInscricao->contato_whatsapp : $usr->phone2;


$result->lotacaoNucleo = $usrInscricao->lotacao_nucleo;
$result->lotacaoCargo = $usrInscricao->lotacao_cargo;
$result->lotacaoLinkAutori = $usrInscricao->lotacao_link_autori;
$result->lotacaoDataInicio = $usrInscricao->lotacao_data_inicio;
$result->lotacaoDataFim = $usrInscricao->lotacao_data_fim;

$result->cotaPretendida = $usrInscricao->cota_pretendida;
$result->cotaLinkAutoDeclaracao = $usrInscricao->cota_link_autodeclaracao;
$result->cotaLinkLaudoMedico = $usrInscricao->cota_link_laudo_medico;
$result->cotaLinkDeclaracao = $usrInscricao->cota_link_declaracao;
$result->cotaLinkCurriculo = $usrInscricao->cota_link_curriculo;

/*
echo '
<h3>Depuração por inscrição</h3></br>
';
foreach ($usrInscricao as $key => $value) {
echo '<p>' . $key . ' -> ' . $value . '</p>';
}
echo '
<h3>Depuração por usuario</h3></br>
';
foreach ($usr as $key => $value) {
echo '<p>' . $key . ' -> ' . $value . '</p>';
}
*/

echo '
<h3>Dados pessoais</h3></br>
<div class="form-row">
   <div class="form-group col-md-8">
      <label for="nomeCompleto">Nome completo</label>
     <input type="text" class="form-control" id="nomeCompleto" value="' . strtoupper($usr->firstname) . ' ' . strtoupper($usr->lastname) . '" readonly>
</div>
   <div class="form-group col-md-4">
      <label for="cpf">CPF</label>
      <input type="text" class="form-control" id="cpf" value="' . $usr->username . '" readonly>
   </div>
</div>

<div class="form-row">
   <div class="form-group col-md-2">
      <label for="dataNacimento">Data de nascimento</label>
      <input type="date" class="form-control" id="dataNacimento" value="' . $usr->data_nacimento . '" readonly>
   </div>
   <div class="form-group col-md-5">
   <label for="rg">RG</label>
   <input type="text" class="form-control" id="rg" value="' . $usr->rg . '" readonly>
</div>
<div class="form-group col-md-5">
   <label for="orgaoExpedidor">Órgão expedidor</label>
   <input type="text" class="form-control" id="orgaoExpedidor" value="' . strtoupper($usr->orgao_expedidor) . '" readonly>
</div>
</div>

<div class="form-row">
   <div class="form-group col-md-6">
      <label for="nomeMae">Nome da Mãe</label>
      <input type="text" class="form-control" id="nomeMae" value="' . strtoupper($usr->nome_mae) . '" readonly>
   </div>
   <div class="form-group col-md-6">
      <label for="nomePai">Nome do pai</label>
      <input type="text" class="form-control" id="nomePai" value="' . strtoupper($usr->nome_pai) . '" readonly>
   </div>
</div>';

if ($usr->link_upload_rg_cpf) {
   echo '
   <div class="form-row">
      <div class="form-group col-md-12">
         <a href="' . $url_upload . '' . $usr->link_upload_rg_cpf . '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Downlod documento oficial com foto e CPF (arquivo único)</strong></a>
      </div>
   </div>
   ';
}


echo '
<hr>
<h3>Endereço</h3></br>

<div class="form-row">
   <div class="form-group col-md-3">
      <label for="enderecoCep">CEP</label>
      <input type="text" class="form-control" id="enderecoCep" value="' . $result->enderecoCep . '" readonly>
   </div>
   <div class="form-group col-md-2">
      <label for="enderecoNumero">Número</label>
      <input type="text" class="form-control" id="enderecoNumero" value="' . $result->enderecoNumero . '" readonly>
   </div>
   <div class="form-group col-md-2">
      <label for="enderecoQuadra">Quadra</label>
      <input type="text" class="form-control" id="enderecoQuadra" value="' . $result->enderecoQuadra . '" readonly>
   </div>
   <div class="form-group col-md-2">
      <label for="enderecoLote">Lote</label>
      <input type="text" class="form-control" id="enderecoLote" value="' . $result->enderecoLote . '" readonly>
   </div>
   <div class="form-group col-md-3">
      <label for="enderecoCidade">Cidade</label>
      <input type="text" class="form-control" id="enderecoCidade" value="' . $result->enderecoCidade . '" readonly>
   </div>
</div>

<div class="form-row">
   <div class="form-group col-md-2">
      <label for="logadouro">Logadouro</label>
      <input type="text" class="form-control" id="logadouro" value="' . strtoupper($usr->logadouro) . '" readonly>
   </div>
   <div class="form-group col-md-4">
      <label for="enderecoBairro">Bairro</label>
      <input type="text" class="form-control" id="enderecoBairro" value="' . strtoupper($usr->endereco_bairro) . '" readonly>
   </div>
   <div class="form-group col-md-4">
      <label for="enderecoComplemento">Complemento</label>
      <input type="text" class="form-control" id="enderecoComplemento" value="' . strtoupper($usr->endereco_complemento) . '" readonly>
   </div>
   <div class="form-group col-md-2">
      <label for="estadoUf">Estado (UF)</label>
      <input type="text" class="form-control" id="estadoUf" value="' . $result->enderecoUf . '" readonly>
   </div>
</div>';


if ($result->linkEndereco) {
   echo '
   <div class="form-row">
      <div class="form-group col-md-12">
         <a href="' . $url_upload . '' . $result->linkEndereco . '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Downlod comprovante de endereço</strong></a>
      </div>
    </div>
   ';
}


echo '
<hr>
<h3>Contato</h3></br>

<div class="form-row">
   <div class="form-group col-md-6">
      <label for="telefoneContato">Telefone de contato</label>
      <input type="text" class="form-control" id="telefoneContato" value="' . $result->contatoTelefone . '" readonly>
   </div>
   <div class="form-group col-md-6">
      <label for="whatsappNumero">Whatsapp</label>
      <input type="text" class="form-control" id="whatsappNumero" value="' . $result->contatoWhatsapp . '" readonly>
   </div>
</div>


<hr>
<h3>Lotação</h3></br>

<div class="form-row">
   <div class="form-group col-md-6">
      <label for="lotacaoNome">Lotação Nome</label>
      <input type="text" class="form-control" id="lotacaoNome" value="' . $result->lotacaoNucleo . '" readonly>
   </div>
   <div class="form-group col-md-6">
      <label for="lotacaoCargo">Lotação Cargo</label>
      <input type="text" class="form-control" id="lotacaoCargo" value="' . $result->lotacaoCargo . '" readonly>
   </div>
</div>';

if ($result->lotacaoLinkAutori) {
   echo '
   <div class="form-row">
   <div class="form-group col-md-12">
         <a href=" ' . $url_upload . '' . $result->lotacaoLinkAutori . '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Autorização do Superior Hierárquico assinada</strong></a>
      </div>
   </div>
   ';
}


echo '
<div class="form-row">
   <div class="form-group col-md-6">
      <label for="lotacaoDataInicio">Data Início</label>
      <input type="date" class="form-control" id="lotacaoDataInicio" value="' . $result->lotacaoDataInicio . '" readonly>
   </div>
   <div class="form-group col-md-6">
      <label for="lotacaoDataFim">Previsão de Término</label>
      <input type="date" class="form-control" id="lotacaoDataFim" value="' . $result->lotacaoDataFim . '" readonly>
   </div>
</div>




<hr>
<h3>Vaga Reservada Pretendida</h3></br>

<div class="form-row">
   <div class="form-group col-md-12">
      <label for="cotaPretendida">Cota Pretendida</label>
      <input type="text" class="form-control" id="cotaPretendida" value="' . $result->cotaPretendida . '" readonly>
   </div>
</div>

<div class="form-row">';
if ($result->cotaLinkAutoDeclaracao) {
   echo '
      <div class="form-group col-md-3">
       <a href="   ' . $url_upload . '' . $result->cotaLinkAutoDeclaracao . '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i>
           <strong>Auto Declaração</strong></a>
      </div>';

}

if ($result->cotaLinkLaudoMedico) {
   echo '
      <div class="form-group col-md-3">
       <a href=" ' . $url_upload . '' . $result->cotaLinkLaudoMedico . '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i>
           <strong>Laudo Médico</strong></a>
      </div>
      ';
}

if ($result->cotaLinkDeclaracao) {
   echo '
      <div class="form-group col-md-3">
       <a href=" ' . $url_upload . '' . $result->cotaLinkDeclaracao . '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i>
           <strong>Declaração</strong></a>
      </div>
      ';
}

if ($result->cotaLinkCurriculo) {
   echo '
      <div class="form-group col-md-3">
         <a href=" ' . $url_upload . '' . $result->cotaLinkCurriculo . '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i>
            <strong>Curriculo</strong></a>
      </div>';
}

echo '
</div>';


//Estágio Graduação e Pós-Graduação-------------------------------------------------------------
if (consultaTipoCursoId($idCurso) == "graduacao" || consultaTipoCursoId($idCurso) == "pos_graduacao") {
   echo '
   <hr>
   <h3>Curso</h3></br>
   
   
   <div class="form-row">
      <div class="form-group col-md-6">
         <label for="graducaoTitulo">Título</label>
         <input type="text" class="form-control" id="graducaoTitulo" value="Direito" readonly>
      </div>
      <div class="form-group col-md-6">
         <label for="graduacoInstituicaoEnsino">Instituição de Ensino Superior - IES</label>
         <input type="text" class="form-control" id="graducaoTitulo" value="' . $usr->graduaco_instituicao_ensino . '" readonly>
      </div>
   </div>
   
   <div class="form-row">
      <div class="form-group col-md-3">
         <label for="graduacoDataInicio">Data início</label>
         <input type="date" class="form-control" id="graduacoDataInicio" value="' . $usr->graduaco_data_inicio . '" readonly>
      </div>
      <div class="form-group col-md-3">
         <label for="graduacoPrevisaoTermino">Previsão de término</label>
         <input type="date" class="form-control" id="graduacoPrevisaoTermino" value="' . $usr->graduaco_previsao_termino . '" readonly>
      </div>
      <div class="form-group col-md-3">
         <label for="graduacaoNumeroMatricula">Nº de matrícula</label>
         <input type="text" class="form-control" id="graduacaoNumeroMatricula" value="' . $usr->graduacao_numero_matricula . '" readonly>
      </div>
      <div class="form-group col-md-3">
         <label for="graduacoModalidadeEnsino">Modalidade de ensino</label>
         <input type="text" class="form-control" id="graduacoModalidadeEnsino" value="' . strtoupper($usr->graduaco_modalidade_ensino) . '" readonly>
      </div>
   </div>
   
   <div class="form-row">
      <div class="form-group col-md-12">
         <label for="graduacoLocalExercicio">Local para o qual pretende a vaga de estágio de pós-graduação</label>
         <input type="text" class="form-control" id="graduacoLocalExercicio" value="' . strtoupper($usr->graduaco_local_exercicio) . '" readonly>
      </div>
   </div>
   
   <div class="form-row">';
   if ($usr->link_upload_comprovante_ensino_medio) {
      echo '
         <div class="form-group col-md-6">
            <a href="' . $url_upload . '' . $usr->link_upload_comprovante_ensino_medio . '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Downlod comprovante de conclusão do ensino médio com histórico</strong></a>
         </div>
         ';
   }

   if ($usr->link_upload_comprovante_matricula) {
      echo '
         <div class="form-group col-md-6">
            <a href="' . $url_upload . '' . $usr->link_upload_comprovante_matricula . '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Downlod comprovante de matrícula emitido pela IES</strong></a>
         </div>
         ';
   }
   echo '
   </div>
   ';


   echo '
   <hr>
   <h3>PIS</h3></br>
   <div class="form-row" id="descPis">
     <div class="form-group col-md-6" id="numPISDesc">
       <label for="numeroPis">Número do PIS</label>
       <input type="text" class="form-control" id="numeroPis" value="' . $usr->numero_pis . '" readonly>
     </div>';
   if ($usr->numero_pis != 'NÃO INFORMADO') {
      echo '
         <div class="form-group col-md-6 mt-5" id="numPISDesc">
            <a href="' . $url_upload . '' . $usr->link_upload_pis . '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Downlod comprovante do PIS</strong></a>
         </div>
      ';
   }

   echo '</div>';



   echo '
   <hr>
   <h3>PCD</h3></br>
   
   <div class="form-row">
      <div class="form-group col-md-12">
         <label for="pcdPossuiDeficiencia">Possui alguma deficiência</label>
         <input type="text" class="form-control" id="pcdPossuiDeficiencia" value="' . $usr->pcdPossuiDeficiencia . '" readonly>
      </div>
   </div>';

   if ($usr->pcd_data) {

      echo '
     <div class="form-row">
        <div class="form-group col-md-6">
           <label for="pcdData">Data do documento</label>
           <input type="date" class="form-control" id="pcdData" value="' . $usr->pcd_data . '" readonly>
        </div>
          <div class="form-group col-md-6 mt-5">
             <a href="' . $url_upload . '' . $usr->link_upload_comprovante_atestado_medico . '" target="_blank"><i class="fa fa-file-pdf-o fa-2x"></i> <strong>Downlod atestado médico</strong></a>
          </div>
     </div>
     ';
   }


   echo '
   
   <div class="form-row">
      <div class="form-group col-md-12">
         <label for="pcdTratamentoEspecial">Necessita de tratamento especial</label>
         <input type="text" class="form-control" id="pcdPossuiDeficiencia" value="' . $usr->pcdPossuiDeficiencia . '" readonly>
      </div>
   </div>';

   if (!empty($usr->pcd_descricao)) {
      echo '
       <div class="form-row" id="pcdDescricaoDesc">
          <div class="form-group col-md-12">
             <label for="pcdDescricao">Condições especiais que necessita para a a realização de prova</label>
             <textarea class="form-control" id="pcdDescricao" rows="3" readonly>' . strtoupper($usr->pcd_descricao) . '</textarea>
          </div>
       </div>
     ';
   }
}






//Listar arquivos-----------------------------------------------------------------------
$url_uploads = $url_upload . 'uploads/' . $curso_db->shortname . '/' . limpaCPF_CNPJ($idUser);
$arquivos = scandir('../../../enrol/uploads/' . $curso_db->shortname . '/' . limpaCPF_CNPJ($idUser));
unset($arquivos[0], $arquivos[1]);

echo '<hr><div class="form-row"><div class="form-group col-md-6"><h3>Lista completa de documentos do usuário:</h3><div class="list-group">';

foreach ($arquivos as &$arquivo) {
   echo '<a href="' . $url_uploads . '/' . $arquivo . '" target="_blank"><button type="button" class="list-group-item list-group-item-action">' . $arquivo . '</button></a>';
}

echo '</div></div></div>';


//Sistema de análise---------------------------------------------------------------

echo '
<hr>
<h3>Análise da inscrição</h3></br>

<form method="post" id="formAnaliseInscricao" enctype="multipart/form-data">
<input type="hidden" id="iduser" name="iduser" value="' . $idUser . '">
<input type="hidden" id="idcurso" name="idcurso" value="' . $idCurso . '">
<input type="hidden" id="adminName" name="adminName" value="' . $adminName . '">
  <div class="form-row">
     <div class="form-group col-md-6">
        <label for="pcdDescricao">Situação da inscrição</label>
        <select id="statusIscricao" class="form-control" required>
           <option value="indeferida">INSCRIÇÃO INDEFERIDA</option>
           <option value="fenotipica">CONVOCAÇÃO PARA ANÁLISE FENOTÍPICA</option>
           <option value="deferida">INSCRIÇÃO DEFERIDA</option>
        </select>
     </div>
     <div id="formStatusInscricao" class="form-group col-md-6">
        <label for="statusIndeferimento">Motivo do indeferimento</label>
        <select id="statusIndeferimento" class="form-control" required>
        ' . $opcoesIndeferimentos . '
        </select>
     </div>
  </div>
  <div class="form-row">
     <div class="form-group col-md-12">
        <div id="msgAguardeStatus"></div>
        <input class="btn btn-success" id="btnVoltar" type="button" onclick="window.history.back()" value="Voltar">
        <input class="btn btn-primary" id="btnAlterarStatus" type="submit" value="Confirmar" style="float:right;">
     </div>
  </div>
</form>

';

//JAVA SCRIPT
echo '<script type="text/javascript" src="../../../lib/jquery/jquery-3.6.0.js"></script>';
echo '<script type="text/javascript" src="custom.js?ver=1.0"></script>';




echo $OUTPUT->footer();