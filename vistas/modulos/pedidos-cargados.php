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
      Pedidos Cargados Al Sistema Administrativo
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
        <button type="button" class="btn btn-default pull-right" id="daterange-btn">

            <span>
              <i class="fa fa-calendar"></i> Rango de fecha
            </span>

            <i class="fa fa-caret-down"></i>

         </button>

      </div>

      <div class="box-body">
      <!--<form id="tablaPedidos" method="POST">-->
      <table id="example" class="display" style="width:100%">
              <thead>
                  <tr>
                      <th>#Nro</th>
                      <th>Afiliado</th>
                      <th>Legajo</th>
                      <th>Importe</th>
                      <th>Fecha Pedido</th>
                      <th>Fecha Pago</th>
                      <th>Nro Asiento</th>
                  </tr>
              </thead>
              <tbody>
                <?php


                    $item = null;
                    $valor = null;
                    $tabla = "pedidos";
                    $respuesta = ModeloPedidos::mdlMostrarPedidosConAsiento($tabla,$item, $valor);


                  foreach ($respuesta as $key => $value) {


                  //BUSCO AFILIADO
                  $itemAfiliado = "clave";
                  $valorAfiliado = $value["afiliados_id"];
                  $respuestaAfiliados = ControladorAfiliados::ctrMostrarAfiliados($itemAfiliado, $valorAfiliado);


                  $itemUsuario = "id";
                  $valorUsuario = $value["usuarios_id"];
                  $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);

                  //BUSCO ESTADO DEL PEDIDO
                  $item = "id";
                  $valor = $value["estados_id"];
                  $estado = ControladorPedidos::ctrMostrarEstados($item, $valor);

                  //USUARIO PERMITIDO PARA EDITAR PEDIDO YA ENTREGADO
                  $editar = null;


                  echo '<tr>

                          <td>'.$value["numero"].'</td>';

                          echo '<td>'.$respuestaAfiliados['nombre'].'</td>';

                          echo '<td>'.$respuestaAfiliados['legajo'].'</td>';

                          echo '<td>$ '.number_format($value["importe"],2).'</td>

                          <td>'.date('d/m/Y', strtotime($value["fecha_pedido"])).'</td>

                          <td>'.date('d/m/Y', strtotime($value["fecha_pago"])).'</td>

                          <td>'.$value["fk_nro_asiento"].'</td>


                        </tr>';
                    }

                ?>
              </tbody>
              <tfoot>
                  <tr>
                      <th>Name</th>
                      <th>Position</th>
                      <th>Office</th>
                      <th>Age</th>
                      <th>Start date</th>
                      <th>Salary</th>
                  </tr>
              </tfoot>
          </table>



        </div>

      </div>

    </div>

  </section>

</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
<script>

$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [

          {
                extend: 'excelHtml5',
                title: 'Pedidos Cargados'
            },
            {
                extend: 'pdfHtml5',
                title: 'Pedidos Cargados'
            },
            'copyHtml5',

            'csvHtml5'

        ]
    } );
} );

</script>
