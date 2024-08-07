var tabla;

//funcion que se ejecuta al inicio
function init(){
   $("#btnCancelar").hide();
   mostrarform(false);
   listar();

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   });

   //cargamos los items al select proveedor
   $.post("../ajax/ingreso.php?op=selectProveedor", function(r){
   	$("#idproveedor").html(r);
   	$('#idproveedor').selectpicker('refresh');
   });
    // Agregar el evento change al select tipo_comprobante
	$("#tipo_comprobante").change(habilitarCamposComprobante);
}

//funcion limpiar
function limpiar(){

	$("#idproveedor").val("");
	$("#idproveedor").selectpicker('refresh');
	$("#proveedor").val("");
	$("#serie_comprobante").val("");
	$("#num_comprobante").val("");
	$("#impuesto").val("");

	$("#total_compra").val("");
	$(".filas").remove();
	$("#total").html("0");

	//obtenemos la fecha actual
	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);
	$("#fecha_hora").val(today);

	//marcamos el primer tipo_documento
	$("#tipo_comprobante").val("Boleta");
	$("#tipo_comprobante").selectpicker('refresh');

	habilitarCamposComprobante();

}

//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		//$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		listarArticulos();

		$("#btnGuardar").hide();
		$("#btnActualizar").hide();
		$("#btnCancelar").show();
		detalles=0;
		$("#btnAgregarArt").show();
		$("#btnMostrarForm").hide();

	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
		$("#btnMostrarForm").show();
		$("#btnCancelar").hide();
	}
}

//cancelar form
function cancelarform(){
	limpiar();
	mostrarform(false);
}

//funcion listar
function listar(){
	tabla=$('#tbllistado').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [
                  //'copyHtml5',
                  'excelHtml5'
                  //'csvHtml5',
         //         'pdf'
		],
		"ajax":
		{
			url:'../ajax/ingreso.php?op=listar',
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":15,//paginacion
		"order":[[0,"desc"]]//ordenar (columna, orden)
	}).DataTable();
}

function listarArticulos(){
	tabla=$('#tblarticulos').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [

		],
		"ajax":
		{
			url:'../ajax/ingreso.php?op=listarArticulos',
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":7,//paginacion
		"order":[[0,"desc"]]//ordenar (columna, orden)
	}).DataTable();
}
//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     //$("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/ingreso.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     		bootbox.alert(datos);
     		mostrarform(false);
     		listar();
     	}
     });

     limpiar();
}

/*function mostrar(idingreso){
	$.post("../ajax/ingreso.php?op=mostrar",{idingreso : idingreso},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#idproveedor").val(data.idproveedor);
			$("#idproveedor").selectpicker('refresh');
			$("#tipo_comprobante").val(data.tipo_comprobante);
			$("#tipo_comprobante").selectpicker('refresh');
			$("#serie_comprobante").val(data.serie_comprobante);
			$("#num_comprobante").val(data.num_comprobante);
			$("#fecha_hora").val(data.fecha);
			$("#impuesto").val(data.impuesto);
			$("#idingreso").val(data.idingreso);
			
			//ocultar y mostrar los botones
			$("#btnGuardar").hide();
			$("#btnCancelar").show();
			$("#btnAgregarArt").hide();
			$("#btnActualizar").hide();

			habilitarCamposComprobante();
		});
	$.post("../ajax/ingreso.php?op=listarDetalle&id="+idingreso,function(r){
		$("#detalles").html(r);
	});

}*/


//funcion para desactivar
function anular(idingreso){
	bootbox.confirm("¿Esta seguro de desactivar este dato?", function(result){
		if (result) {
			// Hacer la solicitud para anular la venta
			$.post("../ajax/ingreso.php?op=anular", {idingreso : idingreso}, function(e){
				bootbox.alert(e);
				// Recargar la tabla de ventas
				tabla.ajax.reload();

				// Recuperar los detalles de la venta para devolver el stock
                $.post("../ajax/ingreso.php?op=listarDetalle", {id: idingreso}, function(data){
                    try {
                        var detallesIngreso = JSON.parse(data);

                        // Iterar sobre los detalles para devolver el stock de cada producto
                        detallesIngreso.forEach(function(detalle){
                            devolverStock(detalle.idarticulo, detalle.cantidad);
                            tabla.ajax.reload();
                        });

                    } catch (error) {
                        console.error("Error al procesar los detalles de la venta:", error);
                        tabla.ajax.reload();
                    }
                }).fail(function(jqXHR, textStatus, errorThrown){
                    console.error("Error en la solicitud AJAX (listarDetalle):", textStatus, errorThrown);
                    tabla.ajax.reload();
                });

            }).fail(function(jqXHR, textStatus, errorThrown){
                console.error("Error en la solicitud AJAX (anular venta):", textStatus, errorThrown);
                tabla.ajax.reload();
            });
        }
    });
}

// Función para devolver el stock de un producto específico
function devolverStock(idarticulo, cantidadDevolver) {
    $.post("../ajax/articulo.php?op=devolverStock", {idarticulo: idarticulo, cantidad: cantidadDevolver}, function(response){
        console.log(response); // Mostrar mensaje de confirmación o error
    }).fail(function(jqXHR, textStatus, errorThrown){
        console.error("Error en la solicitud AJAX (devolverStock):", textStatus, errorThrown);
    });
}

//declaramos variables necesarias para trabajar con las compras y sus detalles
var impuesto=18;
var cont=0;
var detalles=0;

