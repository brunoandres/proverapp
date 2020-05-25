<?php
session_start();
require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";

require_once "../controladores/categorias.controlador.php";
require_once "../modelos/categorias.modelo.php";

class TablaProductos{

 	/*=============================================
 	 MOSTRAR LA TABLA DE PRODUCTOS
  	=============================================*/

	public function mostrarTablaProductos(){

		$item = null;
  	$valor = null;
		$botonAcciones = " disabled='disabled'";
		$botonEliminar = " disabled='disabled'";
	  if ($_SESSION["perfil"] == "Administrador"){
	    $botonAcciones = "";
			$botonEliminar = "";
	  }else{
			if($_SESSION["perfil"] == "Pedidos"){
				$botonAcciones = "";
			}
		}
		$productos = ControladorProductos::ctrMostrarProductos($item, $valor);
		if(count($productos) == 0){

			echo '{"data": []}';

			return;
		}

  		$datosJson = '{
		  "data": [';

		  for($i = 0; $i < count($productos); $i++){

			/*=============================================
 	 		TRAEMOS EL ESTADO
			  ============================================*/

			if($productos[$i]["publicado"] == 1){

				$estado = "<span class='label label-success'>Publicado</span>";

			}else{

				$estado = "<span class='label label-danger'>No Publicado</span>";

			}

		  	/*=============================================
 	 		TRAEMOS LA IMAGEN
  			=============================================*/

		  	$imagen = "<img src='".$productos[$i]["imagen"]."' width='60px'>";

		  	/*=============================================
 	 		TRAEMOS LA CATEGORÍA
  			=============================================*/

		  	$item = "id";
		  	$valor = $productos[$i]["categorias_id"];

				$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);

				/*=============================================
	 	 		TRAEMOS LA UNIDAD DE MEDIDA
	  			=============================================*/
				$item = "id";
				$valor = $productos[$i]['medida_id'];
				$unidades = ControladorProductos::ctrMostrarUnidades($item,$valor);

		  	/*=============================================
 	 			STOCK
  			=============================================*/

  			if($productos[$i]["stock"] <= 10){

  				$stock = "<button class='btn btn-danger'>".$productos[$i]["stock"]."</button>";

  			}else if($productos[$i]["stock"] > 11 && $productos[$i]["stock"] <= 15){

  				$stock = "<button class='btn btn-warning'>".$productos[$i]["stock"]."</button>";

  			}else{

  				$stock = "<button class='btn btn-success'>".$productos[$i]["stock"]."</button>";

  			}

		  	/*=============================================
 	 			TRAEMOS LAS ACCIONES
  			=============================================*/

		  	$botones =  "<div class='btn-group'><button '".$botonAcciones."' class='btn btn-warning btnEditarProducto' idProducto='".$productos[$i]["id"]."' data-toggle='modal' data-target='#modalEditarProducto'><i class='fa fa-pencil'></i></button><button '".$botonAcciones."' '".$botonEliminar."' class='btn btn-danger btnEliminarProducto' idProducto='".$productos[$i]["id"]."' codigo='".$productos[$i]["codigo"]."' imagen='".$productos[$i]["imagen"]."'><i class='fa fa-times'></i></button></div>";

		  	$datosJson .='[

				  "'.$productos[$i]["nombre"].'",
				  "'.$productos[$i]["descripcion"].'",
				  "'.$categorias["nombre"].'",
				  "'.$stock.'",
			      "$ '.$productos[$i]["precio"].'",
				  "'.$unidades["nombre"].'",
				  "'.$estado.'",
				  "'.$imagen.'",
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
ACTIVAR TABLA DE PRODUCTOS
=============================================*/
$activarProductos = new TablaProductos();
$activarProductos -> mostrarTablaProductos();
