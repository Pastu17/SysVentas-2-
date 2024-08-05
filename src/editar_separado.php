<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "editar_separados";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}
if (!empty($_POST)) {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'separar') {
            $nombre_cl = isset($_POST['nombre_cl']) ? $_POST['nombre_cl'] : "";
            $dire_cliente = isset($_POST['direccion_cliente']) ? $_POST['direccion_cliente'] : "";
            $tele_cliente = isset($_POST['telefono_cliente']) ? $_POST['telefono_cliente'] : "";
            $ced_cliente = isset($_POST['cedula_cliente']) ? $_POST['cedula_cliente'] : "";
            $nomb_cliente_upd = isset($_POST['nombre_cl']) ? $_POST['nombre_cl'] : "";
            $nom_separado = isset($_POST['nom_separado']) ? $_POST['nom_separado'] : "";
            $pre_separado = isset($_POST['precio_separado']) ? $_POST['precio_separado'] : "";

            $query_update = mysqli_query($conexion, "UPDATE separados SET nombre_cl = '$nombre_cl', dire_cliente = '$dire_cliente', tele_cliente = '$tele_cliente', ced_cliente = '$ced_cliente', nomb_separado = '$nom_separado', precio_separado = '$pre_separado' WHERE nombre_cl = '$nomb_cliente_upd'");

            if ($query_update) {
                $alert = '<div class="alert alert-success" role="alert">
                            Separado modificado correctamente
                          </div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                            Error al modificar el separado: ' . mysqli_error($conexion) . '
                          </div>';
            }
        }
    }
}

if (empty($_GET['id'])) {
    header("Location: abonos.php");
} else {
    $nomb_cliente = $_GET['id'];
    $query_separado = mysqli_query($conexion, "SELECT * FROM separados WHERE nombre_cl = '$nomb_cliente'");
    $result_separado = mysqli_num_rows($query_separado);

    if ($result_separado > 0) {
        $data_separado = mysqli_fetch_assoc($query_separado);
        $nombre_cl = $data_separado['nombre_cl'];
        $dire_cliente = $data_separado['dire_cliente'];
        $tele_cliente = $data_separado['tele_cliente'];
        $ced_cliente = $data_separado['ced_cliente'];
        $nom_separado = $data_separado['nomb_separado'];
        $pre_separado = $data_separado['precio_separado'];
    } else {
        header("Location: abonos.php");
    }
}
?>

<div class="row">
    <div class="col-lg-6 m-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                Modificar separado
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="nombre_cl">Nombre</label>
                        <input type="text" name="nombre_cl" id="nombre_cl" class="form-control" placeholder="Ingrese nombre del cliente" value="<?php echo isset($nombre_cl) ? $nombre_cl : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="direccion_cliente">Dirección</label>
                        <input type="text" name="direccion_cliente" id="direccion_cliente" class="form-control" placeholder="Ingrese dirección" value="<?php echo isset($dire_cliente) ? $dire_cliente : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="telefono_cliente">Teléfono</label>
                        <input type="text" name="telefono_cliente" id="telefono_cliente" class="form-control" placeholder="Ingrese teléfono" value="<?php echo isset($tele_cliente) ? $tele_cliente : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="cedula_cliente">Cédula</label>
                        <input type="text" name="cedula_cliente" id="cedula_cliente" class="form-control" placeholder="Ingrese cédula" value="<?php echo isset($ced_cliente) ? $ced_cliente : ''; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nom_separado">Nombre del Separado</label>
                        <input type="text" name="nom_separado" id="nom_separado" class="form-control" placeholder="Ingrese nombre del separado" value="<?php echo isset($nom_separado) ? $nom_separado : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="precio_separado">Precio del Separado</label>
                        <input type="text" name="precio_separado" id="precio_separado" class="form-control" placeholder="Ingrese precio del separado" value="<?php echo isset($pre_separado) ? $pre_separado : ''; ?>">
                    </div>
                    <input type="hidden" name="action" value="separar">
                    <input type="submit" value="Actualizar Separado" class="btn btn-primary">
                    <a href="abonos.php" class="btn btn-danger">Atrás</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>