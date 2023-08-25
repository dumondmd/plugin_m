<?php
class block_especializacoes extends block_base {
    public function init() {
        $this->title = get_string('especializacoes', 'block_especializacoes');
    }

    public function instance_allow_multiple() {
        return false;
    }

    public static function get_qtd_html() {
        global $CFG, $DB;
            $especializacoess = null;
            $especializacoess = $DB->count_records('blocks_especializacoes');
        return $especializacoess;
    }


    public function get_content() {
        global $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        if (isloggedin() && is_siteadmin()) { // Show the block.

            $qtd = self::get_qtd_html();

            $this->content = new stdClass();

            if (empty($qtd)){
                $this->content->text = '<h3>Nenhuma especialização cadastrada!</h3>';
            }
            else {
                $this->content->text = '<h3>'.$qtd.' especialização(s) cadastrada(s)</h3>';
            }
           $url = new moodle_url("$CFG->wwwroot/blocks/especializacoes/view.php");
                $html_button = '<a href="'.$url.'"><button data-filteraction="apply" type="button" class="btn btn-primary">Gerenciar especialização</button></a>';
                $this->content->text .= $html_button;

        }
        return $this->content;
    }





}
