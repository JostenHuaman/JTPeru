<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{


require 'header.php';

if ($_SESSION['ventas']==1) {

 ?>

    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Ventas <button class="btn btn-success" onclick="mostrarform(true)" id="btnMostrarForm"><i class="fa fa-plus-circle"></i> &nbsp;Generar Venta</button>
  <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button></h1>
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
      <th>Página</th>
      <th>Cliente</th>
      <th>Usuario</th>
      <th>Documento</th>
      <th>Dirección</th>
      <th>Total Venta</th>
      <th>Estado</th>
    </thead>
    <tbody>
    </tbody>  
  </table>
</div>
<div class="panel-body" style="height: 600px;" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
    <div class="form-group col-lg-2 col-md-4 col-xs-12">
      <label for="">Cliente(*):</label>
      <input class="form-control" type="hidden" name="idventa" id="idventa">
      <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true" required>
      </select>
    </div>
      <div class="form-group col-lg-2 col-md-4 col-xs-12">
      <label for="">Fecha(*): </label>
      <input class="form-control" type="date" name="fecha_hora" id="fecha_hora" required>
    </div>
     <div class="form-group col-lg-2 col-md-6 col-xs-12">
     <label for="tipo_comprobante">Tipo comprobante: 
      <!--<span style="display:inline-block; width: 160px;"></span><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="El costo de envio de cada agencia no es fija, por lo que puede editar"></i>-->
      </label>
     <input type="text" class="form-control" name="tipo_comprobante" value="Ticket" readonly>
     </select>
    </div>

<!--
    <div class="form-group col-lg-2 col-md-3 col-xs-6">
    <label for="agencia">Agencia: 
        <span style="display:inline-block; width: 160px;"></span><i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="El costo de envio de cada agencia no es fija, por lo que puede editar"></i>
    </label>
    <select name="agencia" id="agencia" class="form-control selectpicker" onchange="toggleTextbox(this)">
        <option>Shalom</option>
        <option>Eva</option>
        <option>Olva Courier</option>
        <option value="activar">Otra Agencia</option>
    </select>
    <input type="text" id="agencia" class="form-control" placeholder="Ingrese otra agencia" disabled>
</div>

<script>
    function toggleTextbox(selectElement) {
        var textbox = document.getElementById('agencia');
        if (selectElement.value === 'activar') {
            textbox.disabled = false;
        } else {
            textbox.disabled = true; 
        }
    }
</script>-->

<script>
        function toggleTextbox(selectElement) {
            var otraAgenciaDiv = document.getElementById('otraAgenciaDiv');
            var inputAgencia = document.getElementById('otraAgencia');
            if (selectElement.value === 'activar') {
                otraAgenciaDiv.style.display = 'block';
                inputAgencia.name = 'agencia';
            } else {
                otraAgenciaDiv.style.display = 'none';
                inputAgencia.name = ''; // No enviar este campo si no es necesario
            }
        }
        function handleSubmit(event) {
            var selectElement = document.getElementById('agencia');
            var inputAgencia = document.getElementById('otraAgencia');
            if (selectElement.value === 'activar') {
                selectElement.value = inputAgencia.value; // Establecer el valor del select al del input
            }
            otraAgenciaDiv.style.display = 'none'; // Ocultar el campo de entrada
        }
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('form');
            var btnGuardar = document.getElementById('btnGuardar');

            btnGuardar.addEventListener('click', function() {
                handleSubmit();
                form.submit();
            });
        });
    </script>
  <div class="">
     <form action="" method="post" onsubmit="handleSubmit(event)">
        <div class="form-group col-lg-2 col-md-3 col-xs-6">
                <label for="agencia">Agencia:</label>
                <select name="agencia" id="agencia" class="form-control selectpicker" onchange="toggleTextbox(this)">
                    <option value="Shalom">Shalom</option>
                    <option value="Eva">Eva</option>
                    <option value="Olva Courier">Olva Courier</option>
                    <option value="activar">Otra Agencia</option>
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-3 col-xs-6" id="otraAgenciaDiv" style="display:none;">
                <label for="otraAgencia">Especifique la Agencia:</label>
                <input type="text" id="otraAgencia" class="form-control">
            </div>
      </form>


    <div class="form-group col-lg-3 col-md-2 col-xs-6">
      <label for="">Dirección: </label>
      <input class="form-control" type="text" name="direccion" id="direccion" maxlength="70" placeholder="Ingrese la dirección">
    </div>
    <!--#################################AGREGADO 26-07###############################-->
    <div class="form-group col-lg-3 col-md-2 col-xs-6">
      <label for="">Consideraciones: </label>
      <textarea id="consideraciones" name="consideraciones" rows="3" class="form-control" type="text" maxlength="70" placeholder="Ingrese las observaciones"></textarea><br><br>
      <!--<input type="text" maxlength="70" placeholder="Ingrese las observaciones" value="Enviar">-->
      <!--<input class="form-control" type="text" name="consideraciones" id="consideraciones" maxlength="70" placeholder="Ingrese las observaciones">-->
    </div>
     <!--###############################################################################-->
     <div class="form-group col-lg-1 col-md-2 col-xs-6">
      <label for="">Costo Envio: </label>
      <input class="form-control" type="number" name="costo_envio" onchange="SalirCasilla()" id="costo_envio" maxlength="10" placeholder="S/." step="any">
    </div>
     <div class="form-group col-lg-1 col-md-2 col-xs-6">
      <label for="">Otros costos: </label>
      <input class="form-control" type="number" name="costo_otros" onchange="SalirCasilla()" id="costo_otros" maxlength="10" placeholder="S/. " step="any">
    </div>
    <div class="form-group col-lg-2 col-md-3 col-xs-6">
      <label for="">Pagina(*): </label>
      <select name="pagina_venta" id="pagina_venta" class="form-control selectpicker" data-live-search="true" required>
       <option>Facebook JT</option>
       <option>Facebook LYON</option>
       <option>Facebook FENIX</option>
       <option>TikTok JT</option>
       <option>TikTok FENIX</option>
       <option>TikTok LYON</option>
       <option>TikTok MYSTORE</option>
       <option>TikTok STORELYON</option>
     </select>
    </div>

    <div class="form-group col-lg-12 col-md-3 col-sm-6 col-xs-12">
     <a data-toggle="modal" href="#myModal">
       <button id="btnAgregarArt" type="button" class="btn btn-primary"><span class="fa fa-plus"></span> &nbsp;Agregar Articulos</button>
     </a>
    </div>
    <div class="form-group col-lg-12 col-md-3 col-xs-6">
    <button class="btn btn-info" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>
   <!--<button class="btn btn-info" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>-->
    </div>
