<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/exa/util/Autocarga.php');

use exa\util\Autocarga;
use exa\enrutador\Enrutador;

Autocarga::registraAutocarga();


$enrutador = new Enrutador();
?>