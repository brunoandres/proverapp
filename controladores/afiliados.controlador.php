<?php

class ControladorAfiliados{

	/*=============================================
	DEVUELVE LAS COMPRAS DEL AFILIADO
	=============================================*/

	static public function ctrComprasAfiliados($item, $valor){

		$tabla = "pedidos";
		$respuesta = ModeloAfiliados::mdlComprasAfiliados($tabla,$item,$valor);
		return $respuesta;

	}

	/*=============================================
	CREAR AFILIADO
	=============================================*/

	static public function ctrCrearAfiliado($url){

		if(isset($_POST["nuevoNombre"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoNombre"])){

				$tabla = "afiliados";

				$datos = array("nombre"=>$_POST["nuevoNombre"],
					           "apellido"=>$_POST["nuevoApellido"],
					           "legajo"=>$_POST["nuevoLegajo"]);

				$respuesta = ModeloAfiliados::mdlIngresarAfiliado($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>


					window.location = "'.$url.'";


					</script>';

				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El Afiliado no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "'.$url.'";

							}
						})

			  	</script>';

			}

		}

	}

	/*=============================================
	MOSTRAR AFILIADOS
	=============================================*/

	static public function ctrMostrarAfiliados($item, $valor){

		$tabla = "afiliado";

		$respuesta = ModeloAfiliados::mdlMostrarAfiliados($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	EDITAR CATEGORIA
	=============================================*/

	static public function ctrEditarAfiliado(){

		if(isset($_POST["editarNombre"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarNombre"])){

				$tabla = "afiliados";

				$datos = array("nombre" => $_POST["editarNombre"],"apellido" => $_POST["editarApellido"],"legajo" => $_POST["editarLegajo"],
							   "id" => $_POST["idAfiliado"]);

				$respuesta = ModeloAfiliados::mdlEditarAfiliado($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>


						window.location = "afiliados";


					</script>';

				}else{
					echo'<script>

					swal({
						  type: "error",
						  title: "Afiliado No editado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {



									}
								})

					</script>';
				}


			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡La categoría no puede ir vacía o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "categorias";

							}
						})

			  	</script>';

			}

		}

	}

	/*=============================================
	BORRAR CATEGORIA
	=============================================*/

	static public function ctrBorrarCategoria(){

		if(isset($_GET["idCategoria"])){

			$tabla ="Categorias";
			$datos = $_GET["idCategoria"];

			$respuesta = ModeloCategorias::mdlBorrarCategoria($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

					swal({
						  type: "success",
						  title: "La categoría ha sido borrada correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "categorias";

									}
								})

					</script>';
			}
		}

	}
}
