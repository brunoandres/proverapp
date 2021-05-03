<?php


class ControladorEntregas{

	/*=============================================
	CREAR NUEVA ENTREGA DE PEDIDOS
	=============================================*/

	static public function ctrCrearEntregaNuevo(){

		if(isset($_POST["procesarEntrega"])){

			if(!is_array($_POST["pedidos"])){

				echo'<script>

			  localStorage.removeItem("rango");

			  swal({
			    title: "Agregue al menos un Pedido para realizar la entrega!",
			    text: "Redireccionando...",
			    type: "warning",
			    timer: 2500
			  }).then(function() {
			      window.location = "crear-entrega";
			  });

			  </script>';
				exit;
			}

			/*=============================================
			GUARDAR LA ENTREGA
			=============================================*/

			$tabla = "entregas";
			$fecha = str_replace('/', '-', $_POST['fechaEntrega']);
			$fechaEntrega = date('Y-m-d', strtotime($fecha));

			$datos = array("fecha"=>$fechaEntrega,
						   "usuario"=>$_POST["idUsuario"],
						   "observaciones"=> $_POST["observaciones"]);

			$respuesta = ModeloEntregas::mdlIngresarEntrega($tabla, $datos);

			$IdRegistroEntrega = $respuesta;

			//RECORRER CADA PEDIDO
			foreach ($_POST['pedidos'] as $key => $value) {

				$tabla = "pedidos";
				$item = "entregas_id";
				$idPedido = $value;
				$asignarPedidoEntrega = ModeloEntregas::mdlAsignarPedidos($tabla, $item, $idPedido, $IdRegistroEntrega);

			}

			if($respuesta != "error"){
				echo'<script>

				swal({
					title: "Entrega guardada correctamente!",
					text: "Redireccionando...",
					type: "success",
					timer: 2000
				}).then(function() {
						window.location = "entregas";
				});

				</script>';
			}
		}

	}

	/*=============================================
	EDITAR ENTREGA
	=============================================*/

	static public function ctrEditarEntrega(){

		if(isset($_POST["editarEntrega"])){

			//ACTUALIZAR TABLA ENTREGAS
			$tabla = "entregas";

			$fecha = str_replace('/', '-', $_POST['fechaEntrega']);
			$fechaEntrega = date('Y-m-d', strtotime($fecha));

			$datos = array("fecha"=>$fechaEntrega,
						   "usuario"=>$_POST["idUsuario"],
						   "numero"=>$_POST["nuevoNumero"],
						   "observaciones"=> $_POST["observaciones"],
						   "idEntrega"=> $_POST["idEntrega"]
						   );

			$respuesta = ModeloEntregas::mdlEditarEntrega($tabla, $datos);

			//SETEAR NULL A LOS PEDIDOS CON ÉSTA ENTREGA
			$tabla = "pedidos";
			$item = "entregas_id";
			$idEntrega = $_POST['idEntrega'];
			$setPedidosNull = ModeloEntregas::mdlSetNull($tabla,$item,$idEntrega);

			$listaPedidos = $_POST['pedidos'];
			foreach ($listaPedidos as $key => $value) {

				$tabla = "pedidos";
				$item = "entregas_id";
				$idPedido = $value;
				$actualizarPedidoEntrega = ModeloEntregas::mdlAsignarPedidos($tabla, $item, $idPedido, $_POST["idEntrega"]);
			}

			if($respuesta == "ok"){

				echo'<script>

				swal({
					title: "Entrega editada!",
					text: "Redireccionando...",
					type: "success",
					timer: 2000
				}).then(function() {
						window.location = "entregas";
				});

				</script>';
				exit();
			}
		}
	}
	/*=============================================
	MOSTRAR PEDIDOS DE LA ENTREGA PARA EDITAR
	=============================================*/

