<?php

namespace exra\controlador;
use exra\vista\vistaError13;
class Controlador13
{
protected const string VISTA_ERROR = 'VistaError13';
  protected array $peticiones;

  public function __construct()
  {
    $this->peticiones['buscarPedido'] = [
      'modelo' => 'Modelopedido13',
      'vista' => 'VistaModelo13'
    ];
  }

  public function gestionarPeticion()
  {
    try {
      $peticion = filter_input(INPUT_POST, 'idp', FILTER_SANITIZE_SPECIAL_CHARS);
      if ($peticion)throw new \Exception("la peticion no esta contemplada", 1); ;

      if (!array_key_exists($peticion, $this->peticiones)) throw new \Exception("La peticion no es correcta $peticion", 1);
        
      
        $claseModelo = $this->peticiones[$peticion]['modelo'];
        $claseVista = $this->peticiones[$peticion]['vista'];
       
}
    } catch (\Exception $e) {
      VistaError13($e)
    }
  }
