<?php

require_once "conexion.php";
require_once "auditorias.php";

class ModeloPedidos{


	/*=============================================
	MOSTRAR PEDIDOS
	=============================================*/

	static public function consultarPedidoPrestamo($tabla, $item1, $valor1, $item2, $valor2){

		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item1 = :$item1 AND $item2 = :$item2");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":".$item2, $valor2, PDO::PARAM_STR);

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;

	}

	static public function actualizarPedido($tabla,$idPedido,$nroAsiento){

		$pdo=Conexion::conectar();

		try {
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					$pdo->beginTransaction();

					$stmt = $pdo->prepare("UPDATE $tabla SET fk_nro_asiento = :nro WHERE id = :id");

					$stmt->bindParam(":id", $idPedido, PDO::PARAM_INT);
					$stmt->bindParam(":nro", $nroAsiento, PDO::PARAM_INT);

					$stmt->execute();

				  $pdo->commit();
					$msj = "ok";

			} catch (Exception $e) {
				  $pdo->rollBack();
				  echo "Fallo: " . $e->getMessage();
			}

			return $msj;
			$pdo = null;
			$stmt = null;

	}

	static public function guardarPrestamo($tabla,$datos){

		$pdo=ConexionSoyem::conectarSoyem();

		try {
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					$pdo->beginTransaction();

					$stmt = $pdo->prepare("INSERT INTO $tabla (afiliado, fecha_prestamo, clave_prestamo, cuota, num_cuotas,
						vencimiento, monto, efectivo, banco, proveduria, lena, turismo, cuenta_banco, cheque_nro, vale_pro,
						proveedor, cuotas_pro, vale, cuenta_motivo, banc, tipe_p, observaciones, tipo_pago)
					VALUES (:claveAfiliado, :fechaPrestamo, :clavePrestamo, :cuota, :nroCuotas, :vencimiento,
						:monto, :efectivo, :banco, :proveduria, :lena, :turismo, :cuentaBanco, :chequeNro, :valePro,
						 :proveedor, :cuotasPro, :vale, :cuentaMotivo, :banc, :tipe_p, :observaciones, :tipoPago)");

					$stmt->bindParam(":claveAfiliado", $datos["afiliado"], PDO::PARAM_INT);
					$stmt->bindParam(":fechaPrestamo", $datos["fechaPrestamo"], PDO::PARAM_STR);
					$stmt->bindParam(":clavePrestamo", $datos["clavePrestamo"], PDO::PARAM_STR);
					$stmt->bindParam(":cuota", $datos["conteoCuotas"], PDO::PARAM_INT);
					$stmt->bindParam(":nroCuotas", $datos["nroCuotas"], PDO::PARAM_INT);
					$stmt->bindParam(":vencimiento", $datos["vencimiento"], PDO::PARAM_STR);
					$stmt->bindParam(":monto", $datos["monto"], PDO::PARAM_STR);
					$stmt->bindParam(":efectivo", $datos["efectivo"], PDO::PARAM_STR);
					$stmt->bindParam(":banco", $datos["banco"], PDO::PARAM_STR);
					$stmt->bindParam(":proveduria", $datos["proveduria"], PDO::PARAM_STR);
					$stmt->bindParam(":lena", $datos["lena"], PDO::PARAM_STR);
					$stmt->bindParam(":turismo", $datos["turismo"], PDO::PARAM_STR);
					$stmt->bindParam(":cuentaBanco", $datos["cuentaBanco"], PDO::PARAM_STR);
					$stmt->bindParam(":chequeNro", $datos["chequeNro"], PDO::PARAM_INT);
					$stmt->bindParam(":valePro", $datos["valePro"], PDO::PARAM_STR);
					$stmt->bindParam(":proveedor", $datos["proveedor"], PDO::PARAM_STR);
					$stmt->bindParam(":cuotasPro", $datos["cuotasPro"], PDO::PARAM_STR);
					$stmt->bindParam(":vale", $datos["vale"], PDO::PARAM_STR);
					$stmt->bindParam(":cuentaMotivo", $datos["cuentaMotivo"], PDO::PARAM_STR);
					$stmt->bindParam(":banc", $datos["banc"], PDO::PARAM_STR);
					$stmt->bindParam(":tipe_p", $datos["tipe_p"], PDO::PARAM_STR);
					$stmt->bindParam(":tipoPago", $datos["tipoPago"], PDO::PARAM_STR);
					$stmt->bindParam(":observaciones", $datos["observaciones"], PDO::PARAM_STR);

					//**************** EJECUTA NUEVO PRESTAMO ****************/
					$stmt->execute();


					//AUDITAR

					$query = "INSERT INTO $tabla (afiliado, fecha_prestamo, clave_prestamo, cuota, num_cuotas,vencimiento, monto, efectivo, banco, proveduria, lena, turismo, cuenta_banco, cheque_nro, vale_pro,proveedor, cuotas_pro, vale, cuenta_motivo, banc, tipe_p, observaciones, tipo_pago) VALUES ({$datos['afiliado']}, {$datos['fechaPrestamo']}){$datos['clavePrestamo']}, {$datos['conteoCuotas']}, {$datos['nroCuotas']},{$datos['vencimiento']},{$datos['monto']}, {$datos['efectivo']}, {$datos['banco']}, {$datos['proveduria']}, {$datos['lena']}, {$datos['turismo']}, {$datos['cuentaBanco']},{$datos['chequeNro']},{$datos['valePro']},{$datos['proveedor']}, {$datos['cuotasPro']}, {$datos['vale']}, {$datos['cuentaMotivo']}, {$datos['banc']}, {$datos['tipe_p']}, {$datos['observaciones']}, {$datos['tipoPago']})";

					$accion = "Nuevo prestamo guardado desde proveeduria";
					$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);


					//DATOS AFILIADO
					$clave = $datos["afiliado"];
					$datosAfiliado = ModeloPedidos::datosAfiliado($clave);

					$detalle = "Prestamo de Proveduria al afiliado ".$datosAfiliado['nombre']." legajo ".$datosAfiliado['legajo']. " cuotas ".$datos['nroCuotas']." de $ ".$datos['monto'];
					//$detalle1 = "TEST... INGRESAR ASIENTO NUEVO";

					$ultimoAsiento = ModeloPedidos::consultaUltimoAsiento();
					$nroAsiento = $ultimoAsiento["nro"]+1;

					//INSERTO ASIENTO, DEBE
					$usuario = $datos["usuario"];
					$stmt = $pdo->prepare("INSERT INTO asientos (nro, fecha, cuenta, debe, detalle,
						id_us, activo)
					VALUES (:nroAsiento, :fechaPrestamo, 154, :debe, '$detalle', '$usuario', 'si')");

					$stmt->bindParam(":nroAsiento", $nroAsiento, PDO::PARAM_INT);
 					$stmt->bindParam(":fechaPrestamo", $datos["fechaPrestamo"], PDO::PARAM_STR);
 					$stmt->bindParam(":debe", $datos["monto"], PDO::PARAM_STR);

					//**************** EJECUTA ASIENTO ****************/
					$stmt->execute();


					//AUDITAR

					$query = "INSERT INTO asientos (nro, fecha, cuenta, debe, detalle,id_us, activo) VALUES ({$nroAsiento}, {$datos['fechaPrestamo']}, 154, {$datos['monto']}, {$detalle}, {$usuario})";
					$accion = "Ingreso DEBE en nuevo asiento desde proveeduria";
					$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);


