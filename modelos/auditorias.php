<?php

require_once "conexion.php";

class ModeloAuditar{

  static function auditar($accion,$query,$usuario){

    $stmt = Conexion::conectar()->prepare("INSERT INTO auditorias (accion, query, usuario) VALUES ('$accion','$query','$usuario')");
		if($stmt->execute()){

			return true;

		}else{

			//return "error";
      print_r($stmt->errorInfo());
		}

  }

}



?>
