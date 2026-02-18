<?php

namespace act0713\modelo;

use act0713\orm\BDFactory;
use act0713\orm\modelo\ORMCliente;
use act0713\orm\entidad\ErrorAplicacion;
use act0713\orm\modelo\ORMArticulo;
use act0713\seguridad\Auth;

class MLogin implements Modelo 
{
  public function procesaPeticion(array $param): mixed
  {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $pass = $_POST['pass'] ?? null;

    if (!$email || !$pass) {
      throw new ErrorAplicacion("Credenciales incompletas", 6, 
        ['url' => "/", 'texto' => "Volver al inicio"]);
    }

    $ormCliente = new ORMCliente(BDFactory::create());
    $cliente = $ormCliente->autenticar($email, $pass);

    if ($cliente === null) {
      throw new ErrorAplicacion("Email o contraseña incorrectos", 5, 
        ['url' => "/", 'texto' => "Volver al inicio"]);
    }

    Auth::login($cliente);

    $ormArticulos = new ORMArticulo(BDFactory::create());
    $articulos = $ormArticulos->ultimasCompras($cliente->nif);

    return [
      'cliente' => $cliente,
      'articulos' => $articulos
    ];
  }
}
?>