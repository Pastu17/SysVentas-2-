<?php
session_start();
require("../conexion.php");

$id_user = $_SESSION['idUser'];
$permiso = "eliminar_separado";

$sql = "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = ? AND p.nombre = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "is", $id_user, $permiso);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$existe = mysqli_fetch_all($result);

if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query_delete = "DELETE FROM separados WHERE id = ?";
    $stmt_delete = mysqli_prepare($conexion, $query_delete);
    mysqli_stmt_bind_param($stmt_delete, "i", $id);
    mysqli_stmt_execute($stmt_delete);
    mysqli_stmt_close($stmt_delete);
    mysqli_close($conexion);
    header("Location: abonos.php");
    exit();
} else {
    header("Location: error.php");
    exit();
}
?>