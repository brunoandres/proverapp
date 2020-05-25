<?php

require_once "../controladores/pedidos.controlador.php";
require_once "../modelos/pedidos.modelo.php";

require_once "../controladores/usuarios.controlador.php";
require_once "../modelos/usuarios.modelo.php";

require_once "../controladores/entregas.controlador.php";
require_once "../modelos/entregas.modelo.php";


class TablaPedidosEntregas{

 	/*=============================================
 	 MOSTRAR LA TABLA DE PEDIDOS
  	=============================================*/

	public function mostrarTablaPedidosEntregas(){

		$item = null;
    	$valor = null;

  		$pedidos = ControladorPedidos::ctrMostrarPedidos($item, $valor);

  		if(count($pedidos) == 0){

  			echo '{"data": []}';

		  	return;
  		}

  		$datosJson = '{
		  "data": [';

		  for($i = 0; $i < count($pedidos); $i++){

			/*=============================================
 	 		SABER SI UN PEDIDO TIENE COMPRA ASIGNADA
			  =============================================*/

			$pedidoAsignado = $pedidos[$i]['entregas_id'];

			$item = "id";
			$tabla = "compras";
			$compras = ModeloEntregas::mdlMostrarEntregas($tabla, $item, $pedidoAsignado);
			//$numeroEntrega = "<button class='btn btn-success'>Solicitud N° ".$compras['numero']."</button>";
			$numeroEntrega = $compras['numero'];

			if(is_null($pedidoAsignado)){
				$numeroEntrega = "<span class='label label-warning'>SIN SOLICITUD</span>";
			}

			//var_dump($compras);

			$item = "id";
			$valor = $pedidos[$i]["estados_id"];
			$estado = ControladorPedidos::ctrMostrarEstados($item, $valor);

			$item = "id";
			$valor = $pedidos[$i]["usuarios_id"];
			$usuario = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);


		  	/*=============================================
 	 		TRAEMOS LAS ACCIONES
  			=============================================*/

		  	$botones =  "<div class='btn-group'><button class='btn btn-primary agregarPedido recuperarBoton' idPedido='".$pedidos[$i]["id"]."'>Agregar</button></div>";

		  	$datosJson .='[
			      "'.$pedidos[$i]["numero"].'",
				  "'.date('d/m/Y', strtotime($pedidos[$i]["fecha_pedido"])).'",
				  "$ '.$pedidos[$i]["importe"].'",
				  "'.$estado["estado"].'",
				  "'.$numeroEntrega.'",
			      "'.$usuario["nombre"].'",
			      "'.$botones.'"
			    ],';

		  }

		  $datosJson = substr($datosJson, 0, -1);

		 $datosJson .=   ']

		 }';

		echo $datosJson;
	}
}
/*=============================================
ACTIVAR TABLA DE PEDIDOS
=============================================*/
$activarPedidosEntregas = new TablaPedidosEntregas();
$activarPedidosEntregas -> mostrarTablaPedidosEntregas();
