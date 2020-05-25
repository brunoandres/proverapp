<?php

require_once "conexion.php";

class ModeloProductos{

	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/

	static public function mdlMostrarProductos($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id DESC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id DESC");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}


	/*=============================================
	MOSTRAR UNIDAD MEDIDA
	=============================================*/

	static public function mdlMostrarUnidades($tabla, $item, $valor){

		if($item != null){

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY id DESC");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}




	/*=============================================
	REGISTRO DE PRODUCTO
	=============================================*/
	static public function mdlIngresarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(codigo, nombre, stock, precio, categorias_id,
		medida_id, descripcion, publicado, imagen) VALUES (:codigo, :nombre, :stock, :precio, :categoria,
		:unidad, :descripcion, :publicado, :imagen)");

		$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_INT);
		$stmt->bindParam(":precio", $datos["precio"], PDO::PARAM_INT);
		$stmt->bindParam(":categoria", $datos["categoria"], PDO::PARAM_STR);
		$stmt->bindParam(":unidad", $datos["unidad"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":publicado", $datos["estado"], PDO::PARAM_INT);
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);

		if($stmt->execute()){

			$query = "INSERT INTO $tabla(codigo, nombre, stock, precio, categorias_id,medida_id, descripcion, publicado, imagen) VALUES ({$datos["codigo"]}, {$datos["nombre"]}, {$datos["stock"]}, {$datos["precio"]},{$datos["categoria"]},{$datos["unidad"]},{$datos["descripcion"]}, {$datos["publicado"]}, {$datos["imagen"]})";
			$accion = "Registro Nuevo Producto";
			$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);

			return "ok";

		}else{

			return "error";
			//print_r($stmt->errorInfo());

		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	EDITAR PRODUCTO
	=============================================*/
	static public function mdlEditarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombre = :nombre,
		descripcion = :descripcion, imagen = :imagen, stock = :stock, precio = :precio, publicado = :estado, medida_id = :unidad
		WHERE id = :id");

		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":unidad", $datos["unidad"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
		$stmt->bindParam(":stock", $datos["stock"], PDO::PARAM_STR);
		$stmt->bindParam(":precio", $datos["precio"], PDO::PARAM_STR);

		if($stmt->execute()){
			$query = "UPDATE $tabla SET nombre = {$datos["nombre"]},descripcion = {$datos["descripcion"]}, imagen = {$datos["imagen"]}, stock = {$datos["stock"]}, precio = {$datos["precio"]}, publicado = {$datos["estado"]}, medida_id = {$datos["unidad"]} WHERE id = {$datos["id"]}";
			$accion = "Actualizo Producto ID: ".$datos["id"];
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
	BORRAR PRODUCTO
	=============================================*/

	static public function mdlEliminarProducto($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			$query = "DELETE FROM $tabla WHERE id = $datos";
			$accion = "Elimino Producto ID: ".$datos;
			$auditar = ModeloAuditar::auditar($accion,$query,$_SESSION["usuario"]);

			return "ok";

		}else{

			return "error";

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	ACTUALIZAR PRODUCTO
	=============================================*/

	static public function mdlActualizarProducto($tabla, $item1, $valor1, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $valor, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";

		}else{

			return "error";

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR SUMA VENTAS
	=============================================*/

	static public function mdlMostrarSumaVentas($tabla){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(ventas) as total FROM $tabla");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;
	}

	/*=============================================
	MOSTRAR SUMA PEDIDOS
	=============================================*/

	static public function mdlMostrarSumaPedidos($tabla){

		$stmt = Conexion::conectar()->prepare("SELECT SUM(importe) as total FROM $tabla");

		$stmt -> execute();

		return $stmt -> fetch();

		$stmt -> close();

		$stmt = null;
	}

}
