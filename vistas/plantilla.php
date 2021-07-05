<?php

session_start();
date_default_timezone_set('America/Argentina/Buenos_Aires');

?>

<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>PROVEEDURIA SOYEM</title>

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <style>
  .progress-bar-pendiente {
    background-color: #9C9891 !important;
  }
  .progress-striped .progress-bar-pendiente {
    background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent)!important;
    background-image:      -o-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent)!important;
    background-image:         linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent)!important;
  }
  </style>

  <style>
@import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');
</style>
  <link rel="icon" href="vistas/img/plantilla/icono-blanco.png">

   <!--=====================================
  PLUGINS DE CSS
  ======================================-->
  <!-- link para autocomplete afiliado -->
  <link rel="stylesheet" href="vistas/bower_components/bootstrap/dist/css/jquery-ui.css">

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="vistas/bower_components/bootstrap/dist/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="vistas/bower_components/font-awesome/css/font-awesome.min.css">

  <!-- Ionicons -->
  <link rel="stylesheet" href="vistas/bower_components/Ionicons/css/ionicons.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="vistas/dist/css/AdminLTE.css">

  <!-- AdminLTE Skins -->
  <link rel="stylesheet" href="vistas/dist/css/skins/_all-skins.min.css">

  <!-- Google Font
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">-->

   <!-- DataTables -->
  <link rel="stylesheet" href="vistas/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!--<link rel="stylesheet" href="vistas/bower_components/datatables.net-bs/css/responsive.bootstrap.min.css">-->
  <link rel="stylesheet" href="vistas/bower_components/datatables.net-bs/css/responsive.bootstrap.css">

  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="vistas/plugins/iCheck/all.css">

  <!-- daterange picker -->
  <link rel="stylesheet" href="vistas/bower_components/bootstrap-daterangepicker/daterangepicker.css">

  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="vistas/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">

  <!-- Morris chart -->
  <link rel="stylesheet" href="vistas/bower_components/morris.js/morris.css">

  <!-- Select2 -->
  <link rel="stylesheet" href="vistas/bower_components/select2/dist/css/select2.min.css">

  <!-- Toast -->
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

  <!--=====================================
  PLUGINS DE JAVASCRIPT
  ======================================-->

  <!-- jQuery 3 -->
  <script src="vistas/bower_components/jquery/dist/jquery.min.js"></script>

  <!-- jQuery UI -->

  <script src="vistas/bower_components/jquery/dist/jquery-ui.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="vistas/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <!-- FastClick -->
  <script src="vistas/bower_components/fastclick/lib/fastclick.js"></script>

  <!-- AdminLTE App -->
  <script src="vistas/dist/js/adminlte.min.js"></script>

  <!-- DataTables -->
  <script src="vistas/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="vistas/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="vistas/bower_components/datatables.net-bs/js/dataTables.responsive.js"></script>
  <script src="vistas/bower_components/datatables.net-bs/js/responsive.bootstrap.js"></script>

  <!-- SweetAlert 2 -->
  <script src="vistas/plugins/sweetalert2/sweetalert2.all.js"></script>
   <!-- By default SweetAlert2 doesn't support IE. To enable IE 11 support, include Promise polyfill:-->
  <script src="vistas/plugins/sweetalert2/core.js"></script>

  <!-- iCheck 1.0.1 -->
  <script src="vistas/plugins/iCheck/icheck.min.js"></script>

  <!-- InputMask -->
  <script src="vistas/plugins/input-mask/jquery.inputmask.js"></script>
  <script src="vistas/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
  <script src="vistas/plugins/input-mask/jquery.inputmask.extensions.js"></script>

  <!-- jQuery Number -->
  <script src="vistas/plugins/jqueryNumber/jquerynumber.min.js"></script>

  <!-- daterangepicker http://www.daterangepicker.com/-->
  <script src="vistas/bower_components/moment/min/moment.min.js"></script>
  <script src="vistas/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>

  <!-- Morris.js charts http://morrisjs.github.io/morris.js/-->
  <script src="vistas/bower_components/raphael/raphael.min.js"></script>
  <script src="vistas/bower_components/morris.js/morris.min.js"></script>

  <!-- ChartJS http://www.chartjs.org/-->
  <script src="vistas/bower_components/chart.js/Chart.js"></script>

  <!-- Select2 -->
  <script src="vistas/bower_components/select2/dist/js/select2.full.min.js"></script>

  <!-- bootstrap datepicker -->
  <script src="vistas/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

  <script src="vistas/bower_components/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js"></script>

  <!-- Toast -->
  <script src="vistas/bower_components/toast/js/toastr.min.js"></script>

</head>

<!--=====================================
CUERPO DOCUMENTO
======================================-->

