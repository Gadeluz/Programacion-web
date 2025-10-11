<?php
$alumnos = ["julian", "petunia", "pedro", "mandril", "pacoweb", "starlord", "bruja", "perras", "dogs", "nintendo"];
$califs = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "NP"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de calificaciones</title>
</head>
<body>
    <h1>Lista de calificaciones</h1>

    <form method="POST" action="index2.php">
        <table>
            <tr>
                <th>Nombre</th>
                <th>Calificación</th>
            </tr>

            <?php foreach ($alumnos as $alumnos): ?>
                <tr>
                    <td><?php echo $alumnos; ?></td>
                    <td>
                        <select name="califs[<?php echo $alumnos; ?>]">
                            <?php foreach ($califs as $c): ?>
                                <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <input target="_blank" type="submit">
    </form>
</body>
</html>













   