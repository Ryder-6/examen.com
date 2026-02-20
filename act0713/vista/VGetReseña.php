<?php

namespace act0713\vista;

class VGetReseña extends Vista{
  public function salida(mixed $datos): void
  {
    ob_start();
    $this->inicioHtml('Añadir Reseña', ['/estilos/general.css',  '/estilos/formulario.css']);

    ?>
    
    <h3>Añadir nueva reseña</h3>
    <fieldset>
      <legend>añadir nueva reseña para <?= $datos->descripcion ?></legend>
      <form action="/resenas" method="POST">
        <input type="hidden" name="nif" id="nif" value="<?= $this->cliente->nif; ?>">
        <input type="hidden" name="referencia" id="referencia" value="<?= $datos->referencia?>">
        <input type="hidden" name="fechaHora" id="fechaHora" value="<?= date('Y-m-d H:i:s'); ?>">

        <label for="clasificacion">Clasificación (0-5)</label>
        <input type="range" name="clasificacion" id="clasificacion" min="0" max="5" step="1" value="0">

        <label for="comentario">Comentario</label>
        <textarea name="comentario" id="comentario" cols="30" rows="5" required></textarea>

        <button type="submit" name="operacion" id="operacion" value="anadirResena">Añadir Reseña</button>
      </form>
    </fieldset>
    
    
    <?php
    $this->finHtml();
    ob_end_flush();
  }
}