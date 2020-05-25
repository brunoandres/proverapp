/*=============================================
CARGAR LA TABLA DINÁMICA DE PEDIDOS
=============================================*/

$('.tablaPedidos').DataTable( {
    "ajax": "ajax/datatable-pedidos.ajax.php",
    "deferRender": true,
	"retrieve": true,
	"processing": true,
	 "language": {

			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
			"sFirst":    "Primero",
			"sLast":     "Último",
			"sNext":     "Siguiente",
			"sPrevious": "Anterior"
			},
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}

	}

} );

/*=============================================
AGREGANDO PRODUCTOS AL PEDIDO DESDE LA TABLA
=============================================*/

$(".tablaPedidos tbody").on("click", "button.agregarProducto", function(){

	var idProducto = $(this).attr("idProducto");
	$(this).removeClass("btn-primary agregarProducto");

	$(this).addClass("btn-default");

	var datos = new FormData();
    datos.append("idProducto", idProducto);

     $.ajax({

     	url:"ajax/productos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){

      	    var descripcion = respuesta["descripcion"];
          	var stock = respuesta["stock"];
          	var precio = respuesta["precio"];

          	/*=============================================
          	EVITAR AGREGAR PRODUCTO CUANDO EL STOCK ESTÁ EN CERO
          	=============================================*/

          	if(stock == 0){
        			swal({
  			      title: "No hay stock disponible",
  			      type: "error",
  			      confirmButtonText: "¡Cerrar!"
  			    });

			    $("button[idProducto='"+idProducto+"']").addClass("btn-primary agregarProducto");

			    return;

			  }

          	$(".nuevoProducto").append(

          	'<div class="row" style="padding:5px 15px">'+

			      '<!-- Descripción del producto -->'+

	          '<div class="col-xs-6" style="padding-right:0px">'+

	            '<div class="input-group">'+

	              '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProductos" id="quitarProductos" idProducto="'+idProducto+'"><i class="fa fa-times"></i></button></span>'+

	              '<input type="text" class="form-control nuevaDescripcionProducto" idProducto="'+idProducto+'" name="agregarProducto" value="'+descripcion+'" readonly required>'+

	            '</div>'+

	          '</div>'+

	          '<!-- Cantidad del producto -->'+

	          '<div class="col-xs-3">'+

	             '<input type="number" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" value="1" stock="'+stock+'" nuevoStock="'+Number(stock-1)+'" required>'+

	          '</div>' +

	          '<!-- Precio del producto -->'+

	          '<div class="col-xs-3 ingresoPrecio" style="padding-left:0px">'+

	            '<div class="input-group">'+

	              '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+

	              '<input type="text" class="form-control nuevoPrecioProducto" precioReal="'+precio+'" name="nuevoPrecioProducto" value="'+precio+'" readonly required>'+

	            '</div>'+

	          '</div>'+

	        '</div>')

	        // SUMAR TOTAL DE PRECIOS

	        sumarTotalPrecios()

	        // AGRUPAR PRODUCTOS EN FORMATO JSON

	        listarProductos()

	        // PONER FORMATO AL PRECIO DE LOS PRODUCTOS

	        $(".nuevoPrecioProducto").number(true, 2);

      	}

     })

});

/*=============================================
CUANDO CARGUE LA TABLA CADA VEZ QUE NAVEGUE EN ELLA
=============================================*/

$(".tablaPedidos").on("draw.dt", function(){

	if(localStorage.getItem("quitarProductos") != null){

		var listaIdProductos = JSON.parse(localStorage.getItem("quitarProductos"));

		for(var i = 0; i < listaIdProductos.length; i++){

			$("button.recuperarBoton[idProducto='"+listaIdProductos[i]["idProducto"]+"']").removeClass('btn-default');
			$("button.recuperarBoton[idProducto='"+listaIdProductos[i]["idProducto"]+"']").addClass('btn-primary agregarProducto');

		}
	}
})


/*=============================================
QUITAR PRODUCTOS DEL PEDIDO Y RECUPERAR BOTÓN
=============================================*/

var idQuitarProducto = [];

localStorage.removeItem("quitarProductos");

