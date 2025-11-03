<?php
function seleccionar($query, $server, $base, $usr, $pass) {
    $cnx = mysqli_connect($server, $usr, $pass, $base);
    if (!$cnx) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $resultado = mysqli_query($cnx, $query);
    $datos = [];
    if ($resultado) {
        while ($fila = mysqli_fetch_array($resultado)) {
            $datos[] = $fila;
        }
    }
    mysqli_close($cnx);
    return $datos;
}

function ejecutar($query, $server, $base, $usr, $pass) {
    $cnx = mysqli_connect($server, $usr, $pass, $base);
    if (!$cnx) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $res = mysqli_query($cnx, $query);
    mysqli_close($cnx);
    return $res;
}

function insertar($query, $server, $base, $usr, $pass) {
    return ejecutar($query, $server, $base, $usr, $pass);
}

function eliminarPorCantidad($id, $cantidadEliminar, $server, $base, $usr, $pass) {
    $cnx = mysqli_connect($server, $usr, $pass, $base);
    if (!$cnx) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $query = "SELECT cantidad FROM productos WHERE id=$id";
    $res = mysqli_query($cnx, $query);
    $fila = mysqli_fetch_assoc($res);
    if ($fila) {
        $cantidadActual = $fila['cantidad'];
        if ($cantidadEliminar >= $cantidadActual) {
            $queryDel = "DELETE FROM productos WHERE id=$id";
            mysqli_query($cnx, $queryDel);
            echo "<p style='color:orange;'><strong>Producto eliminado completamente.</strong></p>";
        } else {
            $nuevaCantidad = $cantidadActual - $cantidadEliminar;
            $queryUpd = "UPDATE productos SET cantidad=$nuevaCantidad WHERE id=$id";
            mysqli_query($cnx, $queryUpd);
            echo "<p style='color:orange;'><strong>Se eliminaron $cantidadEliminar unidades. Cantidad restante: $nuevaCantidad.</strong></p>";
        }
    }
    mysqli_close($cnx);
}
?>
