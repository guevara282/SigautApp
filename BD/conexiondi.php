<?php

// Constantes para los datos de conexión a la base de datos
const DB_HOST = "localhost";
const DB_USER = "root";
const DB_PASSWORD = "";
const DB_DATABASE = "di";

// Función de conexión a la base de datos
function conectar()
{
    // Establecer la conexión a la base de datos
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    // Verificar si ocurrió algún error al conectarse a la base de datos
    if (!$conn) {
        die("Error al conectar a la base de datos: " . mysqli_connect_error());
    }

    // Establecer el conjunto de caracteres a utf8
    if (!mysqli_set_charset($conn, "utf8")) {
        die("Error al establecer el conjunto de caracteres: " . mysqli_error($conn));
    }

    // Devolver la conexión
    return $conn;
}

?>