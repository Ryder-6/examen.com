<?php

namespace act0713\modelo;

use act0713\orm\BDFactory;
use act0713\orm\modelo\ORMReseña;
use act0713\seguridad\Auth;
use act0713\orm\entidad\ErrorAplicacion;
use act0713\orm\modelo\ORMArticulo;

class MGetReseña implements Modelo
{
  public function procesaPeticion(array $param): mixed
  {
    if (Auth::check()) {
      $cliente = Auth::cliente();
    } else {
      throw new ErrorAplicacion(
        "Sesion no iniciada",
        8,
        ['url' => "/", 'texto' => "Volver al inicio"]
      );
    }

    $ormArticulo = new ORMArticulo(BDFactory::create());

    if ($ormArticulo->haComprado($cliente->nif, $param[0])) {
      $articulo = $ormArticulo->get($param[0]);
      return $articulo;
    }

    throw new ErrorAplicacion(
      "El cliente nunca ha comprado el articulo",
      9,
      ['url' => "/", 'texto' => "Volver al inicio"]
    );
  }
}
