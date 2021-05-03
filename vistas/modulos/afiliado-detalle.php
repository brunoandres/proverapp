<?php

//DATOS DEL AFILIADO

$item = "clave";
$valor = SED::decryption($_GET["ref"]);
$datos = ControladorAfiliados::ctrMostrarAfiliados($item,$valor);

if (empty($datos)) {
  echo "<script>window.location='inicio';</script>";
}

$item2 = "afiliados_id";
$compras = ControladorAfiliados::ctrComprasAfiliados($item2,$valor);


?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Datos Afiliado
      </h1>
      <ol class="breadcrumb">
        <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="#">Afiliado</a></li>
        <li class="active">Datos</li>
      </ol>
    </section>
    <?php //var_dump($compras); ?>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="vistas/img/usuarios/default/anonymous.png" alt="User profile picture">

              <h3 class="profile-username text-center"><?php echo $datos["nombre"]; ?></h3>

              <p class="text-muted text-center">Tel: <?php echo $datos["telefono"]; ?></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Legajo</b> <a class="pull-right"><?php echo $datos["legajo"]; ?></a>
                </li>
                <li class="list-group-item">
                  <b>Socio Adherido 3%</b> <a class="pull-right"><?php if ($datos["socioos"] == "si") {
                    echo "Si";
                  }else{
                    echo "No";
                  } ?></a>
                </li>
                <li class="list-group-item">
                  <b>Total Compras</b> <a class="pull-right"><?php echo count($compras); ?></a>
                </li>
              </ul>

              <!--<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Acerca</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Fecha Afiliación</strong>

              <p class="text-muted">
                <?php echo date('d/m/Y', strtotime($datos["afiliacion"])); ?>
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Domicilio</strong>

              <p class="text-muted"><?php echo $datos["domicilio"]; ?></p>

              <!--<hr>

              <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>

              <p>
                <span class="label label-danger">UI Design</span>
                <span class="label label-success">Coding</span>
                <span class="label label-info">Javascript</span>
                <span class="label label-warning">PHP</span>
                <span class="label label-primary">Node.js</span>
              </p>

              <hr>-->

              <strong><i class="fa fa-file-text-o margin-r-5"></i> Notas</strong>

              <p><?php echo $datos["observaciones"]; ?></p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">

              <li class="active"><a href="#timeline" data-toggle="tab">Compras</a></li>
              <!--<li><a href="#settings" data-toggle="tab">Settings</a></li>-->
            </ul>
            <div class="tab-content">

              <!-- /.tab-pane -->
              <div class="active tab-pane" id="timeline">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">

                  <?php if (empty($compras)): ?>
                    <div class="alert alert-warning alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                      El Afiliado no posee compras realizadas.
                    </div>
                  <?php endif; ?>

                  <?php foreach ($compras as $key => $pedido) { ?>

                    <!-- timeline time label -->
                    <li class="time-label">
                          <span class="bg-red">
                            <?php setlocale(LC_ALL,"es_ES");
                              $mi_fecha = $pedido["fecha_pedido"];
                              $mi_fecha = str_replace("-", "/", $mi_fecha);
                              $nueva_Fecha = date("d-m-Y", strtotime($mi_fecha));
                              $mes_Anyo = strftime("%A, %d de %B de %Y", strtotime($nueva_Fecha));
                              //devuelve: lunes, 16 de abril de 2018
                              echo htmlentities($mes_Anyo,null,"ISO-8859-1");
                            ?>

                          </span>
                    </li>
                    <!-- /.timeline-label -->
                    <!-- timeline item -->
                    <li>
                      <i class="fa fa-shopping-cart bg-blue"></i>

                      <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                        <h3 class="timeline-header"><a href="#">Pedido Nro. <?php echo $pedido["id"]; ?></a> Importe: $ <?php echo $pedido["importe"]; ?></h3>

                        <div class="timeline-body">
                          <?php $productos = json_decode($pedido["productos"],true); ?>
                          <?php
                          echo "<table class='table'>
                            <thead>
                              <tr>
                                <th scope='col'>#</th>
                                <th scope='col'>Descripción</th>
                                <th scope='col'>Cantidad</th>
                                <th scope='col'>Precio</th>
                              </tr>
                            </thead>
                            <tbody>";
                          foreach ($productos as $key => $producto) {
                            echo "<tr>
                              <th scope='row'>".($key+1)."</th>
                              <td>".$producto["descripcion"]."</td>
                              <td>".$producto["cantidad"]."</td>
                              <td>$ ".$producto["precio"]."</td>
                            </tr>";
                          }

                            echo "</tbody>
                            </table>";?>
                        </div>
                        <div class="timeline-footer">
                          <a class="btn btn-primary btn-xs">Read more</a>
                          <a class="btn btn-danger btn-xs">Delete</a>
                        </div>
                      </div>
                    </li>
                    <!-- END timeline item -->
                    <!-- timeline item
                    <li>
                      <i class="fa fa-user bg-aqua"></i>

                      <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                        <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request
                        </h3>
                      </div>
                    </li>-->
                    <!-- END timeline item -->
                    <!-- timeline item
                    <li>
                      <i class="fa fa-comments bg-yellow"></i>

                      <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                        <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                        <div class="timeline-body">
                          Take me to your leader!
                          Switzerland is small and neutral!
                          We are more like Germany, ambitious and misunderstood!
                        </div>
                        <div class="timeline-footer">
                          <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                        </div>
                      </div>
                    </li>-->
                  <?php } ?>

                  <!-- END timeline item -->
                  <!-- timeline time label 1
                  <li class="time-label">
                        <span class="bg-green">
                          3 Jan. 2014
                        </span>
                  </li>
                  <!-- /.timeline-label
                  <!-- timeline item
                  <li>
                    <i class="fa fa-camera bg-purple"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                      <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                      <div class="timeline-body">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                  </li>
                </ul>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="settings">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                    <div class="col-sm-10">
                      <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Skills</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