<!--<body class="hold-transition skin-blue sidebar-mini login-page">-->
<body class="hold-transition skin-blue sidebar-mini login-page">
  <?php

  if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok"){

   echo '<div class="wrapper">';

    /*=============================================
    CABEZOTE
    =============================================*/

    include "modulos/cabezote.php";

    /*=============================================
    MENU
    =============================================*/

    include "modulos/menu.php";

    /*=============================================
    CONTENIDO
    =============================================*/

    if(isset($_GET["ruta"])){

      if($_GET["ruta"] == "inicio"         ||
         $_GET["ruta"] == "usuarios"       ||
         $_GET["ruta"] == "afiliados"      ||
         $_GET["ruta"] == "afiliado-detalle"      ||
         $_GET["ruta"] == "afiliados-no-socios"      ||
         $_GET["ruta"] == "categorias"     ||
         $_GET["ruta"] == "productos"      ||
         $_GET["ruta"] == "clientes"       ||
         $_GET["ruta"] == "pedidos"        ||
         $_GET["ruta"] == "pedidos-cargados"        ||
         $_GET["ruta"] == "compras"        ||
         $_GET["ruta"] == "crear-compra"   ||
         $_GET["ruta"] == "crear-pedido"   ||
         $_GET["ruta"] == "editar-pedido"  ||
         $_GET["ruta"] == "editar-compra"  ||
         $_GET["ruta"] == "reportes"       ||
         $_GET["ruta"] == "crear-entrega"  ||
         $_GET["ruta"] == "editar-entrega" ||
         $_GET["ruta"] == "entregas"       ||
         $_GET["ruta"] == "perfil"         ||
         $_GET["ruta"] == "salir"){

        include "modulos/".$_GET["ruta"].".php";

      }else{

        include "modulos/404.php";

      }

    }else{

      include "modulos/inicio.php";

    }

    /*=============================================
    FOOTER
    =============================================*/

    include "modulos/footer.php";

    echo '</div>';

  }else{

    include "modulos/login.php";

  }

  ?>
  <script>
  //Date picker
  $('.datepicker').datepicker({
    autoclose: true,
    format: 'yyyy/mm/dd',
    language: "es"
  });

  </script>
  <script src="vistas/js/plantilla.js"></script>
  <script src="vistas/js/usuarios.js"></script>
  <script src="vistas/js/categorias.js"></script>
  <script src="vistas/js/productos.js"></script>
  <script src="vistas/js/clientes.js"></script>
  <script src="vistas/js/reportes.js"></script>
  <script src="vistas/js/afiliados.js"></script>
  <script src="vistas/js/entregas.js"></script>
  <script src="vistas/js/pedidos.js"></script>

  <!-- script para autocomplete afiliado -->
  <script>
    $(function () {
      $('.select2').select2();

      $(".js-example-basic-multiple-limit").select2({
        maximumSelectionLength: 2
      });

    })
  </script>

  <script>
    //BUSCAR AFILIADOS ACTIVOS
    $(document).ready(function(){
        var dataTable=$('#post_list').DataTable({
            "processing": true,
            "serverSide":true,
            "ajax":{
                url:"vistas/traer-afiliados.php",
                type:"post"
            },
            order: [[1, 'asc']]
        });
    });

    //BUSCAR AFILIADOS ACTIVOS NO SOCIOS
    $(document).ready(function(){
        var dataTable=$('#afiliados_no_socios').DataTable({
            "processing": true,
            "serverSide":true,
            "ajax":{
                url:"vistas/traer-afiliados-no-socios.php",
                type:"post"
            },
            order: [[1, 'asc']]
        });
    });

    //SELECIONAR UN AFILIADO PARA CARGAR UN PEDIDO
   $( "#seleccionarAfiliado" ).autocomplete({

    source: function( request, response ) {
     // Fetch data
     $.ajax({
      url: "vistas/select-afiliado.php",
      type: 'post',
      dataType: "json",
      data: {
       search: request.term
      },
      success: function(data) {
       response(data);

      }
     });
    },
    select: function (event, ui) {
      // Set selection
      $('#seleccionarAfiliado').val(ui.item.label); // display the selected text

      var es_socio = ui.item.es_socio;
      var legajo = ui.item.legajo;

      if (es_socio != 1) {
        $("#observacion").attr("required",true);
        swal({
          title: "El afiliado legajo "+legajo+" no está adherido a la obra social!",
          text: "Indique el motivo del pedido en observaciones...",
          type: "warning",
          timer: 8000
        }).then(function() {
            //window.location = "crear-pedido";
        });
      }

      $('#idAfiliado').val(ui.item.value); // save selected id to input
      return false;
      },
      minLength: 1
    });

    //SCRIPT PARA SELCCIONAR VARIOS REGISTROS DEL DATATABLE
    $(document).ready(function(){
      $('body').on('click', '#selectall', function() {
          $('.disponibles').prop('checked', this.checked);
      });

      $('body').on('click', '.disponibles', function() {
          if($(".disponibles").length == $(".disponibles:checked").length) {
              $("#selectall").prop("checked", "checked");
          } else {
              $("#selectall").removeAttr("checked");
          }

      });
    });

  </script>

  <script>
    var table;
    $(document).ready(function(){
      $("#tablaPedidos").submit(function(event){
        event.preventDefault();
        $.ajax({
          type: "POST",
          url: "pedidos",
          data: $(this).serialize(),
          success: function(data){

            swal({
						  title: "Pedidos modificados correctamente!",
						  text: "Redireccionando...",
						  type: "success",
						  timer: 2000
						}).then(function() {
						    window.location = "pedidos";
						});
          },
          error: function(data){

          }
        });
        table = $('#pedidos').dataTable();
      });
    });
  </script>
  <script>
    $(document).ready(function(){
      $('.view_data').click(function(){
           var pedidos_id = $(this).attr("id");
           $.ajax({
                url:"vistas/detalle-pedido.php",
                method:"post",
                data:{pedidos_id:pedidos_id},
                success:function(data){
                     $('#pedidos_detalles').html(data);
                     $('#dataModal').modal("show");
                }
           });
      });
      $('.entregas_detalle').click(function(){
           var entregas_id = $(this).attr("id");
           $.ajax({
                url:"vistas/detalle-entrega.php",
                method:"post",
                data:{entregas_id:entregas_id},
                success:function(data){
                     $('#entregas_detalles').html(data);
                     $('#dataModal').modal("show");
                }
           });
      });
      /*=============================================
      IMPRIMIR PEDIDOS
      =============================================*/

      $(".tablas").on("click", ".btnImprimirPedidos", function(){

      	var numeroEntrega = $(this).attr("numeroEntrega");

      	window.open("extensiones/tcpdf/pdf/pedidos.php?codigo="+numeroEntrega, "_blank");

      })
    });
  </script>

</body>
</html>