$(".formularioVenta").on("click", "button.quitarProductos", function(){

	if($(".nuevoProducto .row").children().length == 3){

		swal({
	      title: "Atención",
	      text: "El pedido no puede quedarse sin productos!",
	      type: "warning",
	      confirmButtonText: "¡Cerrar!"
	    });

		return;

	}

	$(this).parent().parent().parent().parent().remove();

	var idProducto = $(this).attr("idProducto");

	/*=============================================
	ALMACENAR EN EL LOCALSTORAGE EL ID DEL PRODUCTO A QUITAR
	=============================================*/

	if(localStorage.getItem("quitarProductos") == null){

		idQuitarProducto = [];

	}else{

		idQuitarProducto.concat(localStorage.getItem("quitarProductos"))

	}

	idQuitarProducto.push({"idProducto":idProducto});

	localStorage.setItem("quitarProductos", JSON.stringify(idQuitarProducto));

	$("button.recuperarBoton[idProducto='"+idProducto+"']").removeClass('btn-default');

	$("button.recuperarBoton[idProducto='"+idProducto+"']").addClass('btn-primary agregarProducto');

	if($(".nuevoProducto").children().length == 0){

		$("#totalPedido").val(0);
		$("#nuevoTotalPedido").val(0);
		$("#nuevoTotalPedido").attr("total",0);

	}else{

		// SUMAR TOTAL DE PRECIOS

    	sumarTotalPrecios()

        // AGRUPAR PRODUCTOS EN FORMATO JSON

        listarProductos()

	}

})

/*=============================================
AGREGANDO PRODUCTOS DESDE EL BOTÓN PARA DISPOSITIVOS
=============================================*/

var numProducto = 0;

$(".btnAgregarProducto").click(function(){

	numProducto ++;
	var datos = new FormData();
	datos.append("traerProductos", "ok");

	$.ajax({

		url:"ajax/productos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){

      	    	$(".nuevoProducto").append(

          	'<div class="row" style="padding:5px 15px">'+

			  '<!-- Descripción del producto -->'+

	          '<div class="col-xs-6" style="padding-right:0px">'+

	            '<div class="input-group">'+

	              '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarProductos" idProducto><i class="fa fa-times"></i></button></span>'+

	              '<select class="form-control nuevaDescripcionProducto" id="producto'+numProducto+'" idProducto name="nuevaDescripcionProducto" required>'+

	              '<option>Seleccione el producto</option>'+

	              '</select>'+

	            '</div>'+

	          '</div>'+

	          '<!-- Cantidad del producto -->'+

	          '<div class="col-xs-3 ingresoCantidad">'+

	             '<input type="number" class="form-control nuevaCantidadProducto" name="nuevaCantidadProducto" min="1" step="1" value="1" stock nuevoStock required>'+

	          '</div>' +

	          '<!-- Precio del producto -->'+

	          '<div class="col-xs-3 ingresoPrecio" style="padding-left:0px">'+

	            '<div class="input-group">'+

	              '<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+

	              '<input type="text" class="form-control nuevoPrecioProducto" precioReal="" name="nuevoPrecioProducto" readonly required>'+

	            '</div>'+

	          '</div>'+

	        '</div>');


	        // AGREGAR LOS PRODUCTOS AL SELECT

	         respuesta.forEach(funcionForEach);

	         function funcionForEach(item, index){

	         	if(item.stock != 0){

		         	$("#producto"+numProducto).append(

						'<option idProducto="'+item.id+'" value="'+item.descripcion+'">'+item.descripcion+'</option>'
		         	)

				}


	         }

	        // SUMAR TOTAL DE PRECIOS

    		  sumarTotalPrecios()

	        // PONER FORMATO AL PRECIO DE LOS PRODUCTOS

	        $(".nuevoPrecioProducto").number(true, 2);

      	}


	})

})

/*=============================================
SELECCIONAR PRODUCTO
=============================================*/

