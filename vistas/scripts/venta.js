var tabla;

//funcion que se ejecuta al inicio

function init(){
   $("#btnCancelar").hide();
   mostrarform(false);
   listar();

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   });

   //cargamos los items al select cliente
   $.post("../ajax/venta.php?op=selectCliente", function(r){
   	$("#idcliente").html(r);
   	$('#idcliente').selectpicker('refresh');
   });

}


//funcion limpiar
function limpiar(){

	$("#idcliente").val("");
	$("#idcliente").selectpicker('refresh');
	$("#cliente").val("");
	$("#direccion").val("");
    $("#consideraciones").val("");
	$("#agencia").val("");
	$("#agencia").selectpicker('refresh');
	$("#costo_envio").val("");
	$("#costo_otros").val("");
    $("#pagina_venta").val("");
    $("#pagina_venta").selectpicker('refresh');
	$("#impuesto").val("");

	$("#total_venta").val("");
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
                //  'copyHtml5',
                  'excelHtml5'
                 // 'csvHtml5',
                 // 'pdf'
		],
		"ajax":
		{
			url:'../ajax/venta.php?op=listar',
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
			url:'../ajax/venta.php?op=listarArticulos',
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
     	url: "../ajax/venta.php?op=guardaryeditar",
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

function mostrar(idventa) {
    // Mostrar mensaje de carga
    $("#detalles").html('<p>Cargando...</p>');

    // Realizar la solicitud AJAX para obtener los datos principales
    $.post("../ajax/venta.php?op=mostrar", {idventa: idventa}, function(data, status) {
        try {
            data = JSON.parse(data);
            mostrarform(true);

            $("#idcliente").val(data.idcliente);
            $("#idcliente").selectpicker('refresh');
            $("#fecha_hora").val(data.fecha);
            $("#pagina_venta").val(data.pagina_venta);
            $("#pagina_venta").selectpicker('refresh');
            $("#tipo_comprobante").val(data.tipo_comprobante);
            $("#tipo_comprobante").selectpicker('refresh');
            $("#direccion").val(data.direccion);
            //$("#serie_comprobante").val(data.serie_comprobante);
            $("#costo_envio").val(data.costo_envio);
            $("#costo_otros").val(data.costo_otros);
            $("#impuesto").val(data.impuesto);
            $("#idventa").val(data.idventa);

            // ocultar y mostrar los botones
            $("#btnGuardar").hide();
            $("#btnCancelar").show();
            $("#btnAgregarArt").hide();
            $("#btnActualizar").hide();
        } catch (error) {
            console.error("Error al procesar los datos:", error);
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
    });

    // Realizar la solicitud AJAX para obtener los detalles
    $.post("../ajax/venta.php?op=listarDetalle&id=" + idventa, function(r) {
        // Mostrar los detalles obtenidos
        $("#detalles").html(r);
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error("Error en la solicitud AJAX (listarDetalle):", textStatus, errorThrown);
    });
}


// Función para anular la venta y devolver el stock
function anular(idventa){
    bootbox.confirm("¿Estás seguro de desactivar este dato?", function(result){
        if (result) {
            // Hacer la solicitud para anular la venta
            $.post("../ajax/venta.php?op=anular", {idventa: idventa}, function(response){
                bootbox.alert(response);
                // Recargar la tabla de ventas
                tabla.ajax.reload();

                // Recuperar los detalles de la venta para devolver el stock
                $.post("../ajax/venta.php?op=listarDetalle", {id: idventa}, function(data){
                    try {
                        var detallesVenta = JSON.parse(data);

                        // Iterar sobre los detalles para devolver el stock de cada producto
                        detallesVenta.forEach(function(detalle){
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

function agregarDetalle(idarticulo, articulo, precio_venta, stock) {
    var cantidad = 1;
    var descuento = 0;

    if (idarticulo != "") {
        // Verificar si hay suficiente stock
        if (stock >= cantidad) {
            var subtotal = cantidad * precio_venta;
            var fila = '<tr class="filas" id="fila' + cont + '">' +
                '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>' +
                '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>' +
                '<td style="display: flex; align-items: center;">' +
                    '<input type="number" style="text-align: right;" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"onchange="validarStock(this,'+stock+')">' +
                    '<div style="margin-left: 10px; border: 1px solid #ddd; padding: 5px; border-radius: 5px; background-color: #f9f9f9; width: fit-content;">' +
                    'Stock: <span style="font-weight: bold; color: #28a745;">' + stock + '</span></div>' +
                '</td>' +
                '<td>S/. <input type="text" style="text-align: right;" name="precio_venta[]" id="precio_venta[]" value="'+precio_venta+'"onchange="SalirCasilla()"></td>' +
                '<td>S/. <input type="text" style="text-align: right;" name="descuento[]" value="'+descuento+'"onchange="SalirCasilla()"></td>' +
                '<td>S/. <span id="subtotal'+cont+'" name="subtotal">'+subtotal+'</span></td>' +
                '</tr>';
            cont++;
            detalles++;
            $('#detalles').append(fila);
            modificarSubtotales();
        } else {
            alert("No se puede agregar el producto, stock insuficiente.");
        }
    } else {
        alert("Error al ingresar el detalle, revisar los datos del artículo.");
    }
}
function SalirCasilla(){
	modificarSubtotales();
}


function validarStock(input, stockDisponible) {
    var cantidad = input.value;
    if (cantidad > stockDisponible) {
        bootbox.alert({
            message: "Stock insuficiente para la cantidad solicitada.",
            callback: function () {
                input.value = stockDisponible; // Ajustar la cantidad al stock disponible
            }
        });
    }
    modificarSubtotales();
}

// Asegúrate de llamar a esta función también cuando se modifique el stock en el servidor
function verificarStock(idarticulo, cantidadSolicitada, callback) {
    $.post("../ajax/venta.php?op=verificarStock", { idarticulo: idarticulo }, function(data) {
        var stock = JSON.parse(data).stock;
        if (stock >= cantidadSolicitada) {
            callback(true);
        } else {
            callback(false);
        }
    });
}

// Ejemplo de uso en la función agregarDetalle (no se usa en el cod fuente)
function agregarDetalleConVerificacion(idarticulo, articulo, precio_venta, stock) {
    var cantidad = 1;
    var descuento = 0;

    if (idarticulo != "") {
        verificarStock(idarticulo, cantidad, function(disponible) {
            if (disponible) {
                var subtotal = cantidad * precio_venta;
                var fila = '<tr class="filas" id="fila' + cont + '">' +
                    '<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
                    '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
                    '<td><input type="number" style="text-align: right;" name="cantidad[]" id="cantidad[]" value="' + cantidad + '" onchange="validarStock(this, ' + stock + ')"></td>' +
                    '<td>S/. <input type="text" style="text-align: right;" name="precio_venta[]" id="precio_venta[]" value="' + precio_venta + '"></td>' +
                    '<td>S/. <input type="text" style="text-align: right;" name="descuento[]" value="' + descuento + '"></td>' +
                    '<td>S/. <span id="subtotal' + cont + '" name="subtotal">' + subtotal + '</span></td>' +
                    '</tr>';
                cont++;
                detalles++;
                $('#detalles').append(fila);
                modificarSubtotales();
            } else {
                alert("No se puede agregar el producto, stock insuficiente.");
            }
        });
    } else {
        alert("Error al ingresar el detalle, revisar los datos del artículo.");
    }
}

function modificarSubtotales(){
	var cant=document.getElementsByName("cantidad[]");
	var prev=document.getElementsByName("precio_venta[]");
	var desc=document.getElementsByName("descuento[]");
	//var cost=document.getElementsByName("costo_envio[]");
	var sub=document.getElementsByName("subtotal");

	for (var i = 0; i < cant.length; i++) {
		var inpV=cant[i];
		var inpP=prev[i];
		var inpS=sub[i];
		//var inpC=cost[i];
		var des=desc[i];
		
		//inpS.value=(inpV.value*inpP.value)+parseFloat(inpC.value)-des.value;
		inpS.value=(inpV.value*inpP.value)-des.value;
		document.getElementsByName("subtotal")[i].innerHTML=inpS.value;
	}
    // modificarPretotales();
	calcularTotales();
}

function calcularTotales(){
	var sub = document.getElementsByName("subtotal");
	var valor1 = document.getElementById("costo_envio").value;
	var valor2 = document.getElementById("costo_otros").value;
	var total=0.0;

	var numero1 = Number(valor1);
	var numero2 = Number(valor2);

	for (var i = 0; i < sub.length; i++) {
		total += document.getElementsByName("subtotal")[i].value;
	}
	total1 = numero1 + numero2;
	total = total - total1;
	$("#total").html("S/." + total);
	$("#total_venta").val(total);
	evaluar();
}

function sumarNumeros() {
	// Obtener los valores de entrada
	let valor1 = document.getElementById("costo_envio").value;
	let valor2 = document.getElementById("costo_otros").value;
	//var total = document.getElementsByName("total");

	// Convertir los valores a números
	let numero1 = Number(valor1);
	let numero2 = Number(valor2);
	//var total = Number(total);

	// Verificar si las conversiones son válidas
	if (isNaN(numero1) || isNaN(numero2)) {
		document.getElementById("resultado").innerText = "Uno o ambos valores no son números válidos.";
	} else {
		// Sumar los valores convertidos
		let suma = numero1 + numero2;
		document.getElementById("resultado").innerText = "La suma es: " + suma;
	}
	//$("#total").html("S/." + suma);
	//$("#total_venta").val(suma);
	//evaluar();
}

function evaluar(){

	if (detalles>0) 
	{
		$("#btnActualizar").show();
		$("#btnGuardar").show();
	}
	else
	{
		$("#btnActualizar").hide();
		$("#btnGuardar").hide();
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

function abonar(idventa) {
    $('#idventa').val(idventa);
    $.post("../ajax/venta.php?op=abonar", {idventa: idventa}, function(data, status) {
        data = JSON.parse(data);
        $('#total_venta').val(data.total_venta);
        $('#nombre_cliente').text(data.cliente);  // Asegurar de estar correcto
        $('#idcliente').text(data.cliente); // Esta línea está actualizando el div con el idcliente
        let totalPagos = 0;
        $('#tabla_pagos tbody').empty();
        for (let pago of data.pagos) {
            $('#tabla_pagos tbody').append(`<tr>
				<td>${pago.idpago}</td>
				<td>${pago.fecha}</td>
				<td>${pago.monto}</td>
				<td>${pago.metodo_pago}</td>
				<td>${pago.numero_confirmacion ? pago.numero_confirmacion : '-'}</td>
				<td><button class="btn btn-danger btn-xs" onclick="eliminarPago(${pago.idpago})"><i class="fa fa-trash"></i></button></td>
				</tr>`);
            totalPagos += parseFloat(pago.monto);
        }
        let saldoRestante = parseFloat(data.total_venta) - totalPagos;
        $('#saldo_restante').text('Saldo Restante: S/. ' + saldoRestante.toFixed(2));

        // Mostrar icono según el estado de pago y deshabilitar el campo si el saldo es 0
        mostrarIconoEstadoPago(saldoRestante);
        if (saldoRestante <= 0) {
            $('#monto_abonar').prop('disabled', true);
        } else {
            $('#monto_abonar').prop('disabled', false);
        }

        $('#abonarModal').modal('show');
    });
}
//################## IMPLEMENTADO 2-08 #####################################################################
function editar(idventa) {
    $('#idventa').val(idventa);
    $.post("../ajax/venta.php?op=editar", {idventa: idventa}, function(data, status) {
        data = JSON.parse(data);
        $('#total_venta').val(data.total_venta);
        $('#nombre_cliente').text(data.cliente);  // Asegurar de estar correcto
        $('#idcliente').text(data.cliente); // Esta línea está actualizando el div con el idcliente
        let totalPagos = 0;
        $('#tabla_editar tbody').empty();
        for (let pago of data.pagos) {
            $('#tabla_editar tbody').append(`<tr>
				<td>${pago.articulo}</td>
				<td>${pago.codigo}</td>
				<td>${pago.cantidad}</td>
				<td>${pago.precio_venta}</td>
                <td>${pago.descuento}</td>
				<td>${pago.subtotal}</td>
				</tr>`);
            totalPagos += parseFloat(pago.monto);
        }

      //  <td><button class="btn btn-danger btn-xs" onclick="eliminarPago(${pago.idpago})"><i class="fa fa-trash"></i></button></td>
        let saldoRestante = parseFloat(data.total_venta) - totalPagos;
        $('#saldo_restante').text('Saldo Restante: S/. ' + saldoRestante.toFixed(2));

        // Mostrar icono según el estado de pago y deshabilitar el campo si el saldo es 0
        mostrarIconoEstadoPago(saldoRestante);
        if (saldoRestante <= 0) {
            $('#monto_abonar').prop('disabled', true);
        } else {
            $('#monto_abonar').prop('disabled', false);
        }

        $('#editarModal').modal('show');
    });
}



function mostrarIconoEstadoPago(saldoRestante) {
    let estadoPagoDiv = $('#estado_pago');
    estadoPagoDiv.empty(); // Limpiar contenido previo

    if (saldoRestante <= 0) {
        // Mostrar icono de pagado
        estadoPagoDiv.append('<i class="fa fa-check-circle text-success"></i> Pagado');
    } else {
        // Mostrar icono de falta pagar
        estadoPagoDiv.append('<i class="fa fa-exclamation-circle text-warning"></i> Falta pagar');
    }
}

$(document).ready(function() {
    $('#metodo_pago').change(function() {
        if ($(this).val() === 'Efectivo') {
            $('#confirmacion_yape').hide();
            $('#numero_confirmacion').removeAttr('required');
        } else {
            $('#confirmacion_yape').show();
            $('#numero_confirmacion').attr('required', true);
        }
    });

    $('#form_abonar').submit(function(e) {
        e.preventDefault();
        let monto_abonar = parseFloat($('#monto_abonar').val());
		let saldo_restante = parseFloat($('#saldo_restante').text().replace('Saldo Restante: S/. ', ''));

		if (isNaN(monto_abonar)) {
            bootbox.alert({
                title: "Error",
                message: "Por favor, ingrese un monto válido.",
                size: 'small'
            });
            return;
        }

        if (monto_abonar > saldo_restante) {
			bootbox.alert({
				title: "Advertencia",
				message: "El monto a abonar no puede ser mayor que el saldo restante.",
				size: 'small' // Opcional: puedes ajustar el tamaño del modal (small, large)
			});
			return;
		}

        let idventa = $('#idventa').val();
        let metodo_pago = $('#metodo_pago').val();
        let numero_confirmacion = metodo_pago !== 'Efectivo' ? $('#numero_confirmacion').val() : null;

        $.post("../ajax/venta.php?op=realizarAbono", {
			idventa: idventa,
			monto_abonar: monto_abonar,
			metodo_pago: metodo_pago,
			numero_confirmacion: numero_confirmacion
		}, function(data) {
			bootbox.alert({
				message: data,
				size: 'small',
				callback: function() {
					$('#abonarModal').modal('hide');
					tabla.ajax.reload();
				}
			});
		});
    });
	// Limpiar el campo de monto abonar al cerrar el modal
    $('#abonarModal').on('hidden.bs.modal', function (e) {
		$('#monto_abonar').val('');
		$('#metodo_pago').val('Efectivo');
		$('#confirmacion_yape').hide();
		$('#numero_confirmacion').val('').removeAttr('required');
	});
});

function eliminarPago(idpago) {
    bootbox.confirm({
        message: "¿Estás seguro de eliminar este pago?",
        size: 'small',
        buttons: {
            confirm: {
                label: 'Sí',
                className: 'btn-primary'
            },
            cancel: {
                label: 'Cancelar',
                className: 'btn-secondary'
            }
        },
        callback: function(result) {
            if (result) {
                $.post("../ajax/venta.php?op=eliminarAbono", { idpago: idpago }, function(respuesta) {
                    bootbox.alert({
                        message: respuesta,
                        size: 'small',
                        callback: function() {
                            $('#abonarModal').modal('hide'); // Cerrar el modal
                            tabla.ajax.reload(); // Recargar la tabla de pagos
                        }
                    });
                });
            }
        }
    });
}

init();