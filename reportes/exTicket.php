<?php 
// Activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1) 
  session_start();

if (!isset($_SESSION['nombre'])) {
  echo "Debe ingresar al sistema correctamente para visualizar el reporte";
} else {

if ($_SESSION['ventas'] == 1) {

?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="../public/css/ticket.css">
    <style>
        body {
            font-family: Tahoma, sans-serif; 
        }
        .zona_impresion {
            background-image: url(../public/img/fondo-tk2.jpeg);
            background-repeat: no-repeat;
            background-size: cover;
            width: 650px;
            height: 310px; 
            padding: 10px; 
            box-sizing: border-box; 
        }
        .reloj-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 35%;
            height: 100%; 
        }
        .content {
            position: relative;
            margin-left: 26%;
            width: 74%; 
        }
        .logo-img {
            float: left;
            margin-right: 10px; 
            margin-top: -30px;
        }
        .yape-img {
            float: right;
            margin-right: 55px; 
            margin-top: 22px;
        }
        .color1 {
            font-size: 110%;
            transform: translateX(80px) translateY(-10px);
            color: white;
        }
        .color2 {
            font-size: 100%;
            transform: translateX(32px) translateY(-18px);
            color: white;
        }
        .center-text {
            transform: translatex(80px) translateY(-4px);
        }
        .contenido2{
            margin: -15px;
        }
        @media print {
            .zona_impresion {
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact; 
            }
        }
    </style>
</head>
<body onload="window.print();">
    <?php 
    require_once "../modelos/Venta.php";

    $venta = new Venta();
    $rspta = $venta->ventacabecera($_GET["id"]);
    $reg = $rspta->fetch_object();

    $empresa = "J&T Perú Import SAC ";
    $documento = "RUC: 20602832873";
    $telefono = "966 778 063";
    $email = "jytperuimport@gmail.com";
    ?>
<div class="zona_impresion">
    <br>
    <img src="../public/img/photo6.jpeg" class="reloj-img" alt="Reloj">
    <div class="content">
        <table border="0" align="center" width="360px">
            <tr>
                <td align="left" colspan="2" class="color1">
                    <img src="../reportes/logo1.png" class="logo-img" alt="logo" width="80" height="80">
                    <div class="contenido2">
                        <strong> <?php echo $empresa; ?></strong><br>
                        <?php echo $documento; ?><br>
                        <?php echo $telefono; ?><br>
                    </div>
                </td>
            </tr>
            <tr>
                <img src="../public/img/yape-logo.png" class="yape-img" alt="yape" width="40" height="40">
            </tr>
            <tr>
                <br><td colspan="2" class="center-text">Cliente: <?php echo $reg->cliente; ?></td>
            </tr>
            <tr>
                <td colspan="2" class="center-text">
                    <?php echo $reg->tipo_documento . ": " . $reg->num_documento; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="center-text">Celular: <?php echo $reg->telefono; ?></td>
            </tr>
            <tr>
                <td colspan="2" class="center-text">
                    Destino: <?php echo $reg->direccion; ?>
                </td>
            </tr>
        </table>
        <br>
        <table border="0" align="center" width="360px">
            <tr>
                <td align="center" colspan="3" class="color2">¡GRACIAS POR SU COMPRA!</td>
            </tr>
            <tr>
                <td align="center" colspan="3" class="color2">¡SU SATISFACCIÓN ES NUESTRO COMPROMISO!</td>
            </tr>
            <tr>
                <td align="center" colspan="3" class="color2">J&T Perú</td>
            </tr>
            <tr>
                <td align="center" colspan="3" class="color2">Lima - Perú</td>
            </tr>
        </table>
        <br>
    </div>
</div>
<p>&nbsp;</p>
</body>
</html>

<?php

} else {
    echo "No tiene permiso para visualizar el reporte";
}

}

ob_end_flush();
?>







