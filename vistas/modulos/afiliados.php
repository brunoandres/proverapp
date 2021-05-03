<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Afiliados SOYEM <label class="label label-success"> Activos</label>

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Administrar afiliados</li>

    </ol>

  </section>


  <section class="content">

    <div class="box">

      <div class="box-header with-border">

        <a href="afiliados-no-socios">Ver Afiliados No Socios</a>

      </div>

      <div class="box-body">

      <table id="post_list" class="table table-bordered table-striped dt-responsive" width="100%">

        <thead>

          <tr>
            <th>Nombre Afiliado</th>
            <th>Legajo</th>
            <th>Documento</th>
            <th>Opciones</th>
          </tr>

        </thead>

      </table>

      </div>

    </div>

  </section>

</div>

<!--=====================================
MODAL AGREGAR AFILIADO
======================================-->

<div id="modalAgregarAfiliado" class="modal fade" role="dialog">

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

                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                <input type="text" class="form-control input-lg" name="nuevoNombre" placeholder="Ingresar Nombre" required autocomplete="off">

              </div>

            </div>

            <!-- ENTRADA PARA EL APELLIDO -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                <input type="text" class="form-control input-lg" name="nuevoApellido" placeholder="Ingresar Apellido" required autocomplete="off">

              </div>

            </div>

            <!-- ENTRADA PARA EL LEGAJO -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                <input type="number" class="form-control input-lg" name="nuevoLegajo" placeholder="Ingresar Legajo" required>

              </div>

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar afiliado</button>

        </div>

        <?php

          $crearAfiliado = new ControladorAfiliados();
          $crearAfiliado -> ctrCrearAfiliado('afiliados');

        ?>

      </form>

    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR AFILIADO
======================================-->

<div id="modalEditarAfiliado" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar afiliado</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                <input type="text" class="form-control input-lg" name="editarNombre" id="nombre" required>

                 <input type="hidden" name="idAfiliado" id="idAfiliado" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL APELLIDO -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                <input type="text" class="form-control input-lg" name="editarApellido" id="apellido" required>

              </div>

            </div>

            <!-- ENTRADA PARA EL LEGAJO -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                <input type="text" class="form-control input-lg" name="editarLegajo" id="legajo" required>

              </div>

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

          $editarAfiliado = new ControladorAfiliados();
          $editarAfiliado -> ctrEditarAfiliado();

        ?>

      </form>

    </div>

  </div>

</div>

<?php

  $borrarCategoria = new ControladorCategorias();
  $borrarCategoria -> ctrBorrarCategoria();

?>
