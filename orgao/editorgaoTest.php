<?php
require_once('../../config.php');

$url = new moodle_url('/blocks/orgao/view.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

$PAGE->set_context(\context_system::instance());

require_login();

// Bread Crums.
$settingsnode = $PAGE->settingsnav->add('blocks');
$editnode = $settingsnode->add('Órgão', $url);
$editnode->make_active();
echo $OUTPUT->header();


$html = '
<div id="app">
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h4>Orgão</h4>
                <div class="mb-3">
                    <label for="id_orgao" class="form-label">Id do Órgão</label>
                    <input type="text" class="form-control" id="id_orgao" name="id_orgao" readonly>    
                </div>

                <div class="mb-3">
                    <label for="id_rhnet" class="form-label">Id do RHNet</label>
                    <input type="text" class="form-control" id="id_rhnet" name="idthnet" readonly>    
                </div>

                <div class="mb-3">
                    <label for="nameorgao" class="form-label">Nome do Órgão</label>
                    <input type="text" class="form-control" id="nameorgao" name="nameorgao" readonly>   
                </div>
            </div>

            <div class="col-6">
                <h4>Usuários</h4>
                <div class="list-group">    
                    <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>
                    <a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
                    <a href="#" class="list-group-item list-group-item-action">Porta ac consectetur ac</a>                    
                </div>
            </div>
        </div>
    </div>
</div>


';


//$html .= '<script src="axios.js?ver=1.0"></script>';
//$html .= '<script src="vue.js?ver=1.0"></script>';
$html .= '<script src="jquery.js?ver=1.0"></script>';


$html .= '

<script>


$(document).ready(function () {

    $.ajax({
        url: "getusers.php",
        dataType: "json",
        type: "POST",
        beforeSend: function () {
            console.log("Buscando no usuarios Moodle");
        },
        success: function (data, textStatus) {

            console.log(data);

    

        },
        complete: function () {

        },
        error: function (xhr, er) {
            console.log("Erro ao buscar usuários do Moodle " + xhr + ", " + er);
        }
    });

});


</script>


';



echo $html;





echo $OUTPUT->footer();
