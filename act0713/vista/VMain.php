<?php

namespace act0713\vista;

use act0713\orm\entidad\Articulo;

class VMain extends Vista
{
  public function salida(mixed $datos): void
  {
    ob_start();
    $this->inicioHtml('Tiendaol 13', ['/estilos/general.css']);


    if (!$datos['log']) {
?>

      <form action="/login" method="post">
        <fieldset>
          <legend>iniciar sesion</legend>
          <label for="email">email</label>
          <input type="email" name="email" id="email">

          <label for="pass">pasword</label>
          <input type="password" name="pass" id="pass">
        </fieldset>
        <button type="submit" name="operacion" id="operacion" value="login"> iniciar sesion</button>
      </form>

    <?php
    } else {
      $cliente = $datos['log'];
    ?>
      <h2><?= $cliente['nombre'] ?></h2>
      <h2><?= $cliente['apellidos'] ?></h2>


      <form action="/logout" method="post">

        <button type="submit" name="operacion" id="operacion" value="logout"> cerrar sesion</button>
      </form>

    <?php

    }
    ?>

<hr>

<h2>Articulos con un Descuento o mas <?= $datos['descuento'] * 100 ?>%</h2>
<table border="1">
  <tr>
    <th>Descripción</th>
    <th>PVP</th>
    <th>Descuento</th>
  </tr>
  <?php foreach ($datos['enOferta'] as $articulo) : 
  $dto = $articulo->dto_venta * 100;
    ?>
    <tr>
      <td><a href="/articulos/<?= $articulo->referencia ?>"><?= $articulo->descripcion ?></a></td>
      <td><?= number_format($articulo->pvp, 2) ?> €</td>
      <td><?= $dto ?>%</td>
    </tr>
  <?php endforeach ?>
</table>


<?php

    $this->finHtml();
    ob_end_flush();
  }
}


?>