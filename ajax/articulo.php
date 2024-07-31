<?php 
require_once "../modelos/Articulo.php";

$articulo=new Articulo();

$idarticulo=isset($_POST["idarticulo"])? limpiarCadena($_POST["idarticulo"]):"";
$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$stock_maximo=isset($_POST["stock_maximo"])? limpiarCadena($_POST["stock_maximo"]):"";
$stock=isset($_POST["stock"])? limpiarCadena($_POST["stock"]):"";
$stock_minimo=isset($_POST["stock_minimo"])? limpiarCadena($_POST["stock_minimo"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':

	if (!file_exists($_FILES['imagen']['tmp_name'])|| !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
		$imagen=$_POST["imagenactual"];
	}else{
		$ext=explode(".", $_FILES["imagen"]["name"]);
		if ($_FILES['imagen']['type']=="image/jpg" || $_FILES['imagen']['type']=="image/jpeg" || $_FILES['imagen']['type']=="image/png") {
			$imagen=round(microtime(true)).'.'. end($ext);
			move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/articulos/".$imagen);
		}
	}
	if (empty($idarticulo)) {
        $rspta = $articulo->insertar($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $stock_maximo, $stock_minimo);
        echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
    } else {
        $rspta = $articulo->editar($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $stock_maximo, $stock_minimo);
        echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
    }
    break;
	

	case 'desactivar':
		$rspta=$articulo->desactivar($idarticulo);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;
	case 'activar':
		$rspta=$articulo->activar($idarticulo);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;
	
	case 'mostrar':
		$rspta=$articulo->mostrar($idarticulo);
		echo json_encode($rspta);
		break;

    case 'listar':
		$rspta=$articulo->listar();
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>($reg->condicion)?'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-primary btn-xs" onclick="activar('.$reg->idarticulo.')"><i class="fa fa-check"></i></button>',
            "1"=>$reg->nombre,
            "2"=>$reg->categoria,
            "3"=>$reg->codigo,
			"4"=>$reg->stock_maximo,
			"5"=>$reg->stock,
			"6"=>$reg->stock_minimo,
            "7"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>",
            "8"=>$reg->descripcion,
            "9"=>($reg->condicion)?'<span class="label bg-green">Disponible</span>':'<span class="label bg-black">No Disponible</span>'
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);
		break;

		case 'selectCategoria':
			require_once "../modelos/Categoria.php";
			$categoria=new Categoria();

			$rspta=$categoria->select();

			while ($reg=$rspta->fetch_object()) {
				echo '<option value=' . $reg->idcategoria.'>'.$reg->nombre.'</option>';
			}
			break;

			case 'ajustarStock':
				$idArticulo = $_POST['idArticulo'];
				$tipoAjuste = $_POST['tipoAjuste'];
				$cantidad = intval($_POST['cantidad']);
				$motivo = $_POST['motivo'];
				
				// Obtener el stock actual
				$rspta = $articulo->mostrar($idArticulo);
				$stockActual = $rspta['stock'];
			
				if ($tipoAjuste == 'aumentar') {
					$nuevoStock = $stockActual + $cantidad;
				} elseif ($tipoAjuste == 'disminuir') {
					$nuevoStock = $stockActual - $cantidad;
					if ($nuevoStock < 0) {
						echo "La cantidad a disminuir excede el stock actual.";
						break;
					}
				}
			
				$rspta = $articulo->ajustarStock($idArticulo, $nuevoStock);
			
				if ($rspta) {
					$registroAjuste = $articulo->registrarAjuste($idArticulo, $tipoAjuste, $motivo, $cantidad);
					echo $registroAjuste ? "Stock ajustado y registrado correctamente" : "No se pudo registrar el ajuste de stock";
				} else {
					echo "No se pudo ajustar el stock";
				}
				break;
}
 ?>