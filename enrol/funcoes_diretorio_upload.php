<?php

function criarDiretorio($path_completo)
{
  mkdir(__DIR__ . '/' . $path_completo, 0777, true);
}


function criarPath($path, $id_curso, $cpf)
{
  $nomeCurtoCurso = consultaCursoPorId($id_curso);
  $pathCompleto = "{$path}/{$nomeCurtoCurso}/{$cpf}/";
  return $pathCompleto;
}

function consultaCursoPorId($id_curso)
{
  global $DB;
  try {
    $curso = $DB->get_record_sql(
      'SELECT
         shortname
         FROM {course} WHERE id = ?',
      [$id_curso]
    );
  } catch (dml_exception $e) {
    echo json_encode('Erro consulta banco de dados. ' . $e);
  }
  return trim($curso->shortname);
}

function consultaInscricaoUsuario($id_curso, $id_usuario)
{
  global $DB;
  try {
    $curso = $DB->get_record_sql(
      'SELECT
         id,
         id_curso,
         id_usuario
         FROM {blocks_inscricao_usuario} WHERE id_curso = ? AND id_usuario = ?',
      [$id_curso, $id_usuario]
    );
  } catch (dml_exception $e) {
    return ('Erro consulta banco de dados. ' . $e);
  }
  return $curso->id;
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

function separaNomeSobrenome($nome, $tipo)
{
  $nome = trim($nome);
  $nome = strtoupper($nome);
  $nome = explode(" ", $nome);
  if ($tipo == 0) {
    $firstName = $nome[0];
    return $firstName;
  } else if ($tipo == 1) {
    unset($nome[0]);
    $lastName = implode(" ", $nome);
    return $lastName;
  }
}

function formatarData($data)
{
  try {
     return date_create_from_format("d/m/Y", $data)->format("Y-m-d");
  } catch(\Throwable $th) {
     return 0;
  }
}
