<?php

$server = "localhost";
$usuario = "root";
$clave = "root"; 
$base = "tiendita";


$conexion = mysqli_connect($server, $usuario, $clave, $base);
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$mensaje = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["recuperar"])) {
    $correo = trim($_POST["correo"]);
    $nueva_contra = trim($_POST["nueva_contra"]);

    if (empty($correo) || empty($nueva_contra)) {
        $mensaje = "<div class='alert alert-danger text-center'>Por favor completa todos los campos.</div>";
    } else {
       
        $sql_check = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt_check = mysqli_prepare($conexion, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "s", $correo);
        mysqli_stmt_execute($stmt_check);
        $resultado = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($resultado) > 0) {
           
            $hash = password_hash($nueva_contra, PASSWORD_DEFAULT);

            
            $sql_update = "UPDATE usuarios SET contrasena = ? WHERE correo = ?";
            $stmt_update = mysqli_prepare($conexion, $sql_update);
            mysqli_stmt_bind_param($stmt_update, "ss", $hash, $correo);

            if (mysqli_stmt_execute($stmt_update)) {
                $mensaje = "<div class='alert alert-success text-center'>✅ Contraseña actualizada correctamente.</div>";
            } else {
                $mensaje = "<div class='alert alert-danger text-center'>Error al actualizar: " . mysqli_error($conexion) . "</div>";
            }

            mysqli_stmt_close($stmt_update);
        } else {
            $mensaje = "<div class='alert alert-warning text-center'>⚠️ No existe una cuenta con ese correo.</div>";
        }

        mysqli_stmt_close($stmt_check);
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <h4 class="card-title text-center mb-4">Recuperar Contraseña</h4>

                    <?php echo $mensaje; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Correo electrónico</label>
                            <input type="email" name="correo" class="form-control" placeholder="usuario@gmail.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="nueva_contra" class="form-control" minlength="6" required>
                        </div>
                        <button type="submit" name="recuperar" class="btn btn-primary w-100">Actualizar Contraseña</button>

                        <div class="text-center mt-3">
                            <a href="login.php">🔙 Volver al inicio de sesión</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
