<?php

function formatText($input) {
    // Reemplazar caracteres mal codificados
    $search = [
        'Ã³n', 'Ã“N'      
    ];
    $replace = [
        'ón', 'ÓN'
    ];
    $output = str_replace($search, $replace, $input);

    // Añadir saltos de línea y tabulaciones para formatear correctamente el texto
    $output = str_replace(
        ['M1;', ';', '>}'], 
        ["M1\n\n\n", "\n", ">}\n"], 
        $output
    );

    return $output;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload']['tmp_name'];

    // Leer el contenido del archivo
    $inputText = file_get_contents($file);

    if ($inputText === false) {
        echo "Error al leer el archivo.";
        exit;
    }

    // Formatear el texto
    $formattedText = formatText($inputText);

    // Definir la ruta del archivo de salida
    $filePath = __DIR__ . '/formatted_output.txt'; // Guardar en el mismo directorio del script

    // Intentar escribir el archivo y manejar posibles errores
    if (file_put_contents($filePath, $formattedText) !== false) {
        echo "Texto formateado y exportado exitosamente en: <a href=\"$filePath\">$filePath</a>";
    } else {
        echo "Error al intentar exportar el archivo.";
    }
}
?>

<!-- HTML Formulario para subir el archivo -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir y Formatear Archivo de Texto</title>
</head>
<body>
    <h1>Subir Archivo de Texto</h1>
    <form action="" method="post" enctype="multipart/form-data">
        Selecciona el archivo de texto para subir:
        <input type="file" name="fileToUpload" accept=".txt">
        <input type="submit" value="Subir y Formatear">
    </form>
</body>
</html>
