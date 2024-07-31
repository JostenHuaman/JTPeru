<?php 
require_once "../modelos/Venta.php";
if (strlen(session_id())<1) 
	session_start();

$venta = new Venta();

$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
###########################AGREGADO 26-07############################################################
$agencia=isset($_POST["agencia"])? limpiarCadena($_POST["agencia"]):"";

$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";

$consideraciones=isset($_POST["consideraciones"])? limpiarCadena($_POST["consideraciones"]):"";
#####################################################################################################
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$pagina_venta=isset($_POST["pagina_venta"])? limpiarCadena($_POST["pagina_venta"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$costo_envio=isset($_POST["costo_envio"])? limpiarCadena($_POST["costo_envio"]):"";
$costo_otros=isset($_POST["costo_otros"])? limpiarCadena($_POST["costo_otros"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";





switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idventa)) {
		$rspta=$venta->insertar($idcliente,$idusuario,$tipo_comprobante,$agencia,$direccion,$consideraciones,$fecha_hora,$pagina_venta,$impuesto,$costo_envio,$costo_otros,$total_venta,$_POST["idarticulo"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento"]); 
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
        
	}
		break;
	

	case 'anular':
		$rspta=$venta->anular($idventa);
		echo $rspta ? "Ingreso anulado correctamente" : "No se pudo anular el ingreso";
		break;
	
	case 'mostrar':
		$rspta=$venta->mostrar($idventa);
		echo json_encode($rspta);
		break;

	case 'listarDetalle':
		//recibimos el idventa
		$id=$_GET['id'];

		$rspta=$venta->listarDetalle($id);
		$total=0;
		echo ' <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
        <th>Articulo</th>
        <th>Cantidad</th>
        <th>Precio Venta</th>
        <th>Descuento</th>
		
        <th>Subtotal</th>
       </thead>';
		while ($reg=$rspta->fetch_object()) {
			echo '<tr class="filas">
			<td></td>
			<td>'.$reg->nombre.'</td>
			<td>'.$reg->cantidad.'</td>
			<td>'.$reg->precio_venta.'</td>
			<td>'.$reg->descuento.'</td>
			
			<td>'.$reg->subtotal.'</td></tr>';
			$total=$total+($reg->precio_venta*$reg->cantidad)-$reg->descuento;
		}
		echo '<tfoot>
         <th>TOTAL</th>
         <th></th>
         <th></th>
         <th></th>
         <th></th>
         <th><h4 id="total">S/. '.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></th>
       </tfoot>';
		break;

		case 'listar':
			$rspta = $venta->listar();
			$data = array();
		
			while ($reg = $rspta->fetch_object()) {
				if ($reg->tipo_comprobante == 'Ticket') {
					$url = '../reportes/exTicket.php?id=';
				} else {
					$url = '../reportes/exFactura.php?id=';
				}
		
				$botones = '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->idventa . ')"><i class="fa fa-eye"></i></button>';
		
				if ($reg->estado == 'Aceptado') {
					$botones .= ' <button class="btn btn-danger btn-xs" onclick="anular(' . $reg->idventa . ')"><i class="fa fa-close"></i></button>';
					$botones .= ' <a target="_blank" href="' . $url . $reg->idventa . '"><button class="btn btn-info btn-xs"><i class="fa fa-file"></i></button></a>';
					$botones .= ' <button class="btn btn-success btn-xs" onclick="abonar(' . $reg->idventa . ')"><i class="fa fa-money"></i></button>';
				} else {
					$botones = '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->idventa . ')"><i class="fa fa-eye"></i></button>';
				}
		
				$data[] = array(
					"0" => $botones,
					"1" => $reg->idventa,
					"2" => $reg->fecha,
					"3" => $reg->pagina_venta,
					"4" => $reg->cliente,
					"5" => $reg->usuario,
					"6" => $reg->tipo_comprobante,
					#"7" => $reg->serie_comprobante,
					"7" => $reg->direccion,
					"8" => $reg->total_venta,
					"9" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' : '<span class="label bg-red">Anulado</span>'
				);
			}
		
			$results = array(
				"sEcho" => 1, // info para datatables
				"iTotalRecords" => count($data), // enviamos el total de registros al datatable
				"iTotalDisplayRecords" => count($data), // enviamos el total de registros a visualizar
				"aaData" => $data
			);
		
			echo json_encode($results);
			break;

		case 'selectCliente':
			require_once "../modelos/Persona.php";
			$persona = new Persona();

			$rspta = $persona->listarc();

			while ($reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
			}
			break;

			case 'listarArticulos':
				require_once "../modelos/Articulo.php";
				$articulo=new Articulo();
			
				$rspta=$articulo->listarActivosVenta();
				$data=Array();
			
				while ($reg=$rspta->fetch_object()) {
					$data[]=array(
						"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\','.$reg->precio_venta.','.$reg->stock.')"><span class="fa fa-plus"></span></button>',
						"1"=>$reg->nombre,
						"2"=>$reg->categoria,
						"3"=>$reg->codigo,
						"4"=>$reg->stock,
						"5"=>$reg->precio_venta,
						"6"=>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>"
					);
				}
				$results=array(
					"sEcho"=>1,//info para datatables
					"iTotalRecords"=>count($data),//enviamos el total de registros al datatable
					"iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
					"aaData"=>$data); 
				echo json_encode($results);
				break;

				case 'selectProveedor':
					require_once "../modelos/Persona.php";
					$persona = new Persona();
		
					$rspta = $persona->listarp();
		
					while ($reg = $rspta->fetch_object()) {
						echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
					}
					break;
					
						case 'verificarStock':
						$idarticulo = isset($_POST["idarticulo"]) ? limpiarCadena($_POST["idarticulo"]) : "";
						$respuesta = $venta->verificarStock($idarticulo);
						echo json_encode($respuesta);
						break;

						case 'abonar':
							$idventa = $_POST['idventa'];
							
							// Obtener datos del cliente
							$datosCliente = $venta->obtenerDatosCliente($idventa);
							
							// Obtener pagos
							$datosPagos = $venta->mostrarPagos($idventa);
							
							// Combinar todos los datos en una respuesta
							$respuesta = array(
								'cliente' => $datosCliente['cliente'],
								'total_venta' => $datosPagos['total_venta'],
								'pagos' => $datosPagos['pagos']
							);
							
							echo json_encode($respuesta);
							break;
						
							case 'realizarAbono':
								$idpago = isset($_POST['idpago']) ? $_POST['idpago'] : "";
								$idventa = isset($_POST['idventa']) ? $_POST['idventa'] : "";
								$monto_abonar = isset($_POST['monto_abonar']) ? $_POST['monto_abonar'] : 0;
								$metodo_pago = isset($_POST['metodo_pago']) ? $_POST['metodo_pago'] : "Efectivo";
								$numero_confirmacion = isset($_POST['numero_confirmacion']) ? $_POST['numero_confirmacion'] : null;
							
								// Obtener el total de la venta
								$total_venta = $venta->obtenerTotalVenta($idventa); // Necesitas crear esta función en tu modelo
							
								if ($idventa && $monto_abonar && $metodo_pago) {
									if ($monto_abonar > $total_venta) {
										echo "Error: El monto a abonar no puede ser mayor que el total de la venta.";
									} else {
										$rspta = $venta->realizarAbono($idventa, $monto_abonar, $metodo_pago, $numero_confirmacion);
										echo $rspta ? "Abono realizado correctamente" : "No se pudo realizar el abono";
									}
								} else {
									echo "Error: Faltan datos requeridos.";
								}
								break;

								case 'devolverStock':
									// Obtener los datos enviados por la solicitud AJAX
									$idarticulo = isset($_POST["idarticulo"]) ? limpiarCadena($_POST["idarticulo"]) : "";
									$cantidad = isset($_POST["cantidad"]) ? limpiarCadena($_POST["cantidad"]) : 0;
						
									// Comprobar que los parámetros no estén vacíos
									if(!empty($idarticulo) && $cantidad > 0){
										// Preparar la consulta para actualizar el stock
										$sql = "UPDATE articulo SET stock = stock + ? WHERE idarticulo = ?";
						
										// Usar una sentencia preparada para mayor seguridad
										$stmt = $conexion->prepare($sql);
										$stmt->bind_param("ii", $cantidad, $idarticulo);
						
										// Ejecutar la consulta
										if($stmt->execute()){
											echo "Stock devuelto exitosamente.";
										} else {
											echo "Error al devolver el stock: " . $stmt->error;
										}
						
										// Cerrar la sentencia
										$stmt->close();
									} else {
										echo "Datos inválidos.";
									}
									break;

									case 'eliminarAbono':
										$idpago = isset($_POST['idpago']) ? limpiarCadena($_POST['idpago']) : '';
										if ($idpago != '') {
											// Lógica para eliminar el pago en la base de datos
											$rspta = $venta->eliminarAbono($idpago);
											echo $rspta ? "Pago eliminado correctamente" : "No se pudo eliminar el pago";
										} else {
											echo "Error: Falta el ID del pago.";
										}
										break;
}
 ?>