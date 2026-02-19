<?php

namespace act0713\modelo;

use act0713\orm\BDFactory;
use act0713\seguridad\Auth;
use act0713\orm\entidad\ErrorAplicacion;
use act0713\orm\modelo\ORMArticulo;

class MGetArticulo implements Modelo
{
  public function procesaPeticion(array $param): mixed
  {
    $ormArticulo = new ORMArticulo(BDFactory::create());
    $articulo = $ormArticulo->get($param[0]);

    if (!$articulo) {
      throw new ErrorAplicacion(
        "no se ha encontrado articulo",
        7,
        ['url' => "/", 'texto' => "Volver al inicio"]
      );
    }

    if (Auth::check()) {
      $cliente = Auth::cliente();
    } else {
      $cliente = null;
    }

    return [
      'cliente' => $cliente,
      'articulo' => $articulo
    ];
  }
}
