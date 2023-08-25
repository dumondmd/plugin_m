<?php
require('../config.php');
require('../enrol/funcoes_diretorio_upload.php');


//Recebido do front-end
$id = $_POST["id"];
$idUser = $_POST["idUser"];
$idCurso = $_POST["idCurso"];
$idCPF = $_POST["idCPF"];

//Pega os dados da receita em um objeto
$receita = new stdClass();
$receita = consultaReceitaFederal($idCPF);

//Objeto usuario
$user = new stdClass();
$user = getUser($idUser, $idCurso);
$user->firstname = empty($receita->nome) ? $user->firstname : separaNomeSobrenome($receita->nome, 0);
$user->lastname = empty($receita->nome) ? $user->lastname : separaNomeSobrenome($receita->nome, 1);
$user->data_nacimento = empty($receita->dataNascimento) ? $user->data_nacimento : formatarData($receita->dataNascimento);
$user->nome_mae = empty($receita->nomeMae) ? $user->nome_mae : $receita->nomeMae;


echo json_encode($user);



//Consulta na tabela de usuario do Moodle
function getUser($idUser, $idCurso)
{
  global $DB;
  try {
    $users = $DB->get_record_sql(
      'SELECT
        id,
        username,
        firstname,
        lastname,
        data_nacimento,
        nome_mae,
        nome_pai,
        rg,
        orgao_expedidor,
        link_upload_rg_cpf
         FROM {user} AS ue  WHERE id = ?',
      [$idUser]
    );
  } catch (dml_exception $e) {
    return ('Erro consulta banco de dados. ' . $e);
  }


  try {
    $inscricao = $DB->get_record_sql(
      'SELECT
        * 
         FROM {blocks_inscricao_usuario} AS iu  WHERE id_curso = ? and id_usuario = ?',
      [$idCurso, $idUser]
    );
  } catch (dml_exception $e) {
    return ('Erro consulta banco de dados. ' . $e);
  }


  $result = (object) array_merge(
    (array) $users,
    (array) $inscricao
  );
  
  return $result;

}


//Consulta receita federal
function consultaReceitaFederal($cpf)
{

  $usrReceita = new stdClass();
  try {
    $apiReceita = json_decode(file_get_contents("http:xxxxxx/" . limpaCPF_CNPJ($cpf)));
    foreach ($apiReceita[0] as $key => $value) {
      $usrReceita->$key = $value;
    }
  } catch (\Throwable $th) {
    return "Api de consulta CPF desabilitada";
  }

  return $usrReceita;
}
