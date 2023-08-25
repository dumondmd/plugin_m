<?php
require('../config.php');

$cep = $_POST["cep"];

$apiEndereco = file_get_contents("https://viacep.com.br/ws/" . $cep . "/json/");

print_r($apiEndereco);
