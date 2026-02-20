<?php

namespace act0713\vista;

class VPostReseña extends Vista
{
  public function salida(mixed $datos): void
  {
    ob_start();
    $this->inicioHtml('Reseña Insertada', ['/estilos/general.css', '/estilos/tabla.css']);

    ?>
    
    <h3>Reseña insertada correctamente</h3>
    <fieldset>
      <legend>Datos de la reseña</legend>
      <table border="1">
        <tr>
          <th>NIF Cliente</th>
          <td><?= $datos->nif ?></td>
        </tr>
        <tr>
          <th>Referencia Artículo</th>
          <td><?= $datos->referencia ?></td>
        </tr>
        <tr>
          <th>Fecha</th>
          <td><?= $datos->fecha->format('Y-m-d H:i:s') ?></td>
        </tr>
        <tr>
          <th>Clasificación</th>
          <td><?= $datos->clasificacion ?>/5</td>
        </tr>
        <tr>
          <th>Comentario</th>
          <td><?= $datos->comentario ?></td>
        </tr>
      </table>
    </fieldset>
    
    <p>
      <a href="/">Volver al inicio</a>
    </p>

    <?php
    $this->finHtml();
    ob_end_flush();
  }
}
