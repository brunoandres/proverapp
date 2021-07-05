<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');
class ControladorPedidos{

	/*=============================================
	CARGAR PRESTAMO EN EL SISTEMA ADMINISTRATIVO CORRESPONDIENTE A LOS PEDIDOS
	==============================================*/

	static public function ctrCargarPrestamo(){

		if (isset($_GET["claveAfiliado"])) {

			$claveAfiliado = SED::decryption($_GET["claveAfiliado"]);
			$idPedido = SED::decryption($_GET["ref"]);

			$tabla = "pedidos";
			$item1 = "id";
			$valor1 = $idPedido;
			$item2 = "vinculado";
			$valor2 = "1";

			//COMPROBAR QUE EXISTE EL ASIENTO CARGADO CORRESPONDIENTE AL PEDIDO
			$pedidoPrestamoCargado = ModeloPedidos::consultarPedidoPrestamo($tabla,$item1,$valor1,$item2,$valor2);

			if (empty($pedidoPrestamoCargado)) {
				$fechaPrestamo = date("Y-m-d");
				$pclave = ModeloPedidos::consultaPrestamos();

				foreach ($pclave as $key => $value) {
					if (substr($value['clave_prestamo'],0,4) == date("Y") or substr($value['clave_prestamo'],0,4) == 2017) {
						$clavePrestamo = $value['clave_prestamo'];
					}else{
						$clavePrestamo = date("Y").'00001';
					}
				}

				ini_set('memory_limit', '64M');

				$nroCuotas = $_GET['cuotas'];
				$mes = date('m', strtotime($_GET["fechaPago"]));
				$ano = date('Y', strtotime($_GET["fechaPago"]));
				$vencimiento = $ano.'-'.$mes.'-01';
				$monto = SED::decryption($_GET['montoPrestamo']);
				//$monto = $monto/$nroCuotas;

				$proveedor=0;
				$valePro="";

				$efectivo = "";
				$banco = "";
				$proveduria = "X";
				$lena = "";
				$turismo = "";

				$cuentaBanco = "";
				$chequeNro = 0;

				$anio = date("Y");

				//CONSULTA EL ULTIMO VALE PARA ASIGNAR
				$valesPrestamos = ModeloPedidos::consultaVale();

				if (empty($valesPrestamos)) {
					$vale = $anio."00001";
				}else{
					foreach ($valesPrestamos as $key => $value) {
						$vale = $value["vale"]+1;
					}
				}

				$tabla = "prestamos";
				$observacionestxt= "SISTEMA PROVEEDURIA - PRESTAMO PROVEEDURIA ".date("d/m/Y");

				if($_GET['tipoC']=="M"){
					for ($conteoCuotas=1; $conteoCuotas<=$nroCuotas ;$conteoCuotas++){
					$clavePrestamo = $clavePrestamo + 1;


					//DATOS PARA GUARDAR
					$datos = array("afiliado"=>$claveAfiliado,
								   "fechaPrestamo"=>$_GET["fechaPrestamo"],
								   "clavePrestamo"=>$clavePrestamo,
									 "conteoCuotas"=>$conteoCuotas,
								   "nroCuotas"=>$nroCuotas,
									 "vencimiento"=>$vencimiento,
								   "monto"=>str_replace(",","",$monto),
								   "efectivo"=>$efectivo,
								   "banco"=>$banco,
								   "proveduria"=>$proveduria,
								   "lena"=> $lena,
									 "turismo"=> $turismo,
									 "cuentaBanco"=> $cuentaBanco,
								   "chequeNro"=> $chequeNro,
									 "valePro"=> $valePro,
									 "proveedor"=>$proveedor,
									 "cuotasPro"=>1,
									 "vale"=>$vale,
									 "cuentaMotivo"=>0,
									 "banc"=>'no',
									 "tipe_p"=>$_GET['tipoC'],
									 "tipoPago"=>"",
									 "observaciones"=>$observacionestxt,
								 	 "usuario"=>$_SESSION["usuario"],
									 "idPedido"=>$idPedido);

					$respuesta = ModeloPedidos::guardarPrestamo($tabla,$datos);

					}

					if ($respuesta != "") {

						$tabla = "pedidos";

						/*MODIFICACIONES EN LA INSERCION DEL DESCUENTO EN BASE DEL SOYEM, AGREGO EN LA TABLA PEDIDO LA CLAVE PRESTAMO PARA REFERENCIAR LUEGO EN LA BASE DEL SOYEM EN LA TABLA DE PRESTAMOS Y SABER QUE PRESTAMO PERTENCE A QUE PEDIDO DE PROVEEDURIA*/

						$datosPrestamoProveeduria = null;

						$datosPrestamoProveeduria = array("idPedido" => $idPedido, "nroAsiento" => $respuesta, "clavePrestamo" => $clavePrestamo );

						$actualizarPedido = ModeloPedidos::actualizarPedido($tabla,$datosPrestamoProveeduria);

						/* FIN MODIFICACIONES 18/06/2021 */

						if ($actualizarPedido == "ok") {
							echo'<script>

							swal({
								title: "Prestamo guardado correctamente!",
								text: "Redireccionando...",
								type: "success",
								timer: 2000
							}).then(function() {
									window.location = "pedidos";
							});

							</script>';
							exit();
						}
					}
				}
			}else{
				echo'<script>

				swal({
					title: "El prestamo del pedido ya ha sido guardado en el Sistema Administrativo",
					text: "Redireccionando...",
					type: "warning",
					timer: 4000
				}).then(function() {
						window.location = "pedidos";
				});

				</script>';
				exit();
			}


		}

	}