$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto(){
	var tipo_comprobante=$("#tipo_comprobante option:selected").text();
	if (tipo_comprobante=='Factura') {
		$("#impuesto").val(impuesto);
	}else{
		$("#impuesto").val("0");
	}
}

function agregarDetalle(idarticulo,articulo){
	var cantidad=1;
	var precio_compra=1;
	var precio_venta=1;

	if (idarticulo!="") {
		var subtotal=cantidad*precio_compra;
		var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
        '<td><input type="number" style="text-align: right;" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"onchange="SalirCasilla()"> unidades</td>'+
        '<td>S/. <input type="text" style="text-align: right;" name="precio_compra[]" id="precio_compra[]" value="'+precio_compra+' "onchange="SalirCasilla()"></td>'+
        '<td>S/. <input type="text" style="text-align: right;" name="precio_venta[]" value="'+precio_venta+'"></td>'+
        '<td>S/. <span id="subtotal'+cont+'" name="subtotal">'+subtotal+'</span></td>'+
        //'<td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
		'</tr>';
		cont++;
		detalles++;
		$('#detalles').append(fila);
		modificarSubtotales();

	}else{
		alert("error al ingresar el detalle, revisar las datos del articulo ");
	}
}

function SalirCasilla(){
	modificarSubtotales();
}

function modificarSubtotales(){
	var cant=document.getElementsByName("cantidad[]");
	var prec=document.getElementsByName("precio_compra[]");
	var sub=document.getElementsByName("subtotal");

	for (var i = 0; i < cant.length; i++) {
		var inpC=cant[i];
		var inpP=prec[i];
		var inpS=sub[i];

		inpS.value=inpC.value*inpP.value;
		document.getElementsByName("subtotal")[i].innerHTML=inpS.value;
	}

	calcularTotales();
}

function calcularTotales(){
	var sub = document.getElementsByName("subtotal");
	var total=0.0;

	for (var i = 0; i < sub.length; i++) {
		total += document.getElementsByName("subtotal")[i].value;
	}
	$("#total").html("S/." + total);
	$("#total_compra").val(total);
	evaluar();
}

function evaluar(){

	if (detalles>0) 
	{
		$("#btnGuardar").show();
		$("#btnActualizar").show();
	}
	else
	{
		$("#btnGuardar").hide();
		$("#btnActualizar").show();
		cont=0;
	}
}

function eliminarDetalle(indice){
	bootbox.confirm("¿Estás seguro de descartar este producto?", function(result){
		if (result) {
				$("#fila" + indice).remove();
				calcularTotales();
        		detalles = detalles - 1;
				tabla.ajax.reload();
				bootbox.alert("Producto descartado exitosamente.");
			;
		}
	})
}

//########################CAMBIO 06-08################################################################
function mostrar(idingreso) {
    $('#idventa').val(idingreso);
    $.post("../ajax/ingreso.php?op=mostrar", {idingreso: idingreso}, function(data, status) {
        data = JSON.parse(data);
        $('#total_venta').val(data.total_compra);
        $('#nombre_cliente_editar').text(data.cliente);  // Asegurar de estar correcto
        $('#fecha_horas').val(data.fecha_hora); // Esta línea está actualizando el div con el idcliente
        $('#tcomprobante').val(data.tipo_comprobante);
		$('#nserie').val(data.serie_comprobante);
		$('#numero').val(data.num_comprobante);
		//$('#costos_otros').val(data.costo_otros);
        $('#idcliente').text(data.cliente); // Esta línea está actualizando el div con el idcliente
        let totalPagos = 0;
        $('#tablas_editar tbody').empty();
        for (let pago of data.pagos) {
            $('#tablas_editar tbody').append(`<tr>
				<td>${pago.articulo}</td>
				<td>${pago.cantidad}</td>
				<td>${pago.precio_compra}</td>
				<td>${pago.precio_venta}</td>
				<td>${pago.subtotal}</td>
				</tr>`);
            totalPagos += parseFloat(pago.monto);
        }

      //  <td><button class="btn btn-danger btn-xs" onclick="eliminarPago(${pago.idpago})"><i class="fa fa-trash"></i></button></td>
    //  let saldoRestante = parseFloat(data.total_compra);
     //   $('#saldo_restante_editar').text('Total: S/. ' + saldoRestante.toFixed(2));

        // Mostrar icono según el estado de pago y deshabilitar el campo si el saldo es 0
       // mostrarIconoEstadoPago(saldoRestante);
        //if (saldoRestante <= 0) {
        //    $('#monto_abonar').prop('disabled', true);
       // } else {
         //   $('#monto_abonar').prop('disabled', false);
        //}

		let saldoRestante = parseFloat(data.total_compra);
        $('#saldo_restante_compra').text('Total Compra: S/. ' + saldoRestante.toFixed(2));

        // Mostrar icono según el estado de pago y deshabilitar el campo si el saldo es 0

        $('#editarModals').modal('show');
    });
}



// Función para habilitar o deshabilitar los campos de comprobante
function habilitarCamposComprobante() {
	var tipo_comprobante = $("#tipo_comprobante").val();
	if (tipo_comprobante == "Ticket") {
		$("#serie_comprobante").prop("readonly", false);
		$("#num_comprobante").prop("readonly", false);
	} else {
		$("#serie_comprobante").prop("readonly", true);
		$("#num_comprobante").prop("readonly", true);
	}
}

init();