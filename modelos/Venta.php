<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Venta{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
//##############################EDITADO 26-07 ########################
//public function insertar($idcliente, $idusuario, $tipo_comprobante, $serie_comprobante, $fecha_hora, $pagina_venta, $impuesto, $costo_envio, $costo_otros, $total_venta, $idarticulo, $cantidad, $precio_venta, $descuento) {
//	$sql = "INSERT INTO venta (idcliente, idusuario, tipo_comprobante, serie_comprobante, fecha_hora, pagina_venta, impuesto, costo_envio, costo_otros, total_venta, estado) VALUES ('$idcliente', '$idusuario', '$tipo_comprobante', '$serie_comprobante', '$fecha_hora', '$pagina_venta', '$impuesto', '$costo_envio', '$costo_otros', '$total_venta', 'Aceptado')";
//	$idventanew = ejecutarConsulta_retornarID($sql); 

public function insertar($idcliente, $idusuario, $tipo_comprobante,$agencia,$direccion,$consideraciones, $fecha_hora, $pagina_venta, $impuesto, $costo_envio, $costo_otros, $total_venta, $idarticulo, $cantidad, $precio_venta, $descuento) {
	$sql = "INSERT INTO venta (idcliente, idusuario, tipo_comprobante,agencia,direccion,consideraciones,fecha_hora, pagina_venta, impuesto, costo_envio, costo_otros, total_venta, estado) VALUES ('$idcliente', '$idusuario', '$tipo_comprobante','$agencia', '$direccion', '$consideraciones','$fecha_hora', '$pagina_venta', '$impuesto', '$costo_envio', '$costo_otros', '$total_venta', 'Aceptado')";
	$idventanew = ejecutarConsulta_retornarID($sql); 


	$num_elementos = 0;
	$sw = true;
	while ($num_elementos < count($idarticulo)) {
		$sql_detalle = "INSERT INTO detalle_venta (idventa, idarticulo, cantidad, precio_venta, descuento) VALUES ('$idventanew', '$idarticulo[$num_elementos]', '$cantidad[$num_elementos]', '$precio_venta[$num_elementos]', '$descuento[$num_elementos]')";
		ejecutarConsulta($sql_detalle) or $sw = false;
		$num_elementos++;
	}
	return $sw;
}

public function anular($idventa) {
	// Cambiar el estado de la venta a 'Anulado'
	$sql = "UPDATE venta SET estado = 'Anulado' WHERE idventa = '$idventa'";
	$sw = ejecutarConsulta($sql);

	if ($sw) {
		// Obtener los detalles de la venta para devolver el stock
		$sql_detalle = "SELECT idarticulo, cantidad FROM detalle_venta WHERE idventa = '$idventa'";
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
	$sql = "UPDATE articulo SET stock = stock + $cantidad WHERE idarticulo = '$idarticulo'";
	return ejecutarConsulta($sql);
}


//implementar un metodo para mostrar los datos de un registro a modificar
public function mostrar($idventa){
    $sql="SELECT v.idventa, DATE(v.fecha_hora) as fecha, v.pagina_venta, v.idcliente, p.nombre as cliente, u.idusuario, u.nombre as usuario, 
                 v.tipo_comprobante, v.direccion, v.total_venta, v.impuesto, v.costo_envio, v.costo_otros, v.estado 
          FROM venta v 
          INNER JOIN persona p ON v.idcliente=p.idpersona 
          INNER JOIN usuario u ON v.idusuario=u.idusuario 
          WHERE v.idventa='$idventa'";
    return ejecutarConsultaSimpleFila($sql);
}

public function listarDetalle($idventa){
	$sql="SELECT dv.idventa,dv.idarticulo,a.nombre,dv.cantidad,dv.precio_venta,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal FROM detalle_venta dv INNER JOIN articulo a ON dv.idarticulo=a.idarticulo WHERE dv.idventa='$idventa'";
	return ejecutarConsulta($sql);
}

//listar registros
public function listar(){
	$sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha, v.pagina_venta,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.direccion,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idventa DESC";
	return ejecutarConsulta($sql);
}


public function ventacabecera($idventa){
	$sql= "SELECT v.idventa, v.idcliente, p.nombre AS cliente, p.direccion, p.tipo_documento, p.num_documento, p.email, p.telefono, v.idusuario, u.nombre AS usuario, v.tipo_comprobante, v.direccion, DATE(v.fecha_hora) AS fecha, v.impuesto, v.total_venta FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
	return ejecutarConsulta($sql);
}

public function ventadetalles($idventa){
	$sql="SELECT a.nombre AS articulo, a.codigo, d.cantidad, d.precio_venta,d.descuento,(d.cantidad*d.precio_venta-d.descuento) AS subtotal FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idventa='$idventa'";
         return ejecutarConsulta($sql);
}
public function verificarStock($idarticulo) {
	$sql = "SELECT stock FROM articulo WHERE idarticulo = '$idarticulo'";
	return ejecutarConsultaSimpleFila($sql);
}
public function obtenerDatosCliente($idventa) {
    $sql = "SELECT p.nombre as cliente
            FROM venta v
            JOIN persona p ON v.idcliente = p.idpersona
            WHERE v.idventa = '$idventa'";
    return ejecutarConsultaSimpleFila($sql);
}

public function mostrarPagos($idventa) {
    $sql = "SELECT p.idpago, p.fecha, p.monto, p.metodo_pago, p.numero_confirmacion FROM pagos p WHERE p.idventa = '$idventa'";
    $pagos = ejecutarConsulta($sql);

    $sql_total = "SELECT total_venta FROM venta WHERE idventa = '$idventa'";
    $total = ejecutarConsultaSimpleFila($sql_total);

    return array("total_venta" => $total['total_venta'], "pagos" => $pagos->fetch_all(MYSQLI_ASSOC));
}

public function realizarAbono($idventa, $monto_abonar, $metodo_pago, $numero_confirmacion = null) {
    $sql = "INSERT INTO pagos (idventa, monto, fecha, metodo_pago, numero_confirmacion) VALUES ('$idventa', '$monto_abonar', NOW(), '$metodo_pago', '$numero_confirmacion')";
    return ejecutarConsulta($sql);
}

public function obtenerTotalVenta($idventa) {
    $sql = "SELECT total_venta FROM venta WHERE idventa = '$idventa'";
    return ejecutarConsultaSimpleFila($sql)['total_venta'];
}

public function eliminarAbono($idpago) {
    $sql = "DELETE FROM pagos WHERE idpago = '$idpago'";
	return ejecutarConsulta($sql);
}

public function obtenerDatosEditar($idventa) {
    $sql = "SELECT p.nombre as cliente
            FROM venta v
            JOIN persona p ON v.idcliente = p.idpersona
            WHERE v.idventa = '$idventa'";
    return ejecutarConsultaSimpleFila($sql);
}

public function obtenerCostos($idventa) {
    $sql =	"SELECT costo_envio, costo_otros 
            FROM venta
            WHERE idventa = '$idventa'";
    return ejecutarConsultaSimpleFila($sql);
}

public function mostrarEditar($idventa) {
	$sql="SELECT a.nombre AS articulo, a.codigo, d.cantidad, d.precio_venta,d.descuento,(d.cantidad*d.precio_venta-d.descuento) AS subtotal FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idventa='$idventa'";
    $pagos = ejecutarConsulta($sql);

    $sql_total = "SELECT total_venta FROM venta WHERE idventa = '$idventa'";
    $total = ejecutarConsultaSimpleFila($sql_total);

    return array("total_venta" => $total['total_venta'], "pagos" => $pagos->fetch_all(MYSQLI_ASSOC));
}

}

 ?>
