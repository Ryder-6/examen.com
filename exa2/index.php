<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/exa2/util/Autocarga.php");

use exa2\util\Autocarga;
use exa2\enrutador\Enrutador;

Autocarga::iniAutocarga();

$enrutador = new Enrutador;

?>