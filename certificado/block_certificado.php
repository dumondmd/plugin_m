<?php
class block_certificado extends block_base {
    public function init() {
        $this->title = get_string('certificado', 'block_certificado');
    }


    public function instance_allow_multiple() {
      return false;
    }

public function get_content() {

  if ($this->content !== null) {
    return $this->content;
  }

  $url = new moodle_url("$CFG->wwwroot/blocks/certificado/view.php");

  $this->content         =  new stdClass;
  $this->content->text   = '<a href="'.$url.'"><button data-filteraction="apply" type="button" class="btn btn-primary">Download de comprovantes</button></a>';


  return $this->content;
}




























}