	static public function ctrMostrarPedidosEntrega($item, $valor){

		$tabla = "pedidos";

		$respuesta = ModeloEntregas::mdlMostrarPedidosEntrega($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	MOSTRAR CANTIDAD DE PEDIDOS POR ENTREGA EN ESTADO ENTREGADOS
	=============================================*/

	static public function ctrMostrarPedidosEntregados($item, $valor){

		$tabla = "pedidos";

		$respuesta = ModeloEntregas::mdlMostrarPedidosEntregados($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	MOSTRAR CANTIDAD DE PEDIDOS POR ENTREGA EN ESTADO PREPARADOS
	=============================================*/

	static public function ctrMostrarPedidosPreparados($item, $valor){

		$tabla = "pedidos";

		$respuesta = ModeloEntregas::mdlMostrarPedidosPreparados($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	MOSTRAR CANTIDAD PEDIDOS POR ENTREGA
	=============================================*/

	static public function ctrMostrarCantPedidosEntrega($item, $valor){

		$tabla = "pedidos";

		$respuesta = ModeloEntregas::mdlMostrarCantPedidosEntrega($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	PROCESAR ESTADOS DE LOS PEDIDOS EN CANTIDAD
	=============================================*/

	static public function ctrProcesarEstadosPedidos($item, $valor){

		$tabla = "pedidos";

		$respuesta = ModeloEntregas::mdlProcesarEstadosPedidos($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	LISTAR ENTREGAS
	=============================================*/

	static public function ctrMostrarEntregas($item, $valor){

		$tabla = "entregas";

		$respuesta = ModeloEntregas::mdlMostrarEntregas($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	ELIMINAR ENTREGA / DEJA EN NULL EL NUMERO DE ENTREGA, Y A LA VEZ
	DEJA EN NULL A LOS PEDIDOS ASOCIADOS PARA GENERAR LUEGO NUEVA ENTREGA
	=============================================*/

	static public function ctrEliminarEntrega(){

		if(isset($_GET["idEntrega"])){

			$tabla = "entregas";

			$item = "id";
			$valor = $_GET["idEntrega"];
			/*=============================================
			ELIMINAR ENTREGA
			=============================================*/

			//TRAIGO LOS PEDIDOS ASIGNADOS A ÉSTA ENTREGA
			$item1 = "entregas_id";
			$pedidosAsignados = ControladorEntregas::ctrMostrarPedidosEntrega($item1,$valor);

			$respuesta = ModeloEntregas::mdlEliminarEntrega($tabla, $valor);

			if($respuesta == "ok"){

				foreach ($pedidosAsignados as $key => $value) {
					//SETEAR NULL A LOS PEDIDOS CON ÉSTA ENTREGA
					$tabla = "pedidos";
					$item = "entregas_id";
					$setPedidosNull = ModeloEntregas::mdlSetNull($tabla,$item,$valor);
				}

				echo'<script>

					swal({
						title: "La solicitud de entrega ha sido borrado correctamente",
						text: "Redireccionando...",
						type: "success",
						timer: 2000
					}).then(function() {
							window.location = "entregas";
					});

				</script>';
				exit();
			}
		}

	}

	/*=============================================
	RANGO FECHAS
	=============================================*/

	static public function ctrRangoFechasPedidos($fechaInicial, $fechaFinal){

		$tabla = "pedidos";

		$respuesta = ModeloEntregas::mdlRangoFechasPedidos($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;

	}

	/*=============================================
	DESCARGAR EXCEL
	=============================================*/

	public function ctrDescargarReporte(){

		if(isset($_GET["reporte"])){

			$tabla = "entregas";

			if(isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])){

				$entregas = ModeloEntregas::mdlRangoFechasentregas($tabla, $_GET["fechaInicial"], $_GET["fechaFinal"]);

			}else{

				$item = null;
				$valor = null;

				$entregas = ModeloEntregas::mdlMostrarPedidos($tabla, $item, $valor);

			}


			/*=============================================
			CREAMOS EL ARCHIVO DE EXCEL
			=============================================*/

			$Name = $_GET["reporte"].'.xls';

			header('Expires: 0');
			header('Cache-control: private');
			header("Content-type: application/vnd.ms-excel"); // Archivo de Excel
			header("Cache-Control: cache, must-revalidate");
			header('Content-Description: File Transfer');
			header('Last-Modified: '.date('D, d M Y H:i:s'));
			header("Pragma: public");
			header('Content-Disposition:; filename="'.$Name.'"');
			header("Content-Transfer-Encoding: binary");

			echo utf8_decode("<table border='0'>

					<tr>
					<td style='font-weight:bold; border:1px solid #eee;'>CÓDIGO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>CLIENTE</td>
					<td style='font-weight:bold; border:1px solid #eee;'>VENDEDOR</td>
					<td style='font-weight:bold; border:1px solid #eee;'>CANTIDAD</td>
					<td style='font-weight:bold; border:1px solid #eee;'>PRODUCTOS</td>
					<td style='font-weight:bold; border:1px solid #eee;'>IMPUESTO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>NETO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>
					<td style='font-weight:bold; border:1px solid #eee;'>METODO DE PAGO</td
					<td style='font-weight:bold; border:1px solid #eee;'>FECHA</td>
					</tr>");

			foreach ($entregas as $row => $item){

				$cliente = ControladorClientes::ctrMostrarClientes("id", $item["id_cliente"]);
				$vendedor = ControladorUsuarios::ctrMostrarUsuarios("id", $item["id_vendedor"]);

			 echo utf8_decode("<tr>
			 			<td style='border:1px solid #eee;'>".$item["codigo"]."</td>
			 			<td style='border:1px solid #eee;'>".$cliente["nombre"]."</td>
			 			<td style='border:1px solid #eee;'>".$vendedor["nombre"]."</td>
			 			<td style='border:1px solid #eee;'>");

			 	$productos =  json_decode($item["productos"], true);

			 	foreach ($productos as $key => $valueProductos) {

			 			echo utf8_decode($valueProductos["cantidad"]."<br>");
			 		}

			 	echo utf8_decode("</td><td style='border:1px solid #eee;'>");

		 		foreach ($productos as $key => $valueProductos) {

		 			echo utf8_decode($valueProductos["descripcion"]."<br>");

		 		}

		 		echo utf8_decode("</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["impuesto"],2)."</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["neto"],2)."</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["total"],2)."</td>
					<td style='border:1px solid #eee;'>".$item["metodo_pago"]."</td>
					<td style='border:1px solid #eee;'>".substr($item["fecha"],0,10)."</td>
		 			</tr>");


			}


			echo "</table>";

		}

	}


	/*=============================================
	SUMA TOTAL entregas
	=============================================*/

	static public function ctrSumaTotalEntregas(){

		$tabla = "entregas";

		$respuesta = ModeloEntregas::mdlSumaTotalEntregas($tabla);

		return $respuesta;

	}

}
