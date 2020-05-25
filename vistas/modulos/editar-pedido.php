<?php

    //ID DEL PEDIDO
    $item = "id";
    $idPedido = $_GET["id"];

    //DESENCRIPTO EL VALOR DEL ID PARA PROTEGERLO
    $valor = SED::decryption($idPedido);

    //DATOS DEL PEDIDO
    $pedido = ControladorPedidos::ctrMostrarPedidos($item, $valor);

    if(empty($pedido)){
      echo'<script>
				window.location = "pedidos";

			  </script>';
    }

    $itemUsuario = "id";
    $valorUsuario = $pedido["usuarios_id"];

    $usuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);



?>
<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Editar pedido <strong># <?php echo $pedido['numero']; ?> </strong>

    </h1>

    <ol class="breadcrumb">

      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Editar pedido</li>

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
                    <input type="text" class="form-control pull-right" id="datepicker" value="<?php echo date('d/m/Y', strtotime($pedido['fecha_pedido'])); ?>" readonly name="fechaPedido" autocomplete="off">
                    <input type="hidden" name="idPedido" value="<?php echo $pedido['id']; ?>">
                  </div>

                </div>

                <input type="hidden" id="pagoEfectivoBD" name="" value="<?php echo $pedido['pago_efectivo']; ?>">
                <input type="hidden" id="pagoPlanillaBD" name="" value="<?php echo $pedido['pago_planilla']; ?>">
                <input type="hidden" id="comprobanteBD" name="" value="<?php echo $pedido['comprobante']; ?>">

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
                ENTRADA DEL NUMERO DE PEDIDO
                ======================================-->

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-key"></i></span>

                   <input type="text" class="form-control" id="numeroPedido" name="numeroPedido" value="<?php echo $pedido["numero"]; ?>" readonly>

                  </div>

                </div>

                <!--=====================================
                ENTRADA DEL AFILIADO
                BLOQUEO EL CAMPO AFILIADO PARA EDITAR PEDIDO, EN CASO
                DE QUERER CAMBIAR EL AFILIADO, SELECCIONO OTRO PARA HABILITAR NUEVAMENTE EL CAMPO
                ======================================-->
                <script>

                  $(document).ready( function () {
                    var afiliado = $("#seleccionarAfiliado").val();
                    if(!afiliado == ''){
                      $('#seleccionarAfiliado').attr("readonly",true);
                    }
                  });
                </script>

                <div class="form-group">
                <label>Afiliado:</label>

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-users"></i></span>

                    <?php

                      $item = "clave";
                      $valor = $pedido['afiliados_id'];

                      $afiliados = ControladorAfiliados::ctrMostrarAfiliados($item, $valor);

                    ?>

                    <input type="text" class="form-control" id="seleccionarAfiliado" value="<?php echo $afiliados["nombre"]; ?>" required placeholder="Escriba el nombre del afiliado para seleccionar">
                    <input type="hidden" name="seleccionarAfiliado" value="<?php echo $pedido["afiliados_id"]; ?>" id="idAfiliado" required>

                    <span class="input-group-addon"><button type="button" class="btn btn-primary btn-xs" id="seleccionarNuevo">Seleccionar Otro</button></span>

                  </div>

                </div>
                <label>Productos: </label>

                <!--=====================================
                ENTRADA PARA AGREGAR PRODUCTO
                ======================================-->

                <div class="form-group row nuevoProducto">

                <?php

                $listaProducto = json_decode($pedido["productos"], true);

                foreach ($listaProducto as $key => $value) {

                  $item = "id";
                  $valor = $value["id"];
                  $orden = "id";

                  $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);

                  $stockAntiguo = $respuesta["stock"] + $value["cantidad"];

                  echo '<div class="row" style="padding:5px 15px">

                        <div class="col-xs-6" style="padding-right:0px">

                          <div class="input-group">

                            <span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProductos" idProducto="'.$value["id"].'"><i class="fa fa-times"></i></button></span>

                            <input type="text" class="form-control nuevaDescripcionProducto" idProducto="'.$value["id"].'" name="agregarProducto" value="'.$value["descripcion"].'" readonly required>

                          </div>

                        </div>

                        <div class="col-xs-3">

                          <input type="number" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="'.$value["cantidad"].'" stock="'.$stockAntiguo.'" nuevoStock="'.$value["stock"].'" required>

                        </div>

                        <div class="col-xs-3 ingresoPrecio" style="padding-left:0px">

                          <div class="input-group">

                            <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>

                            <input type="text" class="form-control nuevoPrecioProducto" precioReal="'.$respuesta["precio"].'" name="nuevoPrecioProducto" value="'.$value["total"].'" readonly required>

                          </div>

                        </div>

                      </div>';
                }


                ?>

                </div>

                <input type="hidden" id="listaProductos" name="listaProductos">

                <!--=====================================
                BOTÓN PARA AGREGAR PRODUCTO
                ======================================-->

                <button type="button" class="btn btn-default hidden-lg btnAgregarProducto">Agregar producto</button>

                <hr>

                <div class="row">

                  <!--=====================================
                  ENTRADA TOTAL Y PAGOS
                  ======================================-->

                  <div class="col-xs-8 pull-left">

                    <table class="table">

                    <thead>

                      <tr>
                        <th>Total Pedido</th>
                      </tr>

                    </thead>

                      <tbody>

                        <tr>


                           <td style="width: 50%">

                            <div class="input-group">

                              <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>

                              <input type="text" class="form-control input-lg" id="nuevoTotalPedido" name="nuevoTotalPedido" total="<?php echo $pedido["importe"]; ?>" value="<?php echo $pedido["importe"]; ?>" readonly required>

                              <input type="hidden" name="totalPedido" value="<?php echo $pedido["importe"]; ?>" id="totalPedido">

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

                          /*if($value["id"] == $pedido['pagos_id']){
                            $selected = "selected";
                          }else{
                            $selected = "";
                          }*/

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

                          if($value["id"] == $pedido['estados_id']){
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

                    <textarea style="resize: none;" class="form-control" rows="3" name="observaciones" placeholder="Ingrese alguna observación..."><?php echo $pedido["observaciones"]; ?></textarea>

                  </div>

                </div>

                <br>



          </div>

          <div class="box-footer">

            <button type="submit" class="btn btn-success pull-right">Guardar cambios</button>
            <a href="pedidos"><button type="button" class="btn btn-default pull-right">Cancelar</button></a>
          </div>

        </form>

        <?php

          $editarPedido = new ControladorPedidos();
          $editarPedido -> ctrEditarPedido();

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

<!--=====================================
MODAL AGREGAR AFILIADO
======================================-->

<div id="modalAgregarCliente" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar afiliado</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-user"></i></span>

                <input type="text" class="form-control input-lg" name="nuevoCliente" placeholder="Ingresar nombre" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL DOCUMENTO ID -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-key"></i></span>

                <input type="number" min="0" class="form-control input-lg" name="nuevoDocumentoId" placeholder="Ingresar documento" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL EMAIL -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>

                <input type="email" class="form-control input-lg" name="nuevoEmail" placeholder="Ingresar email" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-phone"></i></span>

                <input type="text" class="form-control input-lg" name="nuevoTelefono" placeholder="Ingresar teléfono" data-inputmask="'mask':'(999) 999-9999'" data-mask required>

              </div>

            </div>

            <!-- ENTRADA PARA LA DIRECCIÓN -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>

                <input type="text" class="form-control input-lg" name="nuevaDireccion" placeholder="Ingresar dirección" required>

              </div>

            </div>

             <!-- ENTRADA PARA LA FECHA DE NACIMIENTO -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

                <input type="text" class="form-control input-lg" name="nuevaFechaNacimiento" placeholder="Ingresar fecha nacimiento" data-inputmask="'alias': 'yyyy/mm/dd'" data-mask required>

              </div>

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar cliente</button>

        </div>

      </form>

      <?php

        $crearCliente = new ControladorClientes();
        $crearCliente -> ctrCrearCliente();

      ?>

    </div>

  </div>

</div>
