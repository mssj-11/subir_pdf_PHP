<?php
require_once 'config.php';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $doc_pdf = $_FILES['doc_pdf'];

    // Verificar si se pudo establecer la conexión
    if (!$conexion) {
        die('Error de conexión: ' . mysqli_connect_error());
    }

    // Guardar el archivo PDF en la carpeta "documentos"
    $directorio = 'documentos/';
    $archivo_pdf = $directorio . basename($doc_pdf['name']);
    move_uploaded_file($doc_pdf['tmp_name'], $archivo_pdf);

    // Guardar los datos en la base de datos
    $query = "INSERT INTO documentos (nombre, descripcion, doc_pdf) VALUES ('$nombre', '$descripcion', '$archivo_pdf')";
    $resultado = mysqli_query($conexion, $query);

    // Verificar si se pudo insertar el registro en la base de datos
    if ($resultado) {
        // Obtener el ID del registro insertado
        $documento_id = mysqli_insert_id($conexion);
        echo 'Los datos se han guardado correctamente.';
        // Redirigir a index.php después de 3 segundos
        header("refresh:3;url=index.php");
        exit;
    } else {
        echo 'Error al guardar los datos: ' . mysqli_error($conexion);
    }

    // Cerrar la conexión
    mysqli_close($conexion);
}
?>