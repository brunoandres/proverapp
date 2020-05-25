$('.tablaEntregas').DataTable( {
    "ajax": "ajax/datatable-entregas.ajax.php",
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
AGREGANDO PEDIDOS A LAS COMPRAS DESDE LA TABLA
=============================================*/

$(".tablaEntregas tbody").on("click", "button.agregarPedido", function(){

	var idPedido = $(this).attr("idPedido");

	$(this).removeClass("btn-primary agregarPedido");

	$(this).addClass("btn-default");

	var datos = new FormData();
    datos.append("idPedido", idPedido);

     $.ajax({

     	url:"ajax/pedidos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){

			//Traigo numero de pedido
			var numero = respuesta[0]["numero"];

			//Traigo el estado del pedido
			var estado = respuesta[0]["estados_id"];

			//Afiliado del pedido
			var nombre = respuesta[1]["nombre"];

			//Armo string para mostrar al usuario
            var pedido = 'Pedido N° '+numero+' Afiliado : '+nombre;

          	/*=============================================
          	EVITAR AGREGAR PEDIDO CUANDO ESTÁ ENTREGADO
          	=============================================*/
			//El estado con == 5 es por se entregó
          	if(estado == 5){

      			swal({
			      title: "El pedido ya se encuentra entregado",
			      type: "warning",
			      confirmButtonText: "¡Cerrar!"
			    });
				//Devuelve el boton con el color azul
			    $("button[idPedido='"+idPedido+"']").addClass("btn-primary agregarPedido");

			    return;

          	}

			//Genera la vista con la lista de
          	$(".nuevoPedido").append(

          	'<div class="row" style="padding:5px 15px">'+

			  '<!-- Descripción del pedido -->'+

	          '<div class="col-xs-12" style="padding-right:-5px">'+

	            '<div class="input-group">'+

	              '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarPedido" idPedido="'+idPedido+'"><i class="fa fa-times"></i></button></span>'+

	              '<input type="text" class="form-control nuevaDescripcionPedido" idPedido="'+idPedido+'" name="agregarPedido" value="'+pedido+'" readonly required>'+

	            '</div>'+

	          '</div>'+

	        '</div>')

	        // AGRUPAR PRODUCTOS EN FORMATO JSON

	        listarPedidos();

      	}

     })

});

/*=============================================
CUANDO CARGUE LA TABLA CADA VEZ QUE NAVEGUE EN ELLA
=============================================*/

$(".tablaPedidos").on("draw.dt", function(){

	if(localStorage.getItem("quitarPedido") != null){

		var listaIdPedidos = JSON.parse(localStorage.getItem("quitarPedido"));

		for(var i = 0; i < listaIdPedidos.length; i++){

			$("button.recuperarBoton[idPedido='"+listaIdPedidos[i]["idPedido"]+"']").removeClass('btn-default');
			$("button.recuperarBoton[idPedido='"+listaIdPedidos[i]["idPedido"]+"']").addClass('btn-primary agregarPedido');

		}

	}


})

/*=============================================
QUITAR PEDIDOS DE LA COMPRA Y RECUPERAR BOTÓN
=============================================*/

var idQuitarPedido = [];

localStorage.removeItem("quitarPedido");

$(".formularioVenta").on("click", "button.quitarPedido", function(){

	if($(".nuevoPedido .row").children().length == 3){

		swal({
	      title: "Atención",
	      text: "La entrega no puede quedarse sin pedidos!",
	      type: "warning",
	      confirmButtonText: "¡Cerrar!"
	    });

		return;

	}

	$(this).parent().parent().parent().parent().remove();

	var idPedido = $(this).attr("idPedido");

	/*=============================================
	ALMACENAR EN EL LOCALSTORAGE EL ID DEL PEDIDO A QUITAR
	=============================================*/

	if(localStorage.getItem("quitarPedido") == null){

		idQuitarPedido = [];

	}else{

		idQuitarPedido.concat(localStorage.getItem("quitarPedido"))

	}

	idQuitarPedido.push({"idPedido":idPedido});

	localStorage.setItem("quitarPedido", JSON.stringify(idQuitarPedido));

	$("button.recuperarBoton[idPedido='"+idPedido+"']").removeClass('btn-default');

	$("button.recuperarBoton[idPedido='"+idPedido+"']").addClass('btn-primary agregarPedido');

	if($(".nuevoPedido").children().length == 0){

		$("#nuevoImpuestoVenta").val(0);
		$("#nuevoTotalVenta").val(0);
		$("#totalVenta").val(0);
		$("#nuevoTotalVenta").attr("total",0);

	}else{

		// SUMAR TOTAL DE PRECIOS

    	sumarTotalPrecios()



        // AGRUPAR PRODUCTOS EN FORMATO JSON

        listarPedidos()

	}

})

/*=============================================
AGREGANDO PEDIDOS DESDE EL BOTÓN PARA DISPOSITIVOS
=============================================*/

var numPedido = 0;

$(".btnAgregarPedido").click(function(){

	numPedido ++;
	var datos = new FormData();
	datos.append("traerPedidos", "ok");

	$.ajax({

		url:"ajax/pedidos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){

      	    	$(".nuevoPedido").append(

                '<div class="row" style="padding:5px 15px">'+

                '<!-- Descripción del Pedido -->'+

                '<div class="col-xs-12" style="padding-right:-5px">'+

                    '<div class="input-group">'+

                    '<span class="input-group-addon"><button type="button" class="btn btn-danger btn-xs quitarPedido" idPedido><i class="fa fa-times"></i></button></span>'+

                    '<select class="form-control nuevaDescripcionPedido" id="pedido'+numPedido+'" idPedido name="nuevaDescripcionPedido" required>'+

                    '<option>Seleccione el pedido</option>'+

                    '</select>'+

                    '</div>'+

                '</div>'+

                '</div>'+

                '</div>');


	        // AGREGAR LOS PEDIDOS AL SELECT
            respuesta.forEach(funcionForEach);


	        function funcionForEach(item, index){


                $(".nuevaDescripcionPedido").change(function(){
                    var pedidoSeleccionado = $(this).children("option:selected").val();
                    //alert("Opcion elegida : " + pedidoSeleccionado);
                });
                var pedido = 'Pedido N° '+item.numero;

                $("#pedido"+numPedido).append(

                    '<option idPedido="'+item.id+'" value="'+item.id+'">'+pedido+'</option>'

                )

	        }
      	}
	})
})

