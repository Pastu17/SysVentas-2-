<?php
include_once "includes/header.php";
require "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "consultar";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
  
}

// Obtener los datos registrados en la base de datos
if (!empty($_POST)) {
    $nombre_cl = $_POST['nombre_cl'];
    $query_separados = mysqli_query($conexion, "SELECT * FROM separados WHERE nombre_cl = '$nombre_cl'");
    $separado = mysqli_fetch_assoc($query_separados);

    if ($separado) {
        $tel_cliente = $separado['tele_cliente'];
        $cedula = $separado['ced_cliente'];
        $dir_cliente = $separado['dire_cliente'];
        $saldo_pendiente = $separado['precio_separado'] - $separado['abono'];
    } else {
        $alert = '<div class="alert alert-danger" role="alert">
                    No se encontró el separado para el nombre especificado
                </div>';
    }
}

?>
<table class="table table-light" id="tbl">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Pre_separado</th>
            <th>abonado</th>
            <th>PDF</th>
        </tr>
    </thead>
    <tbody>
<?php
// Obtener los datos de la tabla correspondiente y mostrarlos en la tabla
$query = mysqli_query($conexion, "SELECT * FROM separados"); // Reemplaza "tu_tabla" con el nombre de tu tabla
while ($row = mysqli_fetch_assoc($query)) {
?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nombre_cl']; ?></td>
                <td><?php echo number_format($row['precio_separado'], 0, ',', '.'); ?> COP</td>
                <td><?php echo number_format($row['abono'], 0, ',', '.'); ?> COP</td>
                <td>
                    <a href="pdf/separado.php?cl=<?php echo isset($row['nombre_cl']) ? $row['nombre_cl'] : ''; ?>&v=<?php echo isset($row['id']) ? $row['id'] : ''; ?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                </td>
            </tr>
<?php
}
?>
    </tbody>
</table>


<?php include_once "includes/footer.php"; ?>