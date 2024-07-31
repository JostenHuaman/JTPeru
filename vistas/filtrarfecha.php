<?php
require "../config/Conexion.php";

if (isset($_POST['idarticulo'])) {
    $idarticulo = $_POST['idarticulo'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if ($idarticulo === '') {
        $sql = "SELECT v1.idarticulo, v1.nombre as 'producto', v3.fecha_hora, v2.idingreso as 'documento', 'compra' AS tipo, v2.cantidad, v2.precio_venta, '-' as motivo
                FROM detalle_ingreso v2 
                LEFT JOIN articulo v1 ON v1.idarticulo = v2.idarticulo
                LEFT JOIN ingreso v3 ON v2.idingreso = v3.idingreso
                WHERE v3.fecha_hora BETWEEN '$start_date' AND '$end_date'
                UNION ALL 
                SELECT v1.idarticulo, v1.nombre as 'producto', v3.fecha_hora, v2.idventa as 'documento', 'venta' AS tipo, v2.cantidad, v2.precio_venta, '-' as motivo
                FROM detalle_venta v2
                LEFT JOIN articulo v1 ON v1.idarticulo = v2.idarticulo
                LEFT JOIN venta v3 ON v2.idventa = v3.idventa
                WHERE v3.fecha_hora BETWEEN '$start_date' AND '$end_date'
                UNION ALL
                SELECT v1.idarticulo, v1.nombre as 'producto', a.fecha_ajuste as fecha_hora, a.id_ajuste as documento, a.tipo_ajuste AS tipo, a.cantidad, NULL AS precio_venta, a.motivo
                FROM ajuste_inventario a
                LEFT JOIN articulo v1 ON v1.idarticulo = a.id_articulo
                WHERE a.fecha_ajuste BETWEEN '$start_date' AND '$end_date'
                ORDER BY fecha_hora DESC";
    } else {
        $sql = "SELECT v1.idarticulo, v1.nombre as 'producto', v3.fecha_hora, v2.idingreso as 'documento', 'compra' AS tipo, v2.cantidad, v2.precio_venta, '-' as motivo
                FROM detalle_ingreso v2 
                LEFT JOIN articulo v1 ON v1.idarticulo = v2.idarticulo
                LEFT JOIN ingreso v3 ON v2.idingreso = v3.idingreso 
                WHERE v1.idarticulo = $idarticulo
                AND v3.fecha_hora BETWEEN '$start_date' AND '$end_date'
                UNION ALL 
                SELECT v1.idarticulo, v1.nombre as 'producto', v3.fecha_hora, v2.idventa as 'documento', 'venta' AS tipo, v2.cantidad, v2.precio_venta, '-' as motivo
                FROM detalle_venta v2
                LEFT JOIN articulo v1 ON v1.idarticulo = v2.idarticulo
                LEFT JOIN venta v3 ON v2.idventa = v3.idventa 
                WHERE v1.idarticulo = $idarticulo
                AND v3.fecha_hora BETWEEN '$start_date' AND '$end_date'
                UNION ALL
                SELECT v1.idarticulo, v1.nombre as 'producto', a.fecha_ajuste as fecha_hora, a.id_ajuste as documento, a.tipo_ajuste AS tipo, a.cantidad, NULL AS precio_venta, a.motivo
                FROM ajuste_inventario a
                LEFT JOIN articulo v1 ON v1.idarticulo = a.id_articulo
                WHERE v1.idarticulo = $idarticulo
                AND a.fecha_ajuste BETWEEN '$start_date' AND '$end_date'
                ORDER BY fecha_hora DESC";
    }

    $result = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '
        <div class="panel-body table-responsive" id="listadoregistros">
        <div class="form-group col-lg-12 col-md-12 col-xs-12">
            <table class="table table-striped table-bordered table-condensed table-hover" id="tbllistado">
                <thead>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Fecha</th>
                    <th>Documento</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Precio Venta</th>
                    <th>Motivo</th>
                </thead>
                <tbody>';
        
        while ($fila = mysqli_fetch_assoc($result)) {
            $color = '';
            $cantidad_color = '';
            $signo = '';

            switch ($fila['tipo']) {
                case 'compra':
                    $color = 'style="color:#41E089;"';
                    $cantidad_color = 'style="color:#41E089;"';
                    $signo = '+';
                    break;
                case 'venta':
                    $color = 'style="color:red;"';
                    $cantidad_color = 'style="color:red;"';
                    $signo = '-';
                    break;
                case 'aumentar':
                    $color = 'style="color:black;"';
                    $cantidad_color = 'style="color:#41E089;"';
                    $signo = '+';
                    break;
                case 'disminuir':
                    $color = 'style="color:black;"';
                    $cantidad_color = 'style="color:red;"';
                    $signo = '-';
                    break;
                default:
                    $color = 'style="color:black;"';
                    $cantidad_color = 'style="color:black;"';
                    break;
            }

            $cantidad = $fila['cantidad'] ?? '-';
            $precio_venta = $fila['precio_venta'] ? 'S/. ' . $fila['precio_venta'] : '-';
            $motivo = $fila['motivo'] ?? '-';

            echo '<tr>
                    <td>' . $fila['idarticulo'] . '</td>
                    <td>' . $fila['producto'] . '</td>
                    <td>' . $fila['fecha_hora'] . '</td>
                    <td>' . $fila['documento'] . '</td>
                    <td ' . $color . '>' . $fila['tipo'] . '</td>
                    <td ' . $cantidad_color . '>' . $signo . $cantidad . '</td>
                    <td>' . $precio_venta . '</td>
                    <td>' . $motivo . '</td>
                  </tr>';
        }
        echo '  </tbody>
            </table>
        </div>
    </div>';
    } else {
        echo '<p>No se encontraron movimientos en este producto.</p>';
    }
}
?>
