<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
require_once "../config/Conexion.php";
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{


require 'header.php';

if ($_SESSION['almacen']==1) {

?>
<!-- Contenido -->
<div class="content-wrapper">
    <section class="content">
        <div class="row">
        <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h1 class="box-title">Inventario &nbsp;<button class="btn btn-danger" onclick="mostrarform(true)" id="btnAjuste"><i class="fa fa-cog"></i>&nbsp; Ajustar</button>
                <button class="btn btn-info" onclick="mostrarform(true)" id="btnMostrar"><i class="fa fa-arrow-right"></i>&nbsp; Mostrar</button></h1>
            </div>
    
                <div class="form-group col-md-3">
                <label for="inputElePro">Elegir Producto:</label>
                <select name="idarticulo" class="form-control form-control-sm buscar">
                <option value="0">Seleccionar producto...</option>
                <?php
                $query = $conexion -> query ("SELECT * FROM articulo");
                while ($idarticulo = mysqli_fetch_array($query)) {
                echo "<option value=$idarticulo[idarticulo]>$idarticulo[nombre]</option>";
                }
                ?>  
                </select>
                </div>
            <div class="panel-body table-responsive" id="listadoregistros">
                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                    <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                        <thead >
                            <td>PRODUCTO</td>
                            <td>FECHA</td>
                            <td>ID DOCUMENTO</td>
                            <td>TIPO</td>
                            <td>CANTIDAD</td>
                            <td>PRECIO DE VENTA</td>
                        </thead>
                        <tbody>
                        <?php
                        $idarticulo = 10;
                        $sql_movimientos = "SELECT v1.nombre as 'producto',v2.idingreso as 'documento','compra' AS tipo, v2.cantidad, v2.precio_venta FROM articulo v1 left join detalle_ingreso v2 on v1.idarticulo = v2.idarticulo WHERE 
                        v1.idarticulo = $idarticulo  UNION ALL SELECT v1.nombre as 'producto',v2.idventa as 'documento','venta' AS tipo, v2.cantidad, v2.precio_venta 
                        FROM articulo v1 left join detalle_venta v2 on v1.idarticulo = v2.idarticulo WHERE v1.idarticulo = $idarticulo";
                        $resultado = mysqli_query($conexion,$sql_movimientos);
                        while($row = mysqli_fetch_assoc($resultado)){ ?>
                        <tr>
                        <td><?php echo $row['producto']?></td>
                        <td><?php echo $row['documento']?></td> <!--cambiar a fecha -->
                        <td><?php echo $row['documento']?></td> 
                        <td><?php echo $row['tipo']?></td>
                        <td><?php echo $row['cantidad']?></td>
                        <td><?php echo $row['precio_venta']?></td>
                        <?php } mysqli_free_result($resultado);?>
                        </tr>

                        </tbody>
                    </table>
                </div>
                </div>


                <div class="form-group col-lg-12 col-md-12 col-xs-12">
            <?php 
                require_once "../config/Conexion.php";
                if (strlen(session_id())<1) 
                    session_start();


            //    $idarticulo = 7; // Cambia esto al ID del producto que quieres ver

              //  $sql_producto = "SELECT * FROM articulo WHERE idarticulo = $idarticulo";
               // $result_producto = $conexion->query($sql_producto);

                //if ($result_producto->num_rows > 0) {
                  //  $articulo = $result_producto->fetch_assoc();
                   // echo "<h1>Kardex del Producto: " . $articulo['nombre'] . "</h1>";
                    
                    //$sql_movimientos = "
                      //  SELECT v1.nombre as 'producto',v2.idingreso as 'documento','compra' AS tipo, v2.cantidad, v2.precio_venta 
                       // FROM articulo v1 left join detalle_ingreso v2 on v1.idarticulo = v2.idarticulo 
                        //WHERE v1.idarticulo = $idarticulo  
                        //UNION ALL
                        //SELECT v1.nombre as 'producto',v2.idventa as 'documento','venta' AS tipo, v2.cantidad, v2.precio_venta
                        //FROM articulo v1 left join detalle_venta v2 on v1.idarticulo = v2.idarticulo
                        //WHERE v1.idarticulo = $idarticulo ";
                    
                    //$result_movimientos = $conexion->query($sql_movimientos);

                    //if ($result_movimientos->num_rows > 0) {
                      //  echo "<table border='1'>";
                       // echo "<tr><th>Producto</th>
                         //     <th>Documento</th>
                           //   <th>Tipo</th>
                             // <th>Cantidad</th>
                              //<th>Precio Venta";
        
                    //$saldo = 0;
                    //$costo_total = 0;
                    
                    //while ($movimiento = $result_movimientos->fetch_assoc()) {
                      //  if ($movimiento['tipo'] == 'compra') {
                        //    $saldo += $movimiento['cantidad'];
                         //   $costo_total += $movimiento['cantidad'] * $movimiento['precio_venta'];
                        //} else {
                          //  $saldo -= $movimiento['cantidad'];
                            //$costo_total -= $movimiento['cantidad'] * $movimiento['precio_venta'];
                        //}
                        
                          //      echo "<tr>";
                            //    echo "<td>" . $movimiento['producto'] . "</td>";
                              //  echo "<td>" . $movimiento['documento'] . "</td>";
                               // echo "<td>" . $movimiento['tipo'] . "</td>";
                                //echo "<td>" . $movimiento['cantidad'] . "</td>";
                                //echo "<td>" . $movimiento['precio_venta'] . "</td>";
                                //echo "</tr>";
                                //}
                                
                                //echo "</table>";
                            //} else {
                                //echo "No se encontraron movimientos para este producto.";
                            //}
                        //} else {
                            //echo "Producto no encontrado.";
                        //}

                        //$conexion->close(); 
                        ?>
                        </div>
                        </section>
                        </div>
                        </div>
                        </div>

<?php 
}else{
 require 'noacceso.php'; 
}

require 'footer.php';
 ?>
 <script src="scripts/inventario.js"></script>
 <?php 
}

ob_end_flush();
  ?>