<?php

namespace exra\vista;
use exra\util\Html;

class VistaError13{
 public static function muestraError(\Exception $e) : void {
 ob_start();
  Html::inicio('Error', ['']);

  ?>
  <h2>RAFA FRENA UN POCO HOSTIA <?= $e ?></h2>
  <?php
  Html::fin('Error', ['']);
  ob_clean();
 }
}