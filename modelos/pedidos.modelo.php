<?php

require_once "conexion.php";
require_once "auditorias.php";

class ModeloPedidos{

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

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id ASC");

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

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id ASC");

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

	static public function mdlMostrarPedidos($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id ASC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE activo = 1 ORDER BY id ASC");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

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

		$numero = self::mdlUltimoNumero()+1;
		//SI ES EL PRIMER PEDIDO
		if (empty($numero)) {
			$numero = 1;
		}
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (numero, productos, fecha_pedido,
		importe, pago_efectivo, pago_planilla, comprobante, afiliados_id, usuarios_id, estados_id, pagos_id, observaciones) VALUES
		($numero, :productos, :fecha, :importe, :pagoEfectivo, :pagoPlanilla, :comprobante, :afiliado, :usuario, :estado, :pago, :observaciones)");

		//$stmt->bindParam(":numero", self::mdlUltimoNumero(), PDO::PARAM_INT);
		$stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_INT);
		$stmt->bindParam(":importe", $datos["total"], PDO::PARAM_STR);
		$stmt->bindParam(":pagoEfectivo", $datos["pagoEfectivo"], PDO::PARAM_STR);
		$stmt->bindParam(":pagoPlanilla", $datos["pagoPlanilla"], PDO::PARAM_STR);
		$stmt->bindParam(":comprobante", $datos["comprobante"], PDO::PARAM_STR);
		$stmt->bindParam(":afiliado", $datos["afiliado"], PDO::PARAM_INT);
		$stmt->bindParam(":pago", $datos["metodo_pago"], PDO::PARAM_INT);
		$stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_INT);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_INT);
		$stmt->bindParam(":observaciones", $datos["observaciones"], PDO::PARAM_STR);

		if ($stmt->execute()) {
			$productos = json_encode($datos["productos"]);
			$query = "INSERT INTO $tabla (numero, productos, fecha_pedido,importe, pago_efectivo, pago_planilla, comprobante, afiliados_id, usuarios_id, estados_id, pagos_id, observaciones) VALUES ({$numero}, '$productos',{$datos['fecha']},{$datos['total']},{$datos['pagoEfectivo']},{$datos['pagoPlanilla']},{$datos['comprobante']},{$datos['afiliado']},{$datos['usuario']},{$datos['estado']},{$datos['metodo_pago']},{$datos['observaciones']})";
			$accion = "Registro Nuevo Pedido N° :".$numero;
			$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);
			return "ok";

		} else {
			//return "error";
			print_r($stmt->errorInfo());
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	EDITAR PEDIDO
	=============================================*/

	static public function mdlEditarPedido($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET  numero = :numero, productos = :productos,
		fecha_pedido = :fecha, importe = :total, pago_efectivo = :pagoEfectivo, pago_planilla= :pagoPlanilla,
		comprobante = :comprobante, afiliados_id = :afiliado, usuarios_id = :usuario, estados_id = :estado, pagos_id = :pago, observaciones = :observaciones WHERE id = :pedido");

		$stmt->bindParam(":pago", $datos["metodo"], PDO::PARAM_INT);
		$stmt->bindParam(":numero", $datos["numero"], PDO::PARAM_INT);
		$stmt->bindParam(":productos", $datos["productos"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha", $datos["fechaPedido"], PDO::PARAM_STR);
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

		if($stmt->execute()){
			$productos = json_encode($datos["productos"]);
			$query = "UPDATE $tabla SET numero = {$datos['numero']}, productos = '$productos' fecha_pedido = {$datos['fechaPedido']}, importe = {$datos['total']}, pago_efectivo = {$datos['pagoEfectivo']}, pago_planilla= {$datos['pagoPlanilla']},comprobante = {$datos['comprobante']}, afiliados_id = {$datos['afiliado']}, usuarios_id = {$datos['usuario']}, estados_id = {$datos['estado']}, pagos_id = {$datos['metodo']}, observaciones = {$datos['observaciones']} WHERE id = {$datos['idPedido']}";
			$accion = "Actualizo Pedido N°: ".$datos["numero"];
			$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);

			return "ok";

		}else{

			return "error";

		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	ELIMINAR PEDIDO
	=============================================*/

	static public function mdlEliminarPedido($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET numero = NULL, entregas_id = NULL, activo = NULL WHERE id = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			$query = "UPDATE $tabla SET numero=NULL, entregas_id = NULL, activo = NULL WHERE id = $datos";
			$accion = "Elimino Pedido ID: ".$datos;
			$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);

			return "ok";

		}else{

			return "error";

		}

		$stmt -> close();

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

		$stmt = Conexion::conectar()->prepare("SELECT SUM(importe) as total FROM $tabla WHERE activo = 1");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;

	}


}
