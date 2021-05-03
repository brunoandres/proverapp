<?php

require_once "conexion.php";
require_once "auditorias.php";

class ModeloEntregas{

	/*================
	SETEAR NULL A LOS PEDIDOS PARA EDITARLOS
	==================*/
	static public function mdlSetNull($tabla, $item, $entrega){

			$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item = NULL WHERE $item = :idEntrega");
			$stmt->bindParam(":idEntrega", $entrega, PDO::PARAM_INT);
			$stmt -> execute();

	}

	/*=============================================
	MOSTRAR CANTIDAD DE PEDIDOS POR ENTREGA EN ESTADO ENTREGADO
	=============================================*/
	static public function mdlMostrarPedidosEntregados($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT count(*) as cantidad FROM $tabla WHERE $item = :$item AND estados_id = 3");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR CANTIDAD DE PEDIDOS POR ENTREGA EN ESTADO PREPARADO
	=============================================*/
	static public function mdlMostrarPedidosPreparados($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT count(*) as cantidad FROM $tabla WHERE $item = :$item AND estados_id = 2");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR CANTIDAD DE PEDIDOS POR ENTREGA
	=============================================*/
	static public function mdlMostrarCantPedidosEntrega($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT count(*) as cantidad FROM $tabla WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR PEDIDOS DE LA ENTREGA PARA EDITAR, BORRA LAS ENTREGAS ID DE LOS PEDIDOS Y EN SALIDA PDF
	=============================================*/

	static public function mdlMostrarPedidosEntrega($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

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
	MOSTRAR ENTREGAS
	=============================================*/

	static public function mdlMostrarEntregas($tabla, $item, $valor){

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
	ACTUALIZAR CADA PEDIDO CON LA COMPRA CORRESPONDIENTE
	=============================================*/

	static public function mdlAsignarPedidos($tabla, $item, $idPedido, $idEntrega){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item= :idEntrega WHERE id = :idPedido");
		$stmt->bindParam(":idEntrega", $idEntrega, PDO::PARAM_INT);
		$stmt->bindParam(":idPedido", $idPedido, PDO::PARAM_INT);
		$stmt->execute();

		$query = "UPDATE $tabla SET $item= {$idEntrega} WHERE id = {$idPedido}";
		$accion = "Setear número entrega a un Pedido ID: ".$idPedido;
		$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);

	}


	/*=============================================
	REGISTRO DE ENTREGA
	=============================================*/

	//OBTENER EL ULTIMO MAXIMO NUMERO PARA GENERAR UNO MAYOR
	static public function mdlUltimoNumero(){
		$stmt = Conexion::conectar()->prepare("SELECT max(numero) AS ultimo FROM entregas WHERE activo = 1");
		$stmt->execute();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
   		$numero = $row["ultimo"];
		}
		return $numero;
	}

	static public function mdlIngresarEntrega($tabla, $datos){

		$numero = self::mdlUltimoNumero()+1;
		//SI ES EL PRIMER PEDIDO
		if (empty($numero)) {
			$numero = 1;
		}

		$conexion = Conexion::conectar();
		$stmt = $conexion->prepare("INSERT INTO $tabla (numero, fecha, usuarios_id, observaciones) VALUES
		($numero, :fecha, :usuario, :observaciones)");

		$stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_INT);
		$stmt->bindParam(":usuario", $datos["usuario"], PDO::PARAM_INT);
		$stmt->bindParam(":observaciones", $datos["observaciones"], PDO::PARAM_STR);

		if ($stmt->execute()) {
			$registroEntrega = $conexion->lastInsertId();

			$query = "INSERT INTO $tabla (numero, fecha, usuarios_id, observaciones) VALUES ($numero, {$datos["fecha"]}, {$datos["usuario"]}, {$datos["observaciones"]})";
			$accion = "Registro Nueva Entrega N° :".$numero;
			$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);

			return $registroEntrega;
		} else {
			//return "error";
			print_r($stmt->errorInfo());
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	EDITAR ENTREGA
	=============================================*/

	static public function mdlEditarEntrega($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET numero = :numero,
		fecha = :fecha, usuarios_id = :usuarios_id, observaciones= :observaciones WHERE id = :entrega");

		$stmt->bindParam(":numero", $datos["numero"], PDO::PARAM_INT);
		$stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
		$stmt->bindParam(":observaciones", $datos["observaciones"], PDO::PARAM_STR);
		$stmt->bindParam(":usuarios_id", $datos["usuario"], PDO::PARAM_INT);
		$stmt->bindParam(":entrega", $datos["idEntrega"], PDO::PARAM_INT);

		if($stmt->execute()){
			$query = "UPDATE $tabla SET numero = {$datos["numero"]},fecha = {$datos["fecha"]}, usuarios_id = {$datos["usuario"]}, observaciones= {$datos["observaciones"]} WHERE id = {$datos["idEntrega"]}";
			$accion = "Actualizar Entrega N° :".$datos["numero"];
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
	ELIMINAR ENTREGA
	=============================================*/

	static public function mdlEliminarEntrega($tabla, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET numero=NULL, activo = NULL WHERE id = :id");

		$stmt -> bindParam(":id", $valor, PDO::PARAM_INT);

		if($stmt -> execute()){
			$query = "UPDATE $tabla SET numero=NULL, activo = NULL WHERE id = :id";
			$accion = "Elimino Entrega ID: ".$valor;
			$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);
			return "ok";

		}else{

			return "error";
			//print_r($stmt->errorInfo());

		}

		$stmt -> close();

		$stmt = null;

	}


	/*=============================================
	PROCESAR ESTADOS DE PEDIDOS EN CANTIDAD
	=============================================*/

	static public function mdlProcesarEstadosPedidos($tabla, $estado, $pedido){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estados_id = :estado WHERE id = :pedido");

		$stmt->bindParam(":estado", $estado, PDO::PARAM_INT);
		$stmt->bindParam(":pedido", $pedido, PDO::PARAM_INT);

		if($stmt->execute()){

			return "ok";

		}else{

			//return "error";
			print_r($stmt->errorInfo());

		}

		$stmt->close();
		$stmt = null;

	}



	/*=============================================
	RANGO FECHAS
	=============================================*/

	static public function mdlRangoFechasEntregas($tabla, $fechaInicial, $fechaFinal){

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
	SUMAR EL TOTAL DE Entregas
	=============================================*/

	static public function mdlSumaTotalEntregas($tabla){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(importe) as total FROM $tabla");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;

	}


}
