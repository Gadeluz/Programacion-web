<?php
include("libreria.php");

$server = "localhost";
$base   = "tiendita";
$usr    = "root";
$pass   = "12345"; 

function mostrarProductos($server, $base, $usr, $pass) {
    $query = "SELECT * FROM productos";
    return seleccionar($query, $server, $base, $usr, $pass);
}

function eliminarProducto($id, $server, $base, $usr, $pass) {
    $query = "DELETE FROM productos WHERE id=$id";
    return ejecutar($query, $server, $base, $usr, $pass);
}

function agregarProducto($nombre, $precio, $cantidad, $descripcion, $server, $base, $usr, $pass) {
    $query = "INSERT INTO productos (nombre, precio, cantidad, descripcion)
              VALUES ('$nombre', '$precio', '$cantidad', '$descripcion')";
    return insertar($query, $server, $base, $usr, $pass);
}

function actualizarProducto($id, $nombre, $precio, $cantidad, $descripcion, $server, $base, $usr, $pass) {
    $query = "UPDATE productos 
              SET nombre='$nombre', precio='$precio', cantidad='$cantidad', descripcion='$descripcion'
              WHERE id=$id";
    return ejecutar($query, $server, $base, $usr, $pass);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['agregar'])) {
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $descripcion = $_POST['descripcion'];

        if (agregarProducto($nombre, $precio, $cantidad, $descripcion, $server, $base, $usr, $pass)) {
            echo "<p style='color:green;'><strong>Producto agregado correctamente.</strong></p>";
        } else {
            echo "<p style='color:red;'><strong>Error al agregar el producto.</strong></p>";
        }
    }

    if (isset($_POST['actualizar'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $descripcion = $_POST['descripcion'];

        if (actualizarProducto($id, $nombre, $precio, $cantidad, $descripcion, $server, $base, $usr, $pass)) {
            echo "<p style='color:blue;'><strong>Producto actualizado correctamente.</strong></p>";
        } else {
            echo "<p style='color:red;'><strong>Error al actualizar el producto.</strong></p>";
        }
    }

    if (isset($_POST['eliminarCantidad'])) {
        $id = $_POST['idEliminar'];
        $cantidadEliminar = $_POST['cantidadEliminar'];
        eliminarPorCantidad($id, $cantidadEliminar, $server, $base, $usr, $pass);
    }
}

$productos = mostrarProductos($server, $base, $usr, $pass);

$productoEditar = null;
if (isset($_GET['editar'])) {
    $idEditar = $_GET['editar'];
    $query = "SELECT * FROM productos WHERE id=$idEditar";
    $res = seleccionar($query, $server, $base, $usr, $pass);
    if ($res) {
        $productoEditar = $res[0];
    }
}
?>

<h2><?= $productoEditar ? "Editar Producto" : "Agregar Producto" ?></h2>

<form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
    <?php if ($productoEditar): ?>
        <input type="hidden" name="id" value="<?= $productoEditar[0] ?>">
    <?php endif; ?>
    
    Nombre: <input type="text" name="nombre" required value="<?= $productoEditar ? $productoEditar[1] : '' ?>"><br><br>
    Precio: <input type="number" step="0.01" name="precio" required value="<?= $productoEditar ? $productoEditar[2] : '' ?>"><br><br>
    Cantidad: <input type="number" name="cantidad" required value="<?= $productoEditar ? $productoEditar[3] : '' ?>"><br><br>
    Descripción: <input type="text" name="descripcion" value="<?= $productoEditar ? $productoEditar[4] : '' ?>"><br><br>

    <?php if ($productoEditar): ?>
        <input type="submit" name="actualizar" value="Actualizar Producto">
        <a href="<?= $_SERVER['PHP_SELF'] ?>" style="margin-left:10px; color:red; text-decoration:none;">Cancelar</a>
    <?php else: ?>
        <input type="submit" name="agregar" value="Agregar Producto">
    <?php endif; ?>
</form>

<hr>

<h2>Lista de Productos</h2>

<table border="1" cellpadding="5">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Precio</th>
    <th>Cantidad</th>
    <th>Descripción</th>
    <th>Acciones</th>
</tr>

<?php
if ($productos) {
    foreach ($productos as $p) {
        echo "<tr>
            <td>{$p[0]}</td>
            <td>{$p[1]}</td>
            <td>\${$p[2]}</td>
            <td>{$p[3]}</td>
            <td>{$p[4]}</td>
            <td>
                <a href='?editar={$p[0]}'>Editar</a> | 
                <form method='post' style='display:inline;'>
                    <input type='hidden' name='idEliminar' value='{$p[0]}'>
                    <input type='number' name='cantidadEliminar' min='1' max='{$p[3]}' required placeholder='Cantidad'>
                    <input type='submit' name='eliminarCantidad' value='Eliminar'>
                </form>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No hay productos registrados</td></tr>";
}
?>
</table>
