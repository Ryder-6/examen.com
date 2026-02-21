<?php
namespace act0813\modelo;

use act0813\error\ErrorServicio;
use act0813\orm\entidad\Entidad;
use act0813\orm\modelo\ORMBase;

abstract class ModeloServicio {
  protected ORMBase $claseORM;

  protected abstract function ValidacionDatos(bool $completo): Entidad|array|null;

  public function getAll(): array {
    try {
      $filas = $this->claseORM->getAll();
      return $filas;
    }
    catch( \Exception $e) {
      throw new ErrorServicio(500, $e->getCode(), $e->getMessage());
    }
  }

  public function get(mixed $id): Entidad {
    try {
      $fila = $this->claseORM->get($id);
    }
    catch(\Exception $e) {
      throw new ErrorServicio(500, $e->getCode(), $e->getMessage());
    }
    if( $fila === null ) throw new ErrorServicio(404, ErrorServicio::ERROR_404, "Not Found");
    return $fila;
  }

  public function insert(): bool {
    try {
      $entidad = $this->ValidacionDatos(true);
      if( $entidad ) return $this->claseORM->insert($entidad);

      throw new ErrorServicio(422, ErrorServicio::ERROR_422, "Unproccessable Entity");
    }
    catch( \PDOException $pdoe ) {
      $error = $pdoe->getCode();
      $errorBD = $pdoe->errorInfo[1];
      $mensajeBD = $pdoe->errorInfo[2];
      switch( $error ) {
        case '23000' : {
          // Error por violación de la integridad
          throw new ErrorServicio(409, $errorBD, $mensajeBD);
        }
        default: {
          throw new ErrorServicio(500, $pdoe->getCode(), $pdoe->getMessage());
        }
      }
    }
    catch( \Exception $e) {
      throw new ErrorServicio(500, $e->getCode(), $e->getMessage());
    }
    
  }

  public function update(mixed $id): bool {
    try {
      $entidad = $this->ValidacionDatos(false);
      if( $entidad ) return $this->claseORM->update($id, $entidad);
      throw new ErrorServicio(422, ErrorServicio::ERROR_422, "Unprocessable Entity");
    }
    catch(\PDOException $pdoe) {
      $error = $pdoe->getCode();
      $errorBD = $pdoe->errorInfo[1];
      $mensajeBD = $pdoe->errorInfo[2];
      switch( $error ) {
        case '23000': {
          throw new ErrorServicio(409, $errorBD, $mensajeBD);
        }
        default: {
          throw new ErrorServicio(500, $pdoe->getCode(), $pdoe->getMessage());
        }
      }
    }
    catch( \Exception $e) {
      throw new ErrorServicio(500, $e->getCode(), $e->getMessage());
    }
  }

  public function delete(mixed $id): bool {
    try {
      return $this->claseORM->delete($id);
    }
    catch( \PDOException $pdoe) {
      $error = $pdoe->getCode();
      $errorBD = $pdoe->errorInfo[1];
      $mensajeBD = $pdoe->errorInfo[2];
      switch( $error ) {
        case '23000': {
          throw new ErrorServicio(409, $errorBD, $mensajeBD);
        }
        default: {
          throw new ErrorServicio(500, $pdoe->getCode(), $pdoe->getMessage());
        }
      }
    }
    catch( \Exception $e) {
      throw new ErrorServicio(500, $e->getCode(), $e->getMessage());
    }
  }
}