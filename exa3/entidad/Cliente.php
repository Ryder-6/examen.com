<?php
namespace exa3\entidad;

use JsonSerializable;

class Cliente implements JsonSerializable{

	protected string $nif = '';
	protected string $nombre = '';
	protected string $apellidos = '';
	protected string $clave = '';
	protected string $iban = '';
	protected ?string $telefono = null;
	protected string $email = '';
	protected ?float $ventas = null;

	public function __construct(array $datos = []){
		foreach ($datos as $prop => $val){
			if (property_exists($this, $prop)) $this->$prop = $val;
		}
	}

	public function toArray(): array{
		return [
			'nif' => $this->nif,
			'nombre' => $this->nombre,
			'apellidos' => $this->apellidos,
			'clave' => $this->clave,
			'iban' => $this->iban,
			'telefono' => $this->telefono,
			'email' => $this->email,
			'ventas' => $this->ventas
		];
	}
}

?>
		