<?php
session_start();


if (!isset($_SESSION["registro_exitoso"])) {
    header("Location: index.php");
    exit();
}

unset($_SESSION["registro_exitoso"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height:100vh;">

    <div class="card shadow-lg border-0 text-center" style="max-width: 400px;">
        <div class="card-body p-4">
            <h3 class="text-success mb-3"><i class="bi bi-check-circle-fill"></i> ¡Registro Exitoso!</h3>
            <p class="text-muted mb-4">Tu cuenta ha sido creada correctamente. Ahora puedes iniciar sesión.</p>
            <a href="login.php" class="btn btn-primary w-100">Ir a Iniciar Sesión</a>
        </div>
    </div>

   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</body>
</html>
