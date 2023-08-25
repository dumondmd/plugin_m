$(document).ready(function () {
  $('#username').mask('000.000.000-00');
  $('#id_username').mask('000.000.000-00');
  $("#fitem_id_country").css("visibility", "hidden");

  $("#id_firstname").keyup(function () {
    $(this).val($(this).val().toUpperCase());
  });

  $("#id_lastname").keyup(function () {
    $(this).val($(this).val().toUpperCase());
  });

});
