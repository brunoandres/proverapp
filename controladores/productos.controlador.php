<?php

class ControladorProductos{

	/*=============================================
	MOSTRAR PRODUCTOS
	=============================================*/

	static public function ctrMostrarProductos($item, $valor){

		$tabla = "productos";

		$respuesta = ModeloProductos::mdlMostrarProductos($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	MOSTRAR UNIDAD DE MEDIDA
	=============================================*/
	static public function ctrMostrarUnidades($item, $valor){

		$tabla = "unidad_medida";

		$respuesta = ModeloProductos::mdlMostrarUnidades($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	CREAR NUEVO PRODUCTO
	=============================================*/

	static public function ctrCrearProducto($url){

		if(isset($_POST["nombre"])){

			if(preg_match('/^[,.\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["descripcion"]) &&
			   preg_match('/^[0-9]+$/', $_POST["stock"]) &&
			   preg_match('/^[0-9.,]+$/', $_POST["precio"]) &&
			   preg_match('/^[,.\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nombre"])){

		   		/*=============================================
					VALIDAR IMAGEN
					=============================================*/

			   	$ruta = "vistas/img/productos/default/anonymous.png";

			   	if(isset($_FILES["imagen"]["tmp_name"])){

					list($ancho, $alto) = getimagesize($_FILES["imagen"]["tmp_name"]);

					$nuevoAncho = 500;
					$nuevoAlto = 500;

					/*=============================================
					CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL PRODUCTO
					=============================================*/

					$directorio = "vistas/img/productos/".$_POST["codigo"];

					if(!file_exists($directorio)){
						mkdir($directorio, 0755);
					}

					/*=============================================
					DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
					=============================================*/

					if($_FILES["imagen"]["type"] == "image/jpeg"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["codigo"]."/".$aleatorio.".jpg";

						$origen = imagecreatefromjpeg($_FILES["imagen"]["tmp_name"]);

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagejpeg($destino, $ruta);

					}

					if($_FILES["imagen"]["type"] == "image/png"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["codigo"]."/".$aleatorio.".png";

						$origen = imagecreatefrompng($_FILES["imagen"]["tmp_name"]);

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagepng($destino, $ruta);

					}

				}

				$tabla = "productos";

				//CHECK PARA VALIDAR SI EL PRODUCTO ESTÁ ACTIVO/HABILITADO
				if(!empty($_POST['publicado'])){
					$estado = 1;
				} else {
					$estado = 0;
				}

				$datos = array("categoria" => $_POST["categoria"],
							   "nombre" => $_POST["nombre"],
							   "codigo" => $_POST["codigo"],
							   "descripcion" => $_POST["descripcion"],
							   "stock" => $_POST["stock"],
							   "precio" => $_POST["precio"],
							   "unidad" => $_POST["medida"],
							   "estado" => $estado,
							   "imagen" => $ruta);

				$respuesta = ModeloProductos::mdlIngresarProducto($tabla, $datos);

				if($respuesta == "ok"){

					$_SESSION['flag'] = true;

						echo'<script>

						swal({
						  title: "Producto guardado!",
						  text: "Redireccionando...",
						  type: "success",
						  timer: 750
						}).then(function() {
						    window.location = "'.$url.'";
						});

						</script>';

				}


			}else{


				echo'<script>

					swal({
						  type: "error",
						  title: "¡El producto no puede ir con los campos vacíos o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {


							}
						})

			  	</script>';
			}
		}

	}

	/*=============================================
	EDITAR PRODUCTO
	=============================================*/

	static public function ctrEditarProducto(){

		if(isset($_POST["editarDescripcion"])){

			if(preg_match('/^[,.\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"]) &&
			   preg_match('/^[,.0-9]+$/', $_POST["editarStock"]) &&
			   preg_match('/^[0-9.,]+$/', $_POST["editarPrecio"]) &&
			   preg_match('/^[,.\/a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarDescripcion"])){

		   		/*=============================================
					VALIDAR IMAGEN
					=============================================*/

			   	$ruta = $_POST["imagenActual"];

			   	if(isset($_FILES["editarImagen"]["tmp_name"]) && !empty($_FILES["editarImagen"]["tmp_name"])){

					list($ancho, $alto) = getimagesize($_FILES["editarImagen"]["tmp_name"]);

					$nuevoAncho = 500;
					$nuevoAlto = 500;

					/*=============================================
					CREAMOS EL DIRECTORIO DONDE VAMOS A GUARDAR LA FOTO DEL USUARIO
					=============================================*/

					$directorio = "vistas/img/productos/".$_POST["editarCodigo"];

					/*=============================================
					PRIMERO PREGUNTAMOS SI EXISTE OTRA IMAGEN EN LA BD
					=============================================*/

					if(!empty($_POST["imagenActual"]) && $_POST["imagenActual"] != "vistas/img/productos/default/anonymous.png"){

						unlink($_POST["imagenActual"]);

					}else{

						mkdir($directorio, 0755);

					}

					/*=============================================
					DE ACUERDO AL TIPO DE IMAGEN APLICAMOS LAS FUNCIONES POR DEFECTO DE PHP
					=============================================*/

					if($_FILES["editarImagen"]["type"] == "image/jpeg"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["editarCodigo"]."/".$aleatorio.".jpg";

						$origen = imagecreatefromjpeg($_FILES["editarImagen"]["tmp_name"]);

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagejpeg($destino, $ruta);

					}

					if($_FILES["editarImagen"]["type"] == "image/png"){

						/*=============================================
						GUARDAMOS LA IMAGEN EN EL DIRECTORIO
						=============================================*/

						$aleatorio = mt_rand(100,999);

						$ruta = "vistas/img/productos/".$_POST["editarCodigo"]."/".$aleatorio.".png";

						$origen = imagecreatefrompng($_FILES["editarImagen"]["tmp_name"]);

						$destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

						imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

						imagepng($destino, $ruta);

					}

				}

				$tabla = "productos";

				if (!empty($_POST['publicado'])) {
					$estado = 1;
				}
				$idProducto = (int)$_POST["idProducto"];
				$datos = array("descripcion" => $_POST["editarDescripcion"],
							   "nombre" => $_POST["editarNombre"],
							   "stock" => $_POST["editarStock"],
							   "unidad" => $_POST["editarUnidad"],
							   "estado" => $estado,
							   "precio" => $_POST["editarPrecio"],
							   "imagen" => $ruta,
							   "id" => $idProducto);

				$respuesta = ModeloProductos::mdlEditarProducto($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

					swal({
						title: "Producto modificado!",
						text: "Redireccionando...",
						type: "success",
						timer: 2000
					}).then(function() {
							window.location = "productos";
					});

					</script>';

				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El producto no puede ir con los campos vacíos o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "productos";

							}
						})

			  	</script>';
			}
		}

	}

	/*=============================================
	BORRAR PRODUCTO
	=============================================*/
	static public function ctrEliminarProducto(){

		if(isset($_GET["idProducto"])){

			$tabla ="productos";
			$datos = $_GET["idProducto"];

			if($_GET["imagen"] != "" && $_GET["imagen"] != "vistas/img/productos/default/anonymous.png"){

				unlink($_GET["imagen"]);
				rmdir('vistas/img/productos/'.$_GET["codigo"]);

			}

			$respuesta = ModeloProductos::mdlEliminarProducto($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				window.location = "productos";

				</script>';

			}
		}


	}

	/*=============================================
	MOSTRAR SUMA PEDIDOS
	=============================================*/

	static public function ctrMostrarSumaPedidos(){

		$tabla = "pedidos";

		$respuesta = ModeloProductos::mdlMostrarSumaPedidos($tabla);

		return $respuesta;

	}

}