/*=============================================
SELECCIONAR PEDIDOS
=============================================*/

$(".formularioVenta").on("change", "select.nuevaDescripcionPedido", function(){

	var nombreProducto = $(this).val();

	var nuevaDescripcionPedido = $(this).parent().parent().parent().children().children().children(".nuevaDescripcionPedido");

	//var nuevoPrecioProducto = $(this).parent().parent().parent().children(".ingresoPrecio").children().children(".nuevoPrecioProducto");

	//var nuevaCantidadProducto = $(this).parent().parent().parent().children(".ingresoCantidad").children(".nuevaCantidadProducto");

	var datos = new FormData();
    datos.append("traerPedidos", "ok");


	  $.ajax({

     	url:"ajax/pedidos.ajax.php",
      	method: "POST",
      	data: datos,
      	cache: false,
      	contentType: false,
      	processData: false,
      	dataType:"json",
      	success:function(respuesta){

			console.log(respuesta);

      	    $(nuevaDescripcionPedido).attr("idPedido", respuesta["id"]);
      	    $(nuevoPrecioProducto).attr("precioReal", respuesta["numero"]);

  	      // AGRUPAR PRODUCTOS EN FORMATO JSON

	        listarPedidos()

      	}

      })
})

/*=============================================
LISTAR TODOS LOS PEDIDOS
=============================================*/

function listarPedidos(){

	var listaPedidos = [];

	var numero = $(".nuevaDescripcionPedido");

	for(var i = 0; i < numero.length; i++){
		listaPedidos.push({"id" : $(numero[i]).attr("idPedido"),
		"numero" : $(numero[i]).val()})
	}

	$("#listaPedidos").val(JSON.stringify(listaPedidos));

}

/*=============================================
BOTON EDITAR PEDIDO
=============================================*/
$(".tablas").on("click", ".btnEditarEntrega", function(){

	var idEntrega = $(this).attr("idEntrega");
	window.location = "index.php?ruta=editar-entrega&entregaNumero="+idEntrega;

})

