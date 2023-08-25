<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_material.php');

global $CFG, $DB, $USER;


require_login();
$url = new moodle_url("$CFG->wwwroot/blocks/material/");


$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Material de Apoio');
$PAGE->set_heading('Material de Apoio');
$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('Material');
$editnode = $settingsnode->add('Arquivos');
$editnode->make_active();
echo $OUTPUT->header();


$idcurso = $_GET["idcurso"];

//Consulta nome curso
global $CFG, $DB;
$curso = null;
$curso = $DB->get_record_sql('
        SELECT c.shortname, c.fullname FROM {course} AS c
        WHERE c.id = ' . $idcurso
);




//JAVA SCRIPT
echo '<script type="text/javascript" src="jquery3.6.4.js"></script>';
echo '<script type="text/javascript" src="custom.js?ver=1.0"></script>';


echo '
<div class="card">
  <h5 class="card-header">Upload de documentos</h5>
  <div class="card-body">
    <p class="card-text">Os arquivos que estão na listagem aqui aparecerão para o usuário</p>
    <form id="formDocumentos" method="post" enctype="multipart/form-data">
    <input type="hidden" class="form-control" id="nomecurso" value="' . $curso->shortname . '" readonly>
    <div class="form-row">
        <div class="form-group col-md-12">
            <label for="documento">Documento<span
                    class="text-danger font-weight-bold">*</span></label>
            <input type="file" class="form-control" accept="application/zip" name="documento"
                id="documento"  required><br>
            <div id="downDocOficial"></div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-12">
            <div id="msgAguardeDados"></div>
            <input class="btn btn-primary" id="btnDadosPessoais" type="submit" value="Salvar"
                style="float:right;">
        </div>
    </div>
   </form>
  </div>
</div>
';



echo '
<div class="card mt-3">
<div class="card-header">
    Arquivos do ' . $curso->fullname . '
</div>
</div>';

//echo '<li class="list-group-item"><a href="' . $url_uploads . '/' . $arquivo . '" target="_blank"><button type="button" class="list-group-item list-group-item-action">' . $arquivo . '</button></a></li>';



//Listar arquivos-----------------------------------------------------------------------
$url_uploads = $url . 'uploads/' . $curso->shortname;
$link_excluir = $url . 'excluir.php?';
$arquivos = scandir('uploads/' . $curso->shortname . '/');
unset($arquivos[0], $arquivos[1]);


echo '<div class="form-row"><div class="form-group col-md-12"><div class="list-group">';


foreach ($arquivos as &$arquivo) {
    echo '
    <li class="list-group-item">' . $arquivo . '<div style="float:right;">
    <a class="btn btn-success" href="' . $url_uploads . '/' . $arquivo . '" role="button" target="_blank">Baixar</a> 
    <a class="btn btn-danger" href="' . $link_excluir . 'idcurso=' . $idcurso . '&curso=' . $curso->shortname . '&arquivo=' . $arquivo . '" role="button">Excluir</a></div></li>
    ';
}
echo '</ul>';

echo '</div></div></div>';



echo $OUTPUT->footer();
