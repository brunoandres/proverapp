<div class="content-wrapper">

  <section class="content-header">

    <h1>

      Bienvendio/a <?php echo $_SESSION["nombre"]; ?>

      <small>Sistema de Proveeduria</small>

    </h1>

    <ol class="breadcrumb">

      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>

      <li class="active">Tablero</li>

    </ol>

  </section>

  <section class="content">

    <div class="row">

    <?php

    if($_SESSION["iniciarSesion"] =="ok"){

      include "inicio/cajas-superiores.php";


    }

    ?>

    </div>


     <div class="row">

        <div class="col-lg-12">

          <?php

          if($_SESSION["iniciarSesion"] =="ok"){

           include "reportes/grafico-pedidos.php";

          }

          ?>

        </div>

        <div class="col-lg-6">

          <?php

          if($_SESSION["iniciarSesion"] =="ok"){

           include "reportes/productos-mas-vendidos.php";

         }

          ?>

        </div>

         <div class="col-lg-6">

          <?php

          if($_SESSION["iniciarSesion"] =="ok"){

           include "inicio/productos-recientes.php";

         }

          ?>

        </div>

         <div class="col-lg-12">

          <?php

          if($_SESSION["perfil"] =="Especial" || $_SESSION["perfil"] =="Vendedor"){

             echo '<div class="box box-success">

             <div class="box-header">

             <h1>Bienvenid@ ' .$_SESSION["nombre"].'</h1>

             </div>

             </div>';

          }

          ?>

         </div>

     </div>
     <div class="row">
       <?php
       if($_SESSION["iniciarSesion"] =="ok"){


         //include "inicio/alertas-paneles.php";

       }
       ?>
     </div>

  </section>

</div>
