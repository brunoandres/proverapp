<?php

if(isset($_GET['estado'])){
  if($_GET['estado']=="ok"){
    echo '

      <script>
        toastr.success("Pedido guardado exitosamente!", "Registro OK", {timeOut: 9000});
      </script>

    ';
  }else if($_GET['estado'] == 'vacio'){
    echo '

      <script>
        toastr.warning("Ingrese al menos un Pedido para guardar la compra.", "Carrito Vacío!", {timeOut: 9000});
      </script>

    ';
  }else{
    echo '

      <script>
        toastr.error("No se pudo guardar el Pedido!", "Error!", {timeOut: 9000});
      </script>

    ';
  }
}

?>
<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Crear <strong>Nueva Solicitud</strong>

    </h1>

    <ol class="breadcrumb">

      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Crear solicitud entrega</li>

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
                ENTRADA FECHA COMPRA
                ======================================-->

                <div class="form-group">
                  <label>Fecha:</label>

                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" id="datepicker" value="<?php echo date("d/m/Y"); ?>" name="fechaEntrega" autocomplete="off">
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

                <div class="form-group">
                <p><strong>Entrega N°:</strong> </p>
                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-key"></i></span>

                    <?php

                    $item = null;
                    $valor = null;

                    $compras = ControladorEntregas::ctrMostrarEntregas($item, $valor);

                    if(!$compras){

                      echo '<input type="text" class="form-control" id="nuevoNumero" name="nuevoNumero" value="1" readonly>';


                    }else{

                      foreach ($compras as $key => $value) {



                      }

                      $codigo = $value["numero"] + 1;



                      echo '<input type="text" class="form-control" id="nuevoNumero" name="nuevoNumero" value="'.$codigo.'" readonly>';


                    }

                    ?>


                  </div>

                </div>

                <label>Lista de Pedidos: </label>
                <!--=====================================
                ENTRADA PARA AGREGAR PEDIDOS
                ======================================-->

                <div class="form-group row nuevoPedido">



                </div>


                <input type="hidden" id="listaPedidos" name="listaPedidos">

                <!--=====================================
                BOTÓN PARA AGREGAR PRODUCTO
                ======================================-->

                <button type="button" class="btn btn-default hidden-lg btnAgregarPedido">Agregar pedido</button>

                <hr>

                <hr>



                <br>

                <!--=====================================
                ENTRADA ESTADO COMPRA
                ======================================-->

                <div class="form-group row">

                  <div class="col-xs-6" style="padding-right:0px">
                    <label>Estado solicitud:</label>
                        <div class="input-group">

                        <select class="form-control" id="estadoEntrega" name="estadoEntrega" required>
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

                <textarea class="form-control" rows="3" name="observaciones" placeholder="Ingrese alguna observación..."></textarea>

              </div>

            </div>

          </div>



          <div class="box-footer">

            <button type="submit" class="btn btn-primary pull-right">Guardar Solicitud</button>

          </div>

        </form>

        <?php

          $guardarEntrega = new ControladorEntregas();
          $guardarEntrega -> ctrCrearEntrega();

        ?>

        </div>

      </div>

      <!--=====================================
      LA TABLA DE PEDIDOS
      ======================================-->

      <div class="col-lg-7 hidden-md hidden-sm hidden-xs">

        <div class="box box-warning">

          <div class="box-header with-border"></div>

          <div class="box-body">

            <table class="table table-bordered table-striped dt-responsive tablaEntregas">

               <thead>
                <tr>
                  <th style="width: 80px">N° Pedido</th>
                  <th>Fecha</th>
                  <th>Importe</th>
                  <th>Estado</th>
                  <th>N° Solicitud</th>
                  <th>Usuario</th>
                  <th style="width: 15px">Acciones</th>
                </tr>

              </thead>

            </table>

          </div>

        </div>

      </div>

    </div>

  </section>

</div>
