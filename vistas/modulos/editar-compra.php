<?php

    //ID DE LA COMPRA
    $item = "id";
    $idEntrega = $_GET["EntregaNumero"];

    //DESENCRIPTO EL VALOR DEL ID PARA PROTEGERLO
    $valor = SED::decryption($idEntrega);

    //DATOS DEL PEDIDO
    $compra = ControladorEntregas::ctrMostrarEntregas($item, $valor);

    if(empty($compra)){
      echo'<script>
            window.location = "compras";

            </script>';
    }

    $itemUsuario = "id";
    $valorUsuario = $compra["usuarios_id"];

    $usuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);

?>
<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Editar compra <strong># <?php echo $compra['numero']; ?> </strong>

    </h1>

    <ol class="breadcrumb">

      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Editar compra</li>

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
                  <label>Fecha compra:</label>

                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" id="datepicker" value="<?php echo date('d/m/Y', strtotime($compra['fecha'])); ?>" name="fechaEntrega" autocomplete="off">
                    <input type="hidden" name="idEntrega" value="<?php echo $compra['id']; ?>">
                  </div>

                </div>



                <!--=====================================
                ENTRADA DEL USUARIO
                ======================================-->

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-user"></i></span>

                    <input type="text" class="form-control" id="usuarioPedido" value="<?php echo $usuario["nombre"]; ?>" readonly>

                    <input type="hidden" name="idUsuario" value="<?php echo $usuario["id"]; ?>">

                  </div>

                </div>

                <!--=====================================
                ENTRADA DEL NUMERO DE COMPRA
                ======================================-->

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-key"></i></span>

                   <input type="text" class="form-control" id="numeroEntrega" name="numeroEntrega" value="<?php echo $compra["numero"]; ?>" readonly>

                  </div>

                </div>


                <label>Pedidos: </label>

                <!--=====================================
                ENTRADA PARA AGREGAR PRODUCTO
                ======================================-->

                <div class="form-group row nuevoPedido">

                <?php

                $listaPedidos = json_decode($compra["pedidos"], true);

                foreach ($listaPedidos as $key => $value) {

                  $item = "id";
                  $valor = $value["id"];
                  $orden = "id";

                  $respuesta = ControladorPedidos::ctrMostrarPedidos($item, $valor, $orden);

                  echo '<div class="row" style="padding:5px 15px">

                        <div class="col-xs-12" style="padding-right:-5px">

                          <div class="input-group">

                            <span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarPedido" idPedido="'.$value["id"].'"><i class="fa fa-times"></i></button></span>

                            <input type="text" class="form-control nuevaDescripcionPedido" idPedido="'.$value["id"].'" name="agregarPedido" value="'.$value["numero"].'" readonly required>

                          </div>

                        </div>

                      </div>';
                }


                ?>

                </div>

                <input type="hidden" id="listaPedidos" name="listaPedidos">


                <!--=====================================
                BOTÓN PARA AGREGAR PEDIDO
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
                  <label>Estado pedido:</label>
                     <div class="input-group">

                      <select class="form-control" id="estadoPedido" name="estadoEntrega" required>
                      <option value="">Seleccione Estado</option>
                      <?php

                      $item = null;
                      $valor = null;

                      $estados = ControladorPedidos::ctrMostrarEstados($item, $valor);

                        foreach ($estados as $key => $value) {

                          if($value["id"] == $compra['estados_id']){
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

                <br>
                <!--=====================================
            ENTRADA OBSERVACIONES
            ======================================-->
            <div class="form-group">
            <p><strong>Observaciones</strong> </p>
              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-user"></i></span>

                <textarea class="form-control" rows="3" name="observaciones" placeholder="Ingrese alguna observación..."><?php echo $compra['observaciones']; ?></textarea>

              </div>

            </div>

            </div>

          <div class="box-footer">

            <button type="submit" class="btn btn-warning pull-right">Guardar cambios</button>

          </div>

        </form>

        <?php

          $editarEntrega= new ControladorEntregas();
          $editarEntrega -> ctrEditarEntrega();

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
                  <th>N° Entrega</th>
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
