<?php
$serverName = "PAC-09222\\PRUEBASPAC24"; // Asegúrate de que esta cadena sea correcta para tu servidor

// Datos de conexión (reemplaza con los valores correctos si es necesario)
$connectionInfo = array( "Database" => "PEREGRINACENTRO", "UID" => "sa", "PWD" => " ");
$connectionInfo = array( "Database" => "PEREGRINALORETO", "UID" => "sa", "PWD" => " ");
$connectionInfo = array( "Database" => "PEREGRINATEPEACA", "UID" => "sa", "PWD" => " ");

// Conexión a SQL Server
$conn = sqlsrv_connect( $serverName, $connectionInfo);

// Verificación de la conexión
if( $conn ) {
    echo "Conexión establecida.<br />";
} else {
    echo "Conexión no se pudo establecer.<br />";
    die( print_r( sqlsrv_errors(), true));
}
?>
