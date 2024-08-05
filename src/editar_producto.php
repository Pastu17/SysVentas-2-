<?php
include_once "includes/header.php";
include "../conexion.php";

$id_user = $_SESSION['idUser'];
$permiso = "productos";

$sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
$existe = mysqli_fetch_all($sql);

if (empty($existe) && $id_user != 1) {
    header("Location: permisos.php");
}

if (empty($_GET['id'])) {
    header("Location: productos.php");
} else {
    $id_producto = $_GET['id'];
    if (!is_numeric($id_producto)) {
        header("Location: productos.php");
    }
    $consulta = mysqli_query($conexion, "SELECT * FROM producto WHERE codproducto = $id_producto");
    $data_producto = mysqli_fetch_assoc($consulta);
}

if (!empty($_POST)) {
    $alert = "";
    if (!empty($_POST['nuevo_precio'])) {
        $nuevo_precio = $_POST['nuevo_precio'];
        $id_producto = $_GET['id'];

        $query_update = mysqli_query($conexion, "UPDATE producto SET precio = $nuevo_precio WHERE codproducto = $id_producto");

        if ($query_update) {
            $alert = '<div class="alert alert-success" role="alert">
                        Precio actualizado correctamente
                    </div>';
        } else {
            $alert = '<div class="alert alert-danger" role="alert">
                        Error al actualizar el precio del producto
                    </div>';
        }
        mysqli_close($conexion);
    } else {
        $alert = '<div class="alert alert-danger" role="alert">
                    El campo de nuevo precio es obligatorio
                </div>';
    }
}
?>

<div class="row">
    <div class="col-lg-6 m-auto">
        <div class="card">
            <div class="card-header bg-primary">
                Actualizar Producto
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <?php echo isset($alert) ? $alert : ''; ?>
                    <div class="form-group">
                        <label for="precio">Precio Actual</label>
                        <input type="text" class="form-control" value="<?php echo isset($data_producto['precio']) ? $data_producto['precio'] : ''; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad de Productos Disponibles</label>
                        <input type="number" class="form-control" value="<?php echo isset($data_producto['existencia']) ? $data_producto['existencia'] : ''; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="nuevo_precio">Nuevo Precio</label>
                        <input type="text" placeholder="Ingrese el nuevo precio" name="nuevo_precio" class="form-control" value="<?php echo isset($data_producto['precio']) ? $data_producto['precio'] : ''; ?>">
                    </div>
                    <input type="submit" value="Actualizar" class="btn btn-primary">
                    <a href="productos.php" class="btn btn-danger">Regresar</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>