<?php
class block_universidade extends block_base {
    public function init() {
        $this->title = get_string('universidade', 'block_universidade');
    }

    public function instance_allow_multiple() {
        return false;
    }

    public static function get_qtd_html() {
        global $CFG, $DB;
            $universidades = null;
            $universidades = $DB->count_records('blocks_universidade');
        return $universidades;
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
                $this->content->text = '<h3>Nenhuma universidade cadastrada!</h3>';
            }
            else {
                $this->content->text = '<h3>'.$qtd.' universidade(s) cadastrada(s)</h3>';
            }
           $url = new moodle_url("$CFG->wwwroot/blocks/universidade/view.php");
                $html_button = '<a href="'.$url.'"><button data-filteraction="apply" type="button" class="btn btn-primary">Gerenciar universidades</button></a>';
                $this->content->text .= $html_button;

        }
        return $this->content;
    }





}
