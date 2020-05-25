<?php

if ($_SESSION["perfil"] == "Administrador" or $_SESSION["perfil"] == "Pedidos") {

}else{

  echo'<script>

  swal({
    title: "Error, No tiene permisos para acceder!",
    text: "Redireccionando...",
    type: "error",
    timer: 2000
  }).then(function() {
      window.location = "inicio";
  });

  </script>';
  exit;
}

?>
<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Crear <strong>Nuevo Pedido</strong>

    </h1>


    <ol class="breadcrumb">

      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Crear pedido</li>

    </ol>

  </section>

  <section class="content">

    <div class="row">

      <!--=====================================
      EL FORMULARIO
      ======================================-->

      <div class="col-lg-5 col-xs-12">

        <div class="box box-success">

          <div class="box-header with-border"></div>

          <form role="form" method="post" class="formularioVenta">

            <div class="box-body">

                <!--=====================================
                ENTRADA FECHA PEDIDO
                ======================================-->

                <div class="form-group">
                  <label>Fecha pedido:</label>

                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" id="datepicker" value="<?php echo date("d/m/Y"); ?>" readonly name="fechaPedido" autocomplete="off">
                  </div>

                </div>

                <!--=====================================
                ENTRADA DEL VENDEDOR
                ======================================-->
                <div class="form-group">
                <p><strong>Usuario</strong> </p>
                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-user"></i></span>

                    <input type="text" class="form-control" id="nuevoVendedor" value="<?php echo $_SESSION["nombre"]; ?>" readonly>

                    <input type="hidden" name="idUsuario" value="<?php echo $_SESSION["id"]; ?>">

                  </div>

                </div>

                <!--=====================================
                ENTRADA DEL CÓDIGO
                ======================================-->

                <!--<div class="form-group">
                <p><strong>Pedido N°:</strong> </p>
                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-key"></i></span>-->

                    <?php

                    /*$item = null;
                    $valor = null;

                    $entregas = ControladorPedidos::ctrMostrarPedidos($item, $valor);

                    if(!$entregas){

                      echo '<input type="hidden" class="form-control" id="nuevoNumero" name="nuevoNumero" value="1" readonly>';


                    }else{

                      foreach ($entregas as $key => $value) {



                      }

                      $codigo = $value["numero"] + 1;



                      echo '<input type="hidden" class="form-control" id="nuevoNumero" name="nuevoNumero" value="'.$codigo.'" readonly>';


                    }*/

                    ?>


                <!--  </div>

                </div>-->

                <!--=====================================
                ENTRADA DEL AFILIADO
                ======================================-->

                <div class="form-group">
                <label>Afiliado:</label>

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-users"></i></span>

                    <input type="text" class="form-control" id="seleccionarAfiliado" required placeholder="Escriba el nombre del afiliado para seleccionar">

                    <input type="hidden" name="seleccionarAfiliado" id="idAfiliado" required>

                    <!--<select class="form-control" id="seleccionarAfiliado" name="seleccionarAfiliado" required>-->

                    <?php

                      /*$item = "legajo";
                      $valor = $_POST['legajo'];

                      $afiliados = ControladorAfiliados::ctrMostrarAfiliados($item, $valor);

                       /*foreach ($afiliados as $key => $value) {

                         echo '<option value="'.$value["clave"].'">'.$value["nombre"].' '.$value["legajo"].'</option>';

                       }

                       echo '<option value="'.$afiliados["clave"].'">'.$afiliados["nombre"].' '.$afiliados["legajo"].'</option>';*/


                    ?>

                    </select>

                    <!--<span class="input-group-addon"><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modalAgregarCliente" data-dismiss="modal">Agregar afiliado</button></span>-->
                    <span class="input-group-addon"><button type="button" class="btn btn-primary btn-xs" id="seleccionarNuevo">Seleccionar Otro</button></span>

                  </div>

                </div>

                <label>Productos: </label>
                <!--=====================================
                ENTRADA PARA AGREGAR PRODUCTO
                ======================================-->

                <div class="form-group row nuevoProducto">



                </div>


                <input type="hidden" id="listaProductos" name="listaProductos">

                <!--=====================================
                BOTÓN PARA AGREGAR PRODUCTO
                ======================================-->

                <button type="button" class="btn btn-default hidden-lg btnAgregarProducto">Agregar producto</button>

                <hr>


                <div class="row">

                  <!--=====================================
                  ENTRADA IMPUESTOS Y TOTAL
                  ======================================-->

                  <div class="col-xs-6 pull-left">

                    <table class="table">

                      <thead>

                        <tr>
                          <!--<th>Pago por Planilla</th>
                          <th>Pago en efectivo</th>    -->
                          <th>Total Pedido</th>
                        </tr>

                      </thead>

                      <tbody>

                        <tr>

                          <!--<td style="width: 30%">

                            <div class="input-group">

                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                              <input type="number" class="form-control input-lg" min="0" id="pagoPlanilla" value="0" name="pagoPlanilla" placeholder="00.00" required autocomplete="off">

                               <input type="hidden" name="nuevoPrecioImpuesto" id="nuevoPrecioImpuesto">

                               <input type="hidden" name="nuevoPrecioNeto" id="nuevoPrecioNeto">



                            </div>

                            </td>

                           <td style="width: 30%">

                            <div class="input-group">

                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>

                              <input type="number" class="form-control input-lg" min="0" id="pagoEfectivo" name="pagoEfectivo" total="" placeholder="00.00" required autocomplete="off">

                              <input type="hidden" name="totalVenta" id="totalVenta">


                            </div>

                          </td>-->

                          </td>

                           <td style="width: 30%">

                            <div class="input-group">

                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>

                              <input type="text" class="form-control input-lg" id="totalPedido" name="totalPedido" total="" placeholder="00.00" readonly required>

                              <input type="hidden" name="totalVenta" id="totalVenta">


                            </div>

                          </td>

                        </tr>

                      </tbody>

                    </table>

                  </div>

                </div>

                <hr>

                <!--=====================================
                ENTRADA MÉTODO DE PAGO
                ======================================-->

                <div class="form-group row">


                  <div class="col-xs-6" style="padding-right:0px">
                  <label>Forma de pago:</label>
                     <div class="input-group">

                      <select class="form-control" id="metodoPago" name="metodoPago" required>
                      <option value="">Seleccione Método</option>
                      <?php

                      $item = null;
                      $valor = null;

                      $metodos = ControladorPedidos::ctrMostrarMetodos($item, $valor);

                        foreach ($metodos as $key => $value) {

                          echo '<option metodo="'.$value["tipo"].'" value="'.$value["id"].'">'.$value["detalle"].'</option>';

                        }

                      ?>
                      </select>
                    </div>

                  </div>

                  <div class="cajasMetodoPago"></div>

                  <input type="hidden" id="listaMetodoPago" name="listaMetodoPago">

                </div>

                <br>

                <!--=====================================
                ENTRADA ESTADO PEDIDO
                ======================================-->

                <div class="form-group row">

                  <div class="col-xs-6" style="padding-right:0px">
                  <label>Estado pedido:</label>
                     <div class="input-group">

                      <select class="form-control" id="estadoPedido" name="estadoPedido" required>
                      <option value="">Seleccione Estado</option>
                      <?php

                      $item = null;
                      $valor = null;

                      $estados = ControladorPedidos::ctrMostrarEstados($item, $valor);

                        foreach ($estados as $key => $value) {

                          if($value["estado"] == "Pendiente"){
                            $selected = "selected";
                          }else{
                            $selected = "";
                          }

                          echo '<option value="'.$value["id"].'" '.$selected.'>'.$value["estado"].'</option>';

                        }

                      ?>
                      </select>
                    </div>

                  </div>

                </div>

           <!--=====================================
            ENTRADA OBSERVACIONES
            ======================================-->
            <div class="form-group">
            <p><strong>Observaciones</strong> </p>
              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-user"></i></span>

                <textarea style="resize: none;" class="form-control" rows="3" name="observaciones" placeholder="Ingrese alguna observación..."></textarea>

              </div>

            </div>

          </div>

          <div class="box-footer">

            <button type="submit" class="btn btn-primary pull-right">Guardar pedido</button>

          </div>

        </form>

        <?php

          $guardarPedido = new ControladorPedidos();
          $guardarPedido -> ctrCrearPedido();

        ?>

        </div>

      </div>

      <!--=====================================
      LA TABLA DE PRODUCTOS
      ======================================-->

      <div class="col-lg-7 hidden-md hidden-sm hidden-xs">

        <div class="box box-warning">

          <div class="box-header with-border"></div>

          <div class="box-body">

            <table class="table table-bordered table-striped dt-responsive tablaPedidos">

               <thead>

                 <tr>
                  <th style="width: 10px">#</th>
                  <th>Imagen</th>
                  <th>Precio</th>
                  <th>Descripcion</th>
                  <th>Stock</th>
                  <th>Acciones</th>
                </tr>

              </thead>

            </table>

          </div>

        </div>


      </div>

    </div>

  </section>

</div>
