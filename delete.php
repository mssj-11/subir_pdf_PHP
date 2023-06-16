<?php
require_once 'config.php';
// Verificar si se ha enviado el ID del documento
if (isset($_GET['id'])) {
    // Obtener el ID del documento
    $documento_id = $_GET['id'];

    // Obtener la información del documento de la base de datos
    $query = "SELECT * FROM documentos WHERE id = $documento_id";
    $resultado = mysqli_query($conexion, $query);
    $documento = mysqli_fetch_assoc($resultado);

    if ($documento) {
        // Obtener la ruta del archivo PDF
        $ruta_pdf = $documento['doc_pdf'];

        // Verificar si el archivo existe
        if (file_exists($ruta_pdf)) {
            // Eliminar el archivo PDF
            unlink($ruta_pdf);
        }

        // Eliminar el registro de la base de datos
        $query_delete = "DELETE FROM documentos WHERE id = $documento_id";
        $resultado_delete = mysqli_query($conexion, $query_delete);

        if ($resultado_delete) {
            echo 'El documento y el archivo PDF se han eliminado correctamente.';
            // Redirigir a index.php después de 3 segundos
            header("refresh:3;url=index.php");
            exit;
        } else {
            echo 'Error al eliminar el documento: ' . mysqli_error($conexion);
        }
    } else {
        echo 'El documento no existe.';
    }

    // Liberar memoria y cerrar la conexión
    mysqli_free_result($resultado);
    mysqli_close($conexion);
} else {
    echo 'ID de documento no proporcionado.';
}
?>