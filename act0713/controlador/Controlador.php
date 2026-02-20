<?php

namespace act0713\controlador;

use act0713\orm\entidad\ErrorAplicacion;
use act0713\seguridad\Auth;

class Controlador
{
  protected array $peticiones;
  protected Peticion $peticion;

  protected const NS_MODELOS = "act0713\\modelo\\";
  protected const NS_VISTAS = "act0713\\vista\\";

  protected string $vistaError = "VError";

  public function __construct()
  {
    $this->peticiones = [
      new Peticion("GET", "#^/$#", "MMain", "VMain", false),
      new Peticion("POST", "#^/login$#", "MLogin", "VLogin", false),
      new Peticion("GET", "#^/logout$#", "MLogout", null, true),
      new Peticion("GET", "#^/articulos/(\w+)$#", "MGetArticulo", "VGetArticulo" ),
      new Peticion("GET", "#^/resenas/(\w+)/(\w+)/new$#", "MGetReseña", "VGetReseña"),
      new Peticion("POST", "#^/resenas$#", "MPostReseñas", "VPostReseña")
    ];
    $this->despachaPeticion();
  }

  public function despachaPeticion()
  {
    try {
      $pathPeticion = $this->getPath();
      $metodoPeticion = $this->getMetodoHTTP();

      $this->peticion = $this->getPeticion($metodoPeticion, $pathPeticion);

      if ($this->peticion->getRequiereAuth()) {
        if (!Auth::check()) {
          header('Location: \login');
          exit;
        }
      }

      $claseModelo = $this->peticion->getClaseModelo();
      $claseVista = $this->peticion->getClaseVista();

      if ($claseModelo !== null && !class_exists(self::NS_MODELOS . $claseModelo)) {
        throw new ErrorAplicacion("la clase modelo $claseModelo no existe", 3, ['url' => "/", 'texto' =>  "Ir al inicio"]);
      }
      if ($claseVista !== null && !class_exists(self::NS_VISTAS . $claseVista)) {
        throw new ErrorAplicacion("la clase Vista $claseVista no existe", 4, ['url' => "/",  'texto' => "Ir al inicio"]);
      }

      if ($claseModelo) {
        $modelo = new (self::NS_MODELOS . $claseModelo)();
        $uriPeticion = $this->peticion->getExpRegURI();
        $parametros = $this->getParametros($pathPeticion, $uriPeticion);
        $datos = $modelo->procesaPeticion($parametros);
      }

      if ($claseVista) {
        $vista = new (self::NS_VISTAS . $claseVista)();
        $vista->salida($datos ?? null);
      }
    } catch (\Exception $e) {
      $vistaError = new (self::NS_VISTAS . $this->vistaError)();
      $vistaError->salida($e);
    }
  }

  private function getPath()
  {
    $url = $_SERVER['REQUEST_URI'];
    $path = parse_url($url, PHP_URL_PATH);
    return $path;
  }

  private function getMetodoHTTP()
  {
    $metodosPemitidos = ["POST", "GET", "DELETE", "PUT", "PATCH"];
    $metodoHTTP = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if ($metodoHTTP === 'POST' && isset($_POST['_method'])) {
      $metodoHTTP = filter_input(INPUT_POST, '_method', FILTER_SANITIZE_SPECIAL_CHARS);
    }

    if (in_array($metodoHTTP, $metodosPemitidos)) {
      return $metodoHTTP;
    }
    throw new ErrorAplicacion("el metodo {$metodoHTTP} no se contempla", 1, ['url' => "/", 'texto' => "Ir al inicio"]);
  }

  private function getPeticion(string $metodoHTTP, string $pathPeticion)
  {
    foreach ($this->peticiones as $peticion) {
      if ($peticion->esIgual($metodoHTTP, $pathPeticion)) {
        return $peticion;
      }
    }
    throw new ErrorAplicacion("la peticion {$metodoHTTP} {$pathPeticion} no se contempla", 2, ['url' => "/", 'texto' => "Ir al inicio"]);
  }

  private function getParametros(string $pathPeticion, string $expRegURI)
  {
    $parametros = [];
    preg_match($expRegURI, $pathPeticion, $parametros);
    array_shift($parametros);
    return $parametros;
  }
}
