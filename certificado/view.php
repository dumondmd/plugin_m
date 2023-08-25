<?php
global $CFG, $DB, $USER;
require_once('../../config.php');
require_once('../moodleblock.class.php');
require_once('block_certificado.php');

require_login();

$url = new moodle_url('/blocks/certificado/view.php');
$urlcertificado = new moodle_url('/blocks/certificado/certificado.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Certificado de inscrição realizada');
$PAGE->set_title('Certificado de inscrição realizada');
$PAGE->set_context(\context_system::instance());


// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Certificado', $url);
$editnode->make_active();
echo $OUTPUT->header();


$enroldb = null;

$enrol = 'apply';
$status = 0;
$userid = $USER->id;



$enroldb = $DB->get_records_sql(

'SELECT c.id as courseid, c.fullname, ue.timecreated as enroldate, ue.*
FROM mdl_enrol AS e
LEFT JOIN mdl_user_enrolments ue ON ue.enrolid = e.id
JOIN mdl_user u ON u.id = ue.userid
JOIN mdl_course c ON c.id = e.courseid
WHERE e.enrol = ?
and ue.status != ?
AND u.id = ?',
  array($enrol, $status, $userid)
);



echo  '
  <h5>Lista de inscrições realizadas:</h5>
  <div class="list-group">';


if($enroldb){
  foreach ($enroldb as &$val) {
    echo '<a href="' . $urlcertificado .
      '?idcurso=' . $val->courseid .
      '"><button type="button" class="list-group-item list-group-item-action">' . $val->fullname .
      ' - Data de inscrição: ' . date("d/m/Y", $val->enroldate) .
      '</button></a>';
  }
  
} else {
  echo '<h3>Usuário não possui inscrições!</h3>';
}


echo '</div>';


echo $OUTPUT->footer();
