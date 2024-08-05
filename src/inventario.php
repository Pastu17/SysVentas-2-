<?php
include_once "includes/header.php";
require_once "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso_ventas = "ventas";
$permiso_abonos = "separados";

// Verificar permisos para ventas
$sql_permisos_ventas = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso_ventas'");
$existe_permisos_ventas = mysqli_fetch_all($sql_permisos_ventas);

// Verificar permisos para abonos
$sql_permisos_abonos = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso_abonos'");
$existe_permisos_abonos = mysqli_fetch_all($sql_permisos_abonos);

if (empty($existe_permisos_ventas) && empty($existe_permisos_abonos) && $id_user != 1) {
    header("Location: permisos.php");
}

// Variables para totalizar
$total_ventas = 0;
$total_abonos = 0;
$total_general = 0;

// Procesar el formulario si se envía
if (!empty($_POST['fecha_consulta'])) {
    $fecha_consulta = $_POST['fecha_consulta'];

    // Consulta para obtener el total de ventas para la fecha seleccionada
    $query_total_ventas = mysqli_query($conexion, "SELECT SUM(total) AS total_ventas FROM ventas WHERE DATE(fecha) = '$fecha_consulta'");
    $row_ventas = mysqli_fetch_assoc($query_total_ventas);
    $total_ventas = $row_ventas['total_ventas'];

    // Consulta para obtener todas las ventas para la fecha seleccionada
    $query_ventas = mysqli_query($conexion, "SELECT v.*, c.idcliente, c.nombre FROM ventas v INNER JOIN cliente c ON v.id_cliente = c.idcliente WHERE DATE(v.fecha) = '$fecha_consulta'");

    // Consulta para obtener el total de abonos para la fecha seleccionada
    $query_total_abonos = mysqli_query($conexion, "SELECT SUM(abono) AS total_abonos FROM separados WHERE DATE(fecha_abono) = '$fecha_consulta'");
    $row_abonos = mysqli_fetch_assoc($query_total_abonos);
    $total_abonos = $row_abonos['total_abonos'];

    // Calcular el total general
    $total_general = $total_ventas + $total_abonos;
}
?>

<div class="container">
    <h2>Total de ventas y proovedores por día</h2>
    <form method="post">
        <div class="form-group">
            <label for="fecha_consulta">Selecciona una fecha:</label>
            <input type="date" class="form-control" id="fecha_consulta" name="fecha_consulta" required>
        </div>
        <button type="submit" class="btn btn-primary">Consultar</button>
    </form>

    <?php if (!empty($_POST['fecha_consulta'])) { ?>
        <div class="mt-4">
            <h3>Total de ventas para <?php echo $_POST['fecha_consulta']; ?>:</h3>
            <p><?php echo "$" . number_format($total_ventas, 2); ?></p>
        </div>

        <div class="mt-4">
            <h3>Total de Proveedores para <?php echo $_POST['fecha_consulta']; ?>:</h3>
            <p><?php echo "$" . number_format($total_abonos, 2); ?></p>
        </div>

        <div class="mt-4">
            <h3>Total general para <?php echo $_POST['fecha_consulta']; ?>:</h3>
            <p><?php echo "$" . number_format($total_general, 2); ?></p>
        </div>

        <hr>

        <h3>Ventas para <?php echo $_POST['fecha_consulta']; ?>:</h3>
        <table class="table table-light" id="tbl">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th>PDF</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $contador = 1;
                while ($row = mysqli_fetch_assoc($query_ventas)) {
                ?>
                    <tr>
                        <td><?php echo $contador; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo "$" . number_format($row['total'], 2); ?></td>
                        <td><?php echo $row['fecha']; ?></td>
                        <td>
                            <a href="pdf/generar.php?cl=<?php echo $row['id_cliente'] ?>&v=<?php echo $row['id'] ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                        </td>
                    </tr>
                <?php
                    $contador++;
                } ?>
            </tbody>
        </table>
    <?php } ?>
</div>

<?php include_once "includes/footer.php"; ?>