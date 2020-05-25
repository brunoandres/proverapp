<?php

class ControladorCategorias{

	/*=============================================
	CREAR NUEVA CATEGORIA
	=============================================*/

	static public function ctrCrearCategoria(){

		if(isset($_POST["nuevaCategoria"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaCategoria"])){

				$tabla = "categorias";

				$datos = array("nombre"=>$_POST["nuevaCategoria"],
					           "descripcion"=>$_POST["nuevaDescripcion"]);

				$respuesta = ModeloCategorias::mdlIngresarCategoria($tabla, $datos);

			 	if($respuesta == "ok"){

					echo'<script>

					swal({
					  title: "Categoria guardada!",
					  text: "Redireccionando...",
					  type: "success",
					  timer: 2000
					}).then(function() {
					    window.location = "categorias";
					});

					</script>';

				}else{
					echo'<script>

					swal({
					  title: "Error al guardar Categoria!",
					  text: "Redireccionando...",
					  type: "error",
					  timer: 2000
					}).then(function() {
					    window.location = "categorias";
					});

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
	LISTAR CATEGORIAS
	=============================================*/

	static public function ctrMostrarCategorias($item, $valor){

		$tabla = "categorias";

		$respuesta = ModeloCategorias::mdlMostrarCategorias($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	EDITAR CATEGORIA
	=============================================*/

	static public function ctrEditarCategoria(){

		if(isset($_POST["editarCategoria"])){

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCategoria"])){

				$tabla = "categorias";

				$datos = array("nombre" => $_POST["editarCategoria"], "descripcion" => $_POST["editarDescripcion"], "id" => $_POST["idCategoria"]);

				$respuesta = ModeloCategorias::mdlEditarCategoria($tabla, $datos);

				if($respuesta == "ok"){

					echo'<script>

					swal({
					  title: "Categoria modificada!",
					  text: "Redireccionando...",
					  type: "success",
					  timer: 2000
					}).then(function() {
					    window.location = "categorias";
					});

					</script>';

				}else{
					echo'<script>

					swal({
					  title: "Error al modificar!",
					  text: "Redireccionando...",
					  type: "error",
					  timer: 2000
					}).then(function() {
					    window.location = "categorias";
					});

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

			$tabla ="categorias";
			$datos = $_GET["idCategoria"];

			$respuesta = ModeloCategorias::mdlBorrarCategoria($tabla, $datos);

			if($respuesta == "ok"){
				echo'<script>

				swal({
					title: "Categoría eliminada correctamente",
					text: "Redireccionando...",
					type: "success",
					timer: 2000
				}).then(function() {
						window.location = "categorias";
				});

				</script>';

			}
		}

	}
}
