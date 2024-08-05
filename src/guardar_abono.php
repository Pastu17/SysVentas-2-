<?php
session_start();
require("../conexion.php");
$id_user = $_SESSION['idUser'];
$permiso = "clientes";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_cl = $_POST['nombre_cl_abono'];
    $abono = $_POST['abono'];

    // Utilizar una sentencia preparada para evitar inyección SQL
    $query_update = mysqli_prepare($conexion, "UPDATE separados SET abono = abono + ? WHERE nombre_cl = ?");
    mysqli_stmt_bind_param($query_update, "is", $abono, $nombre_cl);
    mysqli_stmt_execute($query_update);

    if (mysqli_stmt_affected_rows($query_update) > 0) {
        header("Location: abonos.php");
    } else {
        echo "Error al guardar el abono";
    }

    mysqli_stmt_close($query_update);
    mysqli_close($conexion);
}
?>