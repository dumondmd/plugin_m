<?php
class block_periodo extends block_base
{
    public function init()
    {
        $this->title = 'Periodo de inscrição';
    }

    public function instance_allow_multiple()
    {
        return false;
    }

    public static function get_cursos()
    {
        global $CFG, $DB;
        $cursos = null;
        $cursos = $DB->get_records_sql('
            SELECT c.id, c.fullname FROM {course} AS c
            WHERE c.category != 0 and c.visible = 1
            ');
        return $cursos;
    }


    public function get_content()
    {
        global $CFG;
        $url = new moodle_url("$CFG->wwwroot/blocks/periodo/view.php");
        if ($this->content !== null) {
            return $this->content;
        }

        if (isloggedin() && is_siteadmin()) { // Show the block.

            $this->content = new stdClass();

            $list_cursos = self::get_cursos();

            $this->content->text = '<h5>Lista de editais:</h5><div class="list-group">';


            foreach ($list_cursos as &$val) {
                $this->content->text .= '<a href="' . $url . '?idcurso='.$val->id.'"><button type="button" class="list-group-item list-group-item-action">' . $val->fullname . '</button></a>';
            }


            $this->content->text .= '</div>';
        }

        return $this->content;
    }
}
