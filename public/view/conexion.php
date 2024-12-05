<?php
$host = "localhost";
$usuario = "root";     
$clave = "";          
$baseDeDatos = "productos_backend"; 

// Crear la conexión
$conn = new mysqli($host, $usuario, $clave, $baseDeDatos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
