<?php 
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';
if ($_SESSION['almacen']==1) {
 ?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Productos &nbsp;<button class="btn btn-danger" onclick="mostrarform(true)" id="btnagregar"><i class="fa fa-plus-circle"></i> Agregar</button> 
    <a target="_blank" href="../reportes/rptarticulos.php">
    <button class="btn btn-info" id="btnReporte">Reporte</button></a>
    <button class="btn btn-warning" data-toggle="modal" data-target="#ajusteModal" id="btnAjustar"><i class="fa fa-cog"></i>
      Realizar un Ajuste
    </button>
  </h1>
</div>
<!--box-header-->
<!--centro-->
<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>Opciones</th>
      <th>Nombre</th>
      <th>Categoria</th>
      <th>SKU</th>
      <th>Stock Maximo</th>
      <th>Stock</th>
      <th>Stock Minimo</th>
      <th>Imagen</th>
      <th>Descripcion</th>
      <th>Estado</th>
    </thead>
    <tbody>
    </tbody>  
  </table>
</div>
<div class="panel-body" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Nombre(*):</label>
      <input class="form-control" type="hidden" name="idarticulo" id="idarticulo">
      <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Categoria(*):</label>
      <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-Live-search="true" required></select>
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Stock Maximo</label>
      <input class="form-control" type="number" name="stock_maximo" id="stock_maximo" placeholder="Ingrese Stock Maximo" value="0" required>
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Stock</label>
      <input class="form-control" type="number" name="stock" id="stock"  value="0" placeholder="*El stock se almacena comprando*"  readonly>
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Stock Minimo (*)</label>
      <input class="form-control" type="number" name="stock_minimo" id="stock_minimo" placeholder="Ingrese Stock MInimo" value="0" required>
    </div>
       <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Descripcion</label>
      <input class="form-control" type="text" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripcion">
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">SKU:</label>
      <input class="form-control" type="text" name="codigo" id="codigo" placeholder="codigo del prodcuto">
      <button class="btn btn-success" type="button" onclick="generarbarcode()">Generar</button>
      <button class="btn btn-info" type="button" onclick="imprimir()">Imprimir</button>
      <div id="print">
        <svg id="barcode"></svg>
      </div>
    </div>
        <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Imagen:</label>
      <input class="form-control" type="file" name="imagen" id="imagen">
      <input type="hidden" name="imagenactual" id="imagenactual">
      <img src="" alt="" width="150px" height="120" id="imagenmuestra">
    </div>
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <button class="btn btn-secondary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>

      <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
    </div>
  </form>
</div>
<!--fin centro-->
      </div>
      </div>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
<?php 
}else{
 require 'noacceso.php'; 
}
require 'footer.php'
 ?>
 <script src="../public/js/JsBarcode.all.min.js"></script>
 <script src="../public/js/jquery.PrintArea.js"></script>
 <script src="scripts/articulo.js"></script>

 <?php 
}

ob_end_flush();
  ?>

<!-- Modal for Config -->
<div id="ajusteModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><b>Ajuste de Inventario</b></h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="ajuste-info">
                    <label for="filtrar" class="form-label">Artículo:</label>
                    <br>
                    <select name="filtrar" id="filtrar" class="form-control form-control-sm buscar">
                        <option value="0">Seleccionar un producto...</option>
                        <?php
                        include("../config/Conexion.php");
                        $sql = "SELECT * FROM articulo";
                        $resultado = mysqli_query($conexion, $sql);
                        while ($consulta = mysqli_fetch_array($resultado)) {
                            echo '<option value="' . $consulta['idarticulo'] . '">' . $consulta['nombre'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <br>
                <div class="form-group">
                    <label for="tipo_ajuste" class="form-label">Tipo de Ajuste:</label>
                    <select id="tipo_ajuste" class="form-control">
                        <option value="aumentar"> + Aumentar stock</option>
                        <option value="disminuir"> - Disminuir stock</option>
                    </select>
                </div>
                <br>
                <div class="form-group">
                    <label for="motivo">Motivo</label>
                    <input type="text" class="form-control" id="motivo" name="motivo" required>
                </div>
                <br>
                <div class="form-group">
                    <label for="cantidad">Cantidad de Ajustar</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                </div>
                <button type="button" class="btn btn-danger" id="ajustarBtn">Ajustar</button>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
