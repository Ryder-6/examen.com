<?php

namespace act0713\vista;

use act0713\orm\entidad\ErrorAplicacion;

class VError extends Vista
{
  public function salida(mixed $error): void
  {
    ob_clean();

    $this->inicioHtml('Error aplicacion', ['/estilos/general.css']);

    $archivo = explode("/", $error->getFile());
    $archivo = end($archivo);

?>
    <h3>Error de la aplicación</h3>
    <table>
      <tbody>
        <tr>
          <td>Código de error</td>
          <td><?= $error->getCode() ?></td>
        </tr>
        <tr>
          <td>Mensaje de error</td>
          <td><?= $error->getMessage()?></td>
        </tr>
        <tr>
          <td>Archivo</td>
          <td><?= $archivo ?></td>
        </tr>
        <tr>
          <td>Línea</td>
          <td><?= $error->getLine() ?></td>
        </tr>
        <?php

        if ($error instanceof ErrorAplicacion) {
          $pr = $error->getPuntoRecuperacion();
        ?>
          <tr>
            <td>Punto de recuperación</td>
            <td>
              <a href="<?= $pr['url'] ?>"><?= $pr['texto'] ?></a>
            </td>
          </tr>
    <?php
        }
        echo <<<ERROR
      </tbody>
      </table>
    ERROR;
        $this->finHtml();
        ob_end_flush();
      }
    } ?>