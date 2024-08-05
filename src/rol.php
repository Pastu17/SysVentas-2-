<?php
include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser']; // Asigna el valor de $_SESSION['idUser'] a $id_user
$id = mysqli_real_escape_string($conexion, $id_user); // Escapa el valor de $id_user

$permiso = "usuarios";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
    exit; // Agrega esta línea para asegurarte de que no haya más ejecución de código después de la redirección
}

$sqlpermisos = mysqli_query($conexion, "SELECT * FROM permisos");
if (!$sqlpermisos) {
    // Manejo de errores en la conexión o consulta SQL
    die("Error en la consulta SQL: " . mysqli_error($conexion));
}

$usuarios = mysqli_query($conexion, "SELECT * FROM usuario WHERE idusuario = $id");
$consulta = mysqli_query($conexion, "SELECT * FROM detalle_permisos WHERE id_usuario = $id");
$resultUsuario = mysqli_num_rows($usuarios);

if (empty($resultUsuario)) {
    header("Location: usuarios.php");
    exit();
}

$datos = array();
foreach ($consulta as $asignado) {
    $datos[$asignado['id_permiso']] = true;
}

if (isset($_POST['permisos'])) {
    $id_user = $_GET['id']; // Aquí deberías usar $id en lugar de $_GET['id']
    $permisos = $_POST['permisos'];
    mysqli_query($conexion, "DELETE FROM detalle_permisos WHERE id_usuario = $id_user");
    if ($permisos != "") {
        foreach ($permisos as $permiso) {
            $sql = mysqli_query($conexion, "INSERT INTO detalle_permisos(id_usuario, id_permiso) VALUES ($id_user,$permiso)");
            if ($sql == 1) {
                header("Location: rol.php?id=".$id_user."&m=si");
            } else {
                $alert = '<div class="alert alert-primary" role="alert">
                            Error al actualizar permisos
                        </div>';
            }
        }
    }
}
?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header bg-warning text-white">
                Permisos
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <?php if(isset($_GET['m']) && $_GET['m'] == 'si') { ?>
                        <div class="alert alert-success" role="alert">
                            Permisos actualizados
                        </div>
                    <?php } ?>
                    <?php while ($row = mysqli_fetch_assoc($sqlpermisos)) { ?>
                        <div class="form-check form-check-inline m-4">
                            <label for="permisos" class="p-2 text-uppercase"><?php echo $row['nombre']; ?></label>
                            <input id="permisos" type="checkbox" name="permisos[]" value="<?php echo $row['id']; ?>" <?php
                              if (isset($datos[$row['id']])) {
                                  echo "checked";
                              }
                              ?>>
                        </div>
                    <?php } ?>
                    <br>
                    <button class="btn btn-primary btn-block" type="submit">Modificar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>