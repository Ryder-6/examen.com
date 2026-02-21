<?php

namespace act0813\enrutador;

use act0813\error\ErrorServicio;

class Enrutador
{
  protected array $rutas;

  public function __construct()
  {
    $this->rutas = [];
    $this->IniciarRutas();
    $this->DespachaPeticion();
  }

  private function IniciarRutas(): void
  {
    $this->rutas[] = new Ruta('GET', '#^/cliente$#', \act0813\modelo\MSCliente::class, 'getAll', false, ['user', 'admin']);
    $this->rutas[] = new Ruta('GET', "#^/cliente/(\w+)$#", \act0813\modelo\MSCliente::class, 'get');
    $this->rutas[] = new Ruta('POST', "#^/cliente$#", \act0813\modelo\MSCliente::class, 'insert', true, ['admin']);
    $this->rutas[] = new Ruta('PUT', "#^/cliente/(\w+)$#", \act0813\modelo\MSCliente::class, 'update', true, ['admin']);
    $this->rutas[] = new Ruta('DELETE', "#^/cliente/(\w+)$#", \act0813\modelo\MSCliente::class, 'delete', true, ['admin']);
  }

  protected function DespachaPeticion(): void
  {
    $metodoPeticion = $this->getMetodoHTTP();
    $pathPeticion = $this->getPath();

    try {
      $ruta = $this->buscarRuta($metodoPeticion, $pathPeticion);

      if ($ruta === null) throw new ErrorServicio(404, ErrorServicio::ERROR_404, "Not found");

      $datos = $this->ejecutarRuta($ruta, $pathPeticion);

      switch ($ruta->getMetodoHTTP()) {
        case 'GET': {
            RespuestaFactory::ok($datos);
            break;
          }
        case 'POST': {
            RespuestaFactory::created($datos);
            break;
          }
        case 'PUT':
        case 'PATCH':
        case 'DELETE': {
            RespuestaFactory::noContent();
            break;
          }
      }
    } catch (ErrorServicio $es) {
      RespuestaFactory::error($es);
    } catch (\Exception $e) {
      RespuestaFactory::error(new ErrorServicio(500, ErrorServicio::ERROR_500, "Internal Server Error"));
    }
  }

  protected function getMetodoHTTP(): string
  {
    $metodoHTTP = $_SERVER['REQUEST_METHOD'];
    if ($metodoHTTP === "POST") {
      if (isset($_POST['_method'])) {
        $metodoHTTP = filter_input(INPUT_POST, '_method', FILTER_SANITIZE_SPECIAL_CHARS);
      }
    }

    if (in_array($metodoHTTP, ["POST", "GET", "PUT", "PATCH", "DELETE"])) {
      return $metodoHTTP;
    }

    throw new ErrorServicio(405, ErrorServicio::ERROR_405, "Method Not Allowed");
  }

  private function getPath(): string
  {
    $uriPeticion = $_SERVER['REQUEST_URI'];
    return parse_url($uriPeticion, PHP_URL_PATH);
  }

  private function buscarRuta(string $metodoHTTP, string $uriPeticion): ?Ruta
  {
    foreach ($this->rutas as $ruta) {
      if ($ruta->esIgual($metodoHTTP, $uriPeticion)) {
        return $ruta;
      }
    }
    return null;
  }

  private function ejecutarRuta(Ruta $ruta, string $pathPeticion): mixed
  {
    $clase = $ruta->getClase();
    $metodo = $ruta->getMetodo();

    if (!class_exists($clase) || !method_exists($clase, $metodo)) {
      throw new ErrorServicio(500, ErrorServicio::ERROR_500, "Internal Server Error");
    }

    $parametros = $this->extraerParametros($ruta->getExpRegURI(), $pathPeticion);
    $modelo = new $clase();



    $datos = call_user_func_array([$modelo, $metodo], $parametros);
    return $datos;
  }

  private function extraerParametros(string $expRegURI, string $pathPeticion): array
  {
    $parametros = [];
    preg_match($expRegURI, $pathPeticion, $parametros);
    array_shift($parametros);
    return $parametros;
  }
}
