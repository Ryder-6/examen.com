<?php

namespace act0813\modelo;

use act0813\orm\BDFactory;
use act0813\orm\modelo\ORMCliente;
use act0813\orm\entidad\Cliente;


class MSCliente extends ModeloServicio
{

  public function __construct()
  {
    $this->claseORM = new ORMCliente(BDFactory::create());
  }


  protected function ValidacionDatos(bool $completo): Cliente | array | null
  {
    try {
      $entrada = file_get_contents("php://input");
      $datos = json_decode($entrada, true, 512, JSON_THROW_ON_ERROR);

      if (!is_array($datos)) return null;

      $filtro = [
        'nif' => [
          'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
          'flags'  => FILTER_NULL_ON_FAILURE
        ],
        'nombre' => [
          'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
          'flags'  => FILTER_NULL_ON_FAILURE
        ],
        'apellidos' => [
          'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
          'flags'  => FILTER_NULL_ON_FAILURE
        ],
        'clave' => [
          'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
          'flags'  => FILTER_NULL_ON_FAILURE
        ],
        'iban' => [
          'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
          'flags'  => FILTER_NULL_ON_FAILURE
        ],
        'telefono' => [
          'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
          'flags'  => FILTER_NULL_ON_FAILURE
        ],
        'email' => [
          'filter' => FILTER_VALIDATE_EMAIL,
          'flags'  => FILTER_NULL_ON_FAILURE
        ],
        'ventas' => [
          'filter' => FILTER_VALIDATE_FLOAT,
          'options' => ['min_range' => 0],
          'flags'  => FILTER_NULL_ON_FAILURE | FILTER_FLAG_ALLOW_FRACTION
        ]
      ];

      $datosValidados = filter_var_array($datos, $filtro, true);
      $datosValidos = array_filter($datosValidados, fn($d) => $d !== null);

      $datosObligatorios = $completo ? ['nif', 'nombre', 'apellidos', 'clave'] : [];
      $faltan = array_diff($datosObligatorios, array_keys($datosValidos));
      if (!empty($faltan)) return null;

      $Cliente = $completo ? new Cliente($datosValidados) : $datosValidos;
      return $Cliente;
    } catch (\JsonException $je) {
      return null;
    }
  }
}
