<?php
require_once '../../conexion.php';
require_once 'fpdf/fpdf.php';

$pdf = new FPDF('P', 'mm', 'letter');
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);
$pdf->SetTitle("Ventas");
$pdf->SetFont('Arial', 'B', 12);

$id = $_GET['v'];
$idcliente = $_GET['cl'];

$config = mysqli_query($conexion, "SELECT * FROM configuracion");
$datos = mysqli_fetch_assoc($config);

$clientes = mysqli_query($conexion, "SELECT * FROM cliente WHERE idcliente = $idcliente");
$datosC = mysqli_fetch_assoc($clientes);

$ventas = mysqli_query($conexion, "SELECT d.*, p.codproducto, p.descripcion FROM detalle_venta d INNER JOIN producto p ON d.id_producto = p.codproducto WHERE d.id_venta = $id");

$pdf->Cell(195, 5, utf8_decode($datos['nombre']), 0, 1, 'C');
$pdf->Image("../../assets/img/logo.jpeg", 170, 15, 30, 20, 'jpeg');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, utf8_decode("Teléfono: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, $datos['telefono'], 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, utf8_decode("Dirección: "), 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, utf8_decode($datos['direccion']), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, "Correo: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(20, 5, utf8_decode($datos['email']), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(20, 5, "Nit: ", 0, 0, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(195, 5, "1059697752-7", 0, 1, 'L');
$pdf->Ln();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode("**NO RESPONSABLE DE IVA**"), 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 5, "Datos del cliente", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(85, 5, utf8_decode('Nombre'), 0, 0, 'L');
$pdf->Cell(35, 5, utf8_decode('Teléfono'), 0, 0, 'L');
$pdf->Cell(45, 5, utf8_decode('Dirección'), 0, 0, 'L');
$pdf->Cell(45, 5, utf8_decode('Cédula'), 0, 1, 'L');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(85, 5, utf8_decode($datosC['nombre']), 0, 0, 'L');
$pdf->Cell(35, 5, utf8_decode($datosC['telefono']), 0, 0, 'L');
$pdf->Cell(45, 5, utf8_decode($datosC['direccion']), 0, 0, 'L');
$pdf->Cell(45, 5, utf8_decode($datosC['cedula']), 0, 1, 'L');

$pdf->Ln(3);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(196, 5, "Detalle de Producto", 1, 1, 'C', 1);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(14, 5, utf8_decode('N°'), 0, 0, 'L');
$pdf->Cell(90, 5, utf8_decode('Descripción'), 0, 0, 'L');
$pdf->Cell(25, 5, 'Cantidad', 0, 0, 'L');
$pdf->Cell(32, 5, 'Precio', 0, 0, 'L');
$pdf->Cell(35, 5, 'Sub Total.', 0, 1, 'L');

$pdf->SetFont('Arial', '', 10);

// Variable para almacenar la suma total de los subtotales
$total = 0;

// Inicializar la variable $contador
$contador = 1;

// Bucle while para imprimir los detalles de los productos
while ($row = mysqli_fetch_assoc($ventas)) {
    $pdf->Cell(14, 5, $contador, 0, 0, 'L');
    $pdf->Cell(90, 5, $row['descripcion'], 0, 0, 'L');
    $pdf->Cell(25, 5, $row['cantidad'], 0, 0, 'L');
    $pdf->Cell(32, 5, '$' . number_format($row['precio'], 2, ',', '.'), 0, 0, 'L'); // Aquí se muestra el precio con COP
    $subtotal = $row['cantidad'] * $row['precio'];
    $pdf->Cell(35, 5, '$' . number_format($subtotal, 2, ',', '.'), 0, 1, 'L'); // Aquí se muestra el subtotal con COP
    $contador++;

    // Sumar al total
    $total += $subtotal;
}

$pdf->Ln(5);

// Imprimir el total al final del detalle de productos
$pdf->Cell(185, 5, "Total: $" . number_format($total, 2, ',', '.'), 0, 1, 'R'); // Aquí se muestra el total con COP

$pdf->SetLineWidth(0.5);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());

$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, utf8_decode("¡Gracias por su compra!"), 0, 1, 'C');

$pdf->Ln();

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, utf8_decode("Fecha y hora de generación de la factura: " . date('Y-m-d H:i:s')), 0, 1, 'C');

$pdf->Output("ventas.pdf", "I");
?>