					//INSERTO ASIENTO, HABER

					$stmt = $pdo->prepare("INSERT INTO asientos (nro, fecha, cuenta, haber, detalle,
						id_us, activo)
					VALUES (:nroAsiento, :fechaPrestamo, 180, :haber, '$detalle', '$usuario', 'si')");

					$stmt->bindParam(":nroAsiento", $nroAsiento, PDO::PARAM_INT);
 					$stmt->bindParam(":fechaPrestamo", $datos["fechaPrestamo"], PDO::PARAM_STR);
 					$stmt->bindParam(":haber", $datos["monto"], PDO::PARAM_STR);

					//**************** EJECUTA ASIENTO ****************/
					$stmt->execute();

					//AUDITAR
					$query = "INSERT INTO asientos (nro, fecha, cuenta, haber, detalle,id_us, activo) VALUES ({$nroAsiento}, {$datos['fechaPrestamo']}, 180, {$datos['monto']}, {$detalle}, {$usuario})";
					$accion = "Ingreso HABER en nuevo asiento desde proveeduria";
					$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);

					//**************** Commit de los insert ****************//

				  $pdo->commit();
					$msj = "ok";

			} catch (Exception $e) {
				  $pdo->rollBack();
				  echo "Fallo: " . $e->getMessage();
			}

			//RETORNO NRO ASIENTO
			return $nroAsiento;
			$pdo = null;
			$stmt = null;

	}

	static public function datosAfiliado($clave){

		$stmt = ConexionSoyem::conectarSoyem()->prepare("select * from afiliado where clave = $clave");
		$stmt -> execute();
		return $stmt -> fetch();

	}

	static public function consultaUltimoAsiento(){

		$stmt = ConexionSoyem::conectarSoyem()->prepare("select * from asientos order by nro desc limit 1");
		$stmt -> execute();
		return $stmt -> fetch();

	}

	static public function consultaPrestamos(){

		ini_set('memory_limit', '1024M');

		$stmt = ConexionSoyem::conectarSoyem()->prepare("select * from prestamos order by clave_prestamo ASC");
		$stmt -> execute();
		return $stmt -> fetchAll();

	}

	static public function consultaVale(){

		$anio = date("Y");
		$stmt = ConexionSoyem::conectarSoyem()->prepare("select vale from prestamos where vale like '".$anio."%' order by vale ASC");
		$stmt -> execute();
		return $stmt -> fetchAll();

	}

	/*=============================================
	CAMBIAR ESTADOS DE PEDIDOS MASIVO DESDE MODAL EN ENTREGAS
	=============================================*/
	static public function mdlEditarEstadosPedidosEntregas($tabla,$id,$estado){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estados_id = :estado WHERE entregas_id = :id");

		$stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);

		if($stmt->execute()){
			/*$query = "UPDATE $tabla SET estados_id = $valor WHERE id = $pedido";
			$accion = "Actualizo Estados Pedidos Masivos ID: ".$pedido;
			$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);*/
			return "ok";

		}else{

			return "error";
			//print_r($stmt->errorInfo());

		}

		$stmt->close();
		$stmt = null;

	}

	/*=================================================================
	CAMBIAR ESTADO DE LOS PEDIDOS SELECCIONADOS DESDE LA VISTA PEDIDOS
	=================================================================*/
	static public function mdlCambiarEstadosPedidos($tabla,$pedido,$valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estados_id = :estado WHERE id = :pedido");

		$stmt->bindParam(":estado", $valor, PDO::PARAM_STR);
		$stmt->bindParam(":pedido", $pedido, PDO::PARAM_INT);

		if($stmt->execute()){
			$query = "UPDATE $tabla SET estados_id = $valor WHERE id = $pedido";
			$accion = "Actualizo Estados Pedidos Masivos ID: ".$pedido;
			$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);
			return "ok";

		}else{

			//return "error";
			print_r($stmt->errorInfo());

		}

		$stmt->close();
		$stmt = null;

	}
	/*=============================================
	MOSTRAR ESTADOS
	=============================================*/

	static public function mdlMostrarEstados($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id ASC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR METODOS PAGO
	=============================================*/

	static public function mdlMostrarMetodos($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id ASC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id in (1,3) ORDER BY id ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR PEDIDOS
	=============================================*/

	static public function mdlMostrarPedidosPorEstado($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item AND activo = 1 ORDER BY id DESC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR PEDIDOS
	=============================================*/

	static public function mdlMostrarPedidosSinEntrega($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id ASC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE activo = 1 AND NOT estados_id = 4 AND entregas_id IS NULL ORDER BY id ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR PEDIDOS
	=============================================*/

	static public function mdlMostrarPedidos($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id ASC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE activo = 1 AND NOT estados_id = 4 ORDER BY id ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR PEDIDOS CON NRO DE ASIENTO ASIGNADO
	=============================================*/

	static public function mdlMostrarPedidosConAsiento($tabla, $item, $valor){

		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE activo = 1 AND NOT estados_id = 4 AND NOT fk_nro_asiento = '' ORDER BY id ASC");

		$stmt -> execute();

		return $stmt -> fetchAll();

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	REGISTRO DE PEDIDO
	=============================================*/
	//OBTENER EL ULTIMO MAXIMO NUMERO PARA GENERAR UNO MAYOR
	static public function mdlUltimoNumero(){
		$stmt = Conexion::conectar()->prepare("SELECT max(numero) AS ultimo FROM pedidos WHERE activo = 1");
		$stmt->execute();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
   		$numero = $row["ultimo"];
		}
		return $numero;
	}

	static public function mdlIngresarPedido($tabla, $datos){

		//DEVUELVE EL ULITMO NUMERO DE PEDIDO
		$numero = self::mdlUltimoNumero()+1;
		//SI ES EL PRIMER PEDIDO
		if (empty($numero)) {
			$numero = 1;
		}

		$pdo=Conexion::conectar();

		try {

			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$pdo->beginTransaction();

			$stmt = $pdo->prepare("INSERT INTO $tabla (numero, productos, fecha_pedido,
			importe, pago_efectivo, pago_planilla, comprobante, afiliados_id, usuarios_id, estados_id, pagos_id, observaciones, fecha_pago) VALUES
			($numero, :productos, :fecha, :importe, :pagoEfectivo, :pagoPlanilla, :comprobante, :afiliado, :usuario, :estado, :pago, :observaciones, :fechaPago)");

			//$stmt->bindParam(":numero", self::mdlUltimoNumero(), PDO::PARAM_INT);
			$stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
			$stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_INT);
			$stmt->bindParam(":fechaPago", $datos["fechaPago"], PDO::PARAM_INT);
			$stmt->bindParam(":importe", $datos["total"], PDO::PARAM_STR);
			$stmt->bindParam(":pagoEfectivo", $datos["pagoEfectivo"], PDO::PARAM_STR);
			$stmt->bindParam(":pagoPlanilla", $datos["pagoPlanilla"], PDO::PARAM_STR);
			$stmt->bindParam(":comprobante", $datos["comprobante"], PDO::PARAM_STR);
			$stmt->bindParam(":afiliado", $datos["afiliado"], PDO::PARAM_INT);
			$stmt->bindParam(":pago", $datos["metodo_pago"], PDO::PARAM_INT);
			$stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_INT);
			$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
			$stmt->bindParam(":observaciones", $datos["observaciones"], PDO::PARAM_STR);

			$stmt->execute();

			$pdo->commit();
			$msj = "ok";

			$productos = json_encode($datos["productos"]);
			$query = "INSERT INTO $tabla (numero, productos, fecha_pedido,importe, pago_efectivo, pago_planilla, comprobante, afiliados_id, usuarios_id, estados_id, pagos_id, observaciones) VALUES ({$numero}, '$productos',{$datos['fecha']},{$datos['total']},{$datos['pagoEfectivo']},{$datos['pagoPlanilla']},{$datos['comprobante']},{$datos['afiliado']},{$datos['usuario']},{$datos['estado']},{$datos['metodo_pago']},{$datos['observaciones']})";
			$accion = "Registro Nuevo Pedido N° :".$numero;
			$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);

		}catch (Exception $e) {

			$pdo->rollBack();
			echo "Fallo: " . $e->getMessage();
			$msj = "error";
		}


			return $msj;

			$pdo = null;
			$stmt = null;

	}

	/*=============================================
	EDITAR PEDIDO
	=============================================*/

	static public function mdlEditarPedido($tabla, $datos){

		$pdo=Conexion::conectar();

		try {

			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE $tabla SET  numero = :numero, productos = :productos,
			fecha_pedido = :fecha, importe = :total, pago_efectivo = :pagoEfectivo, pago_planilla= :pagoPlanilla,
			comprobante = :comprobante, afiliados_id = :afiliado, usuarios_id = :usuario, estados_id = :estado, pagos_id = :pago, observaciones = :observaciones, fecha_pago = :fechaPago WHERE id = :pedido");

			$stmt->bindParam(":pago", $datos["metodo"], PDO::PARAM_INT);
			$stmt->bindParam(":numero", $datos["numero"], PDO::PARAM_INT);
			$stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
			$stmt->bindParam(":fecha", $datos["fechaPedido"], PDO::PARAM_STR);
			$stmt->bindParam(":fechaPago", $datos["fechaPago"], PDO::PARAM_STR);
			$stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
			$stmt->bindParam(":total", $datos["total"], PDO::PARAM_STR);
			$stmt->bindParam(":pagoEfectivo", $datos["pagoEfectivo"], PDO::PARAM_STR);
			$stmt->bindParam(":pagoPlanilla", $datos["pagoPlanilla"], PDO::PARAM_STR);
			$stmt->bindParam(":comprobante", $datos["comprobante"], PDO::PARAM_STR);
			$stmt->bindParam(":afiliado", $datos["afiliado"], PDO::PARAM_INT);
			$stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_INT);
			$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
			$stmt->bindParam(":pedido", $datos["idPedido"], PDO::PARAM_INT);
			$stmt->bindParam(":observaciones", $datos["observaciones"], PDO::PARAM_STR);

			$stmt->execute();
			$pdo->commit();

			$msj = "ok";

		}catch (Exception $e) {

			$pdo->rollBack();
			echo "Fallo: " . $e->getMessage();
			$msj = "error";
		}

			$productos = json_encode($datos["productos"]);
			$query = "UPDATE $tabla SET numero = {$datos['numero']}, productos = '$productos' fecha_pedido = {$datos['fechaPedido']}, importe = {$datos['total']}, pago_efectivo = {$datos['pagoEfectivo']}, pago_planilla= {$datos['pagoPlanilla']},comprobante = {$datos['comprobante']}, afiliados_id = {$datos['afiliado']}, usuarios_id = {$datos['usuario']}, estados_id = {$datos['estado']}, pagos_id = {$datos['metodo']}, observaciones = {$datos['observaciones']} WHERE id = {$datos['idPedido']}";
			$accion = "Actualizo Pedido N°: ".$datos["numero"];
			$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);

			return $msj;

			$pdo = null;
			$stmt = null;

	}

	/*=============================================
	ELIMINAR PEDIDO
	=============================================*/

	static public function mdlEliminarPedido($tabla, $datos){

		$pdo=Conexion::conectar();

		try {

			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$pdo->beginTransaction();

			$stmt = $pdo->prepare("UPDATE $tabla SET numero = NULL, entregas_id = NULL, activo = NULL WHERE id = :id");
			$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

			$stmt->execute();
			$pdo->commit();

			$msj = "ok";

			$query = "UPDATE $tabla SET numero=NULL, entregas_id = NULL, activo = NULL WHERE id = $datos";
			$accion = "Elimino Pedido ID: ".$datos;
			$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);


		} catch (Exception $e) {

			$pdo->rollBack();
			echo "Fallo: " . $e->getMessage();
			$msj = "error";

		}

		return $msj;
		$pdo = null;
		$stmt = null;

	}

	/*=============================================
	RANGO FECHAS
	=============================================*/

	static public function mdlRangoFechasPedidos($tabla, $fechaInicial, $fechaFinal){

		if($fechaInicial == null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();


		}else if($fechaInicial == $fechaFinal){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha_pedido like '%$fechaFinal%'");

			$stmt -> bindParam(":fecha_pedido", $fechaFinal, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetchAll();

		}else{

			$fechaActual = new DateTime();
			$fechaActual ->add(new DateInterval("P1D"));
			$fechaActualMasUno = $fechaActual->format("Y-m-d");

			$fechaFinal2 = new DateTime($fechaFinal);
			$fechaFinal2 ->add(new DateInterval("P1D"));
			$fechaFinalMasUno = $fechaFinal2->format("Y-m-d");

			if($fechaFinalMasUno == $fechaActualMasUno){

				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha_pedido BETWEEN '$fechaInicial' AND '$fechaFinalMasUno'");

			}else{


				$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE fecha_pedido BETWEEN '$fechaInicial' AND '$fechaFinal'");

			}

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

	}

	/*=============================================
	SUMAR EL TOTAL DE VENTAS
	=============================================*/

	static public function mdlSumaTotalPedidos($tabla){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(importe) as total FROM $tabla WHERE activo = 1 AND NOT estados_id = 4");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;

	}


}
