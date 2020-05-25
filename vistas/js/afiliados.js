/*=============================================
EDITAR CATEGORIA
=============================================*/
$(".tablas").on("click", ".btnEditarAfiliado", function(){

	var idAfiliado = $(this).attr("idAfiliado");

	var datos = new FormData();
	datos.append("idAfiliado", idAfiliado);

	$.ajax({
		url: "ajax/afiliados.ajax.php",
		method: "POST",
      	data: datos,
      	cache: false,
     	contentType: false,
     	processData: false,
     	dataType:"json",
     	success: function(respuesta){

     		$("#nombre").val(respuesta["nombre"]);
     		$("#apellido").val(respuesta["apellido"]);
     		$("#legajo").val(respuesta["legajo"]);
     		$("#idAfiliado").val(respuesta["id"]);

     	}

	})


})

/*=============================================
ELIMINAR CATEGORIA
=============================================*/
$(".tablas").on("click", ".btnEliminarAfiliado", function(){

	 var idAfiliado = $(this).attr("idAfiliado");

	 swal({
	 	title: '¿Está seguro de borrar el Afiliado?',
	 	text: "¡Si no lo está puede cancelar la acción!",
	 	type: 'warning',
	 	showCancelButton: true,
	 	confirmButtonColor: '#3085d6',
	 	cancelButtonColor: '#d33',
	 	cancelButtonText: 'Cancelar',
	 	confirmButtonText: 'Si, borrar Afiliado!'
	 }).then(function(result){

	 	if(result.value){

	 		window.location = "index.php?ruta=categorias&idCategoria="+idCategoria;

	 	}

	 })

})
