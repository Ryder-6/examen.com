<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/act0813/util/Autocarga.php');

use act0813\enrutador\Enrutador;
use act0813\util\Autocarga;
Autocarga::registraAutocarga();

$enrutador = new Enrutador();
?>