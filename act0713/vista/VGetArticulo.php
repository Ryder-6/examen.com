<?php

namespace act0713\vista;

class VGetArticulo extends Vista
{
  public function salida(mixed $datos): void
  {
    ob_start();
    $this->inicioHtml('Articulo encontrado', ['/estilos/general.css', '/estilos/formulario.css']);

    $cliente = $datos['cliente'] ?? null;
    $articulo = $datos['articulo'] ?? null;

    if ($cliente) {
?>
      <h2>Bienvenido, <?= $cliente->nombre ?> <?= $cliente->apellidos ?></h2>
      <form action="/logout" method="get">
        <button type="submit" name="operacion" value="logout">Cerrar sesión</button>
      </form>
      <hr>
    <?php
    }

    if (!$articulo) {
      echo "<p>Artículo no disponible.</p>";
      echo '<p><a href="/">Volver al inicio</a></p>';
      $this->finHtml();
      ob_end_flush();
      return;
    }

    ?>
    <h3>Datos del artículo</h3>
    <table border="1">
      <tr>
        <th>Referencia</th>
        <td><?= $articulo->referencia  ?></td>
      </tr>
      <tr>
        <th>Descripción</th>
        <td><?= $articulo->descripcion ?></td>
      </tr>
      <tr>
        <th>PVP</th>
        <td><?= number_format($articulo->pvp, 2) ?> €</td>
      </tr>
      <tr>
        <th>Descuento</th>
        <td><?= $articulo->dto_venta ? number_format($articulo->dto_venta * 100, 2) . '%' : 'Sin descuento' ?></td>
      </tr>
      <tr>
        <th>Unidades vendidas</th>
        <td><?= $articulo->und_vendidas ?></td>
      </tr>
      <tr>
        <th>Unidades disponibles</th>
        <td><?= $articulo->und_disponibles ?></td>
      </tr>
      <tr>
        <th>Fecha disponible</th>
        <td><?= $articulo->fecha_disponible ? $articulo->fecha_disponible->format('d/m/Y') : '' ?></td>
      </tr>
      <tr>
        <th>Categoría</th>
        <td><?= $articulo->categoria ?></td>
      </tr>
      <tr>
        <th>Tipo IVA</th>
        <td><?= $articulo->tipo_iva ?? '' ?></td>
      </tr>
    </table>

    <?php if ($cliente) : ?>
      <p>
        <a href="/resenas/<?= $cliente->nif ?>/<?= $articulo->referencia ?>/new">Añadir reseña</a>
      </p>
    <?php endif; ?>

    <p><a href="/">Volver al inicio</a></p>

<?php
    $this->finHtml();
    ob_end_flush();
  }
}
