<?php

namespace act0713\vista;

class VLogin extends Vista
{
  public function salida(mixed $datos): void
  {
    ob_start();
    $this->inicioHtml('Autenticación exitosa', ['/estilos/general.css', '/estilos/tabla.css']);

    $cliente = $datos['cliente'];
    $articulos = $datos['articulos'];
?>

    <h2>Bienvenido, <?= $cliente->nombre ?> <?= $cliente->apellidos ?></h2>

    <form action="/logout" method="GET">
      <button type="submit" name="operacion" value="logout">Cerrar sesión</button>
    </form>

    <hr>

    <h3>Artículos comprados</h3>
    <?php
    if (count($articulos) > 0) {
    ?>
      <table border="1">
        <thead>
          <tr>
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Precio</th>
            <th>Unidades</th>
            <th>Descuento</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($articulos as $articulo) : ?>
            <tr>
              <td><?= htmlspecialchars($articulo['descripcion'], ENT_QUOTES) ?></td>
              <td><?= $articulo['fecha'] ?></td>
              <td><?= number_format($articulo['precio'], 2) ?> €</td>
              <td><?= $articulo['unidades'] ?></td>
              <td><?= number_format($articulo['dto'], 2) ?>%</td>
              <td>
                <form action="/resenas/<?= $cliente->nif ?>/<?= $articulo['referencia'] ?>/new" method="get" style="display:inline;">
                  <button type="submit">Añadir reseña</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
<?php
    } else {
      echo "<p>No hay articulos comprados.</p>";
    }

    $this->finHtml();
    ob_end_flush();
  }
}

?>