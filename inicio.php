<?php
session_start();


if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} elseif (isset($_COOKIE['usuario'])) {
    
    $_SESSION['usuario'] = $_COOKIE['usuario'];
    $usuario = $_COOKIE['usuario'];
} else {
   
    echo '<script>window.location.href = "error.php";</script>';
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
</head>
<body>
    <h3>Bienvenido, <?php echo htmlspecialchars($usuario); ?>.</h3>

    <?php if (isset($_COOKIE['usuario'])): ?>
        <p> Sesión Iniciada.</p>
    <?php else: ?>
        <p>Sesion iniciada con exito</p>
    <?php endif; ?>

    <form method="POST">
        <input type="submit" name="cerrar" value="Cerrar Sesión">
    </form>

    <?php
    if (isset($_POST['cerrar'])) {
     
        session_unset();
        session_destroy();
        setcookie("usuario", "", time() - 3600, "/");

        echo '<script>window.location.href = "index.php";</script>';
        exit;
    }
    ?>
</body>
</html>
