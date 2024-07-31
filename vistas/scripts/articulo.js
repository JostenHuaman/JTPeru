var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   listar();

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   })

   //cargamos los items al celect categoria
   $.post("../ajax/articulo.php?op=selectCategoria", function(r){
   	$("#idcategoria").html(r);
   	$("#idcategoria").selectpicker('refresh');
   });
   $("#imagenmuestra").hide();
}

//funcion limpiar
function limpiar(){
	$("#codigo").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
	$("#stock_maximo").val("");
	$("#stock").val("");
	$("#stock_minimo").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#print").hide();
	$("#idarticulo").val("");
}

//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		$("#btnReporte").hide();
		$("#btnAjustar").hide();
	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
		$("#btnReporte").show();
		$("#btnAjustar").show();
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
                  //'pdf'
		],
		"ajax":
		{
			url:'../ajax/articulo.php?op=listar',
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"createdRow": function( row, data, dataIndex ) {
			// Aplica estilos si el stock es menor o igual al stock mínimo
			if (parseInt(data[5]) <= parseInt(data[6])) {
				$('td', row).eq(1).css('color', 'red');
				$('td', row).eq(2).css('color', 'red');
				$('td', row).eq(3).css('color', 'red');
				$('td', row).eq(4).css('color', 'red');
				$('td', row).eq(5).css('color', 'red');
				$('td', row).eq(6).css('color', 'red');
				$('td', row).eq(8).css('color', 'red');
				$('td', row).eq(9).html('<span class="label bg-red">Comprar más</span>'); // Columna adicional con badge de compra
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,//paginacion
		"order":[[0,"desc"]]//ordenar (columna, orden)
	}).DataTable();
}

//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/articulo.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     		bootbox.alert(datos);
     		mostrarform(false);
     		tabla.ajax.reload();
     	}
     });

     limpiar();
}

function mostrar(idarticulo){
	$.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#idcategoria").val(data.idcategoria);
			$("#idcategoria").selectpicker('refresh');
			$("#codigo").val(data.codigo);
			$("#nombre").val(data.nombre);
			$("#stock_maximo").val(data.stock_maximo);
			$("#stock").val(data.stock);
			$("#stock_minimo").val(data.stock_minimo);
			$("#descripcion").val(data.descripcion);
			$("#imagenmuestra").show();
			$("#imagenmuestra").attr("src","../files/articulos/"+data.imagen);
			$("#imagenactual").val(data.imagen);
			$("#idarticulo").val(data.idarticulo);
			generarbarcode();
		})
}


//funcion para desactivar
function desactivar(idarticulo){
	bootbox.confirm("¿Esta seguro de desactivar este dato?", function(result){
		if (result) {
			$.post("../ajax/articulo.php?op=desactivar", {idarticulo : idarticulo}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function activar(idarticulo){
	bootbox.confirm("¿Esta seguro de activar este dato?" , function(result){
		if (result) {
			$.post("../ajax/articulo.php?op=activar" , {idarticulo : idarticulo}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function generarbarcode(){
	codigo=$("#codigo").val();
	JsBarcode("#barcode",codigo);
	$("#print").show();

}

function imprimir(){
	$("#print").printArea();
}

// Función para limpiar los campos del modal
function limpiarModalAjuste() {
	$('#filtrar').val('0').change(); // Restablecer el select
	$('#tipo_ajuste').val('aumentar').change(); // Restablecer el select
	$('#motivo').val('');
	$('#cantidad').val('');
}


function ajustarStock() {
    var idArticulo = $('#filtrar').val();
    var tipoAjuste = $('#tipo_ajuste').val();
    var cantidad = parseInt($('#cantidad').val());
    var motivo = $('#motivo').val();

    if (idArticulo == 0 || cantidad <= 0 || motivo.trim() == '') {
        bootbox.alert('Por favor, complete todos los campos.');
        return;
    }

    $.ajax({
        url: "../ajax/articulo.php?op=ajustarStock",
        type: "POST",
        data: { idArticulo: idArticulo, tipoAjuste: tipoAjuste, cantidad: cantidad, motivo: motivo },
        success: function(response) {
            bootbox.alert(response);
            $('#ajusteModal').modal('hide');
            tabla.ajax.reload();
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

// Añadir el evento click al botón Ajustar
$(document).ready(function () {
    $('#ajustarBtn').on('click', function () {
        ajustarStock();
    });
	$('#ajusteModal').on('hidden.bs.modal', function () {
        limpiarModalAjuste();
    });
});



init();