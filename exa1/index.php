<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/exa1/util/Autocarga.php');

use exa1\util\Autocarga;
use exa1\enrutador\Enrutador;

Autocarga::iniciaAutocarga();

$enrutador = new Enrutador();


?>