/*=============================================
FUNCIÓN PARA DESACTIVAR LOS BOTONES AGREGAR CUANDO EL PRODUCTO YA HABÍA SIDO SELECCIONADO EN LA CARPETA
=============================================*/

function quitarAgregarProducto(){

	//Capturamos todos los id de productos que fueron elegidos en la venta
	var idPedidos = $(".quitarPedido");

	//Capturamos todos los botones de agregar que aparecen en la tabla
	var botonesTabla = $(".tablaEntregas tbody button.agregarPedido");

	//Recorremos en un ciclo para obtener los diferentes idPedidos que fueron agregados a la venta
	for(var i = 0; i < idPedidos.length; i++){

		//Capturamos los Id de los productos agregados a la venta
		var boton = $(idPedidos[i]).attr("idPedido");

		//Hacemos un recorrido por la tabla que aparece para desactivar los botones de agregar
		for(var j = 0; j < botonesTabla.length; j ++){

			if($(botonesTabla[j]).attr("idPedido") == boton){

				$(botonesTabla[j]).removeClass("btn-primary agregarPedido");
				$(botonesTabla[j]).addClass("btn-default");

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
BORRAR SOLICITUD
=============================================*/
$(".tablas").on("click", ".btnEliminarEntrega", function(){

  var idEntrega = $(this).attr("idEntrega");

  swal({
        title: '¿Está seguro de borrar la Solicitud?',
        text: "¡Si no lo está puede cancelar la accíón!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar Solicitud!'
      }).then(function(result){
        if (result.value) {

            window.location = "index.php?ruta=entregas&idEntrega="+idEntrega;
        }

  })


})
//Date picker
$('#datepicker').datepicker({
	autoclose: true
  })
/*=============================================
IMPRIMIR FACTURA
=============================================*/

$(".tablas").on("click", ".btnImprimirFactura", function(){

	var codigoPedido = $(this).attr("codigoPedido");

	window.open("extensiones/tcpdf/pdf/factura.php?codigo="+codigoPedido, "_blank");

})
/*=============================================
IMPRIMIR PEDIDOS
=============================================*/

$(".tablas").on("click", ".btnImprimirPedidos", function(){

	var id = $(this).attr("id");

	window.open("extensiones/tcpdf/pdf/pedidos.php?id="+id, "_blank");

})
/*=============================================
RANGO DE FECHAS
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

   	window.location = "index.php?ruta=ventas&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;

  }

)

/*=============================================
CANCELAR RANGO DE FECHAS
=============================================*/

$(".daterangepicker.opensleft .range_inputs .cancelBtn").on("click", function(){

	localStorage.removeItem("capturarRango");
	localStorage.clear();
	window.location = "ventas";
})

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

		// if(mes < 10){

		// 	var fechaInicial = año+"-0"+mes+"-"+dia;
		// 	var fechaFinal = año+"-0"+mes+"-"+dia;

		// }else if(dia < 10){

		// 	var fechaInicial = año+"-"+mes+"-0"+dia;
		// 	var fechaFinal = año+"-"+mes+"-0"+dia;

		// }else if(mes < 10 && dia < 10){

		// 	var fechaInicial = año+"-0"+mes+"-0"+dia;
		// 	var fechaFinal = año+"-0"+mes+"-0"+dia;

		// }else{

		// 	var fechaInicial = año+"-"+mes+"-"+dia;
	 //    	var fechaFinal = año+"-"+mes+"-"+dia;

		// }

		dia = ("0"+dia).slice(-2);
		mes = ("0"+mes).slice(-2);

		var fechaInicial = año+"-"+mes+"-"+dia;
		var fechaFinal = año+"-"+mes+"-"+dia;

    	localStorage.setItem("capturarRango", "Hoy");

    	window.location = "index.php?ruta=ventas&fechaInicial="+fechaInicial+"&fechaFinal="+fechaFinal;

	}

})

/*=============================================
ABRIR ARCHIVO XML EN NUEVA PESTAÑA
=============================================*/

$(".abrirXML").click(function(){

	var archivo = $(this).attr("archivo");
	window.open(archivo, "_blank");


})