$(".formularioVenta").on("change", "select.nuevaDescripcionProducto", function(){

	var nombreProducto = $(this).val();

	var nuevaDescripcionProducto = $(this).parent().parent().parent().children().children().children(".nuevaDescripcionProducto");

	var nuevoPrecioProducto = $(this).parent().parent().parent().children(".ingresoPrecio").children().children(".nuevoPrecioProducto");

	var nuevaCantidadProducto = $(this).parent().parent().parent().children(".ingresoCantidad").children(".nuevaCantidadProducto");

	var datos = new FormData();
    datos.append("nombreProducto", nombreProducto);


	  $.ajax({

     	url:"ajax/productos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){

    	      $(nuevaDescripcionProducto).attr("idProducto", respuesta["id"]);
      	    $(nuevaCantidadProducto).attr("stock", respuesta["stock"]);
      	    $(nuevaCantidadProducto).attr("nuevoStock", Number(respuesta["stock"])-1);
      	    $(nuevoPrecioProducto).val(respuesta["precio"]);
      	    $(nuevoPrecioProducto).attr("precioReal", respuesta["precio"]);

  	      // AGRUPAR PRODUCTOS EN FORMATO JSON

	        listarProductos()
          // SUMAR TOTAL DE PRECIOS

    		  sumarTotalPrecios()
      	}

      })
})

/*=============================================
MODIFICAR LA CANTIDAD
=============================================*/

$(".formularioVenta").on("change", "input.nuevaCantidadProducto", function(){

	var precio = $(this).parent().parent().children(".ingresoPrecio").children().children(".nuevoPrecioProducto");

	var precioFinal = $(this).val() * precio.attr("precioReal");

	precio.val(precioFinal);

	var nuevoStock = Number($(this).attr("stock")) - $(this).val();

	$(this).attr("nuevoStock", nuevoStock);

	if(Number($(this).val()) > Number($(this).attr("stock"))){

		/*=============================================
		SI LA CANTIDAD ES SUPERIOR AL STOCK REGRESAR VALORES INICIALES
		=============================================*/

		$(this).val(1);

		var precioFinal = $(this).val() * precio.attr("precioReal");

		precio.val(precioFinal);

		sumarTotalPrecios();

		swal({
	      title: "La cantidad supera el Stock",
	      text: "¡Sólo hay "+$(this).attr("stock")+" unidades!",
	      type: "error",
	      confirmButtonText: "¡Cerrar!"
	    });

	    return;

	}

	// SUMAR TOTAL DE PRECIOS

	sumarTotalPrecios()


    // AGRUPAR PRODUCTOS EN FORMATO JSON

    listarProductos()

})

/*=============================================
SUMAR TODOS LOS PRECIOS
=============================================*/

function sumarTotalPrecios(){

	var precioItem = $(".nuevoPrecioProducto");
	var arraySumaPrecio = [];

	for(var i = 0; i < precioItem.length; i++){

		 arraySumaPrecio.push(Number($(precioItem[i]).val()));

	}

	function sumaArrayPrecios(total, numero){

		return total + numero;

	}

	var sumaTotalPrecio = arraySumaPrecio.reduce(sumaArrayPrecios);

	$("#nuevoTotalPedido").val(sumaTotalPrecio);
	$("#totalPedido").val(sumaTotalPrecio);
	$("#nuevoTotalPedido").attr("total",sumaTotalPrecio);

}

/*=============================================
FUNCIÓN AGREGAR DESCUENTO PLANILLA
=============================================*/

function agregarDescuentoPlanilla(){

	var impuesto = $("#pagoPlanilla").val();
	var precioTotal = $("#totalPedido").attr("total");

	var precioImpuesto = Number(precioTotal * impuesto/100);

	var totalConImpuesto = Number(precioImpuesto) + Number(precioTotal);

	$("#nuevoTotalVenta").val(totalConImpuesto);

	$("#totalPedido").val(totalConImpuesto);

	$("#nuevoPrecioImpuesto").val(precioImpuesto);

	$("#nuevoPrecioNeto").val(precioTotal);

}
/*=============================================
FORMATO AL PRECIO FINAL
=============================================*/
$("#nuevoTotalPedido").number(true, 2);
//$("#totalPedido").number(true, 2);
$("#pagoEfectivo").number(true, 2);
$(".nuevoPrecioProducto").number(true, 2);
$('#pagoPlanilla').number(true, 2);

/*=============================================
SELECCIONAR MÉTODO DE PAGO, DEFINIMOS 3 TIPOS DE PAGO
EFECTIVO, DESCUENTO POR PLANILLA, Y MERCADOPAGO
EN CASO DE PAGAR CIERTA PARTE CON DESCUENTO, SE LO AGREGAMOS EN UN CAMPO EXTRA
SE INSERTARÁ EL MONTO EN EL MISMO REGISTRO DEL PEDIDO
=============================================*/

