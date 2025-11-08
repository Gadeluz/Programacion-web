<?php
include("bd.php");
ini_set('display_errors', E_ALL);

if (!isset($_GET['id'])) {
    die("Error: No se proporcionó un ID de producto.");
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM productos WHERE id=$id");
if ($result->num_rows === 0) {
    die("Producto no encontrado.");
}

$producto = $result->fetch_assoc();

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $descripcion = $_POST['descripcion'];

    $sql = "UPDATE productos SET nombre=?, precio=?, cantidad=?, descripcion=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdisi", $nombre, $precio, $cantidad, $descripcion, $id);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Producto actualizado correctamente.'); window.location='panel.php';</script>";
        exit;
    } else {
        echo "<div class='alert alert-danger text-center'>Error al actualizar: {$conn->error}</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Actualizar producto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-body">
      <h3 class="text-center mb-4">✏️ Actualizar producto</h3>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nombre:</label>
          <input type="text" name="nombre" value="<?= $producto['nombre'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Precio:</label>
          <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Cantidad:</label>
          <input type="number" name="cantidad" value="<?= $producto['cantidad'] ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Descripción:</label>
          <input type="text" name="descripcion" value="<?= $producto['descripcion'] ?>" class="form-control">
        </div>
        <div class="d-flex justify-content-between">
          <a href="panel.php" class="btn btn-secondary">⬅️ Volver</a>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
