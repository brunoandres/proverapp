<?php

require_once "../../controladores/pedidos.controlador.php";
require_once "../../modelos/pedidos.modelo.php";
require_once "../../controladores/afiliados.controlador.php";
require_once "../../modelos/afiliados.modelo.php";
require_once "../../controladores/usuarios.controlador.php";
require_once "../../modelos/usuarios.modelo.php";

$reporte = new ControladorPedidos();
$reporte -> ctrDescargarReporte();