$("#metodoPago").change(function(){

  var pagoEfectivoBD = $('#pagoEfectivoBD').val();
  if (pagoEfectivoBD  === "") {
    pagoEfectivoBD = 0;
  }

  var pagoPlanillaBD = $('#pagoPlanillaBD').val();

  if (pagoPlanillaBD ==  "") {
    pagoPlanillaBD = 0;
  }
  var comprobanteBD = $('#comprobanteBD').val();

  if (comprobanteBD  === "") {
    comprobanteBD = '';
  }

	var element = $("option:selected", this);
	var nombreMetodo = element.attr("metodo");
	var metodo = $(this).val();

	if(nombreMetodo == "Efectivo"){

		$(this).parent().parent().removeClass("col-xs-6");

		$(this).parent().parent().addClass("col-xs-4");

		$(this).parent().parent().parent().children(".cajasMetodoPago").html(

			 '<div class="col-xs-4">'+
				'<label>Efectivo</label>'+
			 	'<div class="input-group">'+

			 		'<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+

			 		'<input type="number" class="form-control" min="0" id="pagoEfectivo" value="'+pagoEfectivoBD+'" name="pagoEfectivo" min="0" step="0.1" placeholder="00.00" required autocomplete="off">'+

			 	'</div>'+

			 '</div>'+
			 '<div class="col-xs-4" id="capturarPagoPlanilla" style="padding-left:0px">'+
			 '<label>Descuento Planilla</label>'+
			 	'<div class="input-group">'+

			 		'<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+

			 		'<input type="number" class="form-control" id="pagoPlanilla" value="'+pagoPlanillaBD+'" name="pagoPlanilla" min="0" step="0.1" placeholder="00.00" required autocomplete="off">'+

			 	'</div>'+
			 '</div>'
		 )

	}else if (nombreMetodo == "Descuento"){

		$(this).parent().parent().removeClass("col-xs-6");

		$(this).parent().parent().addClass("col-xs-6");

		$(this).parent().parent().parent().children(".cajasMetodoPago").html(

			'<div class="col-xs-6" id="pagoSoloPlanilla" style="padding-left:0px">'+
			'<label>Descuento Planilla</label>'+
				'<div class="input-group">'+

					'<span class="input-group-addon"><i class="ion ion-social-usd"></i></span>'+

					'<input type="number" class="form-control" value="'+pagoPlanillaBD+'" step="0.1" id="pagoPorPlanilla" name="pagoPlanilla" placeholder="00.00" required autocomplete="off">'+

				'</div>'+

			'</div>'

		 )

	}else{

		$(this).parent().parent().removeClass('col-xs-4');

		$(this).parent().parent().addClass('col-xs-6');

		 $(this).parent().parent().parent().children('.cajasMetodoPago').html(

		 	'<div class="col-xs-6" style="padding-left:0px">'+
			 '<label>N° Comprobante</label>'+
                '<div class="input-group">'+

                  '<input type="text" class="form-control" value="'+comprobanteBD+'" id="nuevoCodigoTransaccion" name="comprobante" placeholder="Código comprobante" required autocomplete="off">'+

                  '<span class="input-group-addon"><i class="fa fa-lock"></i></span>'+

                '</div>'+

              '</div>')

	}

	// Agregar formato al precio

	$('#nuevoValorEfectivo').number( true, 2);



	// Listar método en la entrada
	//listarMetodos();

})

/*=============================================
DESHABILITO AFILIADO UNA VEZ QUE LO SELECCION CON AUTOCOMPLETE
=============================================*/
$(".formularioVenta").on("change", "input#seleccionarAfiliado", function(){

	$('#seleccionarAfiliado').attr("readonly",true);

})

/*=============================================
HABILITO EL CAMPO DE AFILIADO PARA SELECCIONAR OTRO EN CASO DE QUERER
=============================================*/

$("#seleccionarNuevo").click(function() {
	$('#seleccionarAfiliado').removeAttr("readonly");
});

