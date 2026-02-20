<?php

namespace act0713\modelo;

use act0713\orm\BDFactory;
use act0713\seguridad\Auth;
use act0713\orm\entidad\ErrorAplicacion;
use act0713\orm\modelo\ORMArticulo;

class MGetReseña implements Modelo
{
  public function procesaPeticion(array $param): mixed
  {
    if (!Auth::check()) {
      throw new ErrorAplicacion(
        "Sesion no iniciada",
        8,
        ['url' => "/", 'texto' => "Volver al inicio"]
      );
    }

    $cliente = Auth::cliente();
    $nif = $param[0];
    $referencia = $param[1];

    if ($cliente->nif !== $nif) {
      throw new ErrorAplicacion(
        "No tienes permisos para crear una reseña con este NIF",
        10,
        ['url' => "/", 'texto' => "Volver al inicio"]
      );
    }

    $ormArticulo = new ORMArticulo(BDFactory::create());

    if (!$ormArticulo->haComprado($nif, $referencia)) {
      throw new ErrorAplicacion(
        "El cliente nunca ha comprado el articulo",
        9,
        ['url' => "/", 'texto' => "Volver al inicio"]
      );
    }

    $articulo = $ormArticulo->get($referencia);
    return $articulo;
  }
}
