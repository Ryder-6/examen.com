<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/exa5/util/Autocarga.php");
use exa5\util\Autocarga;
use exa5\enrutador\Enrutador;

Autocarga::iniAutocarga();

$enrutador = new Enrutador;


?>