<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');
ob_start();
ini_set('display_errors', 0);
error_reporting(0);

require_once "../../../controladores/pedidos.controlador.php";
require_once "../../../modelos/pedidos.modelo.php";

require_once "../../../controladores/entregas.controlador.php";
require_once "../../../modelos/entregas.modelo.php";

require_once "../../../controladores/afiliados.controlador.php";
require_once "../../../modelos/afiliados.modelo.php";

require_once "../../../controladores/usuarios.controlador.php";
require_once "../../../modelos/usuarios.modelo.php";

require_once "../../../controladores/productos.controlador.php";
require_once "../../../modelos/productos.modelo.php";

class ImprimirPedidos{

  public $id;

  public function traerImpresionPedido(){

    //REQUERIMOS LA CLASE TCPDF
    require_once('tcpdf_include.php');

    //GENERO TEMPLATE CON FORMATE DE PDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->startPageGroup();



    //TRAIGO LOS PEDIDOS ASIGNADOS A ÉSTA ENTREGA
    $item1 = "entregas_id";
    $valorId = $this->id;
    $pedidosAsignados = ControladorEntregas::ctrMostrarPedidosEntrega($item1,$valorId);

    $item = "id";
    $valor = $this->id;

    $respuesta = ControladorEntregas::ctrMostrarEntregas($item, $valor);
    $numeroEntrega = $respuesta["numero"];


    foreach ($pedidosAsignados as $key => $value) {
      $pdf->AddPage();

      $fecha = substr($value["fecha_pedido"],0);
      $fechadePago = substr($value["fecha_pago"],0);
      $fechaPdf = date("d/m/Y", strtotime($fecha));
      $fechaPago = date("d/m/Y", strtotime($fechadePago));
      $neto = number_format($value["importe"],2);
      $impuesto = number_format($value["importe"],2);
      $total = number_format($value["importe"],2);

      //TRAEMOS LA INFORMACIÓN DEL CLIENTE

      $itemAfiliado = "clave";
      $valorAfiliado = $value["afiliados_id"];

      $respuestaAfiliado = ControladorAfiliados::ctrMostrarAfiliados($itemAfiliado, $valorAfiliado);

      //TRAEMOS LA INFORMACIÓN DEL PAGO

      $itemPago = "id";
      $valorPago = $value["pagos_id"];
      $respuestaPago = ControladorPedidos::ctrMostrarMetodos($itemPago, $valorPago);

      //TRAEMOS LA INFORMACIÓN DEL ESTADO

      $itemEstado = "id";
      $valorEstado = $value["estados_id"];
      $respuestaEstado = ControladorPedidos::ctrMostrarEstados($itemEstado, $valorEstado);

      //TRAEMOS LA INFORMACIÓN DEL VENDEDOR

      $itemVendedor = "id";
      $valorVendedor = $value["usuarios_id"];

      $respuestaVendedor = ControladorUsuarios::ctrMostrarUsuarios($itemVendedor, $valorVendedor);

      $productos = json_decode($value["productos"], true);

      $html = '
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
    					Número de Entrega : '.$numeroEntrega.'

    				</div>

    			</td>



    			<td style="background-color:white; width:110px; text-align:center; color:black"><br><br>Pedido N°: <br>'.$value["numero"].'</td>

    		</tr>

    	</table>';

      $html.= '

      <table>

    		<tr>

    			<td style="width:540px"><img src="images/back.jpg"></td>

    		</tr>

    	</table>

    	<table style="font-size:10px; padding:5px 10px;">

    		<tr>

    			<td style="border: 1px solid #666; background-color:white; width:390px">

  				Afiliado: '.$respuestaAfiliado["nombre"].' |  Legajo: '.$respuestaAfiliado["legajo"].'

    			</td>

    			<td style="border: 1px solid #666; background-color:white; width:150px; text-align:right">

    				Fecha Pedido: '.$fechaPdf.'

    			</td>





    		</tr>
        <tr>
        <td style="border: 1px solid #666; background-color:white; width:540px; text-align:right">

          Fecha de Pago: '.$fechaPago.'

        </td>
        </tr>

    		<tr>

    			<td style="border: 1px solid #666; background-color:white; width:540px">Pedido realizado por: '.$respuestaVendedor["nombre"].'</td>

    		</tr>

    		<tr>

    			<td style="border: 1px solid #666; background-color:white; width:540px">Estado Pedido: &nbsp; Pendiente   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Preparado  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   Entregado &nbsp;&nbsp;&nbsp;</td>

    		</tr>


    		<tr>

    			<td style="border: 1px solid #666; background-color:white; width:540px">Método Pago: '.$respuestaPago["detalle"].'</td>

    		</tr>

    		<tr>

    		<td style="border-bottom: 1px solid #666; background-color:white; width:540px"></td>

    		</tr>

    	</table>

      <table style="font-size:10px; padding:5px 10px;">

    		<tr>

    		<td style="border: 1px solid #666; background-color:white; width:260px; text-align:center">Producto</td>
    		<td style="border: 1px solid #666; background-color:white; width:80px; text-align:center">Cantidad</td>
    		<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">Valor Unit.</td>
    		<td style="border: 1px solid #666; background-color:white; width:100px; text-align:center">Valor Total</td>

    		</tr>

    	</table>

      ';

      foreach ($productos as $key => $item) {
        $itemProducto = "descripcion";
        $valorProducto = $item["descripcion"];
        $orden = null;

        $respuestaProducto = ControladorProductos::ctrMostrarProductos($itemProducto, $valorProducto, $orden);

        $valorUnitario = number_format($respuestaProducto["precio"], 2);

        $precioTotalCantidad = $item["cantidad"] * $respuestaProducto["precio"];
        $precioTotal = number_format($precioTotalCantidad, 2);

        $html.='<table style="font-size:10px; padding:5px 10px;">

      		<tr>

      			<td style="border: 1px solid #666; color:#333; background-color:white; width:260px; text-align:center">
      				'.$item["descripcion"].'
      			</td>

      			<td style="border: 1px solid #666; color:#333; background-color:white; width:80px; text-align:center">
      				'.$item["cantidad"].'
      			</td>

      			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">$
      				'.$valorUnitario.'
      			</td>

      			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">$
      				'.$precioTotal.'
      			</td>


      		</tr>

      	</table>';


      }

      $html.='<table style="font-size:10px; padding:5px 10px;">

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
    				$ '.$total.'
    			</td>

    		</tr>


    	</table>';

      ob_end_clean();
      $pdf->writeHTML($html, false, false, false, false, '');

    }


    $fechaImpresion = date("d/m/Y");
    $file = "ENTREGA_NRO_".$numeroEntrega."_".$fechaImpresion.'_'.'.pdf';
    $pdf->Output($file, 'I');


  }//end function


}//end class

$factura = new ImprimirPedidos();
$factura -> id = $_GET["id"];
$factura -> traerImpresionPedido();

?>
