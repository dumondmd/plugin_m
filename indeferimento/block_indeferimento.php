<?php
class block_indeferimento extends block_base {
    public function init() {
        $this->title = get_string('indeferimento', 'block_indeferimento');
    }

    public function instance_allow_multiple() {
        return false;
    }



    public static function get_qtd_html() {
        global $CFG, $DB;
            $indeferimentos = null;
            $indeferimentos = $DB->count_records('block_indeferimento');
        return $indeferimentos;
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
                $this->content->text = '<h3>Nenhum indeferimento cadastrado!</h3>';
            }
            else {
                $this->content->text = '<h3>'.$qtd.' indeferimento(s) cadastrado(s)</h3>';
            }
           $url = new moodle_url("$CFG->wwwroot/blocks/indeferimento/view.php");
                $html_button = '<a href="'.$url.'"><button data-filteraction="apply" type="button" class="btn btn-primary">Gerenciar motivos de indeferimento</button></a>';
                $this->content->text .= $html_button;

        }
        return $this->content;
    }





}
