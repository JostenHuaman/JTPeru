var tabla;

//funcion que se ejecuta al inicio
function init(){

   listar();
   $.post("../ajax/venta.php?op=selectProveedor", function(r){
	$("#idproveedor").html(r);
	$('#idproveedor').selectpicker('refresh');
})}

//funcion listar
function listar(){
var  fecha_inicio = $("#fecha_inicio").val();
var fecha_fin = $("#fecha_fin").val();
var idproveedor = $("#idproveedor").val();

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
			url:'../ajax/consultas.php?op=comprasfecha',
			data:{fecha_inicio:fecha_inicio, fecha_fin:fecha_fin, idproveedor: idproveedor},
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,//paginacion
		"order":[[0,"desc"]]//ordenar (columna, orden)
	}).DataTable();
}


init();