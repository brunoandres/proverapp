<?php

//ID DEL PEDIDO
$item = "id";
$idEntrega = $_GET["entregaNumero"];

//DESENCRIPTO EL VALOR DEL ID PARA PROTEGERLO
$valorId = SED::decryption($idEntrega);

//DATOS DEL PEDIDO
$entrega = ControladorEntregas::ctrMostrarEntregas($item, $valorId);

if(empty($entrega)){
  echo'<script>
            window.location = "entregas";

          </script>';
}

?>
<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Editar <strong>Solicitud N° <?php echo $entrega['numero']; ?></strong>

    </h1>

    <ol class="breadcrumb">

      <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Editar solicitud</li>

    </ol>

  </section>

  <section class="content">

    <div class="row">

      <!--=====================================
      EL FORMULARIO
      ======================================-->

      <div class="col-lg-5">

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
                    <input type="text" class="form-control pull-right" id="datepicker" value="<?php echo date('d/m/Y', strtotime($entrega["fecha"])); ?>" name="fechaEntrega" autocomplete="off" readonly>
                  </div>

                </div>

                <!--=====================================
                ENTRADA DEL VENDEDOR
                ======================================-->
                <div class="form-group">
                <p><strong>Usuario Entrega :</strong> </p>
                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-user"></i></span>

                    <input type="text" class="form-control" id="nuevoVendedor" value="<?php echo $_SESSION["nombre"]; ?>" readonly>

                    <input type="hidden" name="idUsuario" value="<?php echo $_SESSION["id"]; ?>">
                    <input type="hidden" name="idEntrega" value="<?php echo $valorId; ?>">

                  </div>

                </div>

                <!--=====================================
                ENTRADA DEL CÓDIGO
                ======================================-->

                <div class="form-group">
                <p><strong>Entrega N°:</strong> </p>
                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                    <input type="text" class="form-control" id="nuevoNumero" name="nuevoNumero" value="<?php echo $entrega["numero"]; ?>" readonly>

                  </div>

                </div>

                <!--=====================================
                ENTRADA ESTADO ENTREGA
                ======================================

                <div class="form-group row">

                  <div class="col-xs-6" style="padding-right:0px">
                    <label>Estado solicitud:</label>
                        <div class="input-group">

                        <select class="form-control" id="estadoEntrega" name="estadoEntrega" required>
                        <option value="">Seleccione Estado</option>
                        <?php

                        /*$item = null;
                        $valor = null;

                        $estados = ControladorPedidos::ctrMostrarEstados($item, $valor);

                            foreach ($estados as $key => $value) {

                            if($value["estado"] == "Pendiente"){
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }

                            echo '<option value="'.$value["id"].'" '.$selected.'>'.$value["estado"].'</option>';

                          }*/

                        ?>
                        </select>
                    </div>

                  </div>

                </div>-->

                <!--=====================================
            ENTRADA OBSERVACIONES
            ======================================-->
            <div class="form-group">
            <p><strong>Observaciones</strong> </p>
              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-user"></i></span>

                <textarea style="resize: none;" class="form-control" rows="3" name="observaciones" placeholder="Ingrese alguna observación..."><?php echo $entrega['observaciones']; ?></textarea>

              </div>

            </div>

          </div>

        </div>

      </div>

      <!--=====================================
      LA TABLA DE PEDIDOS
      ======================================-->

      <div class="col-lg-7">

        <div class="box box-warning">

          <div class="box-header with-border"></div>

          <div class="box-body">

            <table class="table table-bordered">

               <thead class="thead-dark">
                <tr>
                  <th style="width: 20px">N°</th>
                  <th>Fecha</th>
                  <th>Importe</th>
                  <th>Estado</th>
                  <th>Entrega</th>
                  <th>Usuario Pedido</th>
                  <!--<th>Seleccionar</th>-->
                  <th><input type="checkbox" id="selectall"/></th>
                </tr>

              </thead>

              <tbody>

              <?php


                //TRAIGO LOS PEDIDOS PARA MOSTRAR TABLA
                $item = null;
                $valor = null;

                $respuesta = ControladorPedidos::ctrMostrarPedidos($item, $valor);

                foreach ($respuesta as $key => $value) {

                  //BUSCAR EL NUMERO DE ENTREGA
                  $itemA = "id";
                  $valorA = $value["entregas_id"];
                  $numeroEntrega = ControladorEntregas::ctrMostrarEntregas($itemA,$valorA);

                  $disabled = ' disabled="disabled"';
                  $checked = "";
                  $title = "";
                  if(is_null($value["entregas_id"])){

                    $entregas = null;
                    $disabled = '';
                    $numero = "<label class='label label-danger'>No posee</label>";
                    $title = "Pedido disponible";

                  }else{

                    $entregas = $value["entregas_id"];

                    //AL EDITAR UNA ENTREGA ME ASEGURO QUE LOS PEDIDO SEA DE LA MISMA
                    if($entregas == $valorId){
                      //echo "EL pedido pertenece a la entrega";
                      $checked = " checked='checked'";
                      $disabled = '';
                    }
                    $numero = "N° ".$numeroEntrega["numero"];

                  }

                  //BUSCAR MÉTODOS DE PAGO
                  $item = "id";
                  $valor = $value["pagos_id"];
                  $metodo_pago = ControladorPedidos::ctrMostrarMetodos($item, $valor);

                  //BUSCAR AFILIADO
                  $itemAfiliado = "clave";
                  $valorAfiliado = $value["afiliados_id"];
                  $respuestaAfiliados = ControladorAfiliados::ctrMostrarAfiliados($itemAfiliado, $valorAfiliado);

                  //BUSCAR USUARIOS
                  $itemUsuario = "id";
                  $valorUsuario = $value["usuarios_id"];
                  $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);


                  //BUSCAR ESTADOS
                  $item = "id";
                  $valor = $value["estados_id"];
                  $estado = ControladorPedidos::ctrMostrarEstados($item, $valor);

                  //ESTADO
                  if($estado['estado'] ==  "Pendiente"){
                    $estadoPedido = '<label class="label label-default">'.$estado['estado'].'</label>';
                  }else if($estado['estado'] ==  "Entregado"){
                    $estadoPedido = '<label class="label label-success">'.$estado['estado'].'</label>';
                  }else if($estado['estado'] ==  "Preparado"){
                    $estadoPedido = '<label class="label label-warning">'.$estado['estado'].'</label>';
                  }else{
                    $estadoPedido = '<label class="label label-danger">'.$estado['estado'].'</label>';
                  }

                  echo '<tr>

                    <td>'.$value["numero"].'</td>';

                    echo '<td>'.date('d/m/Y', strtotime($value["fecha_pedido"])).'</td>';

                    echo '<td><strong>$ '.number_format($value["importe"],2).'</strong></td>';

                    echo '<td>'.$estadoPedido.'</td>

                    <td><strong>'.$numero.'</strong></td>
                    <td>'.$respuestaUsuario["nombre"].'</td>

                    <td>

                      <div class="btn-group">';

                        echo '<input type="checkbox" class="disponibles" name="pedidos[]" title="'.$title.'" value="'.$value["id"].'" '.$checked.' '.$disabled.'>

                      </div>

                    </td>

                  </tr>';
              }

              ?>

              </tbody>

            </table>

          </div>
          <div class="box-footer">

            <button type="submit" name="editarEntrega" class="btn btn-success pull-right">Guardar Cambios</button>
            <a href="entregas"><button type="button" class="btn btn-default pull-right">Cancelar</button></a>

          </div>

          </form>

        </div>

      </div>

    </div>

  </section>

</div>

<?php

$editarEntrega = new ControladorEntregas();
$editarEntrega -> ctrEditarEntrega();

?>
