<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Subir documentos PDF</title>
</head>
<body>

<div class="d-flex mt-5 m-2">

    <div class="col-3">
    <form action="upload.php" method="POST" enctype="multipart/form-data" class="form">
        <div class="form-group ">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="form-group ">
            <label for="descripcion" class="form-label">Descripcion:</label>
            <input type="text" name="descripcion" class="form-control" required>
        </div>
        <div class="form-group ">
            <label for="doc_pdf" class="form-label">RFC Formato PDF:</label>
            <input type="file" name="doc_pdf" accept=".pdf" class="form-control" required>
        </div><br>

        <button type="submit" class="btn btn-success">Enviar</button>
    </form>
    </div>


    <div class="col-9">
    <div class="table-responsive">
        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripcion</th>
                    <th scope="col">Ruta PDF</th>
                    <th scope="col">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    require_once 'config.php';
                    $consulta = mysqli_query($conexion, "SELECT * FROM documentos");
                    while($fila = mysqli_fetch_assoc($consulta)):
                ?>
                <tr class="">
                    <td><?php echo $fila['id']; ?></td>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['descripcion']; ?></td>
                    <td><?php echo $fila['doc_pdf']; ?></td>
                    <td>
                        <a href="download.php?id=<?php echo $fila['id'];?>" class="btn btn-primary">Descargar</a>
                        <a href="update.php?id=<?php echo $fila['id'];?>" class="btn btn-warning">Modificar</a>
                        <a href="delete.php?id=<?php echo $fila['id'];?>" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    </div>


</div>


</body>
</html>