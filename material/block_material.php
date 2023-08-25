<?php
class block_material extends block_base
{
    public function init()
    {
        $this->title = get_string('material', 'block_material');
    }


    public function instance_allow_multiple()
    {
        return false;
    }
    public static function get_cursos_inscricao()
    {
        global $CFG, $DB;
        $cursos = null;
        $cursos = $DB->get_records_sql('


	SELECT c.id, c.fullname FROM {course} AS c
	INNER JOIN {blocks_periodo_curso} AS p ON c.id = p.id_curso
	WHERE c.category != 0
	and p.tipo_concurso = "est_dir_int" or p.tipo_concurso = "est_dir_ext"

        ');
        return $cursos;
    }


    public function get_content()
    {
        global $CFG;
        $url = new moodle_url("$CFG->wwwroot/blocks/material/view.php");
        $urladmin = new moodle_url("$CFG->wwwroot/blocks/material/admin.php");

        if ($this->content !== null) {
            return $this->content;
        }

        if (isloggedin() && !isguestuser() && !is_siteadmin()) { // Show the block.

            $this->content = new stdClass();

            $list_cursos = self::get_cursos_inscricao();

            $this->content->text = '<h5>Lista de editais:</h5><div class="list-group">';


            foreach ($list_cursos as &$val) {
                $this->content->text .= '<a href="' . $url . '?idcurso=' . $val->id . '"><button type="button" class="list-group-item list-group-item-action">' . $val->fullname . '</button></a>';
            }

            $this->content->text .= '</div>';

        } elseif (isloggedin() && !isguestuser() && is_siteadmin()) {

            $this->content = new stdClass();

            $list_cursos = self::get_cursos_inscricao();

            $this->content->text = '<h5>Lista de editais:</h5><div class="list-group">';


            foreach ($list_cursos as &$val) {
                $this->content->text .= '<a href="' . $urladmin . '?idcurso=' . $val->id . '"><button type="button" class="list-group-item list-group-item-action">' . $val->fullname . '</button></a>';
            }

            $this->content->text .= '</div>';
        }

        return $this->content;
    }
}