/*=============================================
CAMBIO EN EFECTIVO, ME DEVUELVE EL VUELTO A ENTREGAR
=============================================*/
$(".formularioVenta").on("change", "input#pagoEfectivo", function(){

	var efectivo = $(this).val();

	var cambio =  Number(efectivo) - Number($('#totalPedido').val());

	var nuevoCambioEfectivo = $(this).parent().parent().parent().children('#capturarCambioEfectivo').children().children('#nuevoCambioEfectivo');

	nuevoCambioEfectivo.val(cambio);

})

/*=============================================
PAGO EFECTIVO Y SALDO POR PLANILLA
EL TIPO DE PAGO EN EFECTIVO ME SIRVE PARA PAGAR UNA CIERTA CANTIDAD POR DESCUENTO
POR PLANILLA
=============================================*/
$(".formularioVenta").on("change", "input#pagoEfectivo", function(){

	var totalPedido = Number($('#totalPedido').val());

	var efectivo = $(this).val();

	var planilla = totalPedido - Number(efectivo);

	// SI EL EFECTIVO A PAGAR ES MAYOR AL PEDIDO, EL DESCUENTO POR PLANILLA LO DESCARTO

	if(efectivo >= totalPedido){

		$("#pagoPlanilla").val(0.00);


	}else{

		$("#nuevoCambioEfectivo").val(0.00);
		var nuevoCambioEfectivo = $(this).parent().parent().parent().children('#capturarPagoPlanilla').children().children('#pagoPlanilla');

		nuevoCambioEfectivo.val(planilla);

		$("#pagoEfectivo").attr("max",totalPedido);

	}

})

/*=============================================
PAGO SOLO POR PLANILLA
ESTO SÓLO PERMITE PAGAR EL MONTO DEL PEDIDO POR DESCUENTO POR PLANILLA
=============================================*/

$(".formularioVenta").on("change", "input#pagoPorPlanilla", function(){

	var totalPedido = Number($('#totalPedido').val());

	var pagoPlanilla = $(this).parent().parent().parent().children('#pagoSoloPlanilla').children().children('#pagoPorPlanilla');

	$("#pagoPorPlanilla").attr("min",totalPedido);
	$('#pagoPorPlanilla"').number( true, 2);

})

/*=============================================
CAMBIO TRANSACCIÓN
=============================================*/
$(".formularioVenta").on("change", "input#nuevoCodigoTransaccion", function(){

	// Listar método en la entrada
    listarMetodos()


})


/*=============================================
LISTAR TODOS LOS PRODUCTOS
=============================================*/

function listarProductos(){

	var listaProductos = [];

	var descripcion = $(".nuevaDescripcionProducto");

	var cantidad = $(".nuevaCantidadProducto");

	var precio = $(".nuevoPrecioProducto");

	for(var i = 0; i < descripcion.length; i++){

		listaProductos.push({ "id" : $(descripcion[i]).attr("idProducto"),
							  "descripcion" : $(descripcion[i]).val(),
							  "cantidad" : $(cantidad[i]).val(),
							  "stock" : $(cantidad[i]).attr("nuevoStock"),
							  "precio" : $(precio[i]).attr("precioReal"),
							  "total" : $(precio[i]).val()})

	}

	$("#listaProductos").val(JSON.stringify(listaProductos));

}

/*=============================================
LISTAR MÉTODO DE PAGO
=============================================*/

function listarMetodos(){

	var listaMetodos = "";

	if($("#nuevoMetodoPago").val() == "Efectivo"){

		$("#listaMetodoPago").val("Efectivo");

	}else{

		$("#listaMetodoPago").val($("#nuevoMetodoPago").val()+"-"+$("#nuevoCodigoTransaccion").val());

	}

}

/*=============================================
BOTON EDITAR PEDIDO
=============================================*/
$(".tablas").on("click", ".btnEditarPedido", function(){

	var idPedido = $(this).attr("idPedido");
	var idEntrega = $(this).attr("idEntrega");
	window.location = "index.php?ruta=editar-pedido&id="+idPedido;

})

/*=============================================
FUNCIÓN PARA DESACTIVAR LOS BOTONES AGREGAR CUANDO EL PRODUCTO YA HABÍA SIDO SELECCIONADO EN LA CARPETA
=============================================*/

