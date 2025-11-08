<?php
session_start();


$server = "localhost";
$usuario = "root";
$clave = "root"; 
$base = "tiendita";

$conexion = mysqli_connect($server, $usuario, $clave, $base);
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$mensaje = ""; 


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["registrar"])) {
    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $contraseña = trim($_POST["contraseña"]);

    if (empty($nombre) || empty($correo) || empty($contraseña)) {
        $mensaje = "<div class='alert alert-danger text-center'>Por favor completa todos los campos.</div>";
    } else {
       
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $mensaje = "<div class='alert alert-danger text-center'>El correo no tiene un formato válido.</div>";
        } else {
           
            $sql_check = "SELECT id FROM usuarios WHERE correo = ?";
            $stmt_check = mysqli_prepare($conexion, $sql_check);
            mysqli_stmt_bind_param($stmt_check, "s", $correo);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);

            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                $mensaje = "<div class='alert alert-warning text-center'>El usuario con este correo ya existe.</div>";
            } else {
           
                $hash = password_hash($contraseña, PASSWORD_DEFAULT);

              
                $sql = "INSERT INTO usuarios (nombre, correo, contrasena) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conexion, $sql);
                mysqli_stmt_bind_param($stmt, "sss", $nombre, $correo, $hash);

                if (mysqli_stmt_execute($stmt)) {
                    
                    $_SESSION["registro_exitoso"] = true;
                    $_SESSION["nombre_registrado"] = $nombre;

                    
                    header("Location: registro_exitoso.php");
                    exit();
                } else {
                    $mensaje = "<div class='alert alert-danger text-center'>Error al registrar: " . mysqli_error($conexion) . "</div>";
                }

                mysqli_stmt_close($stmt);
            }

            mysqli_stmt_close($stmt_check);
        }
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <h4 class="card-title text-center mb-4">Crear cuenta</h4>

                    <?php if (!empty($mensaje)) echo $mensaje; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" name="correo" class="form-control" placeholder="usuario@correo.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="contraseña" class="form-control" minlength="6" required>
                        </div>
                        <button type="submit" name="registrar" class="btn btn-primary w-100">Registrar</button>
                        <div class="text-center mt-3">
                            <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
