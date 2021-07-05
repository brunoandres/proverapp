<?php

$item = null;
$valor = null;
$orden = "id";

$ventas = ControladorPedidos::ctrSumaTotalPedidos();

$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
$totalCategorias = count($categorias);

$afiliados = ControladorAfiliados::ctrMostrarAfiliados($item, $valor);
$totalAfiliados = count($afiliados);

$productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
$totalProductos = count($productos);

$item = null;
$valor = null;
$orden = "id";

$ventas = ControladorPedidos::ctrSumaTotalPedidos();

$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
$totalCategorias = count($categorias);

//TOTAL PEDIDOS
$pedidos = ControladorPedidos::ctrMostrarPedidos($item, $valor);
$totalPedidos = count($pedidos);

//TOTAL PEDIDOS PENDIENTES
$pedidosPendientes = ControladorPedidos::ctrMostrarPedidosPorEstado("estados_id", 1);
$totalPedidosPendientes = count($pedidosPendientes);

//TOTAL PEDIDOS PREPARADOS
$pedidosPreparados = ControladorPedidos::ctrMostrarPedidosPorEstado("estados_id", 2);
$totalPedidosPreparados = count($pedidosPreparados);


$entregas = ControladorEntregas::ctrMostrarEntregas($item, $valor, $orden);
$totalEntregas = count($entregas);

?>

<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-aqua">

    <div class="inner">

      <h3><?php echo $totalPedidos; ?></h3>

      <p>Pedidos Generados <?php echo date("m/Y"); ?></p>

    </div>

    <div class="icon">

      <i class="ion ion-clipboard"></i>

    </div>

    <a href="pedidos" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>

<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-green">

    <div class="inner">

      <h3>$<?php echo number_format($ventas["total"],2); ?></h3>

      <p>Total en Pedidos</p>

    </div>

    <div class="icon">

      <i class="ion ion-social-usd"></i>

    </div>

    <a href="pedidos" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>




<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-yellow">

    <div class="inner">

      <h3><?php echo $totalEntregas; ?></h3>

      <p>Entregas Generadas</p>

    </div>

    <div class="icon">

      <i class="ion ion-clipboard"></i>

    </div>

    <a href="entregas" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>

<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-red">

    <div class="inner">

      <h3><?php echo number_format($totalAfiliados); ?></h3>

      <p>Afiliados Activos</p>

    </div>

    <div class="icon">

      <i class="ion-person-stalker"></i>

    </div>

    <a href="afiliados" class="small-box-footer">

      Buscar afiliado <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>

<!--

SEGUNDA LINEA

-->

<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-blue">

    <div class="inner">

      <h3><?php echo number_format($totalProductos); ?></h3>

      <p>Productos en Proveeduria</p>

    </div>

    <div class="icon">

      <i class="ion ion-ios-cart"></i>

    </div>

    <a href="productos" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>

<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-olive">

    <div class="inner">

      <h3><?php echo number_format($totalCategorias); ?></h3>

      <p>Categorías</p>

    </div>

    <div class="icon">

      <i class="ion-pricetags"></i>

    </div>

    <a href="categorias" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>

<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-orange">

    <div class="inner">

      <h3><?php echo number_format($totalPedidosPendientes); ?></h3>

      <p>Pedidos Pendientes</p>

    </div>

    <div class="icon">

      <i class="ion-minus-circled"></i>

    </div>

    <a href="pedidos" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>

<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-purple">

    <div class="inner">

      <h3><?php echo number_format($totalPedidosPreparados); ?></h3>

      <p>Pedidos Preparados</p>

    </div>

    <div class="icon">

      <i class="ion-checkmark-round"></i>

    </div>

    <a href="pedidos" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>
