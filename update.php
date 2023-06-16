<?php
session_start(); // Iniciar la sesión
require_once 'config.php';

// Verificar si se ha enviado el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $doc_pdf = $_FILES['doc_pdf'];

    // Verificar si se pudo establecer la conexión
    if (!$conexion) {
        die('Error de conexión: ' . mysqli_connect_error());
    }

    // Obtener el ID del documento
    $documento_id = $_SESSION['documento_id'];

    // Verificar si se ha seleccionado un archivo PDF nuevo
    if ($doc_pdf['name'] !== '') {
        // Obtener la información del documento de la base de datos
        $query_select = "SELECT * FROM documentos WHERE id = $documento_id";
        $resultado_select = mysqli_query($conexion, $query_select);
        $documento = mysqli_fetch_assoc($resultado_select);

        // Verificar si el archivo PDF existente debe ser reemplazado
        if ($documento && file_exists($documento['doc_pdf'])) {
            unlink($documento['doc_pdf']); // Eliminar el archivo PDF existente
        }

        // Guardar el nuevo archivo PDF en la carpeta "documentos"
        $directorio = 'documentos/';
        $archivo_pdf = $directorio . basename($doc_pdf['name']);
        move_uploaded_file($doc_pdf['tmp_name'], $archivo_pdf);

        // Actualizar los datos en la base de datos, incluyendo el nuevo archivo PDF
        $query_update = "UPDATE documentos SET nombre = '$nombre', descripcion = '$descripcion', doc_pdf = '$archivo_pdf' WHERE id = $documento_id";
    } else {
        // Actualizar los datos en la base de datos sin cambiar el archivo PDF
        $query_update = "UPDATE documentos SET nombre = '$nombre', descripcion = '$descripcion' WHERE id = $documento_id";
    }

    $resultado_update = mysqli_query($conexion, $query_update);

    if ($resultado_update) {
        echo 'Los datos se han actualizado correctamente.';
        // Redirigir a index.php después de 3 segundos
        header("refresh:3;url=index.php");
        exit;
    } else {
        echo 'Error al actualizar el documento: ' . mysqli_error($conexion);
    }

    // Cerrar la conexión
    mysqli_close($conexion);
} else {
    // Verificar si se ha enviado el ID del documento
    if (isset($_GET['id'])) {
        // Obtener el ID del documento
        $documento_id = $_GET['id'];

        // Obtener la información del documento de la base de datos
        $query_select = "SELECT * FROM documentos WHERE id = $documento_id";
        $resultado_select = mysqli_query($conexion, $query_select);
        $documento = mysqli_fetch_assoc($resultado_select);

        // Verificar si se encontró el documento
        if ($documento) {
            // Guardar el ID del documento en la sesión
            $_SESSION['documento_id'] = $documento_id;
            
            // Asignar los valores a los campos del formulario
            $nombre = $documento['nombre'];
            $descripcion = $documento['descripcion'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Actualizar Info</title>
</head>
<body>
<div class="container col-4 mt-5">
    <form method="POST" enctype="multipart/form-data" class="form">
        <div class="form-group ">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $nombre;?>" required>
            <div class="invalid-feedback">Sube el archivo en formato pdf</div>
        </div>
        <div class="form-group ">
            <label for="descripcion" class="form-label">Descripcion:</label>
            <input type="text" name="descripcion" class="form-control" value="<?php echo $descripcion;?>" required>
            <div class="invalid-feedback">Sube el archivo en formato pdf</div>
        </div>
        <div class="form-group ">
            <label for="doc_pdf" class="form-label">RFC Formato PDF:</label>
            <?php if ($documento && isset($documento['doc_pdf'])): ?>
                <?php $archivo_pdf = basename($documento['doc_pdf']); ?>
                <span><?php echo $archivo_pdf; ?></span><br>
            <?php endif; ?>
            <input type="file" name="doc_pdf" accept=".pdf" class="form-control" value="<?php echo $archivo_pdf;?>">
            <div class="invalid-feedback">Sube el archivo en formato pdf</div>
        </div><br>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="./index.php" class="btn btn-dark">Atras</a>
    </form><br><br>
</div>

</body>
</html>