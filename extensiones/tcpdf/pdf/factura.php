<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
ob_start();
ini_set('display_errors', 0);
error_reporting(0);
require_once "../../../controladores/pedidos.controlador.php";
require_once "../../../modelos/pedidos.modelo.php";

require_once "../../../controladores/afiliados.controlador.php";
require_once "../../../modelos/afiliados.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

class imprimirFactura{

public $codigo;

public function traerImpresionFactura(){

//TRAEMOS LA INFORMACIÓN DEL PEDIDO

$itemPedido = "numero";
$valorPedido = $this->codigo;

$respuestaPedidos = ControladorPedidos::ctrMostrarPedidos($itemPedido, $valorPedido);
$fecha = substr($respuestaPedidos["fecha_pedido"],0);
$fechaPdf = date("d/m/Y", strtotime($fecha));
$productos = json_decode($respuestaPedidos["productos"], true);
$neto = number_format($respuestaPedidos["importe"],2);
$impuesto = number_format($respuestaPedidos["importe"],2);
$total = number_format($respuestaPedidos["importe"],2);

//TRAEMOS LA INFORMACIÓN DEL CLIENTE

$itemAfiliado = "clave";
$valorAfiliado = $respuestaPedidos["afiliados_id"];

$respuestaAfiliado = ControladorAfiliados::ctrMostrarAfiliados($itemAfiliado, $valorAfiliado);

//TRAEMOS LA INFORMACIÓN DEL PAGO

$itemPago = "id";
$valorPago = $respuestaPedidos["pagos_id"];
$respuestaPago = ControladorPedidos::ctrMostrarMetodos($itemPago, $valorPago);

//TRAEMOS LA INFORMACIÓN DEL ESTADO

$itemEstado = "id";
$valorEstado = $respuestaPedidos["estados_id"];
$respuestaEstado = ControladorPedidos::ctrMostrarEstados($itemEstado, $valorEstado);

//TRAEMOS LA INFORMACIÓN DEL VENDEDOR

$itemVendedor = "id";
$valorVendedor = $respuestaPedidos["usuarios_id"];

$respuestaVendedor = ControladorUsuarios::ctrMostrarUsuarios($itemVendedor, $valorVendedor);

//REQUERIMOS LA CLASE TCPDF

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->startPageGroup();

$pdf->AddPage();

// ---------------------------------------------------------

$bloque1 = <<<EOF

	<table>

		<tr>

			<td style="width:150px"><img src="images/logo-blanco-bloque.png"></td>

			<td style="background-color:white; width:140px">

				<div style="font-size:8.5px; text-align:right; line-height:15px;">

					<br>
					Dirección: Av. Ángel Gallardo 1262, San Carlos de Bariloche, Río Negro

				</div>

			</td>

			<td style="background-color:white; width:140px">

				<div style="font-size:8.5px; text-align:right; line-height:15px;">

					<br>
					Teléfono: 0294 442-0293

					<br>
					pedidos@soyembariloche.com.ar

				</div>

			</td>

			<td style="background-color:white; width:110px; text-align:center; color:black"><br><br>Pedido N°: <br>$valorPedido</td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($bloque1, false, false, false, false, '');

// ---------------------------------------------------------

$bloque2 = <<<EOF

	<table>

		<tr>

			<td style="width:540px"><img src="images/back.jpg"></td>

		</tr>

	</table>

	<table style="font-size:10px; padding:5px 10px;">

		<tr>

			<td style="border: 1px solid #666; background-color:white; width:390px">

				Afiliado: $respuestaAfiliado[nombre] | Legajo N°: $respuestaAfiliado[legajo]

			</td>

			<td style="border: 1px solid #666; background-color:white; width:150px; text-align:right">

				Fecha: $fechaPdf

			</td>

		</tr>

		<tr>

			<td style="border: 1px solid #666; background-color:white; width:540px">Pedido realizado por: $respuestaVendedor[nombre]</td>

		</tr>

		<tr>

			<td style="border: 1px solid #666; background-color:white; width:540px">Estado Pedido: $respuestaEstado[estado]</td>

		</tr>

		<tr>

			<td style="border: 1px solid #666; background-color:white; width:540px">Método Pago: $respuestaPago[detalle]</td>

		</tr>

		<tr>

		<td style="border-bottom: 1px solid #666; background-color:white; width:540px"></td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($bloque2, false, false, false, false, '');

// ---------------------------------------------------------

$bloque3 = <<<EOF

	<table style="font-size:10px; padding:5px 10px;">

		<tr>

		<td style="border: 1px solid #666; background-color:white; width:260px; text-align:center">Producto</td>
		<td style="border: 1px solid #666; background-color:white; width:80px; text-align:center">Cantidad</td>
		<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">Valor Unit.</td>
		<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">Valor Total</td>

		</tr>

	</table>

EOF;

$pdf->writeHTML($bloque3, false, false, false, false, '');

// ---------------------------------------------------------

foreach ($productos as $key => $item) {

$itemProducto = "descripcion";
$valorProducto = $item["descripcion"];
$orden = null;

$respuestaProducto = ControladorProductos::ctrMostrarProductos($itemProducto, $valorProducto, $orden);

$valorUnitario = number_format($respuestaProducto["precio"], 2);

$precioTotalCantidad = $item["cantidad"] * $respuestaProducto["precio"];
$precioTotal = number_format($precioTotalCantidad, 2);


$bloque4 = <<<EOF

	<table style="font-size:10px; padding:5px 10px;">

		<tr>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:260px; text-align:center">
				$item[descripcion]
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:80px; text-align:center">
				$item[cantidad]
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">$
				$valorUnitario
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">$
				$precioTotal
			</td>


		</tr>

	</table>


EOF;

$pdf->writeHTML($bloque4, false, false, false, false, '');

}

// ---------------------------------------------------------

$bloque5 = <<<EOF

	<table style="font-size:10px; padding:5px 10px;">

		<tr>

			<td style="color:#333; background-color:white; width:340px; text-align:center"></td>

			<td style="border-bottom: 1px solid #666; background-color:white; width:100px; text-align:center"></td>

			<td style="border-bottom: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center"></td>

		</tr>

		<tr>

			<td style="border-right: 1px solid #666; color:#333; background-color:white; width:340px; text-align:center"></td>

			<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">
				Total:
			</td>

			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">
				$ $total
			</td>

		</tr>


	</table>

EOF;
ob_end_clean();

$pdf->writeHTML($bloque5, false, false, false, false, '');



// ---------------------------------------------------------
//SALIDA DEL ARCHIVO


$file = $respuestaAfiliado["nombre"].'_'.$fecha.'.pdf';

$pdf->Output($file, 'D');

}

}

$factura = new imprimirFactura();
$factura -> codigo = $_GET["codigo"];
$factura -> traerImpresionFactura();

?>
