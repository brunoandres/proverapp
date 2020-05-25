<?php

require_once "../controladores/afiliados.controlador.php";
require_once "../modelos/afiliados.modelo.php";

class AjaxAfiliados{

	/*=============================================
	EDITAR CATEGORÍA
	=============================================*/

	public $idAfiliado;

	public function ajaxEditarAfiliado(){

		$item = "id";
		$valor = $this->idAfiliado;

		$respuesta = ControladorAfiliados::ctrMostrarAfiliados($item, $valor);

		echo json_encode($respuesta);

	}
}

/*=============================================
EDITAR CATEGORÍA
=============================================*/
if(isset($_POST["idAfiliado"])){

	$afiliado = new AjaxAfiliados();
	$afiliado -> idAfiliado = $_POST["idAfiliado"];
	$afiliado -> ajaxEditarAfiliado();
}
