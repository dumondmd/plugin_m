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

$id = $_POST["id"];

$citiesdb = $DB->get_records_sql(
    'SELECT id, orgao, responsable, servidor from {blocks_orgao_franchised} where id = ?', array($id)                
); 


$users = $DB->get_records_sql(
    'SELECT id, firstname, lastname from {user}'                
);


$arrUser = '';
foreach ($users as $key => $value){    
    $arrUser .= '<button type="button" @click="addUser('.$value->id.')" class="list-group-item list-group-item-action">'.$value->id.' - '.$value->firstname.' '.$value->lastname.'</button>';    
}
 

$arrorgao = '';
foreach ($citiesdb as $key => $value){  
 
    if($value->servidor){
           $arrorgao = '
        <div class="mb-3">
            <label for="id" class="form-label">Id do Órgão</label>
            <input type="text" class="form-control" id="id" name="id" value="'.$value->id.'" readonly>    
        </div>

        <div class="mb-3">
            <label for="id_rhnet" class="form-label">Id do RHNet</label>
            <input type="text" class="form-control" id="id_rnet" name="id_rhnet" value="'.$value->servidor.'" readonly>    
        </div>

        <div class="mb-3">
            <label for="nameorgao" class="form-label">Nome do Órgão</label>
            <input type="text" class="form-control" id="nameorgao" name="nameorgao" value="'.$value->orgao.'" readonly>   
        </div>

        <div class="mb-3">
            <label for="responsableold" class="form-label">Id(s) responsávei(s) antigo(s)</label>
            <input type="text" class="form-control" id="responsableold" name="responsableold" value="'.$value->responsable.'" readonly>    
        </div>
        ';
    } else {
        $arrorgao = '
        <div class="mb-3">
            <label for="id" class="form-label">Id do Órgão</label>
            <input type="text" class="form-control" id="id" name="id" value="'.$value->id.'" readonly>    
        </div>
        
        <div class="mb-3">
            <label for="nameorgao" class="form-label">Nome do Órgão</label>
            <input type="text" class="form-control" id="nameorgao" name="nameorgao" value="'.$value->orgao.'" readonly>   
        </div>

        <div class="mb-3">
            <label for="responsableold" class="form-label">Id(s) dos responsávei(s) antigo(s)</label>
            <input type="text" class="form-control" id="responsableold" name="responsableold" value="'.$value->responsable.'" readonly>    
        </div>
        ';
    }
}


 
$html = '
    <div id="app">
        <div class="container">
            <div class="row">
            <div class="col-6">
                <h3>Responsáveis por Órgãos</h3>
                <form action="updateorgao.php" method="post">
                
                
                '.$arrorgao.'


                <div class="mb-3">                  
                    <label for="responsable" class="form-label">Id(s) responsávei(s) novo(s)</label>
                    <input type="text" class="form-control" id="responsable" name="responsable" v-model="responsaveis" readonly>    
                </div>

                <button type="submit" class="btn btn-primary">Salvar Modificações</button>
                <button type="reset" class="btn btn-danger" @click="removeAllUsers">Limpar</button>                                             
                </form>             
            </div>

            

            <div class="col-6">
                <h3>Usuários do Moodle</h3>                
                <ul class="list-group overflow-auto" style="max-height: 350px;">
                    '.$arrUser.'                  
                </ul>               
            </div>
        </div>
    </div>'

    ;

$vue_js = '<script src="vue.js?ver=1.0"></script>';

$vue_js .= "<script>
    var app = new Vue({
      el: '#app',
      data: {       
        responsaveis: [],
      },
      methods:{ 
        addUser(id_user) {
            
            if(!this.responsaveis.includes(id_user)){
                this.responsaveis.push(id_user)             
            }
        },
        removeAllUsers() {
            this.responsaveis = []
        }
      },
    })
    </script>";



$html .= $vue_js;

echo $html;


echo $OUTPUT->footer();