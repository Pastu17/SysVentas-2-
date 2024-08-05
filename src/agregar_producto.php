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
    if (!empty($_POST['cantidad'])) {
        $cantidad = $_POST['cantidad'];
        $producto_id = $_GET['id'];
        $total = $cantidad + $data_producto['existencia'];
        
        // Verificar si la clave 'precio' está definida en $data_producto
        if (isset($data_producto['precio'])) {
            $query_insert = mysqli_query($conexion, "UPDATE producto SET existencia = $total WHERE codproducto = $id_producto");
            
            if ($query_insert) {
                $alert = '<div class="alert alert-success" role="alert">
                            Stock actualizado
                        </div>';
            } else {
                $alert = '<div class="alert alert-danger" role="alert">
                            Error al actualizar el stock
                        </div>';
            }
            mysqli_close($conexion);
        } else {
            $alert = '<div class="alert alert-danger" role="alert">
                            No se encontró el precio del producto
                        </div>';
        }
    } else {
        $alert = '<div class="alert alert-danger" role="alert">
                        La cantidad es obligatoria
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
                        <label for="cantidad">Agregar Cantidad</label>
                        <input type="number" placeholder="Ingrese la cantidad" name="cantidad" id="cantidad" class="form-control">
                    </div>
                    <input type="submit" value="Actualizar" class="btn btn-primary">
                    <a href="productos.php" class="btn btn-danger">Regresar</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "includes/footer.php"; ?>