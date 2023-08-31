-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para pwa_ecommerce
CREATE DATABASE IF NOT EXISTS `pwa_ecommerce` ;
USE `pwa_ecommerce`;

-- Volcando estructura para tabla pwa_ecommerce.banners
CREATE TABLE IF NOT EXISTS `banners` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.banners: ~0 rows (aproximadamente)
DELETE FROM `banners`;

-- Volcando estructura para tabla pwa_ecommerce.carritocompras
CREATE TABLE IF NOT EXISTS `carritocompras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `carritocompras_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.carritocompras: ~0 rows (aproximadamente)
DELETE FROM `carritocompras`;

-- Volcando estructura para tabla pwa_ecommerce.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.categorias: ~0 rows (aproximadamente)
DELETE FROM `categorias`;

-- Volcando estructura para tabla pwa_ecommerce.ciudades
CREATE TABLE IF NOT EXISTS `ciudades` (
  `id` int NOT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `provincias_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ciudades_provincias_idx` (`provincias_id`),
  CONSTRAINT `fk_ciudades_provincias` FOREIGN KEY (`provincias_id`) REFERENCES `provincias` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.ciudades: ~0 rows (aproximadamente)
DELETE FROM `ciudades`;

-- Volcando estructura para tabla pwa_ecommerce.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `documento` varchar(45) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `tipo_documento_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `fk_clientes_tipo_documento1_idx` (`tipo_documento_id`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_clientes_tipo_documento1` FOREIGN KEY (`tipo_documento_id`) REFERENCES `tipo_documento` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.clientes: ~0 rows (aproximadamente)
DELETE FROM `clientes`;

-- Volcando estructura para tabla pwa_ecommerce.comentariosreseñas
CREATE TABLE IF NOT EXISTS `comentariosreseñas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int DEFAULT NULL,
  `producto_id` int DEFAULT NULL,
  `comentario` text,
  `puntuacion` int DEFAULT NULL,
  `fecha_comentario` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `comentariosreseñas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `comentariosreseñas_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.comentariosreseñas: ~0 rows (aproximadamente)
DELETE FROM `comentariosreseñas`;

-- Volcando estructura para tabla pwa_ecommerce.datosfacturacion
CREATE TABLE IF NOT EXISTS `datosfacturacion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `ruc_cedula` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `tipo_documento_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `fk_datosfacturacion_tipo_documento1_idx` (`tipo_documento_id`),
  CONSTRAINT `datosfacturacion_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `fk_datosfacturacion_tipo_documento1` FOREIGN KEY (`tipo_documento_id`) REFERENCES `tipo_documento` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.datosfacturacion: ~0 rows (aproximadamente)
DELETE FROM `datosfacturacion`;

-- Volcando estructura para tabla pwa_ecommerce.detallescarrito
CREATE TABLE IF NOT EXISTS `detallescarrito` (
  `id` int NOT NULL AUTO_INCREMENT,
  `carrito_id` int DEFAULT NULL,
  `variante_id` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carrito_id` (`carrito_id`),
  KEY `variante_id` (`variante_id`),
  CONSTRAINT `detallescarrito_ibfk_1` FOREIGN KEY (`carrito_id`) REFERENCES `carritocompras` (`id`),
  CONSTRAINT `detallescarrito_ibfk_2` FOREIGN KEY (`variante_id`) REFERENCES `variantes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.detallescarrito: ~0 rows (aproximadamente)
DELETE FROM `detallescarrito`;

-- Volcando estructura para tabla pwa_ecommerce.detallesorden
CREATE TABLE IF NOT EXISTS `detallesorden` (
  `id` int NOT NULL AUTO_INCREMENT,
  `orden_id` int DEFAULT NULL,
  `variante_id` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orden_id` (`orden_id`),
  KEY `variante_id` (`variante_id`),
  CONSTRAINT `detallesorden_ibfk_1` FOREIGN KEY (`orden_id`) REFERENCES `ordenes` (`id`),
  CONSTRAINT `detallesorden_ibfk_2` FOREIGN KEY (`variante_id`) REFERENCES `variantes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.detallesorden: ~0 rows (aproximadamente)
DELETE FROM `detallesorden`;

-- Volcando estructura para tabla pwa_ecommerce.direccionesentrega
CREATE TABLE IF NOT EXISTS `direccionesentrega` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int DEFAULT NULL,
  `cedula` varchar(45) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `ciudades_id` int NOT NULL,
  `comentarios` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `fk_direccionesentrega_ciudades1_idx` (`ciudades_id`),
  CONSTRAINT `direccionesentrega_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `fk_direccionesentrega_ciudades1` FOREIGN KEY (`ciudades_id`) REFERENCES `ciudades` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.direccionesentrega: ~0 rows (aproximadamente)
DELETE FROM `direccionesentrega`;

-- Volcando estructura para tabla pwa_ecommerce.empresa
CREATE TABLE IF NOT EXISTS `empresa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ruc` varchar(45) DEFAULT NULL,
  `razon_social` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `obligado_contabilidad` tinyint(1) DEFAULT NULL,
  `regimen` varchar(45) DEFAULT NULL,
  `logo` varchar(45) DEFAULT NULL,
  `ambiente` varchar(45) DEFAULT NULL,
  `establecimiento` varchar(45) DEFAULT NULL,
  `punto_emision` varchar(45) DEFAULT NULL,
  `secuencial` varchar(45) DEFAULT NULL,
  `archivop12` varchar(50) DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `clave` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.empresa: ~0 rows (aproximadamente)
DELETE FROM `empresa`;

-- Volcando estructura para tabla pwa_ecommerce.estado_orden
CREATE TABLE IF NOT EXISTS `estado_orden` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estado` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.estado_orden: ~0 rows (aproximadamente)
DELETE FROM `estado_orden`;

-- Volcando estructura para tabla pwa_ecommerce.facturas_electronicas
CREATE TABLE IF NOT EXISTS `facturas_electronicas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `factura` varchar(45) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `numero_autorizacion` varchar(100) DEFAULT NULL,
  `clave_acceso` varchar(100) DEFAULT NULL,
  `fecha` varchar(45) DEFAULT NULL,
  `descargada` varchar(45) DEFAULT NULL,
  `ordenes_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_facturas_ordenes1_idx` (`ordenes_id`),
  CONSTRAINT `fk_facturas_ordenes1` FOREIGN KEY (`ordenes_id`) REFERENCES `ordenes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.facturas_electronicas: ~0 rows (aproximadamente)
DELETE FROM `facturas_electronicas`;

-- Volcando estructura para tabla pwa_ecommerce.formas_pago
CREATE TABLE IF NOT EXISTS `formas_pago` (
  `id` int NOT NULL,
  `pago` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.formas_pago: ~0 rows (aproximadamente)
DELETE FROM `formas_pago`;

-- Volcando estructura para tabla pwa_ecommerce.info_adicional
CREATE TABLE IF NOT EXISTS `info_adicional` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `ordenes_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_info_adicional_ordenes1_idx` (`ordenes_id`),
  CONSTRAINT `fk_info_adicional_ordenes1` FOREIGN KEY (`ordenes_id`) REFERENCES `ordenes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.info_adicional: ~0 rows (aproximadamente)
DELETE FROM `info_adicional`;

-- Volcando estructura para tabla pwa_ecommerce.marcas
CREATE TABLE IF NOT EXISTS `marcas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.marcas: ~0 rows (aproximadamente)
DELETE FROM `marcas`;

-- Volcando estructura para tabla pwa_ecommerce.notificaciones
CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `mensaje` text,
  `leida` tinyint(1) DEFAULT NULL,
  `fecha_envio` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.notificaciones: ~0 rows (aproximadamente)
DELETE FROM `notificaciones`;

-- Volcando estructura para tabla pwa_ecommerce.ofertasespeciales
CREATE TABLE IF NOT EXISTS `ofertasespeciales` (
  `id` int NOT NULL AUTO_INCREMENT,
  `producto_id` int DEFAULT NULL,
  `descuento` decimal(5,2) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `ofertasespeciales_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.ofertasespeciales: ~0 rows (aproximadamente)
DELETE FROM `ofertasespeciales`;

-- Volcando estructura para tabla pwa_ecommerce.ordenes
CREATE TABLE IF NOT EXISTS `ordenes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `estado_orden_id` int NOT NULL,
  `datosfacturacion_id` int NOT NULL,
  `direccionesentrega_id` int NOT NULL,
  `subtotal12` decimal(12,2) DEFAULT NULL,
  `subtotaliva0` decimal(12,2) DEFAULT NULL,
  `subtotal_sin_impuestos` decimal(12,2) DEFAULT NULL,
  `descuento` decimal(12,2) DEFAULT NULL,
  `iva` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `fk_ordenes_estado_orden1_idx` (`estado_orden_id`),
  KEY `fk_ordenes_datosfacturacion1_idx` (`datosfacturacion_id`),
  KEY `fk_ordenes_direccionesentrega1_idx` (`direccionesentrega_id`),
  CONSTRAINT `fk_ordenes_datosfacturacion1` FOREIGN KEY (`datosfacturacion_id`) REFERENCES `datosfacturacion` (`id`),
  CONSTRAINT `fk_ordenes_direccionesentrega1` FOREIGN KEY (`direccionesentrega_id`) REFERENCES `direccionesentrega` (`id`),
  CONSTRAINT `fk_ordenes_estado_orden1` FOREIGN KEY (`estado_orden_id`) REFERENCES `estado_orden` (`id`),
  CONSTRAINT `ordenes_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.ordenes: ~0 rows (aproximadamente)
DELETE FROM `ordenes`;

-- Volcando estructura para tabla pwa_ecommerce.pagos_orden
CREATE TABLE IF NOT EXISTS `pagos_orden` (
  `id` int NOT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `plazo` varchar(45) DEFAULT NULL,
  `tiempo` varchar(45) DEFAULT NULL,
  `formas_pago_id` int NOT NULL,
  `ordenes_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pagos_orden_formas_pago1_idx` (`formas_pago_id`),
  KEY `fk_pagos_orden_ordenes1_idx` (`ordenes_id`),
  CONSTRAINT `fk_pagos_orden_formas_pago1` FOREIGN KEY (`formas_pago_id`) REFERENCES `formas_pago` (`id`),
  CONSTRAINT `fk_pagos_orden_ordenes1` FOREIGN KEY (`ordenes_id`) REFERENCES `ordenes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.pagos_orden: ~0 rows (aproximadamente)
DELETE FROM `pagos_orden`;

-- Volcando estructura para tabla pwa_ecommerce.productos
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categoria_id` int DEFAULT NULL,
  `marca_id` int DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `precio` decimal(10,2) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `caracteristicas` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `marca_id` (`marca_id`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.productos: ~0 rows (aproximadamente)
DELETE FROM `productos`;

-- Volcando estructura para tabla pwa_ecommerce.productosdeseados
CREATE TABLE IF NOT EXISTS `productosdeseados` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int DEFAULT NULL,
  `producto_id` int DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `productosdeseados_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `productosdeseados_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.productosdeseados: ~0 rows (aproximadamente)
DELETE FROM `productosdeseados`;

-- Volcando estructura para tabla pwa_ecommerce.provincias
CREATE TABLE IF NOT EXISTS `provincias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `provincia` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.provincias: ~0 rows (aproximadamente)
DELETE FROM `provincias`;

-- Volcando estructura para tabla pwa_ecommerce.publicidad
CREATE TABLE IF NOT EXISTS `publicidad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.publicidad: ~0 rows (aproximadamente)
DELETE FROM `publicidad`;

-- Volcando estructura para tabla pwa_ecommerce.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.roles: ~0 rows (aproximadamente)
DELETE FROM `roles`;

-- Volcando estructura para tabla pwa_ecommerce.sliders
CREATE TABLE IF NOT EXISTS `sliders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.sliders: ~0 rows (aproximadamente)
DELETE FROM `sliders`;

-- Volcando estructura para tabla pwa_ecommerce.tipo_documento
CREATE TABLE IF NOT EXISTS `tipo_documento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `valor` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.tipo_documento: ~0 rows (aproximadamente)
DELETE FROM `tipo_documento`;

-- Volcando estructura para tabla pwa_ecommerce.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `contrasena` varchar(100) NOT NULL,
  `rol_id` int DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rol_id` (`rol_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.usuarios: ~0 rows (aproximadamente)
DELETE FROM `usuarios`;

-- Volcando estructura para tabla pwa_ecommerce.variantes
CREATE TABLE IF NOT EXISTS `variantes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `producto_id` int DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `talla` varchar(50) DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `variantes_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ;

-- Volcando datos para la tabla pwa_ecommerce.variantes: ~0 rows (aproximadamente)
DELETE FROM `variantes`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
