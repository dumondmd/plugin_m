<?php

$nomecurso = $_POST["nomecurso"];
$path = 'uploads/' . $nomecurso . '/';
mkdir(__DIR__ . '/' . $path, 0777, true);
$notificacoes = '';


if ($_FILES['documento']['type'] == 'application/zip') {

    $tmp = $_FILES['documento']['tmp_name'];
    $name = $_FILES['documento']['name'];
    $path = $path . $name;

    if (move_uploaded_file($tmp, $path)) {
        $notificacoes .= "Upload concluido!\n";
    } else {
        $notificacoes .= "Erro no upload do documento!\n";
    }
} else {
    $notificacoes = "Sem documento de em anexo!";
}



echo json_encode($notificacoes);
