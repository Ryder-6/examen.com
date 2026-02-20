<?php

namespace act0713\modelo;

use act0713\orm\entidad\ErrorAplicacion;
use act0713\orm\BDFactory;
use act0713\orm\entidad\Reseña;
use act0713\orm\modelo\ORMReseña;
use act0713\orm\modelo\ORMArticulo;
use act0713\seguridad\Auth;

class MPostReseñas implements Modelo
{
  public function procesaPeticion(array $param): mixed
  {
    $filtro = [
      'nif' => FILTER_SANITIZE_SPECIAL_CHARS,
      'referencia' => FILTER_SANITIZE_SPECIAL_CHARS,
      'fechaHora' => FILTER_SANITIZE_SPECIAL_CHARS,
      'clasificacion' => [
        'filter' => FILTER_VALIDATE_INT,
        'flags'  => FILTER_NULL_ON_FAILURE,
        'options' => [
          'min_range' => 0,
          'max_range' => 5
        ]
      ],
      'comentario' => FILTER_SANITIZE_SPECIAL_CHARS,
    ];

    $datosValidados = filter_input_array(INPUT_POST, $filtro);

    $datosValidados['nif'] = preg_match("/[0-9]{8}[A-Za-z]/", $datosValidados['nif']) ? $datosValidados['nif'] : false;

    $obligatorios = ['nif', 'referencia', 'clasificacion'];
    $datosFiltrados = array_filter($datosValidados, fn(mixed $e) => $e !== null);

    $faltan = array_diff($obligatorios, array_keys($datosFiltrados));
    if (count($faltan) > 0) {
      throw new ErrorAplicacion(
        "Algún dato obligatorio no está presente",
        11,
        ['url' => '/', 'texto' => "Ir al inicio de la aplicación"]
      );
    }

    if (!Auth::check()) {
      throw new ErrorAplicacion(
        "Sesión no iniciada",
        12,
        ['url' => '/', 'texto' => "Volver al inicio"]
      );
    }

    $cliente = Auth::cliente();

    if ($cliente->nif !== $datosValidados['nif']) {
      throw new ErrorAplicacion(
        "El NIF del formulario no coincide con el cliente autenticado",
        13,
        ['url' => '/', 'texto' => "Volver al inicio"]
      );
    }

    $ormArticulo = new ORMArticulo(BDFactory::create());
    if (!$ormArticulo->haComprado($datosValidados['nif'], $datosValidados['referencia'])) {
      throw new ErrorAplicacion(
        "El cliente no ha comprado el artículo",
        14,
        ['url' => '/', 'texto' => "Volver al inicio"]
      );
    }

    $reseña = new Reseña(
      [
        'id_reseña'     => null,
        'nif'           => $datosValidados['nif'],
        'referencia'    => $datosValidados['referencia'],
        'fecha'         => new \DateTime($datosValidados['fechaHora'] ?? 'now'),
        'clasificacion' => $datosValidados['clasificacion'],
        'comentario'    => $datosValidados['comentario']
      ]
    );

    $ormReseñas = new ORMReseña(BDFactory::create());
    $ormReseñas->insert($reseña);

    return $reseña;
  }
}
