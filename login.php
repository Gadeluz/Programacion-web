<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("bd.php");

// Si ya hay sesión o cookie activa
if (isset($_SESSION['usuario']) || isset($_COOKIE['usuario'])) {
    $_SESSION['usuario'] = isset($_COOKIE['usuario']) ? $_COOKIE['usuario'] : $_SESSION['usuario'];
    echo "<script>window.location.href='panel.php';</script>";
    exit();
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = $_POST['correo'];
    $pass = $_POST['password'];
    $recordar = isset($_POST['recordar']);

    // Consulta preparada
    $query = $conn->prepare("SELECT * FROM usuarios WHERE correo=?");
    if (!$query) {
        die("Error en la consulta: " . $conn->error);
    }

    $query->bind_param("s", $correo);
    $query->execute();
    $res = $query->get_result();
    $usuarios = $res->fetch_all(MYSQLI_ASSOC);

    if (count($usuarios) > 0) {
        foreach ($usuarios as $user) {
            if (password_verify($pass, $user['contrasena'])) {
                $_SESSION['usuario'] = $user['correo'];

                if ($recordar) {
                    setcookie("usuario", $user['correo'], time() + (86400 * 30), "/"); // 30 días
                }

                echo "<div class='alert alert-success text-center mt-3'>
                        ✅ Inicio de sesión correcto. Redirigiendo al panel...
                      </div>
                      <script>
                        setTimeout(()=>{ window.location.href='panel.php'; }, 1500);
                      </script>";
                exit();
            } else {
                $mensaje = "⚠️ Contraseña incorrecta.";
            }
        }
    } else {
        $mensaje = "❌ No existe una cuenta con ese correo.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Iniciar sesión</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
          <h3 class="text-center mb-4">Inicio de sesión</h3>

          <?php
          if ($mensaje) {
              echo "<div class='alert alert-danger text-center py-2'>$mensaje</div>";
          }
          ?>

          <form method="POST">
            <div class="mb-3">
              <input type="email" name="correo" class="form-control" placeholder="Correo electrónico" required>
            </div>
            <div class="mb-3">
              <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            </div>
            <div class="form-check mb-3">
              <input type="checkbox" class="form-check-input" name="recordar" id="recordar">
              <label for="recordar" class="form-check-label">Recordar sesión</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Entrar</button>
          </form>

          <div class="text-center mt-3">
            <a href="registrar.php" class="text-decoration-none">Crear cuenta</a><br>
            <a href="recuperar.php" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
