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

            Agregar Pedido

          </button>


        </a>

        <a href="pedidos-cargados">

          <button type="button" class="btn btn-default" name="button">Pedidos Con Asientos</button>
        </a>
        <button type="button" class="btn btn-default pull-right" id="daterange-btn">

            <span>
              <i class="fa fa-calendar"></i> Rango de fecha
            </span>

            <i class="fa fa-caret-down"></i>

         </button>

      </div>

      <div class="box-body">
      <!--<form id="tablaPedidos" method="POST">-->
       <table class="table table-bordered table-striped dt-responsive tablas" id="pedidos" width="100%">

        <thead>

         <tr>

           <th width="5%">Nro.</th>
           <th>Afiliado</th>
           <th>Legajo</th>
           <th>Importe</th>
           <th>Pago</th>
           <th>Estado</th>
           <th>Usuario</th>
           <th>Fecha pedido</th>
           <th>Fecha pago</th>
           <th width="15%">Acciones</th>
           <!--<th><input type="checkbox" title="Seleccionar todos" id="selectall"/></th>-->

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

          //ASIGNAR IMPORTE SI ES JUBILADO
          $es_jubilado = false;
          if ($respuestaAfiliados["jubilado"] == "si") {
            $es_jubilado = true;
          }


          //var_dump($respuestaAfiliados);
          //BUSCO USUARIO QUE GENERÓ EL PEDIDO
          $itemUsuario = "id";
          $valorUsuario = $value["usuarios_id"];
          $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);

          //BUSCO ESTADO DEL PEDIDO
          $item = "id";
          $valor = $value["estados_id"];
          $estado = ControladorPedidos::ctrMostrarEstados($item, $valor);

          //USUARIO PERMITIDO PARA EDITAR PEDIDO YA ENTREGADO
          $editar = null;

          if($estado['estado'] ==  "Pendiente"){
            $estadoPedido = '<label class="label label-default">'.$estado['estado'].'</label>';


          }else if($estado['estado'] ==  "Entregado"){

            $estadoPedido = '<label class="label label-success">'.$estado['estado'].'</label>';
            $editar = " disabled='disabled'";
          }else if($estado['estado'] ==  "Preparado"){
            $estadoPedido = '<label class="label label-warning">'.$estado['estado'].'</label>';
          }else{
            $estadoPedido = '<label class="label label-danger">'.$estado['estado'].'</label>';
          }

          $eliminar = " disabled='disabled'";
          $btnPrestamo = '';
          //PEDIDO CARGADO EN SISTEMA ADMINISTRATIVO
          $prestamoCargado = "";
          if ($_SESSION["perfil"] === "Administrador") {

            $eliminar = null;

            if ($value["fk_nro_asiento"] != "") {
              $prestamoCargado = " disabled='disabled'";
            }

            if ($_SESSION["usuario"] == 'graciela.huen') {
              $btnPrestamo = '<button type="button" class="btn btn-primary btn-xs btnCargarPrestamo" '.$prestamoCargado.' idPedido="'.$dato::encryption($value["id"]).'" claveAfiliado="'.$dato::encryption($respuestaAfiliados["clave"]).'" montoPrestamo="'.$dato::encryption(number_format($value["pago_planilla"],2)).'" fechaPago="'.$value["fecha_pago"].'" title="Guardar prestamo"><i class="fa fa-check"></i></button>';
            }
          }




          echo '<tr>

                  <td>'.$value["numero"].'</td>';

                  echo '<td><a href="index.php?ruta=afiliado-detalle&ref='.$dato::encryption($respuestaAfiliados["clave"]).'"</a>'.$respuestaAfiliados["nombre"].'</td>';

                  echo '<td>'.$respuestaAfiliados['legajo'].'</td>';

                  echo '<td>$ '.number_format($value["importe"],2).'</td>';

                  echo '<td>'.$metodo_pago["detalle"].'</td>

                  <td>'.$estadoPedido.'</td>

                  <td>'.$respuestaUsuario["nombre"].'</td>

                  <td>'.date('d/m/Y', strtotime($value["fecha_pedido"])).'</td>

                  <td>'.date('d/m/Y', strtotime($value["fecha_pago"])).'</td>

                  <td>

                    <div class="btn-group">

                      <button type="button" class="btn btn-default btn-xs btnImprimirFactura" title="Imprimir recibo" codigoPedido="'.$value["numero"].'">

                        <i class="fa fa-print"></i>

                      </button>
                      <button type="button" name="view" value="Ver" id="'.$value["id"].'" class="btn btn-info btn-xs view_data"><i class="fa fa-eye"></i></button>

                      '.$btnPrestamo.'

                      <button type="button" class="btn btn-warning btn-xs btnEditarPedido" valorPagoEfectivo="'.$value['pago_efectivo'].'" idPedido="'.$dato::encryption($value["id"]).'" '.$editar.'><i class="fa fa-pencil"></i></button>

                      <button type="button" class="btn btn-danger btn-xs btnEliminarPedido" idPedido="'.$value["id"].'" '.$eliminar.'><i class="fa fa-times"></i></button>




                    </div>


                  </td>


                </tr>';
            }
/*<td>
  <input type="checkbox" class="disponibles" title="'.$title.'" '.$botonAcciones.' name="pedidos[]" value="'.$value["id"].'" '.$modificar.'>
</td>*/
        ?>

        </tbody>

       </table>

       <!--<div class="form-group row">
          <div class="col-xs-6" style="padding-right:0px">
            <label>Cambio Estados Seleccionados:</label>
                <div class="input-group">
                <select class="form-control" id="estadoPedidos" name="estadoPedidos" required>
                  <option value="">Seleccione Estado</option>
                  <?php

                  /*$item = null;
                  $valor = null;

                  $estados = ControladorPedidos::ctrMostrarEstados($item, $valor);

                    foreach ($estados as $key => $value) {

                    echo '<option value="'.$value["id"].'">'.$value["estado"].'</option>';

                  }*/

                  ?>
                </select>
            </div>
          </div>
        </div>-->

        <!--<div class="box-footer">

          <button type="submit" name="procesarPedidos" class="btn btn-primary pull-right" <?php if (isset($modificar)) {
            //echo $modificar;
          } ?>>Modificar pedidos</button>

        </div>-->

      <!--</form>-->

      <?php

      $pedidos = new ControladorPedidos();
      $pedidos->ctrEliminarPedido();
      $pedidos->ctrCambiarEstadosPedidos();



      //CARGAR PRESTAMO SISTEMA ADMINISTRATIVO

      $pedidos->ctrCargarPrestamo();

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
