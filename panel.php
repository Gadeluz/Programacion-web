<?php
session_start();
include("bd.php");
ini_set('display_errors', E_ALL);

if (!isset($_SESSION['usuario']) && !isset($_COOKIE['usuario'])) {
    echo "
    <div style='text-align:center; margin-top:50px; font-family:Arial;'>
        <h2>🚫 Acceso denegado</h2>
        <p>Debe <a href='login.php'>iniciar sesión</a> para acceder al panel.</p>
    </div>";
    exit();
}


if (isset($_GET['cerrar'])) {
    session_unset();
    session_destroy();
    setcookie("usuario", "", time() - 3600, "/");
    echo "<script>alert('Sesión cerrada correctamente.'); window.location='login.php';</script>";
    exit();
}


if (isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $descripcion = $_POST['descripcion'];

    $sql = "INSERT INTO productos (nombre, precio, cantidad, descripcion) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdis", $nombre, $precio, $cantidad, $descripcion);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success text-center'>✅ Producto agregado correctamente.</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>❌ Error al agregar producto: {$conn->error}</div>";
    }
}


if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $id = $_POST['id'];
    $cantidadEliminar = $_POST['cantidad_eliminar'];

    $res = $conn->query("SELECT cantidad FROM productos WHERE id=$id");
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $cantidadActual = $row['cantidad'];

        if ($cantidadEliminar >= $cantidadActual) {
            $conn->query("DELETE FROM productos WHERE id=$id");
            echo "<div class='alert alert-warning text-center'>🗑️ Producto eliminado completamente.</div>";
        } else {
            $nuevaCantidad = $cantidadActual - $cantidadEliminar;
            $conn->query("UPDATE productos SET cantidad=$nuevaCantidad WHERE id=$id");
            echo "<div class='alert alert-secondary text-center'>➖ Se eliminaron $cantidadEliminar unidades del producto.</div>";
        }
    }
}


$result = $conn->query("SELECT * FROM productos");
$productos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel de Productos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">

  
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📦 Panel de Productos</h3>
    <div>
      <span class="me-3">👤 <?= $_SESSION['usuario'] ?? $_COOKIE['usuario'] ?></span>
      <a href="?cerrar=1" class="btn btn-outline-danger btn-sm">Cerrar sesión</a>
    </div>
  </div>

  <table class="table table-bordered table-hover text-center bg-white">
    <thead class="table-dark">
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
      <?php foreach ($productos as $p): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['nombre']) ?></td>
        <td>$<?= number_format($p['precio'], 2) ?></td>
        <td><?= $p['cantidad'] ?></td>
        <td><?= htmlspecialchars($p['descripcion']) ?></td>
        <td>
         
          <a href="actualizar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-info">Actualizar</a>

         
          <form method="POST" class="d-inline">
            <input type="hidden" name="accion" value="eliminar">
            <input type="hidden" name="id" value="<?= $p['id'] ?>">
            <input type="number" name="cantidad_eliminar" min="1" max="<?= $p['cantidad'] ?>" placeholder="Cant." required>
            <button class="btn btn-sm btn-danger">Eliminar</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <hr>

 
  <h4 class="text-center mt-4">➕ Agregar nuevo producto</h4>
  <form method="POST" class="p-3 bg-white rounded shadow-sm">
    <input type="hidden" name="accion" value="agregar">
    <div class="mb-3">
      <label class="form-label">Nombre:</label>
      <input type="text" name="nombre" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Precio:</label>
      <input type="number" step="0.01" name="precio" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Cantidad:</label>
      <input type="number" name="cantidad" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Descripción:</label>
      <input type="text" name="descripcion" class="form-control">
    </div>
    <button type="submit" class="btn btn-success w-100">Agregar producto</button>
  </form>
</div>
</body>
</html>
