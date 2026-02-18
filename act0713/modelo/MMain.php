<?php
namespace act0713\orm\modelo;

use act0713\orm\modelo\ORMArticulo;
use act0713\orm\BDFactory;
use act0713\seguridad\Auth;

const DESCUENTO = 0.25;

class MMain implements Modelo {
  public function procesaPeticion(array $param): mixed
  {
   $ormArticulo = new ORMArticulo(BDFactory::create());
   $enOferta = $ormArticulo->getOfertas(DESCUENTO);

   if (Auth::check()) {
    $cliente = Auth::cliente();
    
   }

   return [
    'descuento' => DESCUENTO,
    'enOferta' => $enOferta,
    'log' => isset($cliente) ? $cliente : null 
   ];
  }
}


?>