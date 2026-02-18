<?php
// Credenciales para Tiendaol13(Ryder)


return $confBD13 = [
  'dsn'     => "mysql:host=cpd.iesgrancapitan.org;port=9990;dbname=tiendaol13;charset=utf8mb4",
  'usuario' => "jrider",
  'clave'   => "usuario",
  'opciones'=> [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
  ]  
];

?> 