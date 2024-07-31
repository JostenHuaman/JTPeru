-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-07-2024 a las 07:01:28
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbsistema`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajuste_inventario`
--

CREATE TABLE `ajuste_inventario` (
  `id_ajuste` int(11) NOT NULL,
  `id_articulo` int(11) NOT NULL,
  `tipo_ajuste` enum('aumentar','disminuir') NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_ajuste` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ajuste_inventario`
--

INSERT INTO `ajuste_inventario` (`id_ajuste`, `id_articulo`, `tipo_ajuste`, `motivo`, `cantidad`, `fecha_ajuste`) VALUES
(1, 10, 'aumentar', 'Mal conteo.', 5, '2024-07-19 16:23:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo`
--

CREATE TABLE `articulo` (
  `idarticulo` int(11) NOT NULL,
  `idcategoria` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `stock_maximo` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `stock_minimo` int(11) DEFAULT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  `imagen` varchar(50) DEFAULT NULL,
  `condicion` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `articulo`
--

INSERT INTO `articulo` (`idarticulo`, `idcategoria`, `codigo`, `nombre`, `stock_maximo`, `stock`, `stock_minimo`, `descripcion`, `imagen`, `condicion`) VALUES
(6, 9, '00001', 'Reloj Navi Force Azul', 500, 124, 2, 'modelo de reloj navi force de color azul', '1718913783.jpg', 1),
(7, 13, '00003', 'Reloj Navi Force Amarillo', 500, 137, 2, 'modelo de reloj navi force de color amarillo', '1718913834.jpg', 1),
(8, 9, '00004', 'Reloj Navi Force Negro', 500, 194, 2, 'modelo de reloj navi force de color negro mate', '1718913897.jpg', 1),
(9, 13, '1314', 'Reloj Navi Force', 500, 126, 2, 'relojes de tipo navi force', '1718899182.png', 1),
(10, 8, '00005', 'Encendedor Tipo Palanca', 500, 95, 2, 'encendedor de tipo palanca colo negro', '1718913979.jpg', 1),
(11, 8, '00005', 'Encendedor Tipo Variada', 500, 22, 2, 'encendedor de tipo variado con tapa', '1718914060.jpg', 1),
(12, 8, '023', 'Encendedor Azul', 500, 0, 2, 'encendedor de tipo palanca color azul', '1719425499.png', 1),
(13, 8, '029', 'Encendedor Rosado', 500, 80, 2, 'Encendedor de color rosa de tamaño grande', '1719869868.png', 1),
(14, 7, '030', 'Linterna Dorada', 500, 50, 2, 'Linterna con forma de porsche color dorado', '1719869903.png', 1),
(15, 7, '031', 'Reloj Navi Force Morado', 500, 304, 2, 'reloj de la marca navi force de color morado', '1719869933.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `idcategoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(256) DEFAULT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`idcategoria`, `nombre`, `descripcion`, `condicion`) VALUES
(7, 'LINTERNA', 'collares de todo tipo', 1),
(8, 'ENCENDEDOR', 'todo tipo de encendedor', 1),
(9, 'RELOJ DIGITAL', 'relojes con la hora digital', 1),
(13, 'RELOJ ANÁLOGICO', 'relojes analógicos de distinto tipo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ingreso`
--

CREATE TABLE `detalle_ingreso` (
  `iddetalle_ingreso` int(11) NOT NULL,
  `idingreso` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_compra` decimal(11,2) NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `detalle_ingreso`
--

INSERT INTO `detalle_ingreso` (`iddetalle_ingreso`, `idingreso`, `idarticulo`, `cantidad`, `precio_compra`, `precio_venta`) VALUES
(16, 6, 6, 10, '20.00', '30.00'),
(17, 6, 7, 5, '200.00', '250.00'),
(18, 7, 8, 10, '16.00', '25.00'),
(19, 8, 7, 10, '250.00', '300.00'),
(20, 9, 8, 50, '20.00', '30.00'),
(21, 10, 6, 10, '25.00', '30.00'),
(22, 11, 7, 15, '250.00', '300.00'),
(23, 12, 9, 50, '50.00', '90.00'),
(24, 13, 12, 40, '15.00', '18.90'),
(25, 14, 13, 89, '10.00', '15.90'),
(26, 15, 6, 100, '25.00', '30.00'),
(27, 16, 12, 5, '16.00', '20.00'),
(28, 17, 11, 20, '20.00', '25.00'),
(29, 18, 10, 20, '30.00', '35.00');

--
-- Disparadores `detalle_ingreso`
--
DELIMITER $$
CREATE TRIGGER `tr_updStockIngreso` AFTER INSERT ON `detalle_ingreso` FOR EACH ROW BEGIN
UPDATE articulo SET stock=stock + NEW.cantidad
WHERE articulo.idarticulo = NEW.idarticulo;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `iddetalle_venta` int(11) NOT NULL,
  `idventa` int(11) NOT NULL,
  `idarticulo` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  `descuento` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`iddetalle_venta`, `idventa`, `idarticulo`, `cantidad`, `precio_venta`, `descuento`) VALUES
(12, 10, 6, 10, '30.00', '5.00'),
(13, 10, 7, 10, '250.00', '10.00'),
(14, 11, 6, 1, '30.00', '0.00'),
(15, 11, 7, 1, '250.00', '0.00'),
(16, 12, 7, 4, '250.00', '0.00'),
(17, 13, 7, 1, '250.00', '10.00'),
(18, 14, 7, 2, '250.00', '10.00'),
(19, 15, 6, 1, '30.00', '10.00'),
(20, 16, 7, 1, '250.00', '5.00'),
(21, 17, 7, 1, '250.00', '5.00'),
(22, 18, 6, 1, '30.00', '0.00'),
(23, 19, 7, 1, '250.00', '2.00'),
(24, 20, 8, 2, '25.00', '0.00'),
(25, 21, 6, 1, '30.00', '5.00'),
(26, 22, 6, 1, '30.00', '0.00'),
(27, 22, 7, 1, '300.00', '0.00'),
(28, 22, 8, 1, '30.00', '0.00'),
(29, 23, 9, 20, '90.00', '10.00'),
(30, 24, 6, 10, '120.00', '5.00'),
(31, 24, 9, 3, '100.00', '5.00'),
(32, 26, 6, 2, '30.00', '10.00'),
(33, 26, 8, 3, '30.00', '10.00'),
(34, 27, 13, 2, '15.90', '0.00'),
(35, 27, 12, 1, '18.90', '0.00'),
(36, 28, 12, 1, '18.90', '1.00'),
(37, 29, 6, 3, '30.00', '0.00'),
(38, 29, 7, 2, '300.00', '0.00'),
(39, 29, 8, 1, '30.00', '0.00'),
(40, 30, 13, 5, '15.90', '0.00'),
(41, 30, 12, 5, '18.90', '0.00'),
(42, 31, 8, 1, '30.00', '0.00'),
(43, 31, 6, 1, '30.00', '0.00'),
(44, 31, 12, 1, '18.90', '0.00'),
(45, 32, 7, 1, '300.00', '0.00'),
(46, 33, 9, 1, '90.00', '0.00'),
(47, 33, 8, 1, '30.00', '0.00'),
(48, 33, 13, 1, '15.90', '0.00'),
(49, 34, 14, 20, '40.00', '5.00'),
(50, 35, 14, 5, '40.00', '5.00'),
(51, 36, 12, 30, '18.90', '0.00'),
(52, 37, 6, 10, '30.00', '0.00'),
(53, 38, 12, 1, '20.00', '5.00'),
(54, 38, 11, 1, '25.00', '5.00'),
(55, 39, 10, 5, '35.00', '0.00'),
(56, 40, 13, 1, '15.90', '0.00'),
(57, 40, 12, 1, '20.00', '0.00'),
(59, 42, 12, 3, '20.00', '10.00'),
(60, 43, 12, 3, '20.00', '5.00'),
(61, 44, 12, 3, '20.00', '5.00'),
(62, 45, 11, 1, '25.00', '0.00'),
(63, 46, 11, 1, '25.00', '10.00'),
(64, 47, 12, 3, '20.00', '5.00'),
(65, 48, 10, 2, '35.00', '10.00'),
(66, 48, 11, 1, '25.00', '0.00'),
(67, 49, 10, 2, '35.00', '0.00'),
(68, 50, 12, 2, '20.00', '10.00'),
(69, 51, 10, 3, '35.00', '10.00'),
(70, 52, 12, 2, '20.00', '10.00'),
(71, 52, 11, 3, '25.00', '20.00'),
(72, 53, 10, 2, '35.00', '0.00'),
(73, 53, 12, 1, '20.00', '0.00'),
(74, 54, 10, 2, '35.00', '0.00'),
(75, 54, 13, 3, '15.90', '0.00'),
(76, 55, 10, 1, '35.00', '0.00'),
(77, 55, 11, 1, '25.00', '0.00'),
(78, 56, 11, 1, '25.00', '10.00'),
(79, 56, 12, 1, '20.00', '5.00'),
(80, 57, 10, 2, '35.00', '10.00'),
(81, 57, 10, 3, '35.00', '10.00'),
(82, 58, 11, 2, '25.00', '0.00'),
(83, 58, 10, 1, '35.00', '0.00'),
(84, 59, 11, 2, '25.00', '0.00'),
(85, 60, 10, 3, '35.00', '0.00'),
(86, 61, 11, 2, '25.00', '10.00'),
(87, 62, 11, 2, '25.00', '5.00'),
(88, 63, 10, 2, '35.00', '10.00'),
(89, 64, 10, 2, '35.00', '10.00'),
(90, 65, 10, 1, '35.00', '0.00'),
(91, 66, 10, 1, '35.00', '0.00'),
(92, 67, 11, 2, '25.00', '10.00'),
(93, 68, 11, 2, '25.00', '10.00'),
(94, 69, 11, 1, '25.00', '0.00'),
(95, 70, 11, 2, '25.00', '10.00'),
(96, 71, 11, 1, '25.00', '5.00'),
(97, 72, 10, 2, '35.00', '0.00'),
(98, 73, 11, 1, '25.00', '5.00'),
(99, 74, 10, 2, '35.00', '10.00'),
(100, 75, 11, 1, '25.00', '5.00'),
(101, 76, 11, 2, '25.00', '0.00'),
(102, 77, 11, 2, '25.00', '10.00'),
(103, 78, 10, 2, '35.00', '10.00'),
(104, 79, 10, 1, '35.00', '5.00'),
(105, 80, 10, 1, '35.00', '5.00');

--
-- Disparadores `detalle_venta`
--
DELIMITER $$
CREATE TRIGGER `tr_udpStockVenta` AFTER INSERT ON `detalle_venta` FOR EACH ROW BEGIN
UPDATE articulo SET stock = stock - NEW.cantidad
WHERE articulo.idarticulo = NEW.idarticulo;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso`
--

CREATE TABLE `ingreso` (
  `idingreso` int(11) NOT NULL,
  `idproveedor` int(11) NOT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `tipo_comprobante` varchar(20) NOT NULL,
  `serie_comprobante` varchar(7) DEFAULT NULL,
  `num_comprobante` varchar(10) NOT NULL,
  `fecha_hora` date NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total_compra` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `ingreso`
--

INSERT INTO `ingreso` (`idingreso`, `idproveedor`, `idusuario`, `tipo_comprobante`, `serie_comprobante`, `num_comprobante`, `fecha_hora`, `impuesto`, `total_compra`, `estado`) VALUES
(6, 7, 1, 'Factura', '001', '0001', '2018-08-20', '18.00', '1200.00', 'Aceptado'),
(7, 7, 1, 'Factura', '001', '008', '2018-08-21', '18.00', '160.00', 'Aceptado'),
(8, 7, 1, 'Boleta', '0002', '0004', '2018-08-22', '0.00', '2500.00', 'Aceptado'),
(9, 9, 1, 'Factura', '001', '0005', '2018-08-23', '18.00', '1000.00', 'Aceptado'),
(10, 10, 1, 'Factura', '001', '0006', '2018-08-25', '18.00', '250.00', 'Aceptado'),
(11, 10, 1, 'Factura', '001', '0007', '2018-08-27', '18.00', '3750.00', 'Aceptado'),
(12, 9, 1, 'Ticket', '0001238', '87264812', '2024-06-20', '12.00', '2500.00', 'Aceptado'),
(13, 10, 1, 'SinComprobante', '', '', '2024-07-01', '0.00', '600.00', 'Aceptado'),
(14, 14, 1, 'Ticket', '', '', '2024-07-01', '0.00', '890.00', 'Aceptado'),
(15, 10, 1, 'SinComprobante', '', '', '2024-07-05', '0.00', '2500.00', 'Aceptado'),
(16, 7, 1, 'SinComprobante', '', '', '2024-07-09', '0.00', '80.00', 'Aceptado'),
(17, 10, 1, 'Ticket', '', '', '2024-07-09', '0.00', '400.00', 'Aceptado'),
(18, 10, 1, 'SinComprobante', '', '', '2024-07-10', '0.00', '600.00', 'Aceptado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `idpago` int(11) NOT NULL,
  `idventa` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` datetime NOT NULL,
  `metodo_pago` varchar(20) NOT NULL,
  `numero_confirmacion` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`idpago`, `idventa`, `monto`, `fecha`, `metodo_pago`, `numero_confirmacion`) VALUES
(21, 40, '30.00', '2024-07-10 17:51:43', 'efectivo', ''),
(22, 40, '25.90', '2024-07-10 18:07:23', 'efectivo', ''),
(33, 36, '300.00', '2024-07-11 17:50:09', 'efectivo', ''),
(36, 44, '85.00', '2024-07-11 18:19:01', 'efectivo', ''),
(37, 39, '40.00', '2024-07-12 15:22:52', 'BCP', ''),
(38, 39, '40.00', '2024-07-12 15:33:33', 'Interbank', '14786654378'),
(39, 39, '20.00', '2024-07-12 15:33:53', 'PLIN', '10102012-B'),
(40, 39, '70.00', '2024-07-12 15:34:20', 'BBVA', '152098776577812'),
(41, 39, '5.00', '2024-07-12 15:35:39', 'Efectivo', ''),
(42, 39, '2.00', '2024-07-12 15:36:29', 'Yape', '9087665321-KLQ'),
(43, 45, '10.00', '2024-07-13 22:35:40', 'Efectivo', ''),
(44, 45, '1.00', '2024-07-13 22:36:18', 'Efectivo', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `idpermiso` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`idpermiso`, `nombre`) VALUES
(1, 'Escritorio'),
(2, 'Almacen'),
(3, 'Compras'),
(4, 'Ventas'),
(5, 'Acceso'),
(6, 'Consulta Compras'),
(7, 'Consulta Ventas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `idpersona` int(11) NOT NULL,
  `tipo_persona` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo_documento` varchar(20) DEFAULT NULL,
  `num_documento` varchar(20) DEFAULT NULL,
  `direccion` varchar(70) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`idpersona`, `tipo_persona`, `nombre`, `tipo_documento`, `num_documento`, `direccion`, `telefono`, `email`) VALUES
(7, 'Proveedor', 'Importaciones SAC', 'RUC', '20727104527', 'Av. Aviacion 299', '989776352', 'ImportSAC@hotmail.com'),
(8, 'Cliente', 'Juan Fernandez', 'DNI', '78723612', 'Av. Aviación 142', '965678422', 'juanfermay12@gmail.com'),
(9, 'Proveedor', 'Importaciones San Isidro', 'RUC', '20485248751', 'Calle los naranjales 245', '054587852', 'ImportSANISI@gmail.com'),
(10, 'Proveedor', 'Importaciones Relojes SAC', 'RUC', '20485245824', 'Av. quiñones 102', '976774390', 'relojsacimports@hotmail.com'),
(11, 'Cliente', 'Pedro Martinez', 'DNI', '76382711', 'Urb. Los Robles 886', '973681021', 'jorge12@gmail.com'),
(12, 'Cliente', 'Cristopher Lopez', 'DNI', '72710452', 'Av. La Molina 123', '976332763', 'Cristtto123@gmail.com'),
(13, 'Cliente', 'Angel Prado', 'RUC', '10719054211', 'Calle los alpes 210', '965678232', 'MarcosLA203@gmail.com'),
(14, 'Proveedor', 'Relojería San Francisco', 'RUC', '20763789765', 'Calle Las Golondrinas 8990', '20767754315', 'San_Fran123Rel@hotmail.com'),
(15, 'Cliente', 'Manuel Fernández Collado', 'DNI', '76535144', 'Javier Prado Este, 23441', '989765531', 'ManuCraft2983@gmail.com'),
(16, 'Cliente', 'Julián Ramírez Bonaventura', 'RUC', '10717265319', 'Calle Los Electricos 87, mz 10', '907654712', 'JulianAFF54@hotmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo_documento` varchar(20) NOT NULL,
  `num_documento` varchar(20) NOT NULL,
  `direccion` varchar(70) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `cargo` varchar(20) DEFAULT NULL,
  `login` varchar(20) NOT NULL,
  `clave` varchar(64) NOT NULL,
  `imagen` varchar(50) NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `tipo_documento`, `num_documento`, `direccion`, `telefono`, `email`, `cargo`, `login`, `clave`, `imagen`, `condicion`) VALUES
(1, 'admin', 'DNI', '72154871', 'Calle los alpes 210', '547821', 'admin@gmail.com', 'Administrador', 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', '1535417472.jpg', 1),
(2, 'juan', 'DNI', '30115425', 'calle los jirasoles 450', '054789521', 'juan@hotmail.com', 'empleado', 'juan', '5b065b0996c44ab2e641e24472b28d3e38554ce13d06d72b1aa93480dda21d43', '1535417486.jpg', 1),
(3, 'francesco', 'DNI', '72710452', 'Calle Los Paujiles 152', '976332763', 'franpi1357@gmail.com', 'Administrador', 'fran', '8ec98ed17b2fba4b6065111ab34427993f210e878414df557b31b443aacaa327', '1718908032.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_permiso`
--

CREATE TABLE `usuario_permiso` (
  `idusuario_permiso` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idpermiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario_permiso`
--

INSERT INTO `usuario_permiso` (`idusuario_permiso`, `idusuario`, `idpermiso`) VALUES
(98, 2, 4),
(127, 1, 1),
(128, 1, 2),
(129, 1, 3),
(130, 1, 4),
(131, 1, 5),
(132, 1, 6),
(133, 1, 7),
(134, 3, 1),
(135, 3, 2),
(136, 3, 3),
(137, 3, 4),
(138, 3, 5),
(139, 3, 6),
(140, 3, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `idventa` int(11) NOT NULL,
  `idcliente` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `tipo_comprobante` varchar(20) NOT NULL,
  `agencia` varchar(70) DEFAULT NULL,
  `direccion` varchar(70) DEFAULT NULL,
  `consideraciones` varchar(70) DEFAULT NULL,
  `fecha_hora` date NOT NULL,
  `pagina_venta` varchar(25) NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `costo_envio` decimal(11,2) DEFAULT NULL,
  `costo_otros` decimal(11,2) DEFAULT NULL,
  `total_venta` decimal(11,2) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`idventa`, `idcliente`, `idusuario`, `tipo_comprobante`, `agencia`, `direccion`, `consideraciones`, `fecha_hora`, `pagina_venta`, `impuesto`, `costo_envio`, `costo_otros`, `total_venta`, `estado`) VALUES
(10, 8, 1, 'Boleta', NULL, 'Av. Arequipa 1230, Santa Beatriz, Lima.', NULL, '2018-01-08', 'Facebook FENIX', '0.00', '5.00', '10.00', '11800.15', 'Anulado'),
(11, 8, 1, 'Factura', NULL, 'Calle Las Camelias 590, San Isidro, Lima.', NULL, '2018-03-05', 'Facebook FENIX', '0.00', '5.00', '10.00', '3800.00', 'Aceptado'),
(12, 8, 1, 'Ticket', NULL, 'Av. Camino Real 770, San Isidro, Lima.', NULL, '2018-04-17', 'Facebook FENIX', '0.00', '10.00', '5.00', '1000.00', 'Aceptado'),
(13, 8, 1, 'Factura', NULL, 'Av. Benavides 1050, Miraflores, Lima.', NULL, '2018-06-09', 'Facebook FENIX', '0.00', '5.00', '5.00', '240.00', 'Aceptado'),
(14, 8, 1, 'Factura', NULL, 'Av. La Paz 645, Miraflores, Lima.', NULL, '2018-07-24', 'Facebook FENIX', '0.00', '10.00', '5.00', '490.00', 'Aceptado'),
(15, 8, 1, 'Factura', NULL, 'Mz. C Lt. 8, Urb. Las Flores, San Juan de Lurigancho, Lima.', NULL, '2018-08-26', 'Facebook FENIX', '0.00', '10.00', '5.00', '20.00', 'Aceptado'),
(16, 8, 1, 'Boleta', NULL, 'Av. Pardo y Aliaga 640, San Isidro, Lima.', NULL, '2018-08-26', 'Facebook FENIX', '0.00', '5.00', '10.00', '245.00', 'Anulado'),
(17, 8, 1, 'Factura', NULL, 'Av. Salaverry 3050, Magdalena del Mar, Lima.', NULL, '2018-08-26', 'Facebook LYON', '0.00', '5.00', '10.00', '245.00', 'Aceptado'),
(18, 8, 1, 'Boleta', NULL, 'Calle Los Alamos 135, San Isidro, Lima.', NULL, '2018-08-26', 'Facebook LYON', '0.00', '10.00', '5.00', '30.00', 'Aceptado'),
(19, 8, 1, 'Factura', NULL, 'Calle Berlín 370, Miraflores, Lima.', NULL, '2018-08-26', 'Facebook LYON', '0.00', '5.00', '10.00', '248.00', 'Aceptado'),
(20, 8, 1, 'Factura', NULL, 'Jr. de la Unión 840, Centro de Lima, Lima.', NULL, '2018-08-26', 'Facebook LYON', '0.00', '10.00', '5.00', '50.00', 'Anulado'),
(21, 8, 1, 'Factura', NULL, 'Av. Larco 1150, Miraflores, Lima.', NULL, '2018-08-27', 'Facebook LYON', '0.00', '5.00', '10.00', '25.00', 'Aceptado'),
(22, 11, 1, 'Ticket', NULL, 'Calle Los Eléctricos, mz 9, lote 3', NULL, '2018-08-27', 'Facebook LYON', '0.00', '5.00', '10.00', '360.00', 'Aceptado'),
(23, 12, 1, 'Ticket', NULL, 'Calle Los Paujiles 165, urb. Santa Anita', NULL, '2024-06-20', 'TikTok FENIX', '0.00', '5.00', '5.00', '1790.00', 'Aceptado'),
(24, 8, 1, 'Boleta', NULL, 'Av. Las Lomas 198, lote 2', NULL, '2024-06-20', 'TikTok FENIX', '0.00', '10.00', '10.00', '1490.00', 'Aceptado'),
(25, 11, 1, 'Ticket', NULL, 'Las Golondrinas 778, urb. Ate', NULL, '2024-07-01', 'TikTok MYSTORE', '0.00', '5.00', '10.00', '420.00', 'Aceptado'),
(26, 12, 1, 'Ticket', NULL, 'Av. Javier Prado Este 4200, San Borja, Lima.', NULL, '2024-07-01', 'TikTok MYSTORE', '0.00', '5.00', '5.00', '140.00', 'Aceptado'),
(27, 12, 1, 'Sin_Comprobante', NULL, 'Calle Schell 250, Miraflores, Lima.', NULL, '2024-07-01', 'TikTok MYSTORE', '0.00', '10.00', '10.00', '70.70', 'Aceptado'),
(28, 16, 1, 'Ticket', NULL, 'Av. Primavera 120, Santiago de Surco, Lima.', NULL, '2024-07-01', 'TikTok MYSTORE', '0.00', '10.00', '10.00', '37.90', 'Aceptado'),
(29, 12, 1, 'Ticket', NULL, 'Av. San Luis 200, San Borja, Lima.', NULL, '2024-07-02', 'TikTok MYSTORE', '0.00', '10.00', '10.00', '740.00', 'Aceptado'),
(30, 13, 1, 'Sin_Comprobante', NULL, 'Jr. Carabaya 500, Centro de Lima, Lima.', NULL, '2024-07-02', 'TikTok MYSTORE', '0.00', '5.00', '15.00', '194.00', 'Aceptado'),
(31, 13, 1, 'Sin_Comprobante', NULL, 'Los Alamos 987, mz 3, lote 89', NULL, '2024-07-03', 'TikTok MYSTORE', '0.00', '20.00', '50.00', '148.90', 'Aceptado'),
(32, 12, 1, 'Ticket', NULL, 'Ovalo Santa Anita 600, urb Santa Ana', NULL, '2024-07-03', 'TikTok MYSTORE', '0.00', '30.00', '20.00', '350.00', 'Aceptado'),
(33, 16, 1, 'Ticket', NULL, 'Cerro San Cristobal 98, casa 2a', NULL, '2024-07-03', 'TikTok MYSTORE', '0.00', '100.00', '10.00', '245.90', 'Aceptado'),
(34, 11, 1, 'Ticket', NULL, 'Cerro San Cristobal 98, casa 1a', NULL, '2024-07-05', 'TikTok MYSTORE', '0.00', '10.00', '20.00', '825.00', 'Aceptado'),
(35, 13, 1, 'Ticket', NULL, 'Cerro San Cristobal 76, casa 3b', NULL, '2024-07-05', 'TikTok MYSTORE', '0.00', '10.00', '10.00', '215.00', 'Aceptado'),
(36, 13, 1, 'Sin_Comprobante', NULL, 'Cerro San Cristobal 98, casa 5a', NULL, '2024-07-07', 'TikTok FENIX', '0.00', '20.00', '10.00', '597.00', 'Aceptado'),
(37, 12, 1, 'Ticket', NULL, 'Cerro San Cristobal 98, casa 10a', NULL, '2024-07-07', 'TikTok FENIX', '0.00', '20.00', '5.00', '325.00', 'Aceptado'),
(38, 12, 1, 'Ticket', NULL, 'Cerro San Cristobal 98, casa 98l', NULL, '2024-07-09', 'TikTok FENIX', '0.00', '25.00', '30.00', '90.00', 'Aceptado'),
(39, 12, 1, 'Ticket', NULL, 'Cerro San Cristobal 98, casa 9t', NULL, '2024-07-10', 'TikTok FENIX', '0.00', '10.00', '0.00', '185.00', 'Aceptado'),
(40, 12, 1, 'Ticket', NULL, 'Cerro San Cristobal 98, casa 2t', NULL, '2024-07-10', 'TikTok FENIX', '0.00', '10.00', '10.00', '55.90', 'Aceptado'),
(41, 12, 1, 'Ticket', NULL, 'Los Paujiles 198, urb Comas', NULL, '2024-07-11', 'TikTok FENIX', '0.00', '10.00', '20.00', '80.00', 'Anulado'),
(42, 15, 1, 'Sin_Comprobante', NULL, 'Alameda 900, casa 2', NULL, '2024-07-11', 'TikTok FENIX', '0.00', '5.00', '10.00', '65.00', 'Anulado'),
(43, 16, 1, 'Sin_Comprobante', NULL, 'Cerro San Cristobal 48, casa 9i', NULL, '2024-07-11', 'TikTok FENIX', '0.00', '10.50', '10.50', '76.00', 'Anulado'),
(44, 12, 1, 'Ticket', NULL, 'Cerro San Cristobal 98, casa 4a', NULL, '2024-07-11', 'TikTok FENIX', '0.00', '10.00', '20.00', '85.00', 'Anulado'),
(45, 16, 1, 'Sin_Comprobante', NULL, 'Cerro San Cristobal 98, cas 1p', NULL, '2024-07-12', 'TikTok LYON', '0.00', '2.00', '1.80', '28.80', 'Aceptado'),
(46, 16, 1, 'Ticket', NULL, 'Cerro San Cristobal 76, casa 66i', NULL, '2024-07-12', 'TikTok STORELYON', '0.00', '20.00', '0.00', '35.00', 'Anulado'),
(47, 12, 1, 'Sin_Comprobante', NULL, 'prueba', NULL, '2024-07-13', 'Facebook JT', '0.00', '10.00', '12.00', '77.00', 'Anulado'),
(48, 12, 1, 'Ticket', NULL, 'Av. Trapiche Mz. H LT', NULL, '2024-07-25', 'Facebook FENIX', '0.00', '10.00', '20.00', '115.00', 'Aceptado'),
(49, 8, 1, 'Ticket', NULL, 'Av. Trapiche Mz. H Lt. 01', NULL, '2024-07-26', 'Facebook FENIX', '0.00', '50.00', '40.00', '160.00', 'Aceptado'),
(50, 13, 1, 'Ticket', NULL, 'Av. Trapiche Mz. H Lt. 01', NULL, '2024-07-26', 'Facebook FENIX', '0.00', '8.00', '20.00', '58.00', 'Aceptado'),
(51, 12, 1, 'Ticket', NULL, 'Av. Trapiche Mz. H Lt. 01', NULL, '2024-07-26', 'Facebook LYON', '0.00', '10.00', '5.00', '110.00', 'Aceptado'),
(52, 12, 1, 'Ticket', NULL, 'Av. Los Olivos', NULL, '2024-07-26', 'TikTok JT', '0.00', '5.00', '4.00', '94.00', 'Aceptado'),
(53, 8, 1, 'Ticket', NULL, '', NULL, '2024-07-26', 'Facebook JT', '0.00', '0.00', '0.00', '90.00', 'Anulado'),
(54, 8, 1, 'Ticket', NULL, '', NULL, '2024-07-26', 'Facebook JT', '0.00', '7.00', '0.00', '124.70', 'Anulado'),
(55, 11, 1, 'Ticket', '', 'Av. Sarvia', 'Hola Como estas', '2024-07-26', 'Facebook LYON', '0.00', '5.00', '5.00', '50.00', 'Aceptado'),
(56, 13, 1, 'Ticket', 'Eva', 'Av. Adria', 'Todo estara bien', '2024-07-26', 'Facebook FENIX', '0.00', '5.00', '10.00', '15.00', 'Aceptado'),
(57, 15, 1, 'Ticket', 'otros', 'Av. Girasoles', 'Personas desaparecidas.', '2024-07-26', 'TikTok JT', '0.00', '15.00', '10.00', '130.00', 'Anulado'),
(58, 12, 1, 'Ticket', 'otros', 'av. oleaje', 'pedro es fuerte', '2024-07-26', 'TikTok FENIX', '0.00', '5.00', '10.00', '70.00', 'Anulado'),
(59, 12, 1, 'Ticket', 'Shalom', 'Av. Huaman', 'Rosa Maria', '2024-07-26', 'TikTok JT', '0.00', '5.00', '10.00', '35.00', 'Aceptado'),
(60, 15, 1, 'Ticket', 'Olva Courier', 'Av. Universitaria 1045', 'El producto no tiene devolucion', '2024-07-26', 'Facebook JT', '0.00', '5.00', '5.00', '95.00', 'Aceptado'),
(61, 13, 1, 'Ticket', 'Shalom', 'Av. Jhony', 'Solo una venta', '2024-07-26', 'TikTok JT', '0.00', '5.00', '5.00', '30.00', 'Aceptado'),
(62, 13, 1, 'Ticket', 'Uber', 'Av. Universitaria', 'Personas de poder', '2024-07-27', 'TikTok LYON', '0.00', '5.00', '10.00', '30.00', 'Aceptado'),
(63, 16, 1, 'Ticket', 'Shalom', 'Av. Las Uvas', 'Precios altos', '2024-07-27', 'Facebook FENIX', '0.00', '5.00', '5.00', '50.00', 'Aceptado'),
(64, 12, 1, 'Ticket', 'Shalom', 'Av. franquito', 'Upn no ayuda', '2024-07-28', 'Facebook JT', '0.00', '5.00', '5.00', '50.00', 'Aceptado'),
(65, 12, 1, 'Ticket', 'Shalom', 'Av. franquito', 'Upn no ayuda', '2024-07-28', 'TikTok JT', '0.00', '5.00', '5.00', '25.00', 'Aceptado'),
(66, 15, 1, 'Ticket', 'Shalom', 'Av. Upn', 'Personas', '2024-07-28', 'TikTok FENIX', '0.00', '10.00', '5.00', '20.00', 'Aceptado'),
(67, 13, 1, '', '', 'Av. Uni', 'Franquito', '2024-07-28', 'Facebook JT', '0.00', '5.00', '5.00', '30.00', 'Aceptado'),
(68, 12, 1, '', 'Shalom', 'Av. Razuri', 'Peres', '2024-07-28', 'Facebook JT', '0.00', '5.00', '5.00', '30.00', 'Aceptado'),
(69, 13, 1, '', 'Shalom', 'Av. Manzana', 'Peruano', '2024-07-28', 'TikTok MYSTORE', '0.00', '5.00', '5.00', '15.00', 'Aceptado'),
(70, 13, 1, 'Ticket', '', 'Av. Rios', 'Emancipacion', '2024-07-28', 'TikTok MYSTORE', '0.00', '5.00', '5.00', '30.00', 'Aceptado'),
(71, 16, 1, '', '', 'Av. Teresa', 'upn', '2024-07-28', 'TikTok LYON', '0.00', '5.00', '5.00', '10.00', 'Aceptado'),
(72, 13, 1, '', 'Shalom', 'Av. Rios', 'Emancipacion', '2024-07-28', 'TikTok FENIX', '0.00', '5.00', '5.00', '60.00', 'Aceptado'),
(73, 8, 1, '', '', 'Av. Jhony', 'Tarapoto', '2024-07-28', 'TikTok FENIX', '0.00', '5.00', '5.00', '10.00', 'Aceptado'),
(74, 12, 1, 'Ticket', '', 'Av. Pardo', 'Peruano', '2024-07-28', 'TikTok MYSTORE', '0.00', '5.00', '10.00', '45.00', 'Aceptado'),
(75, 13, 1, 'Ticket', 'Shalom', 'Av. Tarapoto', 'Si se puede', '2024-07-28', 'TikTok FENIX', '0.00', '5.00', '5.00', '10.00', 'Aceptado'),
(76, 12, 1, 'Ticket', 'Indriver', 'Av. Japon', 'Pedro', '2024-07-28', 'Facebook JT', '0.00', '5.00', '5.00', '40.00', 'Aceptado'),
(77, 13, 1, 'Ticket', 'activar', 'Av. Trapiche', 'Peruana', '2024-07-28', 'TikTok FENIX', '0.00', '5.00', '5.00', '30.00', 'Aceptado'),
(78, 13, 1, 'Ticket', 'activar', 'Av. Marquito', 'Upene', '2024-07-29', 'Facebook FENIX', '0.00', '5.00', '5.00', '50.00', 'Aceptado'),
(79, 8, 1, 'Ticket', 'Olva Courier', 'Pedro', 'ojala se pueda', '2024-07-29', 'TikTok JT', '0.00', '5.00', '5.00', '20.00', 'Aceptado'),
(80, 13, 1, 'Ticket', 'activar', 'Av. Pierola', 'se puede', '2024-07-29', 'TikTok MYSTORE', '0.00', '10.00', '5.00', '15.00', 'Aceptado');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ajuste_inventario`
--
ALTER TABLE `ajuste_inventario`
  ADD PRIMARY KEY (`id_ajuste`),
  ADD KEY `ajuste_inventario_ibfk_1` (`id_articulo`);

--
-- Indices de la tabla `articulo`
--
ALTER TABLE `articulo`
  ADD PRIMARY KEY (`idarticulo`),
  ADD UNIQUE KEY `nombre_UNIQUE` (`nombre`),
  ADD KEY `fk_articulo_categoria_idx` (`idcategoria`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idcategoria`),
  ADD UNIQUE KEY `nombre_UNIQUE` (`nombre`);

--
-- Indices de la tabla `detalle_ingreso`
--
ALTER TABLE `detalle_ingreso`
  ADD PRIMARY KEY (`iddetalle_ingreso`),
  ADD KEY `fk_detalle_ingreso_idx` (`idingreso`),
  ADD KEY `fk_detalle_articulo_idx` (`idarticulo`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`iddetalle_venta`),
  ADD KEY `fk_detalle_venta_venta_idx` (`idventa`),
  ADD KEY `fk_detalle_venta_articulo_idx` (`idarticulo`);

--
-- Indices de la tabla `ingreso`
--
ALTER TABLE `ingreso`
  ADD PRIMARY KEY (`idingreso`),
  ADD KEY `fk_ingreso_persona_idx` (`idproveedor`),
  ADD KEY `fk_ingreso_usuario_idx` (`idusuario`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`idpago`),
  ADD KEY `idventa` (`idventa`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`idpermiso`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`idpersona`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `login_UNIQUE` (`login`);

--
-- Indices de la tabla `usuario_permiso`
--
ALTER TABLE `usuario_permiso`
  ADD PRIMARY KEY (`idusuario_permiso`),
  ADD KEY `fk_u_permiso_usuario_idx` (`idusuario`),
  ADD KEY `fk_usuario_permiso_idx` (`idpermiso`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`idventa`),
  ADD KEY `fk_venta_persona_idx` (`idcliente`),
  ADD KEY `fk_venta_usuario_idx` (`idusuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ajuste_inventario`
--
ALTER TABLE `ajuste_inventario`
  MODIFY `id_ajuste` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `articulo`
--
ALTER TABLE `articulo`
  MODIFY `idarticulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `detalle_ingreso`
--
ALTER TABLE `detalle_ingreso`
  MODIFY `iddetalle_ingreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `iddetalle_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT de la tabla `ingreso`
--
ALTER TABLE `ingreso`
  MODIFY `idingreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `idpago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `idpermiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `idpersona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario_permiso`
--
ALTER TABLE `usuario_permiso`
  MODIFY `idusuario_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idventa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ajuste_inventario`
--
ALTER TABLE `ajuste_inventario`
  ADD CONSTRAINT `ajuste_inventario_ibfk_1` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`idarticulo`);

--
-- Filtros para la tabla `articulo`
--
ALTER TABLE `articulo`
  ADD CONSTRAINT `fk_articulo_categoria` FOREIGN KEY (`idcategoria`) REFERENCES `categoria` (`idcategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_ingreso`
--
ALTER TABLE `detalle_ingreso`
  ADD CONSTRAINT `fk_detalle_articulo` FOREIGN KEY (`idarticulo`) REFERENCES `articulo` (`idarticulo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalle_ingreso` FOREIGN KEY (`idingreso`) REFERENCES `ingreso` (`idingreso`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `fk_detalle_venta_articulo` FOREIGN KEY (`idarticulo`) REFERENCES `articulo` (`idarticulo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalle_venta_venta` FOREIGN KEY (`idventa`) REFERENCES `venta` (`idventa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ingreso`
--
ALTER TABLE `ingreso`
  ADD CONSTRAINT `fk_ingreso_persona` FOREIGN KEY (`idproveedor`) REFERENCES `persona` (`idpersona`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ingreso_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`idventa`) REFERENCES `venta` (`idventa`);

--
-- Filtros para la tabla `usuario_permiso`
--
ALTER TABLE `usuario_permiso`
  ADD CONSTRAINT `fk_u_permiso_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuario_permiso` FOREIGN KEY (`idpermiso`) REFERENCES `permiso` (`idpermiso`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `fk_venta_persona` FOREIGN KEY (`idcliente`) REFERENCES `persona` (`idpersona`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_venta_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
