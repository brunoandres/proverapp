<?php

$item = null;
$valor = null;

$pedidos = ControladorPedidos::ctrMostrarPedidos($item, $valor);
$afiliados = ControladorAfiliados::ctrMostrarAfiliados($item, $valor);

$arrayAfiliados = array();
$arraylistaAfiliados = array();

foreach ($pedidos as $key => $valuePedidos) {

  foreach ($afiliados as $key => $valueAfiliados) {

      if($valueAfiliados["clave"] == $valuePedidos["afiliados_id"]){

        #Capturamos los Clientes en un array
        array_push($arrayAfiliados, $valueAfiliados["nombre"]);

        #Capturamos las nombres y los valores netos en un mismo array
        $arraylistaAfiliados = array($valueAfiliados["nombre"] => $valuePedidos["importe"]);

        #Sumamos los netos de cada afiliado
        foreach ($arraylistaAfiliados as $key => $value) {

          $sumaTotalAfiliados[$key] += $value;

        }

      }
  }

}

#Evitamos repetir nombre
$noRepetirNombres = array_unique($arrayAfiliados);

?>

<!--=====================================
VENDEDORES
======================================-->

<div class="box box-primary">

	<div class="box-header with-border">

    	<h3 class="box-title">Afiliados</h3>

  	</div>

  	<div class="box-body">

		<div class="chart-responsive">

			<div class="chart" id="bar-chart2" style="height: 300px;"></div>

		</div>

  	</div>

</div>

<script>

//BAR CHART
var bar = new Morris.Bar({
  element: 'bar-chart2',
  resize: true,
  data: [
     <?php

    foreach($noRepetirNombres as $value){

      echo "{y: '".$value."', a: '".$sumaTotalAfiliados[$value]."'},";

    }

  ?>
  ],
  barColors: ['#f6a'],
  xkey: 'y',
  ykeys: ['a'],
  labels: ['Pedidos'],
  preUnits: '$',
  hideHover: 'auto'
});


</script>
