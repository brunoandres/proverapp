<?php

require_once "../controladores/pedidos.controlador.php";
require_once "../modelos/pedidos.modelo.php";

require_once "../controladores/afiliados.controlador.php";
require_once "../modelos/afiliados.modelo.php";


class AjaxPedidos{

  /*=============================================
  EDITAR PEDIDO
  =============================================*/

  public $idPedido;
  public $traerPedidos;
  public $nombreProducto;

  public function ajaxEditarPedido(){

    if($this->traerPedidos == "ok"){

      $item = null;
      $valor = null;
      $orden = "id";

      $respuesta = ControladorPedidos::ctrMostrarPedidos($item, $valor,
        $orden);
        echo json_encode($respuesta);

    }else if($this->nombreProducto != ""){

      $item = "numero";
      $valor = $this->nombrePedido;
      $orden = "id";

      $respuesta = ControladorPedidos::ctrMostrarPedidos($item, $valor,
        $orden);

      echo json_encode($respuesta);

    }else{

      $item = "id";
      $valor = $this->idPedido;
      $orden = "id";

      $respuesta = ControladorPedidos::ctrMostrarPedidos($item, $valor,
        $orden);

      $claveAfiliado = $respuesta['afiliados_id'];
      $itemAfiliado= "clave";
      $respuestaAfiliados = ControladorAfiliados::ctrMostrarAfiliados($itemAfiliado, $claveAfiliado);

      $json1 = json_encode($respuesta);
      $json2 = json_encode($respuestaAfiliados);

      $datosPedido[] = json_decode($json1,true);
      $datosPedido[]= json_decode($json2,true);
      $json_merge = json_encode($datosPedido);

      echo $json_merge;

    }

  }

}


/*=============================================
GENERAR CÓDIGO A PARTIR DE ID CATEGORIA
=============================================*/

if(isset($_POST["idCategoria"])){

	$codigoProducto = new AjaxPedidos();
	$codigoProducto -> idCategoria = $_POST["idCategoria"];
	$codigoProducto -> ajaxCrearCodigoProducto();

}
/*=============================================
EDITAR PEDIDO
=============================================*/

if(isset($_POST["idPedido"])){

  $editarProducto = new AjaxPedidos();
  $editarProducto -> idPedido = $_POST["idPedido"];
  $editarProducto -> ajaxEditarPedido();

}

/*=============================================
TRAER PRODUCTO
=============================================*/

if(isset($_POST["traerPedidos"])){

  $traerPedidos = new AjaxPedidos();
  $traerPedidos -> traerPedidos = $_POST["traerPedidos"];
  $traerPedidos -> ajaxEditarPedido();

}

/*=============================================
TRAER PRODUCTO
=============================================*/

if(isset($_POST["nombreProducto"])){

  $traerPedidos = new AjaxPedidos();
  $traerPedidos -> nombreProducto = $_POST["nombreProducto"];
  $traerPedidos -> ajaxEditarPedido();

}
