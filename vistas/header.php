 <?php 
if (strlen(session_id())<1) 
  session_start();

  ?>

 <!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>J&TPerú</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../public/css/bootstrap.min.css">
  <!-- Font Awesome -->

  <link rel="stylesheet" href="../public/css/font-awesome.min.css">

  <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../public/css/_all-skins.min.css">
  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- Morris chart --><!-- Daterange picker -->
 <link rel="stylesheet" href="img/apple-touch-ico.png">
 <link rel="stylesheet" href="img/favicon.ico">
<!-- DATATABLES-->
<link rel="stylesheet" href="../public/datatables/jquery.dataTables.min.css">
<link rel="stylesheet" href="../public/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="../public/datatables/responsive.dataTables.min.css">
<link rel="stylesheet" href="../public/css/bootstrap-select.min.css">


</head>
<body class="hold-transition skin-red-light sidebar-mini">
<div class="wrapper">

  <header class="main-header ">
    <!-- Logo -->
    <a href="escritorio.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>J&T</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Menú</b> Administrador</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs"><?php echo $_SESSION['nombre']; ?></span>
              <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="user-image" alt="User Image">
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header skin-red">
                <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-circle" alt="User Image">
                <p>
                  Software de Gestion v.1
                  <small>Junio-2024</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer skin-red-light">
                <div class="pull-left">
                  <a href="usuario.php" class="btn btn-default btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                  <a href="../ajax/usuario.php?op=salir" class="btn btn-default btn-flat">Salir</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->

        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar ">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" >
      <!-- Sidebar user panel -->
     
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu " data-widget="tree">
<br>
       <?php 
if ($_SESSION['escritorio']==1) {
  echo '<li ><a href="escritorio.php"><i class="fa  fa-home (alias)"></i> <span>MENÚ PRINCIPAL</span></a>
        </li>';
}
        ?>
        <!-- Divider -->
        <hr class="sidebar-divider bg-red">
        <?php 
if ($_SESSION['ventas']==1) {
  echo '<li><a href="cliente.php"><i class="fa  fa-user (alias)"></i> <span>Clientes</span></a>
        </li>';
}
        ?>
                <?php 
if ($_SESSION['compras']==1) {
  echo ' <li><a href="proveedor.php"><i class="fa  fa-search (alias)"></i> <span>Proveedores</span></a>
        </li>';
}
?>
               <?php 
if ($_SESSION['almacen']==1) {
  echo '<li class="treeview">
          <a href="#">
            <i class="fa fa-cog"></i> <span>Control de Productos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="articulo.php"><i class="fa fa-tag"></i> Productos</a></li>
            <li><a href="categoria.php"><i class="fa fa-search"></i> Categoria</a></li>
          </ul>
        </li>
        <hr class="sidebar-divider">';
}

        ?>

<?php 
if ($_SESSION['almacen']==1) {
  echo '<li class="treeview">
          <a href="#">
            <i class="fa fa-archive"></i> <span>Control de Inventario</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <small class="label pull-right bg-yellow">New</small><li><a href="kardex.php"><i class="fa fa-list"></i> Kardex</a></li>
          </ul>
        </li>
        <hr class="sidebar-divider">';
}
        ?>

                <?php 
if ($_SESSION['ventas']==1) {
  echo '
  <li><a href="venta.php"><i class="fa  fa-shopping-cart (alias)"></i> <span>Registro Ventas</span></a>
        </li>';
}
        ?>
        <?php
if ($_SESSION['compras']==1) {
  echo '
        <li><a href="ingreso.php"><i class="fa  fa-shopping-cart (alias)"></i> <span>Registro Compra</span></a>
        </li>
        <hr class="sidebar-divider">';
}
        ?>
                             <?php 
if ($_SESSION['acceso']==1) {
  echo '  <li class="treeview">
          <a href="#">
            <i class="fa fa-cog"></i> <span>Configuración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="usuario.php"><i class="fa fa-user"></i> Usuarios</a></li>
            <li><a href="permiso.php"><i class="fa fa-lock"></i> Permisos</a></li>
          </ul>
        </li>
        <hr class="sidebar-divider">';
}
        ?>
   
        <?php 
if ($_SESSION['consultav']==1) {
  echo '<li><a href="ventasfechacliente.php"><i class="fa fa-question"></i><span>Consulta de Ventas</span></a>
        </li> ';
}
        ?>
        <?php 
if ($_SESSION['consultac']==1) {
  echo '<li><a href="comprasfecha.php"><i class="fa fa-question"></i><span>Consulta de Compras</span></a>
        </li>';
}
        ?> 
        <br><br>
        <?php 
 echo'<li>
      <spam class="logo-mayor">
      <img src="logo1.png" style="width: 220px; height: 220px;">
      </spam>
      </li>';
        ?>
        <!--<li><a href="#"><i class="fa fa-question-circle"></i> <span>Ayuda</span><small class="label pull-right bg-yellow">PDF</small></a></li>
        <li><a href="#"><i class="fa  fa-exclamation-circle"></i> <span>Ayuda</span><small class="label pull-right bg-yellow">IT</small></a></li>-->
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>