function quitarAgregarProducto(){

	//Capturamos todos los id de productos que fueron elegidos en la venta
	var idProductos = $(".quitarProductos");

	//Capturamos todos los botones de agregar que aparecen en la tabla
	var botonesTabla = $(".tablaPedidos tbody button.agregarProducto");

	//Recorremos en un ciclo para obtener los diferentes idProductos que fueron agregados a la venta
	for(var i = 0; i < idProductos.length; i++){

		//Capturamos los Id de los productos agregados a la venta
		var boton = $(idProductos[i]).attr("idProducto");

		//Hacemos un recorrido por la tabla que aparece para desactivar los botones de agregar
		for(var j = 0; j < botonesTabla.length; j ++){

			if($(botonesTabla[j]).attr("idProducto") == boton){

				$(botonesTabla[j]).removeClass("btn-primary agregarProducto");
				$(botonesTabla[j]).addClass("btn-default");

				console.log(botonesTabla[j]);

			}
		}

	}

}

/*=============================================
CADA VEZ QUE CARGUE LA TABLA CUANDO NAVEGAMOS EN ELLA EJECUTAR LA FUNCIÓN:
=============================================*/

$('.tablaPedidos').on( 'draw.dt', function(){

	quitarAgregarProducto();

})


/*=============================================
BORRAR PEDIDO
=============================================*/
$(".tablas").on("click", ".btnEliminarPedido", function(){

  var idPedido = $(this).attr("idPedido");

  swal({
        title: '¿Está seguro de borrar el pedido?',
        text: "¡Si no lo está puede cancelar la accíón!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar pedido!'
      }).then(function(result){
        if (result.value) {

            window.location = "index.php?ruta=pedidos&idPedido="+idPedido;
        }

  })
  //Date picker
  $('#datepicker').datepicker({
	autoclose: true
  })

})

/*=============================================
IMPRIMIR FACTURA DE PEDIDOS
=============================================*/

$(".tablas").on("click", ".btnImprimirFactura", function(){

	var codigoPedido = $(this).attr("codigoPedido");

	window.open("extensiones/tcpdf/pdf/factura.php?codigo="+codigoPedido, "_blank");

})

/*=============================================
RANGO DE FECHAS FILTRO PEDIDOS
=============================================*/

$('#daterange-btn').daterangepicker(
  {
    ranges   : {
      'Hoy'       : [moment(), moment()],
      'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Últimos 7 días' : [moment().subtract(6, 'days'), moment()],
      'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
      'Este mes'  : [moment().startOf('month'), moment().endOf('month')],
      'Último mes'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment(),
    endDate  : moment()
  },
  function (start, end) {
    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

    var fechaInicial = start.format('YYYY-MM-DD');

    var fechaFinal = end.format('YYYY-MM-DD');

    var capturarRango = $("#daterange-btn span").html();

   	localStorage.setItem("capturarRango", capturarRango);

   	window.location = "index.php?ruta=pedidos&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;

  }

)

/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/

$(".daterangepicker.opensleft .range_inputs .cancelBtn").on("click", function(){

	localStorage.removeItem("capturarRango");
	localStorage.clear();
	window.location = "pedidos";
});

/*=============================================
CAPTURAR HOY
=============================================*/

$(".daterangepicker.opensleft .ranges li").on("click", function(){

	var textoHoy = $(this).attr("data-range-key");

	if(textoHoy == "Hoy"){

		var d = new Date();

		var dia = d.getDate();
		var mes = d.getMonth()+1;
		var año = d.getFullYear();

		dia = ("0"+dia).slice(-2);
		mes = ("0"+mes).slice(-2);

		var fechaInicial = año+"-"+mes+"-"+dia;
		var fechaFinal = año+"-"+mes+"-"+dia;

    	localStorage.setItem("capturarRango", "Hoy");

    	window.location = "index.php?ruta=pedidos&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;

	}

});

/*=============================================
ABRIR ARCHIVO XML EN NUEVA PESTAÑA
=============================================*/

$(".abrirXML").click(function(){

	var archivo = $(this).attr("archivo");
	window.open(archivo, "_blank");


})

/*=============================================
EDITAR PEDIDOS MASIVOS ESTADO
=============================================*/
$(".tablas").on("click", ".btnEditarPedidos", function(){

	var idEntrega = $(this).attr("idEntrega");
  $("#idEntrega").val(idEntrega);

})
