<?php
include("db.php");
$mensaje = "";


if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $descripcion = $_POST['descripcion'];

    $query = $conn->prepare("INSERT INTO productos (nombre, precio, cantidad, descripcion) VALUES (?, ?, ?, ?)");
    $query->bind_param("sdiss", $nombre, $precio, $cantidad, $descripcion);
    if ($query->execute()) {
        $mensaje = "✅ Producto agregado correctamente.";
    } else {
        $mensaje = "❌ Error al agregar el producto.";
    }
}

if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $descripcion = $_POST['descripcion'];

    $query = $conn->prepare("UPDATE productos SET nombre=?, precio=?, cantidad=?, descripcion=? WHERE id=?");
    $query->bind_param("sdiss", $nombre, $precio, $cantidad, $descripcion, $id);
    if ($query->execute()) {
        $mensaje = "✅ Producto actualizado correctamente.";
    } else {
        $mensaje = "❌ Error al actualizar el producto.";
    }
}


if (isset($_POST['eliminar'])) {
    $id = $_POST['id'];
    $cantidadEliminar = $_POST['cantidad_eliminar'];

    $query = $conn->prepare("SELECT cantidad FROM productos WHERE id=?");
    $query->bind_param("i", $id);
    $query->execute();
    $res = $query->get_result();

    if ($res->num_rows > 0) {
        $producto = $res->fetch_assoc();
        $nuevaCantidad = $producto['cantidad'] - $cantidadEliminar;

        if ($nuevaCantidad <= 0) {
            $del = $conn->prepare("DELETE FROM productos WHERE id=?");
            $del->bind_param("i", $id);
            $del->execute();
            $mensaje = "🗑️ Producto eliminado completamente (sin stock).";
        } else {
            $upd = $conn->prepare("UPDATE productos SET cantidad=? WHERE id=?");
            $upd->bind_param("ii", $nuevaCantidad, $id);
            $upd->execute();
            $mensaje = "✅ Se eliminaron $cantidadEliminar unidades del producto.";
        }
    }
}


$result = $conn->query("SELECT * FROM productos ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestión de Productos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-body">
      <h3 class="text-center mb-4">🛒 Gestión de Productos</h3>

      <?php if ($mensaje): ?>
        <div class="alert alert-info text-center py-2"><?php echo $mensaje; ?></div>
      <?php endif; ?>

      
      <form method="POST" class="row g-3 mb-4">
        <div class="col-md-3">
          <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
        </div>
        <div class="col-md-2">
          <input type="number" step="0.01" name="precio" class="form-control" placeholder="Precio" required>
        </div>
        <div class="col-md-2">
          <input type="number" name="cantidad" class="form-control" placeholder="Cantidad" required>
        </div>
        <div class="col-md-3">
          <input type="text" name="descripcion" class="form-control" placeholder="Descripción" required>
        </div>
        <div class="col-md-2">
          <button type="submit" name="agregar" class="btn btn-success w-100">Agregar</button>
        </div>
      </form>

      
      <table class="table table-hover align-middle text-center">
        <thead class="table-primary">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Descripción</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <form method="POST">
                <td><?php echo $row['id']; ?></td>
                <td><input type="text" name="nombre" value="<?php echo $row['nombre']; ?>" class="form-control"></td>
                <td><input type="number" step="0.01" name="precio" value="<?php echo $row['precio']; ?>" class="form-control"></td>
                <td><input type="number" name="cantidad" value="<?php echo $row['cantidad']; ?>" class="form-control"></td>
                <td><input type="text" name="descripcion" value="<?php echo $row['descripcion']; ?>" class="form-control"></td>
                <td>
                  <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                  <div class="d-flex justify-content-center gap-2">
                    <button type="submit" name="actualizar" class="btn btn-warning btn-sm">Actualizar</button>
                  </div>
                </td>
              </form>
            </tr>
            <tr>
              <form method="POST">
                <td colspan="6" class="text-center">
                  <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                  <div class="input-group justify-content-center">
                    <input type="number" name="cantidad_eliminar" placeholder="Cantidad a eliminar" class="form-control w-25" required>
                    <button type="submit" name="eliminar" class="btn btn-danger">Eliminar por cantidad</button>
                  </div>
                </td>
              </form>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

    </div>
  </div>
</div>

</body>
</html>
