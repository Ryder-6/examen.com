<?php
namespace act0713\seguridad;

use act0713\orm\entidad\Cliente;

class Auth {
  private const COOKIE_JWT = "jwt";

  public static function check(): bool {
    // Recupera el JWT
    // Verifica el JWT
    if( isset($_COOKIE[self::COOKIE_JWT]) ) {
      $jwt = $_COOKIE[self::COOKIE_JWT];
      $payload = JWT::verificarJWT($jwt);

      return (bool)$payload && isset($_SESSION['cliente']);
    }
    return false;
  }

  public static function cliente(): ?Cliente {
    if( self::check() ) {
      return $_SESSION['cliente'];
    }
    return null;
  }

  public static function login(Cliente $cliente): void {
    // Apertura de sesión
    $payload = ['email' => $cliente->email];
    $jwt = JWT::generarJWT($payload);
    setcookie(self::COOKIE_JWT, $jwt, 0, "/");
    $_SESSION['cliente'] = $cliente;
    $_SESSION['carrito'] = [];
  } 

  public static function logout(): void {
    // Cierre de la sesión
    $parametros = session_get_cookie_params();
    $sesionId = session_name();
    setcookie($sesionId, "", time() - 100,
      $parametros['path'], $parametros['domain'],
      $parametros['secure'], $parametros['httponly']);
    setcookie(self::COOKIE_JWT, "", time() - 100, "/");
    session_destroy();
    $_SESSION = [];
    session_start();
  }


}