<div class="form-group col-lg-12 col-md-12 col-xs-12">
     <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
       <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
        <th>Articulo</th>
        <th>Cantidad</th>
        <th>Precio Venta</th>
        <th>Descuento</th>
        <!--<th>Costo Envio</th> -->
        <th>Subtotal</th>
       </thead>
       <tfoot>
         <th>TOTAL</th>
         <th></th>
         <th></th>
         <th></th>
         <th></th>
         <th><h4 id="total">S/. 0.00</h4><input type="hidden" name="total_venta" id="total_venta"></th>
       </tfoot>
       <tbody>
         
       </tbody>
     </table>
    </div>
  </form>
  <hr>
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
    <div class="modal-dialog" style="width: 39% !important;">
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
              <th>Precio Venta</th>
              <th>Imagen</th>
            </thead>
            <tbody>
              
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- fin Modal-->
  <!-- Modal for Payments -->
  <div id="abonarModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                  <h3 class="modal-title"><b>Abonar a la Venta</b></h3> 
                <div class="ml-auto">
                  <h4 id="estado_pago" class="float-right"></h4> 
                </div>
            </div>
            <div class="modal-body">
            <div id="venta-info" style="color: #e7493b; border-radius: 5px; font-size: 16px;">
                Cliente: <span id="nombre_cliente"></span>
                </div>
                <br>
                <form id="form_abonar">
                    <input type="hidden" name="idventa" id="idventa">
                    <div class="form-group">
                        <label for="monto_abonar">Monto a Abonar</label>
                        <input type="number" class="form-control" id="monto_abonar" name="monto_abonar" step="any" required>
                    </div>
                    <div class="form-group">
                        <label for="metodo_pago">Método de Pago</label>
                        <select class="form-control" id="metodo_pago" name="metodo_pago" required>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Yape">Yape</option>
                            <option value="BCP">Transferencia BCP</option>
                            <option value="Interbank">Interbank</option>
                            <option value="BBVA">BBVA</option>
                            <option value="Banco de la Nacion">Banco de la Nacion</option>
                            <option value="PLIN">PLIN</option>
                        </select>
                    </div>
                    <div class="form-group" id="confirmacion_yape" style="display: none;">
                        <label for="numero_confirmacion">Número de Operación</label>
                        <input type="text" class="form-control" id="numero_confirmacion" name="numero_confirmacion" maxlength="15">
                    </div>
                    <button type="submit" class="btn btn-danger">Abonar</button>
                </form>
                <br>
                <div align="center">
                    <h4>Pagos Realizados</h4>
                </div>
                <table id="tabla_pagos" class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Método de Pago</th>
                            <th>N° Operación</th>
                            <th>Borrar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Payments will be loaded here -->
                    </tbody>
                </table>
                <h4 id="saldo_restante">Saldo Restante: S/. 0.00</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="location.reload()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

 <!--IMPLEMENTADO 2-08 EDITAR -->

  <div id="editarModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title center"><b>Visualizar Venta</b></h3>
                <div class="ml-auto">
                  <h4 id="estado_pago" class="float-right"></h4> 
                </div>
            </div>
            <div class="modal-body">            
              <div id="venta-info" style="color: #e7493b; border-radius: 5px; font-size: 16px;">
                <b>Cliente: <span id="nombre_cliente_editar"></b></span>
               </div>
                <br>
                <form id="form_abonar">
                    <input type="hidden" name="idventa" id="idventa">
                    <div class="form-group">
                        <label for="monto_abonar">Costos de Envio</label>
                        <br>
                        <span>S/. </span><input class="" type="number" name="costos_envios" onchange="SalirCasilla()" id="costos_envios" maxlength="10" placeholder="S/." step="any">
                        <br><br>
                        <label for="monto_abonar">Otros Costos</label>
                        <br>
                        <span>S/. </span><input class="" type="number" name="costos_otros" onchange="SalirCasilla()" id="costos_otros" maxlength="10" placeholder="S/. " step="any">
                    </div>
                    <div class="form-group" id="confirmacion_yape" style="display: none;">
                        <label for="numero_confirmacion">Número de Operación</label>
                        <input type="text" class="form-control" id="numero_confirmacion" name="numero_confirmacion" maxlength="15">
                    </div>
                    <center>
                    <button type="submit" class="btn btn-danger" >Editar Costos</button>
                    </center>
                </form>
                <br>
                <div align="center">
                    <h4>Ventas Realizadas</h4>
                </div>
                <table id="tabla_editar" class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th>Precio Venta</th>
                            <th>Descuento</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <h4 id="saldo_restante_editar">Total: S/. 0.00</h4>
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
 <script src="scripts/venta.js"></script>
 <script>
  $(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
 <?php 
}

ob_end_flush();
  ?>

<?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $agencia = $_POST['agencia'];
            $otra_agencia = isset($_POST['otra_agencia']) ? $_POST['otra_agencia'] : '';

            if ($agencia === 'activar') {
              $agencia = $otra_agencia;
          }
           // echo "<div class='alert alert-success mt-3'>";
           // echo "<strong>Datos enviados:</strong><br>";
           // echo "Agencia: " . htmlspecialchars($agencia) . "<br>";
           // if ($otra_agencia) {
           //     echo "Otra Agencia: " . htmlspecialchars($otra_agencia);
           // }
           // echo "</div>"; -->
        }
?>


