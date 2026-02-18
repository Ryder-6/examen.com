<?php

namespace act0713\vista;

use act0713\orm\entidad\Cliente;
use act0713\seguridad\Auth;

abstract class Vista
{
  protected ?Cliente $cliente;

  public abstract function salida(mixed $datos): void;

  public function __construct()
  {
    $this->cliente = Auth::cliente();
  }

  protected function inicioHtml(string $titulo, array $hojas_estilo): void {
  ?>
   <!DOCTYPE html>
    <html lang="es">

    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, intial-scale=1">
      <title><?= $titulo ?></title>
      <?php
      $raiz_servidor = $_SERVER['DOCUMENT_ROOT'];
      foreach ($hojas_estilo as $hoja) {
        echo "\t\t<link type='text/css' rel='stylesheet' href='$hoja'>\n";
      }
      ?>
    </head>

    <body>

    <h1><?= $titulo ?></h1>
    <hr>
  <?php 
  }

  protected function finHtml(): void
  {
    echo <<<FIN
    </body>
    </html>
    FIN;
  }
}
