<?php

$dato = new SED();

?>
<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Administrar Entregas

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Administrar entregas</li>

    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">

        <a href="crear-entrega">

          <button class="btn btn-primary">

            Agregar Entrega

          </button>

        </a>

      </div>

      <div class="box-body">

       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">

        <thead>

         <tr>

           <th width="10%">N°</th>
           <th width="15%">Fecha</th>
           <th width="30%">Estado</th>
           <th width="10%">Usuario</th>
           <th width="15%">Acciones</th>

         </tr>

        </thead>

        <tbody>

        <?php

          $item = null;
          $valor = null;

          $respuesta = ControladorEntregas::ctrMostrarEntregas($item, $valor);


          foreach ($respuesta as $key => $value) {

          //CANTIDAD DE PEDIDOS POR ENTREGA
          $item1 = "entregas_id";
          $pedidosAsignados = ControladorEntregas::ctrMostrarCantPedidosEntrega($item1,$value["id"]);


          //CANTIDAD DE PEDIDOS POR ENTREGA EN ESTADO ENTREGADOS
          $item1a = "entregas_id";
          $pedidosAsignadosEntregados = ControladorEntregas::ctrMostrarPedidosEntregados($item1a,$value["id"]);


          //CANTIDAD DE LOS PEDIDOS EN ESTRADO PREPARADOS
          $pedidosAsignadosPreparados = ControladorEntregas::ctrMostrarPedidosPreparados($item1a,$value["id"]);


          $cantidadPedidos = $pedidosAsignados["cantidad"];
          $cantidadEntregados = $pedidosAsignadosEntregados["cantidad"];
          if($cantidadPedidos == 0 || $pedidosAsignadosEntregados == 0){
            $porcentaje = 0;
          }else{
            $porcentaje = ($cantidadEntregados/$cantidadPedidos)*100;
          }



          //$restantes = null;
          //BARRA PROGRESO
          if ($porcentaje != 0) {
            //$restantes = (intval($cantidadPedidos)-intval($cantidadEntregados));
            $progreso = '<div class="progress">
              <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="'.$porcentaje.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$porcentaje.'%">
                <span class="">'.round($porcentaje).'% Entregado</span>
              </div>';

          }else{

            if($pedidosAsignados === $pedidosAsignadosPreparados){
              $progreso = '<div class="progress">
                <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                  <span class="">En preparación</span>
                </div>
              </div>';
            }else{
              $progreso = '<div class="progress">
                <div class="progress-bar progress-bar-pendiente progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                  <span class="">Pendiente</span>
                </div>
              </div>';
            }

          }

          $botonAcciones = "";
          if ($porcentaje === 100) {
            $botonAcciones = " disabled='disabled'";
          }


          /*if ($_SESSION["perfil"] == "Administrador") {
            $botonAcciones = "";
            $botonEliminar = '<button class="btn btn-danger btnEliminarEntrega" idEntrega="'.$value["id"].'"><i class="fa fa-times"></i></button>';

          }else{
            if ($_SESSION["perfil"] == "Pedidos") {
              $botonAcciones = "";
            }
            $botonEliminar = "";
          }*/

          echo '<tr>

                  <td>'.$value["numero"].'</td>
                  <td>'.date('d/m/Y', strtotime($value["fecha"])).'</td>';

                  $itemUsuario = "id";
                  $valorUsuario = $value["usuarios_id"];

                  $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);

                  echo '<td>'.$progreso.'</td>

                  <td>'.$respuestaUsuario["nombre"].'</td>

                  <td>

                    <div class="btn-group">
                      <button class="btn btn-default btn-xs btnImprimirPedidos" title="Imprimir Pedidos" id="'.$value["id"].'">

                        <i class="fa fa-print"></i>

                      </button>
                      <button type="button" name="view" title="Ver Detalle" value="Ver" id="'.$value["id"].'" class="btn btn-info btn-xs entregas_detalle"><i class="fa fa-eye"></i></button>
                      <button '.$botonAcciones.' title="Edita Entrega" class="btn btn-warning btn-xs btnEditarEntrega" idEntrega="'.$dato::encryption($value["id"]).'"><i class="fa fa-pencil"></i></button>
                      <button '.$botonAcciones.' title="Editar estados Pedidos" class="btn btn-danger btn-xs btnEditarPedidos" idEntrega="'.$value["id"].'" data-toggle="modal" data-target="#modalEditarPedidos"><i class="fa fa-cog"></i></button>

                      '.
                      /*$botonEliminar

                      .'*/'


                    </div>

                  </td>

                </tr>';
            }

        ?>

        </tbody>

       </table>



      </div>

    </div>

  </section>

</div>

<?php

$eliminarSolicitud = new ControladorEntregas();
$eliminarSolicitud -> ctrEliminarEntrega();

?>

<div id="dataModal" class="modal fade">
  <div class="modal-dialog">
       <div class="modal-content">
            <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title">Pedidos de la Entrega</h4>
            </div>
            <div class="modal-body" id="entregas_detalles">
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
       </div>
  </div>
</div>


<div id="modalEditarPedidos" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar Estados Pedidos Masivos</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">


            <input type="hidden" name="id" id="idEntrega">
            <div class="form-group">
               <label>Cambiar estado de los pedidos de ésta entrega</label>

                   <select class="form-control" id="estadoPedidos" name="estadoPedidos" required>
                     <option value="">Seleccione Estado</option>
                     <?php

                     $item = null;
                     $valor = null;

                     $estados = ControladorPedidos::ctrMostrarEstados($item, $valor);

                       foreach ($estados as $key => $value) {

                       echo '<option value="'.$value["id"].'">'.$value["estado"].'s</option>';

                       }

                     ?>
                   </select>

             </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar cambios</button>

        </div>

        <?php

          $editarPedidos = new ControladorPedidos();
          $editarPedidos -> ctrEditarEstadosPedidosEntregas();

        ?>

      </form>

    </div>

  </div>

</div>
