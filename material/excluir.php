<?php
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_material.php');

global $CFG, $DB, $USER;

require_login();
$url = new moodle_url("$CFG->wwwroot/blocks/material");


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



//Deletando arquivo
$idcurso = $_GET["idcurso"];
$curso = $_GET["curso"];
$arquivo = $_GET["arquivo"];

    
unlink('uploads/'.$curso.'/'.$arquivo);



$link_redirecionar = $url.'/admin.php?idcurso='.$idcurso;


redirect($link_redirecionar);


echo $OUTPUT->footer();