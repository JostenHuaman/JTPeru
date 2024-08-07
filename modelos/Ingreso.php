<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Ingreso{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
public function insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$num_comprobante,$fecha_hora,$impuesto,$total_compra,$idarticulo,$cantidad,$precio_compra,$precio_venta){
	$sql="INSERT INTO ingreso (idproveedor,idusuario,tipo_comprobante,serie_comprobante,num_comprobante,fecha_hora,impuesto,total_compra,estado) VALUES ('$idproveedor','$idusuario','$tipo_comprobante','$serie_comprobante','$num_comprobante','$fecha_hora','$impuesto','$total_compra','Aceptado')";
	//return ejecutarConsulta($sql);
	 $idingresonew=ejecutarConsulta_retornarID($sql);
	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($idarticulo)) {

	 	$sql_detalle="INSERT INTO detalle_ingreso (idingreso,idarticulo,cantidad,precio_compra,precio_venta) VALUES('$idingresonew','$idarticulo[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]','$precio_venta[$num_elementos]')";

	 	ejecutarConsulta($sql_detalle) or $sw=false;

	 	$num_elementos=$num_elementos+1;
	 }
	 return $sw;
}

public function anular($idingreso){
	// Cambiar el estado de la venta a 'Anulado'
	$sql= "UPDATE ingreso SET estado = 'Anulado' WHERE idingreso='$idingreso'";
	$sw = ejecutarConsulta($sql);

	if ($sw) {
		// Obtener los detalles de la venta para devolver el stock
		$sql_detalle = "SELECT idarticulo, cantidad FROM detalle_ingreso WHERE idingreso = '$idingreso'";
		$detalles = ejecutarConsulta($sql_detalle);

		while ($detalle = $detalles->fetch_assoc()) {
			// Devolver el stock de cada artículo
			$this->devolverStock($detalle['idarticulo'], $detalle['cantidad']);
		}
	}
	return $sw;
}

// Método para devolver stock
public function devolverStock($idarticulo, $cantidad) {
	$sql = "UPDATE articulo SET stock = stock - $cantidad WHERE idarticulo = '$idarticulo'";
	return ejecutarConsulta($sql);
}


//metodo para mostrar registros
public function mostrar($idingreso){
	$sql="SELECT i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE idingreso='$idingreso'";
	return ejecutarConsultaSimpleFila($sql);
}

public function listarDetalle($idingreso){
	$sql="SELECT di.idingreso,di.idarticulo,a.nombre,di.cantidad,di.precio_compra,di.precio_venta FROM detalle_ingreso di INNER JOIN articulo a ON di.idarticulo=a.idarticulo WHERE di.idingreso='$idingreso'";
	return ejecutarConsulta($sql);
}

//listar registros
public function listar(){
	$sql="SELECT i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.num_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario ORDER BY i.idingreso DESC";
	return ejecutarConsulta($sql);
}

//###############################CAMBIOS 06-08##########################################################################
public function obtenerDatosEditar($idingreso) {
    $sql = "SELECT p.nombre as cliente
            FROM ingreso v
            JOIN persona p ON v.idproveedor = p.idpersona
            WHERE v.idingreso = '$idingreso'";
    return ejecutarConsultaSimpleFila($sql);
}

public function obtenerCostos($idingreso) {
   // $sql =	"SELECT impuesto
   //         FROM ingreso
   //         WHERE idingreso = '$idingreso'";
	$sql =  "SELECT fecha_hora, tipo_comprobante, serie_comprobante, num_comprobante
            FROM ingreso
            WHERE idingreso = '$idingreso'";
    return ejecutarConsultaSimpleFila($sql);
}

public function mostrarEditar($idingreso) {
	$sql="SELECT a.nombre AS articulo,di.cantidad,di.precio_compra,di.precio_venta,(di.precio_compra*di.cantidad) AS subtotal FROM detalle_ingreso di INNER JOIN articulo a ON di.idarticulo=a.idarticulo WHERE di.idingreso='$idingreso'";
    $pagos = ejecutarConsulta($sql);

	$sql_total = "SELECT total_compra FROM ingreso WHERE idingreso = '$idingreso'";
    $total = ejecutarConsultaSimpleFila($sql_total);

    return array("total_compra" => $total['total_compra'], "pagos" => $pagos->fetch_all(MYSQLI_ASSOC));
}


}

 ?>
