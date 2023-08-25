<?php
class block_orgao extends block_base {
    public function init() {
        $this->title = get_string('orgao', 'block_orgao');        
    }

    public function instance_allow_multiple() {
        return false;
    }

    public static function get_qtd_html() {
        global $CFG, $DB;
            $cities = null;                                
            $cities = $DB->count_records('blocks_orgao_franchised');
        return $cities;
    }

  
    public function get_content() {
        global $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        if (isloggedin() && !isguestuser()) { // Show the block.
           
            $qtd = self::get_qtd_html();

            $this->content = new stdClass();
            
            if (empty($qtd)){
                $this->content->text = '<h3>Nenhum orgao cadastrado!</h3>';
            }
            else {
                $this->content->text = '<h3>'.$qtd.' orgao(s) cadastrado(s)</h3>';
            }
           $url = new moodle_url("$CFG->wwwroot/blocks/orgao/view.php");
                $html_button = '<a href="'.$url.'"><button data-filteraction="apply" type="button" class="btn btn-primary">Gerenciar orgao</button></a>';
                $this->content->text .= $html_button;    
            
        }
        return $this->content;
    }




    
}