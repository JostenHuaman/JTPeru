<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{


require 'header.php';

if ($_SESSION['compras']==1) {

 ?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Compras <button class="btn btn-success" onclick="mostrarform(true)" id="btnMostrarForm"><i class="fa fa-plus-circle"></i>&nbsp; Agregar Compra</button>
  <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar"><i class="fa fa-arrow-circle-left"></i>&nbsp; Cancelar</button></h1>
  <div class="box-tools pull-right">
    
  </div>
</div>
<!--box-header-->
<!--centro-->
<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>Opciones</th>
      <th>#</th>
      <th>Fecha</th>
      <th>Proveedor</th>
      <th>Usuario</th>
      <th>Documento</th>
      <th>Número</th>
      <th>Total Compra</th>
      <th>Estado</th>
    </thead>
    <tbody>
    </tbody>  
  </table>
</div>
<div class="panel-body" style="height: 400px;" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
    <div class="form-group col-lg-2 col-md-8 col-xs-12">
      <label for="">Proveedor(*):</label>
      <input class="form-control" type="hidden" name="idingreso" id="idingreso">
      <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" required>
        
      </select>
    </div>
      <div class="form-group col-lg-2 col-md-4 col-xs-12">
      <label for="">Fecha(*): </label>
      <input class="form-control" type="date" name="fecha_hora" id="fecha_hora" required>
    </div>
    <div class="form-group col-lg-2 col-md-6 col-xs-12">
      <label for="">Tipo Comprobante: 
      <span style="display:inline-block; width: 90px;"></span><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Cuando el tipo de comprobante sea 'Ticket', se requerirá rellenar la serie y el número."></i>
      </label>
      <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker">
        <option value="Sin Comprobante">Sin Comprobante</option>
        <option value="Ticket">Ticket</option>
      </select>
    </div>
     <div class="form-group col-lg-1 col-md-2 col-xs-6">
      <label for="">Serie: </label>
      <input class="form-control" type="text" name="serie_comprobante" id="serie_comprobante" maxlength="9">
    </div>
     <div class="form-group col-lg-1 col-md-2 col-xs-6">
      <label for="">Número: </label>
      <input class="form-control" type="text" name="num_comprobante" id="num_comprobante" maxlength="10">
    </div>
    <div class="form-group col-lg-12 col-md-3 col-sm-6 col-xs-12">
     <a data-toggle="modal" href="#myModal">
       <button id="btnAgregarArt" type="button" class="btn btn-primary"><span class="fa fa-plus"></span> Agregar Articulos</button>
     </a>
    </div>
    <div class="form-group col-lg-2 col-md-3 col-xs-6">

    <button class="btn btn-info" type="submit" id="btnGuardar"><i class="fa fa-save"></i> &nbsp;Guardar</button>
    </div>
<div class="form-group col-lg-12 col-md-12 col-xs-12">
     <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
       <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
        <th>Articulo</th>
        <th>Cantidad</th>
        <th>Precio Compra x Unidad</th>
        <th>Precio Venta x Unidad</th>
        <th>Subtotal</th>
       </thead>
       <tfoot>
         <th>TOTAL</th>
         <th></th>
         <th></th>
         <th></th>
         <th></th>
         <th><h4 id="total">S/. 0.00</h4><input type="hidden" name="total_compra" id="total_compra"></th>
       </tfoot>
       <tbody>
         
       </tbody>
     </table>
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

  <!--Modal-->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleccione un Articulo</h4>
        </div>
        <div class="modal-body">
          <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Categoria</th>
              <th>Código</th>
              <th>Stock</th>
              <th>Imagen</th>
            </thead>
            <tbody>
              
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" type="button" data-dismiss="modal"> Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- fin Modal-->

 <!--IMPLEMENTADO 06-08 MOSTRAR-->

 <div id="editarModals" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width:800px">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" style="text-align:center"><b>Visualizar Compras</b></h3> 
            </div>
            <div class="modal-body">            
              <div id="venta-info" style="color: #e7493b; border-radius: 5px; font-size: 16px; text-align:center">
                <b>Proveedor: <span id="nombre_cliente_editar"></b></span>
               </div>
                <br>
                <div align="center">
                <form id="form_abonar">
                    <input type="hidden" name="idventa" id="idventa">
                    <div class="form-group" style="display:flex;">
                        <div style="margin-right: 40px">
                          <label for="">Fecha:</label>
                          <br>
                          <span></span><input class="form-control" type="date" name="fecha_horas" onchange="SalirCasilla()" id="fecha_horas" maxlength="10" step="any">
                        </div>
                        <div style="margin-right: 40px">
                          <label for="">Tipo Comprobante:</label>
                          <br>
                          <span></span><input class="form-control" type="text" name="tcomprobante" onchange="SalirCasilla()" id="tcomprobante" maxlength="10" step="any">
                        </div>
                        <div style="margin-right: 20px">
                          <label for="">Serie:</label>
                          <br>
                          <span></span><input class="form-control" type="text" name="nserie" onchange="SalirCasilla()" id="nserie" maxlength="10" step="any">
                        </div>
                        <div style="margin-right: 20px">
                          <label for="">Numero:</label>
                          <br>
                          <span></span><input class="form-control" type="numero" name="numero" onchange="SalirCasilla()" id="numero" maxlength="10" step="any">
                        </div>
                    </div>
                    <div class="form-group" id="confirmacion_yape" style="display: none;">
                        <label for="numero_confirmacion">Número de Operación</label>
                        <input type="text" class="form-control" id="numero_confirmacion" name="numero_confirmacion" maxlength="15">
                    </div >
                    <div>
                    <!--<button type="submit" class="btn btn-danger" >Editar Costos</button>-->
                    </div>
                </form>      
                    <h4>Compras Realizadas</h4>
                </div>
                <table id="tablas_editar" class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th>Precio Compra</th>
                            <th>Precio Venta</th>
                            <th>SubTotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <h4 style="margin-top: 32px; margin-bottom: 32px" id="saldo_restante_compra">Total Compra: S/. 0.00</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="location.reload()">Cerrar</button>
            </div>
        </div>
    </div>
</div>



<?php 
}else{
 require 'noacceso.php'; 
}

require 'footer.php';
 ?>
 <script src="scripts/ingreso.js"></script>
 <script>
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
 <?php 
}

ob_end_flush();
  ?>

