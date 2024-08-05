<?php include_once "includes/header.php";
require "../conexion.php";
$usuarios = mysqli_query($conexion, "SELECT * FROM usuario");
$totalU= mysqli_num_rows($usuarios);
$clientes = mysqli_query($conexion, "SELECT * FROM cliente");
$totalC = mysqli_num_rows($clientes);
$productos = mysqli_query($conexion, "SELECT * FROM producto");
$totalP = mysqli_num_rows($productos);
$ventas = mysqli_query($conexion, "SELECT * FROM ventas");
$totalV = mysqli_num_rows($ventas);
$interface_colors = array(
    "Separados" => "bg-danger",
    "Consultar separados" => "bg-warning",
    "Inventario" => "bg-info",
    "Configuracion" => "bg-success"
);
?>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray">Panel de Administración</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <a class="col-xl-3 col-md-6 mb-4" href="usuarios.php">
            <div class="card border-left-primary shadow h-100 py-2 bg-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Usuarios</div>
                            <div class="h5 mb-0 font-weight-bold text-white"><?php echo $totalU; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        <!-- Earnings (Monthly) Card Example -->
        <a class="col-xl-3 col-md-6 mb-4" href="clientes.php">
            <div class="card border-left-success shadow h-100 py-2 bg-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Clientes</div>
                            <div class="h5 mb-0 font-weight-bold text-white"><?php echo $totalC; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        <!-- Earnings (Monthly) Card Example -->
        <a class="col-xl-3 col-md-6 mb-4" href="productos.php">
            <div class="card border-left-info shadow h-100 py-2 bg-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Productos</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-white"><?php echo $totalP; ?></div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        <!-- Pending Requests Card Example -->
        <a class="col-xl-3 col-md-6 mb-4" href="ventas.php">
            <div class="card border-left-warning bg-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Ventas</div>
                            <div class="h5 mb-0 font-weight-bold text-white"><?php echo $totalV; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a class="col-xl-3 col-md-6 mb-4" href="abonos.php">
            <div class="card border-left-warning <?php echo $interface_colors['Separados']; ?> shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Proveedores</div>
                            <div class="h5 mb-0 font-weight-bold text-white"></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a class="col-xl-3 col-md-6 mb-4" href="consultar.php">
            <div class="card border-left-warning <?php echo $interface_colors['Consultar separados']; ?> shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Consultar Proveedores</div>
                            <div class="h5 mb-0 font-weight-bold text-white"></div>
                        </div>
                        <div class="col-auto">
                          <i class="fas fa-search fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a class="col-xl-3 col-md-6 mb-4" href="inventario.php">
            <div class="card border-left-warning <?php echo $interface_colors['Inventario']; ?> shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Caja</div>
                            <div class="h5 mb-0 font-weight-bold text-white"></div>
                        </div>
                        <div class="col-auto">
                          <i class="fas fa-archive fa-2x text-white-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <a class="col-xl-3 col-md-6 mb-4" href="salir.php">
             <div class="card border-left-warning <?php echo $interface_colors['Configuracion']; ?> shadow h-100 py-2">
                 <div class="card-body">
                     <div class="row no-gutters align-items-center">
                         <div class="col mr-2">
                             <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Salir</div>
                             <div class="h5 mb-0 font-weight-bold text-white"></div>
                         </div>
                         <div class="col-auto">
                             <i class="fas fa-sign-out-alt fa-2x text-white-300"></i>
                         </div>
                     </div>
                 </div>
             </div>
        </a>
            <div class="col-lg-6">
                    <div class="au-card m-b-30">
                        <div class="au-card-inner">
                            <h3 class="title-2 m-b-40">Productos con stock mínimo</h3>
                            <canvas id="sales-chart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
            <div class="au-card m-b-30">
                <div class="au-card-inner">
                    <h3 class="title-2 m-b-40">Productos más vendidos</h3>
                    <canvas id="polarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

<?php include_once "includes/footer.php"; ?>