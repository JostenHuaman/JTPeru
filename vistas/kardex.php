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
<!DOCTYPE html>
<html lang="en">

<head>
    <title>FILTRAR - JTPERU</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <!-- jQuery 3.x CDN -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<div class="content-wrapper">
<section class="content">
    <div class="row">
    <div class="col-md-12">
    <div class="box">    
    <div class="box-header with-border">
            <h1 class="box-title">Kardex Por Articulos &nbsp;</h1>
            </div>
<body>      
            <div class="box-header with-border">
           <!-- <div class="form-group col-md-3">-->
            <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <label for="" class="form-label">Articulo:</label>
            <br>
            <select name="filtrar" id="filtrar" class="form-control form-control-sm buscar">
                <option value="0">Seleccionar un producto...</option>
                <?php
                include("../config/Conexion.php");
                $sql = "SELECT * FROM articulo ";
                $resultado = mysqli_query($conexion, $sql);
                while ($consulta = mysqli_fetch_array($resultado)) {
                    echo '<option value="' . $consulta['idarticulo'] . '">' . $consulta['nombre'] . '</option>';
                }
                ?>
            </select>
            </div>
            <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <label for="" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" name="star" id="star">
            </div>

            <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <label for="" class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" name="fin" id="fin">
            </div>

            <div class="col-md-3">
                    <div class="form-group">
                        <label for="" class="form-label"><b>Filtrar</b></label><br>
                        <button type="button" id="filtro" class="btn btn-info"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>
                </div>



            <div class="form-group col-md-12">
                <div id="tabla-productos"></div>
            </div>
            </div>
</body>
    </div>
    </div>
    </div>
</section>
</div>

<script>
$('#filtrar').select2();
cargarProductos(idarticulo);
</script>
            
<script>
    $(document).ready(function () {
        $('#filtrar').select2();});
        $('#filtro').click(function () {
            var start_date = $('#star').val();
            var end_date = $('#fin').val();
            var cboidarticulo = $('#filtrar').val(); 

            $.ajax({
                url: 'filtrarfecha.php',
                type: 'post',
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    idarticulo: cboidarticulo
                },
                success: function (response) {
                    $('#tabla-productos').html(response); // Mostrar respuesta HTML directamente
                },
                error: function () {
                    alert('Error al filtrar los datos.');
                }
            });
        });
//chenge si hay un cambio en mi select2
$(document).on('change', '#filtrar', function () {
    debugger;
        //alert("dfdf");
        var idarticulo = $('#filtrar').val();  //el valor del select2
        var start_date = $('#star').val(); //atrapando la fecha inicial
        var end_date = $('#fin').val();
        cargarProductos(idarticulo,start_date, end_date); //llmando a la funcion
    })



        function cargarProductos(idarticulo,start_date,end_date) {
           // alert("llego al ajax");
            $.ajax({
                url: 'filtrar.php',
                type: 'post',
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    idarticulo: idarticulo
                },
                success: function (data) {
                    $('#tabla-productos').html(data);
                },
                error: function () {
                    alert('Error al cargar los productos.');
                }
            });
        }

  





        cargarProductos('1'); // Mostrar todos los productos al cargar la página inicialmente

</script>

</html>

<?php 
}else{
 require 'noacceso.php'; 
}

require 'footer.php';
 ?>
 <?php 
}

ob_end_flush();
  ?>