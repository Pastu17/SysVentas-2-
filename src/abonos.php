<?php
include_once "includes/header.php";
include "../conexion.php";
$id_user = $_SESSION['idUser'];
$permiso = "separados";
$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);
if (empty($existe) && $id_user != 1) {
  
}

$nombre_cl = "";
$dire_cliente = "";
$tele_cliente = "";
$ced_cliente = "";
$nom_separado = "";
$pre_separado = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'abonar') {
    $nombre_cliente = $_POST['nombre_cl_abono'];
    $abono = $_POST['abono'];

    if ($action === 'separar') {
        $nombre_cl = isset($_POST['nombre_cl']) ? $_POST['nombre_cl'] : "";
        $dire_cliente = isset($_POST['direccion_cliente']) ? $_POST['direccion_cliente'] : "";
        $tele_cliente = isset($_POST['telefono_cliente']) ? $_POST['telefono_cliente'] : "";
        $ced_cliente = isset($_POST['cedula_cliente']) ? $_POST['cedula_cliente'] : "";
        $nom_separado = isset($_POST['nom_separado']) ? $_POST['nom_separado'] : "";
        $pre_separado = isset($_POST['precio_separado']) ? $_POST['precio_separado'] : "";

        // Modificar la consulta SQL para registrar la fecha de abono
        $query = "UPDATE separados SET abono = abono + ?, precio_separado = precio_separado - ?, fecha_abono = CURRENT_TIMESTAMP WHERE nombre_cl = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "dds", $abono, $abono, $nombre_cliente);
        $result = mysqli_stmt_execute($stmt);

        if ($result_separar) {
            $alert = '<div class="alert alert-success" role="alert">
                Separado registrado correctamente
            </div>';

            $nombre_cl = "";
            $dire_cliente = "";
            $tele_cliente = "";
            $ced_cliente = "";
            $nom_separado = "";
            $pre_separado = "";
        } else {
            $alert = '<div class="alert alert-danger" role="alert">
                Error al registrar el separado: ' . mysqli_error($conexion) . '
            </div>';
        }
    }elseif ($action === 'consultar') {
        $nombre_cl = isset($_POST['nombre_consultar']) ? $_POST['nombre_consultar'] : "";
    
        if (!empty($nombre_cl)) {
            $query_separados = mysqli_query($conexion, "SELECT * FROM separados WHERE nombre_cl = '$nombre_cl'");
            $separado = mysqli_fetch_assoc($query_separados);
    
            if ($separado) {
                $valor_total = $separado['precio_separado'];
                $abonado = $separado['abono'];
                $restante = $valor_total - $abonado;
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                    No se encontró el separado para el nombre especificado
                </div>';
            }
        } else {
            $alert = '<div class="alert alert-danger" role="alert">
                Ingresa un nombre para realizar la consulta
            </div>';
        }
    }
}
if (!empty($_POST)) {
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
$query_separados = mysqli_query($conexion, "SELECT * FROM separados");
?>

<button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#nuevo_separado"><i class="fas fa-plus"></i></button>

<?php echo isset($alert) ? $alert : ''; ?>

<div class="table-responsive">
    <table class="table table-striped table-bordered" id="tbl">
        <thead class="thead-dark">
        <tr>
            <th>Nombre</th>
            <th>Dirección</th>
            <th>Cédula</th>
            <th>Teléfono</th>
            <th>Nom de Separado</th>
            <th>Pre de Separado</th>
            <th>Saldo Pendiente</th> <!-- Nueva columna -->
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
           <?php
           while ($data = mysqli_fetch_assoc($query_separados)) {
            $saldo_pendiente = $data['precio_separado'] - $data['abono'];
           ?>
               <tr>
            <td><?php echo $data['nombre_cl']; ?></td>
            <td><?php echo $data['dire_cliente']; ?></td>
            <td><?php echo $data['ced_cliente']; ?></td>
            <td><?php echo $data['tele_cliente']; ?></td>
            <td><?php echo $data['nomb_separado']; ?></td>
            <td><?php echo number_format($data['precio_separado'], 0, ',', '.'); ?> COP</td>
            <td><?php echo number_format($saldo_pendiente, 0, ',', '.'); ?> COP</td>
            <td>
                <div class="btn-group">
                    <a>
                    <button class="btn btn-primary btn-nuevo-abonar" type="button" data-toggle="modal" data-target="#nuevo_abonar" data-nombre="<?php echo $data['nombre_cl']; ?>">
                     <i class="fas fa-plus"></i>
                     </button>
                    </a>
                    <a href="editar_separado.php?id=<?php echo $data['nombre_cl']; ?>" class="btn btn-success">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="eliminar_separado.php?id=<?php echo $data['id']; ?>" method="post" class="confirmar d-inline">
                        <button class="btn btn-danger btn-action" type="submit">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    <?php } ?>
</tbody>
    </table>
</div>

<div id="nuevo_separado" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo Separado</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="guardar_separado.php" method="post">
                    <div class="form-group">
                        <label for="nombre_cl">Nombre</label>
                        <input type="text" name="nombre_cl" id="nombre_cl" class="form-control" placeholder="Ingrese nombre del cliente" value="">
                    </div>
                    <div class="form-group">
                        <label for="direccion_cliente">Dirección</label>
                        <input type="text" name="direccion_cliente" id="direccion_cliente" class="form-control" placeholder="Ingrese dirección" value="">
                    </div>
                    <div class="form-group">
                        <label for="telefono_cliente">Teléfono</label>
                        <input type="text" name="telefono_cliente" id="telefono_cliente" class="form-control" placeholder="Ingrese teléfono" value="">
                    </div>
                    <div class="form-group">
                        <label for="cedula_cliente">Cédula</label>
                        <input type="text" name="cedula_cliente" id="cedula_cliente" class="form-control" placeholder="Ingrese cédula" value="">
                    </div>
                    <div class="form-group">
                        <label for="nom_separado">Nombre del Proveedor</label>
                        <input type="text" name="nom_separado" id="nom_separado" class="form-control" placeholder="Ingrese nombre del separado" value="">
                    </div>
                    <div class="form-group">
                        <label for="precio_separado">Precio de la Compra</label>
                        <input type="text" name="precio_separado" id="precio_separado" class="form-control" placeholder="Ingrese precio del separado" value="">
                    </div>
                    <input type="hidden" name="action" value="separar">
                    <input type="submit" value="Guardar" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>

<div id="nuevo_abonar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="my-modal-title">Nuevo abono</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="guardar_abono.php" method="post">
                    <div class="form-group">
                        <label for="nombre_cl_abono">Nombre</label>
                        <input type="text" name="nombre_cl_abono" id="nombre_cl_abono" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="abono">Valor del abono</label>
                        <input type="text" name="abono" id="abono" class="form-control" placeholder="Ingrese el abono">
                    </div>
                    <input type="hidden" name="action" value="abonar">
                    <input type="submit" value="Abonar" class="btn btn-primary">
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once "includes/footer.php"; ?>