<?php 
// Incluir la conexión de base de datos
require "../config/Conexion.php";

class Articulo {

    // Implementamos nuestro constructor
    public function __construct() {

    }

    // Método insertar registro
    public function insertar($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $stock_maximo, $stock_minimo) {
        $sql = "INSERT INTO articulo (idcategoria, codigo, nombre, stock, descripcion, imagen, stock_maximo, stock_minimo, condicion)
                VALUES ('$idcategoria', '$codigo', '$nombre', '$stock', '$descripcion', '$imagen', '$stock_maximo', '$stock_minimo', '1')";
        return ejecutarConsulta($sql);
    }

    // Método editar registro
    public function editar($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $stock_maximo, $stock_minimo) {
        $sql = "UPDATE articulo SET idcategoria='$idcategoria', codigo='$codigo', nombre='$nombre', stock='$stock', descripcion='$descripcion', imagen='$imagen', stock_maximo='$stock_maximo', stock_minimo='$stock_minimo' 
                WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    public function desactivar($idarticulo) {
        $sql = "UPDATE articulo SET condicion='0' WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    public function activar($idarticulo) {
        $sql = "UPDATE articulo SET condicion='1' WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar registros
    public function mostrar($idarticulo) {
        $sql = "SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Listar registros
    public function listar() {
        $sql = "SELECT a.idarticulo, a.idcategoria, c.nombre as categoria, a.codigo, a.nombre, a.stock, a.descripcion, a.imagen, a.stock_maximo, a.stock_minimo, a.condicion 
                FROM articulo a 
                INNER JOIN categoria c ON a.idcategoria=c.idcategoria";
        return ejecutarConsulta($sql);
    }

    // Listar registros activos
    public function listarActivos() {
        $sql = "SELECT a.idarticulo, a.idcategoria, c.nombre as categoria, a.codigo, a.nombre, a.stock, a.descripcion, a.imagen, a.stock_maximo, a.stock_minimo, a.condicion 
                FROM articulo a 
                INNER JOIN categoria c ON a.idcategoria=c.idcategoria 
                WHERE a.condicion='1'";
        return ejecutarConsulta($sql);
    }

    // Implementar un método para listar los activos, su último precio y el stock
    public function listarActivosVenta() {
        $sql = "SELECT a.idarticulo, a.idcategoria, c.nombre as categoria, a.codigo, a.nombre, a.stock, (SELECT precio_venta FROM detalle_ingreso WHERE idarticulo=a.idarticulo ORDER BY iddetalle_ingreso DESC LIMIT 0,1) AS precio_venta, a.descripcion, a.imagen, a.stock_maximo, a.stock_minimo, a.condicion 
                FROM articulo a 
                INNER JOIN categoria c ON a.idcategoria=c.idcategoria 
                WHERE a.condicion='1'";
        return ejecutarConsulta($sql);
    }
    public function ajustarStock($idarticulo, $nuevoStock) {
        $sql = "UPDATE articulo SET stock='$nuevoStock' WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    public function registrarAjuste($idarticulo, $tipoAjuste, $motivo, $cantidad) {
        $sql = "INSERT INTO ajuste_inventario (id_articulo, tipo_ajuste, motivo, cantidad)
                VALUES ('$idarticulo', '$tipoAjuste', '$motivo', '$cantidad')";
        return ejecutarConsulta($sql);
    }
}
?>