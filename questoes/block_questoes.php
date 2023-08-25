<?php
class block_questoes extends block_base
{
  public function init()
  {
    $this->title = get_string('questoes', 'block_questoes');
  }


  public function instance_allow_multiple()
  {
    return false;
  }


  public static function get_qtd_html()
  {
    global $CFG, $DB;
    $questoes = null;
    $questoes = $DB->count_records('block_questoes');
    return $questoes;
  }


  public function get_content()
  {
    global $CFG;

    if ($this->content !== null) {
      return $this->content;
    }

    if (isloggedin() && is_siteadmin()) { // Show the block.

      $qtd = self::get_qtd_html();

      $this->content = new stdClass();

      if (empty($qtd)) {
        $this->content->text = '<h3>Nenhuma questão cadastrada!</h3>';
      } else {
        $this->content->text = '<h3>' . $qtd . ' questão(s) cadastrada(s)</h3>';
      }
      $url = new moodle_url("$CFG->wwwroot/blocks/questoes/view.php");
      $html_button = '<a href="' . $url . '"><button data-filteraction="apply" type="button" class="btn btn-primary">Gerenciar questões</button></a>';
      $this->content->text .= $html_button;
    }
    return $this->content;
  }
}
