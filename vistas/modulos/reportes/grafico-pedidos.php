<?php

error_reporting(0);

if(isset($_GET["fechaInicial"])){

    $fechaInicial = $_GET["fechaInicial"];
    $fechaFinal = $_GET["fechaFinal"];

}else{

$fechaInicial = null;
$fechaFinal = null;

}

$respuesta = ControladorPedidos::ctrRangoFechasPedidos($fechaInicial, $fechaFinal);

$arrayFechas = array();
$arrayVentas = array();
$sumaPagosMes = array();

foreach ($respuesta as $key => $value) {

	#Capturamos sólo el año y el mes
  $fecha = substr($value["fecha_pedido"],0,7);

	#Introducir las fechas en arrayFechas
	array_push($arrayFechas, $fecha);

	#Capturamos las ventas
	$arrayVentas = array($fecha => $value["importe"]);

	#Sumamos los pagos que ocurrieron el mismo mes
	foreach ($arrayVentas as $key => $value) {

    $sumaPagosMes[$key] += $value;

	}

}
$noRepetirFechas = array_unique($arrayFechas);
?>

<!--=====================================
GRÁFICO DE VENTAS
======================================


<div class="box box-solid bg-teal-gradient">

	<div class="box-header">

 		<i class="fa fa-th"></i>

  		<h3 class="box-title">Gráfico de Pedidos</h3>

	</div>

	<div class="box-body border-radius-none nuevoGraficoPedidos">

		<div class="chart" id="graficoPedidos" style="height: 250px;"></div>

  </div>

</div>

<script>

 var line = new Morris.Line({
    element          : 'graficoPedidos',
    resize           : true,
    data             : [

    <?php

    if($noRepetirFechas != null){

	    foreach($noRepetirFechas as $key){

	    	echo "{ p: '".$key."', pedidos: ".$sumaPagosMes[$key]." },";


	    }

	    echo "{p: '".$key."', pedidos: ".$sumaPagosMes[$key]." }";

    }else{

       echo "{ p: '0', pedidos: '0' }";

    }

    ?>

    ],
    xkey             : 'p',
    ykeys            : ['pedidos'],
    labels           : ['Total'],
    //LINEA TENDENCIA
    lineColors       : ['#FB1F04'],
    lineWidth        : 2,
    hideHover        : 'auto',
    //NUMEROS
    gridTextColor    : '#fff',
    gridStrokeWidth  : 0.4,
    pointSize        : 4,
    //COLOR CIRCULO PUNTOS
    pointStrokeColors: ['#E87318'],
    //LINEAS GRILLA DE PRECIOS
    gridLineColor    : '#FAFAFA',
    gridTextFamily   : 'Source Sans Pro',
    preUnits         : '$',
    gridTextSize     : 14

  });

</script>-->
