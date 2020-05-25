<?php
  $botonAcciones = " disabled='disabled'";
  if ($_SESSION["perfil"] === "Administrador" || $_SESSION["perfil"] === "Pedidos"){
    $botonAcciones = "";
  }
?>
<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Administrar Productos

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Administrar Productos</li>

    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">

        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarProducto" <?php echo $botonAcciones; ?>>

          Agregar producto

        </button>

        <label class="label label-danger">STOCK BAJO</label>
        <label class="label label-warning">STOCK MEDIO</label>
        <label class="label label-success">STOCK ALTO</label>

      </div>

      <div class="box-body">

       <table class="table table-bordered table-striped dt-responsive tablaProductos" width="100%">

        <thead>

          <tr>

           <th>Nombre</th>
           <th>Desc.</th>
           <th>Categoria</th>
           <th>Stock</th>
           <th>Precio</th>
           <th>U.Medida</th>
           <th>Estado</th>
           <th>Imagen</th>
           <th>Acciones</th>

         </tr>

        </thead>

       </table>

      </div>

    </div>

  </section>

</div>

<!--=====================================
MODAL AGREGAR PRODUCTO
======================================-->

<div id="modalAgregarProducto" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar producto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-code"></i></span>

                <input type="text" class="form-control input-lg" name="nombre" placeholder="Ingresar nombre" required autocomplete="off">

              </div>

            </div>

            <!-- ENTRADA PARA LA DESCRIPCIÓN -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                <input type="text" class="form-control input-lg" name="descripcion" placeholder="Ingresar descripción" required autocomplete="off">

              </div>

            </div>

            <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->
            <div class="row">
              <div class="col-lg-7">

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-th"></i></span>

                    <select class="form-control input-lg select2" style="width:100%!important;" id="nuevaCategoria" name="categoria" required>

                      <option value="">Selecionar categoría</option>

                      <?php

                      $item = null;
                      $valor = null;

                      $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);

                      foreach ($categorias as $key => $value) {

                        echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                      }

                      ?>

                    </select>

                  </div>

                </div>

              </div>

              <div class="col-lg-5">

              <!-- ENTRADA PARA EL CÓDIGO -->

                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-code"></i></span>

                    <input type="text" class="form-control input-lg" id="codigo" name="codigo" placeholder="# Código categoria" readonly required>

                  </div>

                </div>

              </div>

            </div>


            <!-- ENTRADA PARA LA UNIDAD DE MEDIDA -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                <select class="form-control input-lg select2" style="width:100%!important;" name="medida" required >

                  <option value="">Selecionar unidad medida</option>

                  <?php

                  $item = null;
                  $valor = null;

                  $unidades = ControladorProductos::ctrMostrarUnidades($item, $valor);

                  foreach ($unidades as $key => $value) {

                    echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                  }

                  ?>

                </select>

              </div>

            </div>

             <!-- ENTRADA PARA STOCK -->
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-check"></i></span>

                    <input type="number" class="form-control input-lg" name="stock" min="0" placeholder="Stock" required>

                  </div>

                </div>
              </div>
              <div class="col-lg-6">
                <!-- ENTRADA PARA PRECIO  -->

                <div class="form-group row">

                  <div class="col-xs-12 col-sm-12">

                    <div class="input-group">

                      <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>

                      <input type="number" class="form-control input-lg" name="precio" min="0" step="any" placeholder="Precio" required>

                    </div>

                  </div>
                </div>

              </div>

            </div>

            <div class="form-group">

              <div class="input-group">

                  <div class="form-group">

                    <label>
                      <input type="checkbox" name="publicado" checked>
                      Producto disponible
                    </label>

                  </div>

              </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="form-group">

              <div class="panel">SUBIR IMAGEN</div>

              <input type="file" class="nuevaImagen" name="imagen">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar producto</button>

        </div>

      </form>

        <?php

          $crearProducto = new ControladorProductos();
          $crearProducto -> ctrCrearProducto('productos');

        ?>

    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR PRODUCTO
======================================-->

<div id="modalEditarProducto" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar producto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">


            <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                <select class="form-control input-lg" name="editarCategoria" readonly required>

                  <option id="editarCategoria"></option>


                </select>
                  <input type="hidden" id="id" name="idProducto">
              </div>

            </div>

            <!-- ENTRADA PARA EL CÓDIGO -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-code"></i></span>

                <input type="text" class="form-control input-lg" id="editarCodigo" name="editarCodigo" readonly required>

              </div>

            </div>

            <!-- ENTRADA PARA EL NOMBRE -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-code"></i></span>

                <input type="text" class="form-control input-lg" id="editarNombre" name="editarNombre" placeholder="Ingresar nombre" required autocomplete="off">

              </div>

            </div>

            <!-- ENTRADA PARA LA DESCRIPCIÓN -->

             <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>

                <input type="text" class="form-control input-lg" id="editarDescripcion" name="editarDescripcion" required autocomplete="off">

              </div>

            </div>

            <!-- ENTRADA PARA LA UNIDAD DE MEDIDA -->

            <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-th"></i></span>

                <select class="form-control input-lg select2" style="width:100%!important;" id="editarUnidad" name="editarUnidad" required>

                  <option value="">Selecionar unidad medida</option>

                  <?php

                  $item = null;
                  $valor = null;

                  $unidades = ControladorProductos::ctrMostrarUnidades($item, $valor);

                  foreach ($unidades as $key => $value) {

                    echo '<option value="'.$value["id"].'">'.$value["nombre"].'</option>';
                  }

                  ?>

                </select>

              </div>


            </div>

             <!-- ENTRADA PARA STOCK -->

             <div class="form-group">

              <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-check"></i></span>

                <input type="number" class="form-control input-lg" id="editarStock" name="editarStock" min="0" required>

              </div>

            </div>

             <!-- ENTRADA PARA PRECIO -->

             <div class="form-group row">

                <div class="col-xs-12">

                  <div class="input-group">

                    <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span>

                    <input type="number" class="form-control input-lg" id="editarPrecio" name="editarPrecio" step="any" min="0" required>

                  </div>

                </div>

            </div>

            <div class="form-group">

              <div class="input-group">

                  <div class="form-group">

                    <label>
                      <input type="checkbox" value="1" id="estado" name="publicado">
                      Producto disponible
                    </label>

                  </div>

                </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="form-group">

              <div class="panel">SUBIR IMAGEN</div>

              <input type="file" class="nuevaImagen" name="editarImagen">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">

              <input type="hidden" name="imagenActual" id="imagenActual">

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

      </form>

        <?php

          $editarProducto = new ControladorProductos();
          $editarProducto -> ctrEditarProducto();

        ?>

    </div>

  </div>

</div>

<?php

  $eliminarProducto = new ControladorProductos();
  $eliminarProducto -> ctrEliminarProducto();

?>
