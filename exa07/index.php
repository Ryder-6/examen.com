<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/exa07/util/Autocarga.php');
use exa07\util\Autocarga;
use exa07\enrutador\Enrutador;

Autocarga::registraAutocarga();

$enrutador = new Enrutador();

?>