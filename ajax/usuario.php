<?php 
session_start();
require_once "../modelos/Usuario.php";

$usuario=new Usuario();

$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$cargo=isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
$login=isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
$clave=isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':

	if (!file_exists($_FILES['imagen']['tmp_name'])|| !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
		$imagen=$_POST["imagenactual"];
	}else{
		$ext=explode(".", $_FILES["imagen"]["name"]);
		if ($_FILES['imagen']['type']=="image/jpg" || $_FILES['imagen']['type']=="image/jpeg" || $_FILES['imagen']['type']=="image/png") {
			$imagen=round(microtime(true)).'.'. end($ext);
			move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/".$imagen);
		}
	}

	//Hash SHA256 para la contraseña
	$clavehash=hash("SHA256", $clave);
	if (empty($idusuario)) {
		$rspta=$usuario->insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clavehash,$imagen,$_POST['permiso']);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar todos los datos del usuario";
	}else{
		$rspta=$usuario->editar($idusuario,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clavehash,$imagen,$_POST['permiso']);
		echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
	}
	break;
	

	case 'desactivar':
	$rspta=$usuario->desactivar($idusuario);
	echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
	break;

	case 'activar':
	$rspta=$usuario->activar($idusuario);
	echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
	break;
	
	case 'mostrar':
	$rspta=$usuario->mostrar($idusuario);
	echo json_encode($rspta);
	break;

	case 'listar':
	$rspta=$usuario->listar();
	$data=Array();

	while ($reg=$rspta->fetch_object()) {
		$data[]=array(
			"0"=>($reg->condicion)?'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idusuario.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-primary btn-xs" onclick="activar('.$reg->idusuario.')"><i class="fa fa-check"></i></button>',
			"1"=>$reg->nombre,
			"2"=>$reg->tipo_documento,
			"3"=>$reg->num_documento,
			"4"=>$reg->telefono,
			"5"=>$reg->email,
			"6"=>$reg->login,
			"7"=>"<img src='../files/usuarios/".$reg->imagen."' height='50px' width='50px'>",
			"8"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-black">Desactivado</span>'
		);
	}

	$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
	echo json_encode($results);
	break;

	case 'permisos':
			//obtenemos toodos los permisos de la tabla permisos
	require_once "../modelos/Permiso.php";
	$permiso=new Permiso();
	$rspta=$permiso->listar();
//obtener permisos asigandos
	$id=$_GET['id'];
	$marcados=$usuario->listarmarcados($id);
	$valores=array();

//almacenar permisos asigandos
	while ($per=$marcados->fetch_object()) {
		array_push($valores, $per->idpermiso);
	}
			//mostramos la lista de permisos
	while ($reg=$rspta->fetch_object()) {
		$sw=in_array($reg->idpermiso,$valores)?'checked':'';
		echo '<li><input type="checkbox" '.$sw.' name="permiso[]" value="'.$reg->idpermiso.'">'.$reg->nombre.'</li>';
	}
	break;

	case 'verificar':
		// Validar si el usuario tiene acceso al sistema
		$logina = $_POST['logina'];
		$clavea = $_POST['clavea'];
	
		// Hash SHA256 en la contraseña
		$clavehash = hash("SHA256", $clavea);
	
		$rspta = $usuario->verificar($logina, $clavehash);
	
		if ($rspta) {
			$fetch = $rspta->fetch_object();
	
			if ($fetch) {
				// Declaramos las variables de sesión
				$_SESSION['idusuario'] = $fetch->idusuario;
				$_SESSION['nombre'] = $fetch->nombre;
				$_SESSION['imagen'] = $fetch->imagen;
				$_SESSION['login'] = $fetch->login;
	
				// Obtenemos los permisos
				$marcados = $usuario->listarmarcados($fetch->idusuario);
	
				// Declaramos el array para almacenar todos los permisos
				$valores = array();
	
				// Almacenamos los permisos marcados en el array
				while ($per = $marcados->fetch_object()) {
					array_push($valores, $per->idpermiso);
				}
	
				// Determinamos los accesos del usuario
				$_SESSION['escritorio'] = in_array(1, $valores) ? 1 : 0;
				$_SESSION['almacen'] = in_array(2, $valores) ? 1 : 0;
				$_SESSION['compras'] = in_array(3, $valores) ? 1 : 0;
				$_SESSION['ventas'] = in_array(4, $valores) ? 1 : 0;
				$_SESSION['acceso'] = in_array(5, $valores) ? 1 : 0;
				$_SESSION['consultac'] = in_array(6, $valores) ? 1 : 0;
				$_SESSION['consultav'] = in_array(7, $valores) ? 1 : 0;
	
				// Devolvemos los datos del usuario como JSON
				echo json_encode($fetch);
			} else {
				// Si las credenciales son incorrectas
				echo json_encode(null);
			}
		} else {
			// Manejo de errores de la consulta o conexión
			echo json_encode(null);
		}
	
		break;
		
	case 'salir':
	   //limpiamos la variables de la secion
	session_unset();

	  //destruimos la sesion
	session_destroy();
		  //redireccionamos al login
	header("Location: ../index.php");
	break;

	


	
}
?>

