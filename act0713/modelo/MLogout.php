<?php

namespace act0713\modelo;

use act0713\seguridad\Auth;

class MLogout implements Modelo
{
  public function procesaPeticion(array $param): mixed
  {
    if (!Auth::check()) {
      header('Location: /');
      exit;
    }

    Auth::logout();

    header('Location: /');
    exit;
  }
}

?>