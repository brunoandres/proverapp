<?php
$dato = new SED();

$xml = ControladorPedidos::ctrDescargarXML();

if($xml){

  rename($_GET["xml"].".xml", "xml/".$_GET["xml"].".xml");

  echo '<a class="btn btn-block btn-success abrirXML" archivo="xml/'.$_GET["xml"].'.xml" href="ventas">Se ha creado correctamente el archivo XML <span class="fa fa-times pull-right"></span></a>';

}
?>
<div class="content-wrapper">

  <section class="content-header">

    <h1>
      Administrar Pedidos
    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Administrar pedidos</li>

    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">

        <a href="crear-pedido">

          <button class="btn btn-primary">

            Agregar pedido

          </button>

        </a>
        <button type="button" class="btn btn-default pull-right" id="daterange-btn">

            <span>
              <i class="fa fa-calendar"></i> Rango de fecha
            </span>

            <i class="fa fa-caret-down"></i>

         </button>

      </div>

      <div class="box-body">
      <form id="tablaPedidos" method="POST">
       <table class="table table-bordered table-striped dt-responsive tablas" id="pedidos" width="100%">

        <thead>

         <tr>

           <th width="5%">N°</th>
           <th>Afiliado</th>
           <th>Importe total</th>
           <th>Forma de pago</th>
           <th>Estado</th>
           <th>Usuario</th>
           <th>Fecha</th>
           <th>Acciones </th>
           <th><input type="checkbox" title="Seleccionar todos" id="selectall"/></th>

         </tr>

        </thead>

        <tbody>

        <?php

          if(isset($_GET["fechaInicial"])){

            $fechaInicial = $_GET["fechaInicial"];
            $fechaFinal = $_GET["fechaFinal"];

            $respuesta = ControladorPedidos::ctrRangoFechasPedidos($fechaInicial, $fechaFinal);

          }else{

            $item = null;
            $valor = null;

            $respuesta = ControladorPedidos::ctrMostrarPedidos($item, $valor);

          }

          //$respuesta = ControladorPedidos::ctrMostrarPedidos($item, $valor);

          foreach ($respuesta as $key => $value) {

          //SABER SI TIENE COMPRA ASGINADA EL PEDIDO

          $itemEntrega = "id";
          $valorEntrega = $value['entregas_id'];
          if(is_null($valorEntrega)){
            $valorEntrega = NULL;
          }

          //BUSCO MÉTODO DE PAGO
          $item = "id";
          $valor = $value["pagos_id"];
          $metodo_pago = ControladorPedidos::ctrMostrarMetodos($item, $valor);

          //BUSCO AFILIADO
          $itemAfiliado = "clave";
          $valorAfiliado = $value["afiliados_id"];
          $respuestaAfiliados = ControladorAfiliados::ctrMostrarAfiliados($itemAfiliado, $valorAfiliado);

          //BUSCO USUARIO QUE GENERÓ EL PEDIDO
          $itemUsuario = "id";
          $valorUsuario = $value["usuarios_id"];
          $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);

          //BUSCO ESTADO DEL PEDIDO
          $item = "id";
          $valor = $value["estados_id"];
          $estado = ControladorPedidos::ctrMostrarEstados($item, $valor);

          //USUARIO PERMITIDO PARA EDITAR PEDIDO YA ENTREGADO
          $editar = false;
          $admin = false;
          $modificar = " disabled='disabled'";
          if($_SESSION["perfil"] === "Administrador" || $_SESSION["perfil"] === "Pedidos"){
            $admin = true;
            $eliminar = "";
            $modificar = "";
          }

          if($estado['estado'] ==  "Pendiente"){
            $estadoPedido = '<label class="label label-default">'.$estado['estado'].'</label>';
            $editar = true;
          }else if($estado['estado'] ==  "Entregado"){
            $editar = false;
            $estadoPedido = '<label class="label label-success">'.$estado['estado'].'</label>';
            if($admin == true){
              $editar = true;
            }
          }else if($estado['estado'] ==  "Preparado"){
            $estadoPedido = '<label class="label label-warning">'.$estado['estado'].'</label>';
            $editar = true;
          }else{
            $estadoPedido = '<label class="label label-danger">'.$estado['estado'].'</label>';
            if($admin == true){
              $editar = true;
            }
          }

          if($editar == true){
            $botonAcciones = "";
            $title = " Permitido";
          }else{
            $botonAcciones = " disabled='disabled'";
            $title = " Sin permiso";
          }
          if ($_SESSION["perfil"] == "Administrador") {
            $botonEliminar = '<button type="button" class="btn btn-danger btnEliminarPedido" title="'.$title.'" idPedido="'.$value["id"].'"><i class="fa fa-times"></i></button>';
          }else{
            $botonEliminar = "";
          }


          echo '<tr>

                  <td>'.$value["numero"].'</td>';

                  echo '<td>'.$respuestaAfiliados["nombre"]. '<strong> Legajo : '.$respuestaAfiliados['legajo'].'</td>';

                  echo '<td><strong>$ '.number_format($value["importe"],2).'</strong></td>';

                  echo '<td>'.$metodo_pago["detalle"].'</td>

                  <td>'.$estadoPedido.'</td>

                  <td>'.$respuestaUsuario["nombre"].'</td>

                  <td>'.date('d/m/Y', strtotime($value["fecha_pedido"])).'</td>

                  <td>

                    <div class="btn-group">

                      <button class="btn btn-default btnImprimirFactura" title="Imprimir recibo" codigoPedido="'.$value["numero"].'">

                        <i class="fa fa-print"></i>

                      </button>
                      <button type="button" name="view" value="Ver" id="'.$value["id"].'" class="btn btn-info view_data"><i class="fa fa-eye"></i></button>
                      <button type="button" class="btn btn-warning btnEditarPedido" title="'.$title.'" '.$botonAcciones.' valorPagoEfectivo="'.$value['pago_efectivo'].'" idPedido="'.$dato::encryption($value["id"]).'" '.$modificar.'><i class="fa fa-pencil"></i></button>
                      '.
                      $botonEliminar

                      .'


                    </div>
                    <td>
                      <input type="checkbox" class="disponibles" title="'.$title.'" '.$botonAcciones.' name="pedidos[]" value="'.$value["id"].'" '.$modificar.'>
                    </td>

                  </td>


                </tr>';
            }

        ?>

        </tbody>

       </table>

       <div class="form-group row">
          <div class="col-xs-6" style="padding-right:0px">
            <label>Cambio Estados Seleccionados:</label>
                <div class="input-group">
                <select class="form-control" id="estadoPedidos" name="estadoPedidos" required>
                  <option value="">Seleccione Estado</option>
                  <?php

                  $item = null;
                  $valor = null;

                  $estados = ControladorPedidos::ctrMostrarEstados($item, $valor);

                    foreach ($estados as $key => $value) {

                    echo '<option value="'.$value["id"].'">'.$value["estado"].'</option>';

                    }

                  ?>
                </select>
            </div>
          </div>
        </div>

        <div class="box-footer">

          <button type="submit" name="procesarPedidos" class="btn btn-primary pull-right" <?php if (isset($modificar)) {
            echo $modificar;
          } ?>>Modificar pedidos</button>

        </div>

      </form>

      <?php

      $pedidos = new ControladorPedidos();
      $pedidos->ctrEliminarPedido();
      $pedidos->ctrCambiarEstadosPedidos();

      ?>


      </div>

    </div>

  </section>

</div>

<div id="dataModal" class="modal fade">
  <div class="modal-dialog">
       <div class="modal-content">
            <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title">Detalles Pedido</h4>
            </div>
            <div class="modal-body" id="pedidos_detalles">
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
       </div>
  </div>
</div>