	/*=============================================
	CAMBIAR ESTADO DE LOS PEDIDOS EN UNA ENTREGA, DESDE LA VISTA DE ENTREGAS
	CAMBIO MASIVO DE TODOS LOS PEDIDOS ASOCIADOS A UNA ENTREGA PARA NO HACERLO UNO POR UNO
	==============================================*/

	static public function ctrEditarEstadosPedidosEntregas(){

		if (isset($_POST["estadoPedidos"])) {

			$tabla = "pedidos";
			$id = $_POST["id"];
			$estado = $_POST["estadoPedidos"];

			$respuesta = ModeloPedidos::mdlEditarEstadosPedidosEntregas($tabla,$id,$estado);

			if ($respuesta == true) {

				echo'<script>

				swal({
					title: "Entrega editada correctamente!",
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
	CAMBIAR ESTADO DE LOS PEDIDOS SELECCIONADOS DESDE
	LA VISTA DE PEDIDOS
	==============================================*/

	static public function ctrCambiarEstadosPedidos(){

		if (isset($_POST["pedidos"])) {
			$respuesta = false;
			$tabla = "pedidos";
			$pedidosSeleccionados = $_POST["pedidos"];
			$valor = $_POST["estadoPedidos"];
			foreach ($pedidosSeleccionados as $key => $value) {

				$pedido = $value;
				$cambiarEstado = ModeloPedidos::mdlCambiarEstadosPedidos($tabla,$pedido,$valor);
				if($cambiarEstado){
					$respuesta = true;
				}
			}

		}

	}

	/*=============================================
	LISTAR PEDIDOS
	=============================================*/

	static public function ctrMostrarPedidos($item, $valor){

		$tabla = "pedidos";

		$respuesta = ModeloPedidos::mdlMostrarPedidos($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	LISTAR PEDIDOS
	=============================================*/

	static public function ctrMostrarPedidosSinEntrega($item, $valor){

		$tabla = "pedidos";

		$respuesta = ModeloPedidos::mdlMostrarPedidosSinEntrega($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	CONTAR CANTIDAD DE PEDIDOS SEGUN ESTADO
	=============================================*/

	static public function ctrMostrarPedidosPorEstado($item, $valor){

		$tabla = "pedidos";

		$respuesta = ModeloPedidos::mdlMostrarPedidosPorEstado($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	LISTAR ESTADOS
	=============================================*/

	static public function ctrMostrarEstados($item, $valor){

		$tabla = "estados";

		$respuesta = ModeloPedidos::mdlMostrarEstados($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	LISTAR METODOS DE PAGO
	=============================================*/

	static public function ctrMostrarMetodos($item, $valor){

		$tabla = "tipos_pagos";

		$respuesta = ModeloPedidos::mdlMostrarMetodos($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	CREAR PEDIDO
	=============================================*/

	static public function ctrCrearPedido(){

		if(isset($_POST["listaProductos"])){

			/*=============================================
			ACTUALIZAR LOS PEDIDOS DEL AFILIADO Y REDUCIR EL STOCK Y AUMENTAR LAS ENTREGAS DE LOS PRODUCTOS
			=============================================*/

			//DEVUELVE UN MSJ DE ERROR SI EL PEDIDO VIENE VACÍO
			if($_POST["listaProductos"] == ""){

				echo'<script>
							swal({
								title: "Agregue al menos un producto para realizar el pedido",
								text: "Redireccionando...",
								type: "error",
								timer: 3000
							}).then(function() {
									window.location = "crear-pedido";
							});

				</script>';

				return;
			}

			/*=============================================
			GUARDAR EL PEDIDO
			=============================================*/

			//VERIFICAR EL TIPO DE PAGO
			if($_POST["metodoPago"] == 1 || $_POST["metodoPago"] == 3){

				$pagoEfectivo = $_POST["pagoEfectivo"];
				$pagoPlanilla = $_POST["pagoPlanilla"];

			}else{
				$comprobante = $_POST['comprobante'];
			}

			$tabla = "pedidos";
			$fecha = str_replace('/', '-', $_POST['fechaPedido']);
			$fechaPedido = date('Y-m-d', strtotime($fecha));

			$fechaDePago = str_replace('/', '-', $_POST['fechaPago']);
			$fechaPago = date('Y-m-d', strtotime($fechaDePago));

			$datos = array("fecha"=>$fechaPedido,
						   "usuario"=>$_POST["idUsuario"],
						   "afiliado"=>$_POST["seleccionarAfiliado"],
						   "productos"=>$_POST["listaProductos"],
							 "total"=>str_replace(",","",$_POST["totalPedido"]),
						   "pagoEfectivo"=>$pagoEfectivo,
						   "pagoPlanilla"=>$pagoPlanilla,
						   "comprobante"=>$comprobante,
						   "metodo_pago"=>$_POST["metodoPago"],
						   "estado"=> $_POST["estadoPedido"],
						   "observaciones"=> $_POST["observaciones"],
						 	"fechaPago"=>$fechaPago);

							 /*var_dump($datos);
							 exit();*/

			$respuesta = ModeloPedidos::mdlIngresarPedido($tabla, $datos);

			if($respuesta == "ok"){

				$listaProductos = json_decode($_POST["listaProductos"], true);

					$totalProductosEntregados = array();

					foreach ($listaProductos as $key => $value) {

						/*=============================================
						ACTUALIZAR LA CANTIDAD DE PRODUCTOS ENTREGADOS
						=============================================*/

						array_push($totalProductosEntregados, $value["cantidad"]);

						$tablaProductos = "productos";

						$item = "id";
						$valor = $value["id"];
						$orden = "id";

						$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);

						$item1a = "entregados";
						$valor1a = $value["cantidad"] + $traerProducto["entregados"];

						$nuevosPedidos = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

						$item1b = "stock";
						$valor1b = $value["stock"];

						$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);

					}

				}
				echo'<script>

				localStorage.removeItem("rango");

				swal({
					title: "Pedido guardado!",
					text: "Redireccionando...",
					type: "success",
					timer: 2000
				}).then(function() {
						window.location = "pedidos";
				});

				</script>';

			}

	}

	/*=============================================
	EDITAR PEDIDO
	=============================================*/

	static public function ctrEditarPedido(){

		if(isset($_POST["numeroPedido"])){

			/*=============================================
			FORMATEAR TABLA DE PRODUCTOS Y LA DE CLIENTES
			=============================================*/
			$tabla = "pedidos";
			$item = "numero";
			$valor = $_POST["numeroPedido"];

			$traerPedido = ModeloPedidos::mdlMostrarPedidos($tabla, $item, $valor);

			/*=============================================
			REVISAR SI VIENE PRODUCTOS EDITADOS
			=============================================*/

			if($_POST["listaProductos"] == ""){

				$listaProductos = $traerPedido["productos"];
				$cambioProducto = false;


			}else{

				$listaProductos = $_POST["listaProductos"];
				$cambioProducto = true;
			}

			//SI HACEMOS ALGUNA MODIFICACION EN EL PEDIDO, SE ACTUALIZA EL STOCK
			if($cambioProducto){

				$productos =  json_decode($traerPedido["productos"], true);

				$totalProductosEntregados = array();

				foreach ($productos as $key => $value) {

					array_push($totalProductosEntregados, $value["cantidad"]);

					$tablaProductos = "productos";

					$item = "id";
					$valor = $value["id"];
					$orden = "id";

					$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);

					$item1a = "entregados";
					$valor1a = $traerProducto["entregados"] - $value["cantidad"];

					$nuevosPedidos = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

					$item1b = "stock";
					$valor1b = $value["cantidad"] + $traerProducto["stock"];

					$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);

				}

				/*=============================================
				REDUCIR EL STOCK Y AUMENTAR LAS ENTREGAS DE LOS PRODUCTOS
				=============================================*/

				$listaProductos_2 = json_decode($listaProductos, true);

				$totalProductosEntregados_2 = array();

				foreach ($listaProductos_2 as $key => $value) {

					array_push($totalProductosEntregados_2, $value["cantidad"]);

					$tablaProductos_2 = "productos";

					$item_2 = "id";
					$valor_2 = $value["id"];
					$orden = "id";

					$traerProducto_2 = ModeloProductos::mdlMostrarProductos($tablaProductos_2, $item_2, $valor_2, $orden);

					$item1a_2 = "entregados";
					$valor1a_2 = $value["cantidad"] + $traerProducto_2["entregados"];

					$nuevosPedidos_2 = ModeloProductos::mdlActualizarProducto($tablaProductos_2, $item1a_2, $valor1a_2, $valor_2);

					$item1b_2 = "stock";
					$valor1b_2 = $traerProducto_2["stock"] - $value["cantidad"];

					$nuevoStock_2 = ModeloProductos::mdlActualizarProducto($tablaProductos_2, $item1b_2, $valor1b_2, $valor_2);

				}

			}

			/*=============================================
			GUARDAR CAMBIOS DEL PEDIDO
			=============================================*/

			//BUSCO SI TIENE PAGOS POR EFECTIVO Y PLANILLA

			$item = "id";
			$valor = $_POST["idPedido"];
			$tabla2 = "pedidos";

			$pagos = ModeloPedidos::mdlMostrarPedidos($tabla2, $item, $valor);

			//VERIFICAR EL TIPO DE PAGO
			if($_POST["metodoPago"] == 1 || $_POST["metodoPago"] == 3){

				$pagoEfectivo = $_POST["pagoEfectivo"];
				$pagoPlanilla = $_POST["pagoPlanilla"];

			}else{
				$comprobante = $_POST['comprobante'];
			}

			$fecha = str_replace('/', '-', $_POST['fechaPedido']);
			$fechaPedido = date('Y-m-d', strtotime($fecha));

			$fechaDePago = str_replace('/', '-', $_POST['fechaPago']);
			$fechaPago = date('Y-m-d', strtotime($fechaDePago));

			$datos = array("idPedido"=>$_POST["idPedido"],
						   "fechaPedido"=>$fechaPedido,
						   "afiliado"=>$_POST["seleccionarAfiliado"],
						   "numero"=>$_POST["numeroPedido"],
						   "productos"=>$listaProductos,
						   "total"=>$_POST["totalPedido"],
						   "metodo"=>$_POST["metodoPago"],
						   "estado"=>$_POST["estadoPedido"],
						   "pagoEfectivo"=>$pagoEfectivo,
						   "pagoPlanilla"=>$pagoPlanilla,
						   "comprobante"=>$comprobante,
						   "usuario"=>$_POST["idUsuario"],
						   "observaciones"=>$_POST["observaciones"],
							 "fechaPago"=>$fechaPago);
			$respuesta = ModeloPedidos::mdlEditarPedido($tabla, $datos);

			/*============================================
			SABER SI EL PEDIDO TIENE UN NUMERO DE COMPRA ASIGNADO
			=============================================*/

			if($respuesta == "ok"){

				echo'<script>

				swal({
					title: "Pedido editado!",
					text: "Redireccionando...",
					type: "success",
					timer: 1000
				}).then(function() {
						window.location = "pedidos";
				});

				</script>';

			}else{
				echo'<script>

				swal({
					title: "Error al actualizar pedido!",
					text: "Redireccionando...",
					type: "error",
					timer: 2000
				}).then(function() {

				});

				</script>';
			}

		}

	}


	/*=============================================
	ELIMINAR PEDIDO
	=============================================*/

	static public function ctrEliminarPedido(){

		if(isset($_GET["idPedido"])){

			$tabla = "pedidos";

			$item = "id";
			$valor = $_GET["idPedido"];

			$traerPedido = ModeloPedidos::mdlMostrarPedidos($tabla, $item, $valor);

			//CONDICIÓN, SI EL PEDIDO PREVIO A SER ELIMINADO, ESTÁ EN ESTADO ENTREGADO
			//NO PODEMOS ACTUALIZAR EL STOCK, YA QUE EN TEORIA, SI FUE ENTREGADO, EL STOCK DEBÍO HABERSE DISMINUIDO
			//Y NO PODEMOS VOLVER A AUMENTARLO, LOS PRODUCTOS YA NO LOS TENEMOS.

			$idEstado = $traerPedido["estados_id"];
			$itemA = "id";
			$valorEstado = self::ctrMostrarEstados($itemA,$idEstado);

			$estadoAnterior = $valorEstado["estado"];

			if($estadoAnterior != "Entregado"){

				/*=============================================
				FORMATEAR TABLA DE PRODUCTOS
				=============================================*/
				$productos =  json_decode($traerPedido["productos"], true);

				$totalProductosEntregados = array();

				foreach ($productos as $key => $value) {

					array_push($totalProductosEntregados, $value["cantidad"]);

					$tablaProductos = "productos";

					$item = "id";
					$valor = $value["id"];
					$orden = "id";

					$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);

					$item1a = "entregados";
					$valor1a = $traerProducto["entregados"] - $value["cantidad"];

					$nuevosPedidos = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

					$item1b = "stock";
					$valor1b = $value["cantidad"] + $traerProducto["stock"];

					$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);

				}

			}

			/*=============================================
			ELIMINAR PEDIDO
			=============================================*/

			$respuesta = ModeloPedidos::mdlEliminarPedido($tabla, $_GET["idPedido"]);

			if($respuesta == "ok"){

				echo'<script>
							swal({
								title: "Pedido eliminado correctamente.",
								text: "Redireccionando...",
								type: "success",
								timer: 2000
							}).then(function() {
									window.location = "pedidos";
							});

				</script>';
			}
		}

	}

	/*=============================================
	RANGO FECHAS
	=============================================*/

	static public function ctrRangoFechasPedidos($fechaInicial, $fechaFinal){

		$tabla = "pedidos";

		$respuesta = ModeloPedidos::mdlRangoFechasPedidos($tabla, $fechaInicial, $fechaFinal);

		return $respuesta;

	}

	/*=============================================
	DESCARGAR EXCEL
	=============================================*/

	public function ctrDescargarReporte(){

		if(isset($_GET["reporte"])){

			$tabla = "pedidos";

			if(isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])){

				$pedidos = ModeloPedidos::mdlRangoFechasPedidos($tabla, $_GET["fechaInicial"], $_GET["fechaFinal"]);

			}else{

				$item = null;
				$valor = null;

				$pedidos = ModeloPedidos::mdlMostrarPedidos($tabla, $item, $valor);

			}


			/*=============================================
			CREAMOS EL ARCHIVO DE EXCEL
			=============================================*/

			$date = date("d/m/Y");
			$nombre = $_GET["reporte"].$date.'.xls';

			header('Expires: 0');
			header('Cache-control: private');
			header("Content-type: application/vnd.ms-excel"); // Archivo de Excel
			header("Cache-Control: cache, must-revalidate");
			header('Content-Description: File Transfer');
			header('Last-Modified: '.date('D, d M Y H:i:s'));
			header("Pragma: public");
			header('Content-Disposition:; filename="'.$nombre.'"');
			header("Content-Transfer-Encoding: binary");

			echo utf8_decode("<table border='0'>

					<tr>
					<td style='font-weight:bold; border:1px solid #eee;'>NÚMERO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>AFILIADO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>USUARIO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>CANTIDAD</td>
					<td style='font-weight:bold; border:1px solid #eee;'>PRODUCTOS</td>
					<td style='font-weight:bold; border:1px solid #eee;'>IMPORTE</td>
					<td style='font-weight:bold; border:1px solid #eee;'>METODO DE PAGO</td
					<td style='font-weight:bold; border:1px solid #eee;'>FECHA</td>
					</tr>");

			foreach ($pedidos as $row => $item){

				$afiliado = ControladorAfiliados::ctrMostrarAfiliados("clave", $item["afiliados_id"]);
				$usuario = ControladorUsuarios::ctrMostrarUsuarios("id", $item["usuarios_id"]);
				$metodo = ControladorPedidos::ctrMostrarMetodos("id", $item["pagos_id"]);

			 echo utf8_decode("<tr>
			 			<td style='border:1px solid #eee;'>".$item["numero"]."</td>
			 			<td style='border:1px solid #eee;'>".$afiliado["nombre"]."</td>
			 			<td style='border:1px solid #eee;'>".$usuario["nombre"]."</td>
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
					<td style='border:1px solid #eee;'>$ ".number_format($item["importe"],2)."</td>
					<td style='border:1px solid #eee;'>".$metodo["detalle"]."</td>
					<td style='border:1px solid #eee;'>".substr($item["fecha_pedido"],0,10)."</td>
		 			</tr>");


			}


			echo "</table>";

		}

	}


	/*=============================================
	SUMA TOTAL VENTAS
	=============================================*/

	static public function ctrSumaTotalPedidos(){

		$tabla = "pedidos";

		$respuesta = ModeloPedidos::mdlSumaTotalPedidos($tabla);

		return $respuesta;

	}

	/*=============================================
	DESCARGAR XML
	=============================================*/

	static public function ctrDescargarXML(){

		if(isset($_GET["xml"])){


			$tabla = "ventas";
			$item = "codigo";
			$valor = $_GET["xml"];

			$pedidos = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

			// PRODUCTOS

			$listaProductos = json_decode($pedidos["productos"], true);

			// CLIENTE

			$tablaClientes = "clientes";
			$item = "id";
			$valor = $pedidos["id_cliente"];

			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $item, $valor);

			// VENDEDOR

			$tablaVendedor = "usuarios";
			$item = "id";
			$valor = $pedidos["id_vendedor"];

			$traerVendedor = ModeloUsuarios::mdlMostrarUsuarios($tablaVendedor, $item, $valor);

			//http://php.net/manual/es/book.xmlwriter.php

			$objetoXML = new XMLWriter();

			$objetoXML->openURI($_GET["xml"].".xml"); //Creación del archivo XML

			$objetoXML->setIndent(true); //recibe un valor booleano para establecer si los distintos niveles de nodos XML deben quedar indentados o no.

			$objetoXML->setIndentString("\t"); // carácter \t, que corresponde a una tabulación

			$objetoXML->startDocument('1.0', 'utf-8');// Inicio del documento

			// $objetoXML->startElement("etiquetaPrincipal");// Inicio del nodo raíz

			// $objetoXML->writeAttribute("atributoEtiquetaPPal", "valor atributo etiqueta PPal"); // Atributo etiqueta principal

			// 	$objetoXML->startElement("etiquetaInterna");// Inicio del nodo hijo

			// 		$objetoXML->writeAttribute("atributoEtiquetaInterna", "valor atributo etiqueta Interna"); // Atributo etiqueta interna

			// 		$objetoXML->text("Texto interno");// Inicio del nodo hijo

			// 	$objetoXML->endElement(); // Final del nodo hijo

			// $objetoXML->endElement(); // Final del nodo raíz


			$objetoXML->writeRaw('<fe:Invoice xmlns:fe="http://www.dian.gov.co/contratos/facturaelectronica/v1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:clm54217="urn:un:unece:uncefact:codelist:specification:54217:2001" xmlns:clm66411="urn:un:unece:uncefact:codelist:specification:66411:2001" xmlns:clmIANAMIMEMediaType="urn:un:unece:uncefact:codelist:specification:IANAMIMEMediaType:2003" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sts="http://www.dian.gov.co/contratos/facturaelectronica/v1/Structures" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.dian.gov.co/contratos/facturaelectronica/v1 ../xsd/DIAN_UBL.xsd urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2 ../../ubl2/common/UnqualifiedDataTypeSchemaModule-2.0.xsd urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2 ../../ubl2/common/UBL-QualifiedDatatypes-2.0.xsd">');

			$objetoXML->writeRaw('<ext:UBLExtensions>');

			foreach ($listaProductos as $key => $value) {

				$objetoXML->text($value["descripcion"].", ");

			}



			$objetoXML->writeRaw('</ext:UBLExtensions>');

			$objetoXML->writeRaw('</fe:Invoice>');

			$objetoXML->endDocument(); // Final del documento

			return true;
		}

	}

}
