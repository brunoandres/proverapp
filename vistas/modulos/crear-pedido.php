<?php
if (isset($_GET["socio"])) {
  if ($_GET["socio"] == "NO") {
    echo'<script>

    swal({
      title: "El afiliado no está adherido a la obra social!",
      text: "Indique el motivo del pedido en observaciones...",
      type: "warning",
      timer: 8000
    }).then(function() {
        //window.location = "pedidos";
    });

    </script>';

  }

}

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


//Manejo de fechas

//Fecha actual pedido
$fechaActual = date("Y-m-d");
//Por defecto si el pedido está hecho antes del 15 pasa para el siguiente mes (el 01 del mes)
$fechaVencimiento = date("Y/m/01", strtotime("+1 month"));
//Fecha de cierre para pasar prestamos a descuento (despues del 15 cada mes)
$fechaCierre = date("Y-m-15");
//Si la fecha del pedido es mayor a la del cierre, pasa para dos mes despues
if($fechaActual >= $fechaCierre){
  $fechaVencimiento = date("Y/m/01", strtotime("+2 month"));
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

                <div class="row">

                  <div class="col-md-6">

                    <div class="form-group">
                      <label>Fecha Pedido:</label>

                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>

                        <input type="text" id="fechaPedido" class="form-control pull-right datepicker" value="<?php echo date("Y/m/d"); ?>" readonly name="fechaPedido" autocomplete="off">
                      </div>

                    </div>

                  </div>

                  <div class="col-md-6">

                    <div class="form-group">
                      <label>Fecha de Pago:</label>

                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>




                        <input type="hidden" id="fechaCierre" value="<?php echo $fechaCierre; ?>">
                        <input type="text" id="fechaVencimiento" class="form-control pull-right datepicker" value="<?php echo $fechaVencimiento; ?>" readonly name="fechaPago" autocomplete="off">
                      </div>

                    </div>

                  </div>

                </div>



                <!--=====================================
                ENTRADA DEL VENDEDOR
                ======================================
                <div class="form-group">
                <p><strong>Usuario:</strong> </p>
                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-user"></i></span>

                    <input type="text" class="form-control" id="nuevoVendedor" value="<?php echo $_SESSION["nombre"]; ?>" readonly>




                  </div>

                </div>
                -->
                <input type="hidden" name="idUsuario" value="<?php echo $_SESSION["id"]; ?>">
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

                    <input type="text" class="form-control" id="seleccionarAfiliado" value="<?php if (isset($_GET["afiliado"])) {
                      echo $_GET["afiliado"];
                    } ?>" placeholder="Escriba el nombre del afiliado para seleccionar" required>

                    <input type="hidden" name="seleccionarAfiliado" id="idAfiliado" value="<?php if (isset($_GET["ref"])) {
                      echo SED::decryption($_GET["ref"]);
                    } ?>" required>

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
                          <th>Total Pedido:</th>
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
                  <label>Forma de Pago:</label>
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
                  <label>Estado Pedido:</label>
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
            <p><strong>Observaciones:</strong> </p>
              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-user"></i></span>

                <textarea style="resize: none;" class="form-control" rows="3" id="observacion" name="observaciones" placeholder="Agregue alguna observación extra, por ejemplo si el afiliado no está adherido al 3% el motivo por el cúal se realiza el pedido." <?php if (isset($_GET["socio"])) {
                  if ($_GET["socio"] == "NO") {
                    echo "required";
                  }
                } ?>></textarea>

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
                  <th>Nombre</th>
                  <th>Descr.</th>
                  <th>Precio</th>
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

<script>

  $(document).ready(function(){

    //Si se modifica la fecha del pedido
    $("#fechaPedido").change(function(){

      alert("Atención: si elige una fecha mayor o igual al 15, la fecha de pago de este préstamo irá a descuento en los próximos dos meses");

      //Tomo valor de la fecha pedido
      var fechaPedidoVal = $("#fechaPedido").val();
      //Tomo valor de la fecha de cierre (el 15 de cada mes)
      var fechaCierreVal = $("#fechaCierre").val();

      //Convierto los campos en formato fecha para trabajarlos
      var fechaPedido = new Date(fechaPedidoVal);
      var fechaCierre = new Date(fechaCierreVal);

      //Si la fecha del pedido es mayor o igual al 15 del mes, pasa para dos meses a partir del mes del pedido
      //sino pasa para el siguiente mes
      if(fechaPedido >= fechaCierre){

        //Seteo fecha de vencimiento para dos meses posterior al mes del pedido
        <?php $fechaVencimiento = date("Y/m/01", strtotime("+2 month")); ?>
        $("#fechaVencimiento").val("<?php echo $fechaVencimiento; ?>");

      }else {
        //Seteo fecha de vencimiento para 1 mes posterior al mes del pedido
        <?php $fechaVencimiento = date("Y/m/01", strtotime("+1 month")); ?>
        $("#fechaVencimiento").val("<?php echo $fechaVencimiento; ?>");
      }

    });
  });

</script>
