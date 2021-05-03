<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";


class TablaProductosPedidos{

 	/*=============================================
 	 MOSTRAR LA TABLA DE PRODUCTOS
	=============================================*/

	public function mostrarTablaProductosPedidos(){

			$item = null;
    	$valor = null;

  		$productos = ControladorProductos::ctrMostrarProductos($item, $valor);

  		if(count($productos) == 0){

  			echo '{"data": []}';

		  	return;
  		}

  		$datosJson = '{
		  "data": [';

		  for($i = 0; $i < count($productos); $i++){

				$disabled = "";
				$title = "";
				$inactivo = $productos[$i]["publicado"];

		  	/*=============================================
 	 			TRAEMOS LA IMAGEN
  			=============================================*/

		  	$imagen = "<img src='".$productos[$i]["imagen"]."' width='40px'>";

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

				if($inactivo != 1){
					$disabled = " disabled = 'disabled'";
					$title = "Inactivo";
				}

		  	$botones =  "<div class='btn-group'><button class='btn btn-primary agregarProducto recuperarBoton' idProducto='".$productos[$i]["id"]."' ".$disabled." ".$title.">Agregar <i class='ion-plus-round'></i></button></div>";

		  	$datosJson .='[
			      "'.($i+1).'",
						"'.$productos[$i]["nombre"].'",
						"'.$productos[$i]["descripcion"].'",
				  	"$ '.$productos[$i]["precio"].'", 
			      "'.$stock.'",
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
$activarProductosVentas = new TablaProductosPedidos();
$activarProductosVentas -> mostrarTablaProductosPedidos();
