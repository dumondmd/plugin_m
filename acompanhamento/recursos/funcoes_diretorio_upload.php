<?php

function criarDiretorio($path_completo){
  mkdir(__DIR__.'/'.$path_completo, 0777, true);
}


function criarPath($path, $id_curso, $cpf){
  $nomeCurtoCurso = consultaCursoPorId($id_curso);
  $pathCompleto = "{$path}/{$nomeCurtoCurso}/{$cpf}/";
  return $pathCompleto;
}

function consultaCursoPorId($id_curso){
  global $DB;
  try {
    $curso = $DB->get_record_sql(
        'SELECT
         shortname
         FROM {course} WHERE id = ?', [$id_curso]
    );
  } catch (dml_exception $e) {
      echo json_encode('Erro consulta banco de dados. '.$e);
  }
  return trim($curso->shortname);
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
