<?php
ob_start();
error_reporting(E_ERROR);

class imprimirPedido{

public function traerImpresionPedido(){

//REQUERIMOS LA CLASE TCPDF
require_once('tcpdf_include.php');

//GENERO TEMPLATE CON FORMATE DE PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

//MI CICLO PARA RECORRER TODOS LOS PEDIDOS DE LA ENTREGA
for ($i=0; $i <= 2 ; $i++) {


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

  			<td style="background-color:white; width:110px; text-align:center; color:black"><br><br>Pedido N°: <br></td>

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



  			</td>

  			<td style="border: 1px solid #666; background-color:white; width:150px; text-align:right">

  				Fecha:

  			</td>

  		</tr>

  		<tr>

  			<td style="border: 1px solid #666; background-color:white; width:540px">Pedido realizado por:</td>

  		</tr>

  		<tr>

  			<td style="border: 1px solid #666; background-color:white; width:540px">Estado Pedido: </td>

  		</tr>

  		<tr>

  			<td style="border: 1px solid #666; background-color:white; width:540px">Método Pago:</td>

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


  $bloque4 = <<<EOF

  	<table style="font-size:10px; padding:5px 10px;">

  		<tr>

  			<td style="border: 1px solid #666; color:#333; background-color:white; width:260px; text-align:center">

  			</td>

  			<td style="border: 1px solid #666; color:#333; background-color:white; width:80px; text-align:center">

  			</td>

  			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">$

  			</td>

  			<td style="border: 1px solid #666; color:#333; background-color:white; width:100px; text-align:center">$

  			</td>


  		</tr>

  	</table>


  EOF;

  $pdf->writeHTML($bloque4, false, false, false, false, '');



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
  				$
  			</td>

  		</tr>


  	</table>

  EOF;
  ob_end_clean();

  $pdf->writeHTML($bloque5, false, false, false, false, '');
  $pdf->Output("test.pdf", 'D');

}
  // ---------------------------------------------------------
  //SALIDA DEL ARCHIVO




  }

}

$factura = new imprimirPedido();
$factura -> traerImpresionPedido();

?>
