<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $recordar = isset($_POST['recordar']);

    $usuario_valido = "admin";
    $password_valido = "12345";

    if ($usuario === $usuario_valido && $password === $password_valido) {
        $_SESSION['usuario'] = $usuario;

        if ($recordar) {
           
            setcookie("usuario", $usuario, time() + (24 * 60), "/");
        } else {
           
            setcookie("usuario", "", time() - 3600, "/");
        }

        
        echo '<script>window.location.href = "inicio.php";</script>';
        exit;
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h3>Inicio de Sesión</h3>

    <form method="POST">
        <label>Usuario:</label><br>
        <input type="text" name="usuario" required><br>
        <label>Contraseña:</label><br>
        <input type="password" name="password" required><br>
        <label><input type="checkbox" name="recordar"> Recordar usuario</label><br>
        <input type="submit" value="Ingresar">
    </form>
</body>
</html>
<?php
}
?>
