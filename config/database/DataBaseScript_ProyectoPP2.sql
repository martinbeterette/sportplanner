-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-09-2024 a las 23:53:40
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_pp2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_perfil_modulo`
--

CREATE TABLE `asignacion_perfil_modulo` (
  `id_asignacion_perfil_modulo` int(11) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_perfil` int(11) DEFAULT NULL,
  `rela_modulo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignacion_perfil_modulo`
--

INSERT INTO `asignacion_perfil_modulo` (`id_asignacion_perfil_modulo`, `estado`, `rela_perfil`, `rela_modulo`) VALUES
(6, 1, 3, 1),
(7, 1, 3, 3),
(10, 1, 1, 1),
(11, 1, 1, 2),
(12, 1, 1, 3),
(13, 1, 2, 1),
(14, 1, 2, 2),
(15, 1, 2, 3),
(16, 1, 2, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_persona_complejo`
--

CREATE TABLE `asignacion_persona_complejo` (
  `id_asignacion_persona_complejo` int(11) NOT NULL,
  `fecha_alta` date DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_persona` int(11) DEFAULT NULL,
  `rela_complejo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignacion_persona_complejo`
--

INSERT INTO `asignacion_persona_complejo` (`id_asignacion_persona_complejo`, `fecha_alta`, `estado`, `rela_persona`, `rela_complejo`) VALUES
(1, '2024-07-01', 1, 18, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_persona_domicilio`
--

CREATE TABLE `asignacion_persona_domicilio` (
  `id_asignacion_persona_domicilio` int(11) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_persona` int(11) DEFAULT NULL,
  `rela_barrio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_sucursal_domicilio`
--

CREATE TABLE `asignacion_sucursal_domicilio` (
  `id_asignacion_sucursal_domicilio` int(11) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_sucursal` int(11) DEFAULT NULL,
  `rela_barrio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion_tarifa_servicio`
--

CREATE TABLE `asignacion_tarifa_servicio` (
  `id_asignacion_tarifa_servicio` int(11) NOT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_tarifa` int(11) DEFAULT NULL,
  `rela_servicio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asignacion_tarifa_servicio`
--

INSERT INTO `asignacion_tarifa_servicio` (`id_asignacion_tarifa_servicio`, `estado`, `rela_tarifa`, `rela_servicio`) VALUES
(1, 1, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `barrio`
--

CREATE TABLE `barrio` (
  `id_barrio` int(11) NOT NULL,
  `descripcion_barrio` varchar(50) DEFAULT NULL,
  `rela_localidad` int(11) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `barrio`
--

INSERT INTO `barrio` (`id_barrio`, `descripcion_barrio`, `rela_localidad`, `estado`) VALUES
(1, 'republica argentina', 1, 1),
(2, '7 de mayo', 1, 1),
(3, 'lote 111', 1, 1),
(4, 'la paz', 1, 1),
(5, 'el porvenir', 1, 1),
(6, 'el palomar', 1, 1),
(7, 'la orquidia', 1, 1),
(8, '25 de mayo', 1, 1),
(9, 'don bosco', 1, 1),
(10, 'tatane', 1, 1),
(11, 'parque industrial', 1, 1),
(12, 'fontana', 1, 1),
(13, 'barrio la pilar', 1, 1),
(14, 'san francisco', 1, 1),
(15, 'san pedro', 1, 1),
(16, 'san miguel', 1, 1),
(17, 'independencia', 1, 1),
(18, 'obrero', 1, 1),
(19, 'virgen del pilar', 1, 1),
(20, 'mariano moreno', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `complejo`
--

CREATE TABLE `complejo` (
  `id_complejo` int(11) NOT NULL,
  `descripcion_complejo` varchar(50) DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `complejo`
--

INSERT INTO `complejo` (`id_complejo`, `descripcion_complejo`, `fecha_alta`, `estado`) VALUES
(1, 'Complejo YPF', '2024-06-29', 1),
(2, 'Complejo LeClub', '2024-06-29', 1),
(3, 'Complejo Futbar', '2024-06-29', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto`
--

CREATE TABLE `contacto` (
  `id_contacto` int(11) NOT NULL,
  `descripcion_contacto` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_tipo_contacto` int(11) DEFAULT NULL,
  `rela_persona` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contacto`
--

INSERT INTO `contacto` (`id_contacto`, `descripcion_contacto`, `estado`, `rela_tipo_contacto`, `rela_persona`) VALUES
(12, 'Rinngley@gmail.com', 1, 1, 13),
(13, 'Maurolezcano111@gmail.com', 1, 1, 15),
(15, 'Maurinasd@gmail.com', 1, 1, 17),
(16, 'maurinprueba@gmail.com', 1, 1, 18),
(17, '12321414', 1, 2, 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control`
--

CREATE TABLE `control` (
  `id_control` int(11) NOT NULL,
  `descripcion_control` varchar(50) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_estado_control` int(11) DEFAULT NULL,
  `rela_reserva` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deporte`
--

CREATE TABLE `deporte` (
  `id_deporte` int(11) NOT NULL,
  `descripcion_deporte` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `deporte`
--

INSERT INTO `deporte` (`id_deporte`, `descripcion_deporte`, `estado`) VALUES
(1, 'Futbol', 1),
(2, 'Volley', 1),
(3, 'Basquet', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE `documento` (
  `id_documento` int(11) NOT NULL,
  `descripcion_documento` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_tipo_documento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documento`
--

INSERT INTO `documento` (`id_documento`, `descripcion_documento`, `estado`, `rela_tipo_documento`) VALUES
(13, '15.253.244', 1, 1),
(14, '15.253.2441', 1, 1),
(15, 'a', 1, 1),
(17, '24454253', 1, 1),
(18, '42757241', 1, 1),
(19, 'documento-NO-USUARIO', 1, 1),
(20, NULL, 1, 1),
(21, '42757241', 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id_empleado` int(11) NOT NULL,
  `empleado_descripcion` varchar(50) DEFAULT NULL,
  `empleado_cargo` varchar(50) DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_persona` int(11) DEFAULT NULL,
  `rela_sucursal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id_empleado`, `empleado_descripcion`, `empleado_cargo`, `fecha_alta`, `estado`, `rela_persona`, `rela_sucursal`) VALUES
(2, NULL, NULL, '2024-09-10', 1, 21, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `id_equipo` int(11) NOT NULL,
  `descripcion_equipo` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_evento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_control`
--

CREATE TABLE `estado_control` (
  `id_estado_control` int(11) NOT NULL,
  `descripcion_estado_control` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_reserva`
--

CREATE TABLE `estado_reserva` (
  `id_estado_reserva` int(11) NOT NULL,
  `descripcion_estado_reserva` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_zona`
--

CREATE TABLE `estado_zona` (
  `id_estado_zona` int(11) NOT NULL,
  `descripcion_estado_zona` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_zona`
--

INSERT INTO `estado_zona` (`id_estado_zona`, `descripcion_estado_zona`, `estado`) VALUES
(1, 'Buen estado', 1),
(2, 'Regular', 1),
(3, 'Mal estado', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

CREATE TABLE `evento` (
  `id_evento` int(11) NOT NULL,
  `descripcion_evento` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_servicio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formato_deporte`
--

CREATE TABLE `formato_deporte` (
  `id_formato_deporte` int(11) NOT NULL,
  `descripcion_formato_deporte` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_deporte` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `formato_deporte`
--

INSERT INTO `formato_deporte` (`id_formato_deporte`, `descripcion_formato_deporte`, `estado`, `rela_deporte`) VALUES
(1, 'Futbol 5', 1, 1),
(2, 'Futbol 7', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `id_horario` int(11) NOT NULL,
  `horario_inicio` time DEFAULT NULL,
  `horario_fin` time DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horario`
--

INSERT INTO `horario` (`id_horario`, `horario_inicio`, `horario_fin`, `fecha_alta`, `estado`) VALUES
(1, '00:00:00', '01:00:00', '2024-06-29', 1),
(2, '01:00:00', '02:00:00', '2024-06-29', 1),
(3, '02:00:00', '03:00:00', '2024-06-29', 1),
(4, '03:00:00', '04:00:00', '2024-06-29', 1),
(5, '04:00:00', '05:00:00', '2024-06-29', 1),
(6, '05:00:00', '06:00:00', '2024-06-29', 1),
(7, '06:00:00', '07:00:00', '2024-06-29', 1),
(8, '07:00:00', '08:00:00', '2024-06-29', 1),
(9, '08:00:00', '09:00:00', '2024-06-29', 1),
(10, '09:00:00', '10:00:00', '2024-06-29', 1),
(11, '10:00:00', '11:00:00', '2024-06-29', 1),
(12, '11:00:00', '12:00:00', '2024-06-29', 1),
(13, '12:00:00', '13:00:00', '2024-06-29', 1),
(14, '13:00:00', '14:00:00', '2024-06-29', 1),
(15, '14:00:00', '15:00:00', '2024-06-29', 1),
(16, '15:00:00', '16:00:00', '2024-06-29', 1),
(17, '16:00:00', '17:00:00', '2024-06-29', 1),
(18, '17:00:00', '18:00:00', '2024-06-29', 1),
(19, '18:00:00', '19:00:00', '2024-06-29', 1),
(20, '19:00:00', '20:00:00', '2024-06-29', 1),
(21, '20:00:00', '21:00:00', '2024-06-29', 1),
(22, '21:00:00', '22:00:00', '2024-06-29', 1),
(23, '22:00:00', '23:00:00', '2024-06-29', 1),
(24, '23:00:00', '00:00:00', '2024-06-29', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localidad`
--

CREATE TABLE `localidad` (
  `id_localidad` int(11) NOT NULL,
  `descripcion_localidad` varchar(50) DEFAULT NULL,
  `rela_provincia` int(11) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `localidad`
--

INSERT INTO `localidad` (`id_localidad`, `descripcion_localidad`, `rela_provincia`, `estado`) VALUES
(1, 'Formosa', 1, 1),
(2, 'Pirané', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcador_asistencia`
--

CREATE TABLE `marcador_asistencia` (
  `id_marcador_asistencia` int(11) NOT NULL,
  `descripcion_marcador_asistencia` varchar(50) DEFAULT NULL,
  `hora_alta` time DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `marcador` enum('entrada','salida') DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_empleado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `membresia`
--

CREATE TABLE `membresia` (
  `id_membresia` int(11) NOT NULL,
  `beneficio_membresia` varchar(20) DEFAULT NULL,
  `descripcion_membresia` varchar(100) DEFAULT NULL,
  `precio_membresia` float DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `membresia`
--

INSERT INTO `membresia` (`id_membresia`, `beneficio_membresia`, `descripcion_membresia`, `precio_membresia`, `estado`) VALUES
(1, '20', 'Descuento en Reservas', NULL, 1),
(2, 'Gratis', 'Reservas de forma gratuita', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `id_modulo` int(11) NOT NULL,
  `descripcion_modulo` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`id_modulo`, `descripcion_modulo`, `estado`) VALUES
(1, 'inicio', 1),
(2, 'personas', 1),
(3, 'Domicilios', 1),
(4, 'zonas', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `id_perfil` int(11) NOT NULL,
  `descripcion_perfil` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`id_perfil`, `descripcion_perfil`, `estado`) VALUES
(1, 'Basico', 1),
(2, 'administrador', 1),
(3, 'Personal Administrativo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_persona` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `cuil` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_documento` int(11) DEFAULT NULL,
  `rela_sexo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `nombre`, `apellido`, `cuil`, `fecha_nacimiento`, `fecha_alta`, `estado`, `rela_documento`, `rela_sexo`) VALUES
(12, 'Nombre de prueba', 'Apellido de Prueba', NULL, NULL, NULL, 1, 13, 1),
(13, 'edgar', 'coppa', NULL, NULL, NULL, 1, 14, 1),
(15, 'martin', 'coppa', NULL, NULL, NULL, 1, 15, NULL),
(17, 'martin', 'coppa', NULL, NULL, NULL, 1, 17, 2),
(18, 'Roberto', 'Pelaez', NULL, '2000-07-05', '2024-07-01', 1, 18, 1),
(19, 'nombre-NO-USUARIO', 'apellido-NO-USUARIO', '3135151', '2000-08-05', NULL, 1, 19, 1),
(21, 'bob esponja', 'Repetida', NULL, '2024-09-18', '2024-09-10', 1, 21, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincia`
--

CREATE TABLE `provincia` (
  `id_provincia` int(11) NOT NULL,
  `descripcion_provincia` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `provincia`
--

INSERT INTO `provincia` (`id_provincia`, `descripcion_provincia`, `estado`) VALUES
(1, 'Formosa', 1),
(2, 'Buenos Aires', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `descripcion_reserva` varchar(50) DEFAULT NULL,
  `fecha_reserva` date DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_estado_reserva` int(11) DEFAULT NULL,
  `rela_persona` int(11) DEFAULT NULL,
  `rela_zona` int(11) DEFAULT NULL,
  `rela_horario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`id_reserva`, `descripcion_reserva`, `fecha_reserva`, `fecha_alta`, `estado`, `rela_estado_reserva`, `rela_persona`, `rela_zona`, `rela_horario`) VALUES
(1, NULL, '2024-06-29', '2024-06-29', 1, NULL, 17, 3, 8),
(3, NULL, '2024-06-29', '2024-06-30', 1, NULL, 17, 3, 1),
(4, NULL, '2024-06-29', '2024-06-30', 1, NULL, 17, 3, 9),
(5, NULL, '2024-06-29', '2024-06-30', 1, NULL, 17, 4, 24),
(6, NULL, '2024-06-29', '2024-07-01', 1, NULL, 17, 3, 24),
(9, NULL, '2024-06-29', '2024-07-01', 1, NULL, 17, 3, 2),
(10, NULL, '2024-07-01', '2024-07-01', 1, NULL, 17, 3, 1),
(11, NULL, '2024-06-29', '2024-07-01', 1, NULL, 13, 3, 6),
(12, NULL, '2024-06-29', '2024-07-01', 1, NULL, 17, 3, 18),
(13, NULL, '2024-07-05', '2024-07-01', 1, NULL, 17, 6, 19),
(14, NULL, '2024-07-05', '2024-07-01', 1, NULL, 13, 6, 24),
(15, NULL, '2024-07-10', '2024-07-02', 1, NULL, 13, 2, 1),
(16, NULL, '2024-07-10', '2024-07-02', 1, NULL, 12, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `id_servicio` int(11) NOT NULL,
  `descripcion_servicio` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`id_servicio`, `descripcion_servicio`, `estado`) VALUES
(1, 'Cancha', 1),
(2, 'Parrillada', 1),
(3, 'Bar', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sexo`
--

CREATE TABLE `sexo` (
  `id_sexo` int(11) NOT NULL,
  `descripcion_sexo` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sexo`
--

INSERT INTO `sexo` (`id_sexo`, `descripcion_sexo`, `estado`) VALUES
(1, 'masculino', 1),
(2, 'femenino', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `socio`
--

CREATE TABLE `socio` (
  `id_socio` int(11) NOT NULL,
  `descripcion_socio` varchar(50) DEFAULT NULL,
  `rela_complejo` int(11) DEFAULT NULL,
  `rela_membresia` int(11) DEFAULT NULL,
  `fecha_afiliacion` date DEFAULT NULL,
  `fecha_expiracion` date DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_persona` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `socio`
--

INSERT INTO `socio` (`id_socio`, `descripcion_socio`, `rela_complejo`, `rela_membresia`, `fecha_afiliacion`, `fecha_expiracion`, `fecha_alta`, `estado`, `rela_persona`) VALUES
(2, NULL, 1, 1, '2024-09-10', '2024-09-30', '2024-09-10', 1, 17);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE `sucursal` (
  `id_sucursal` int(11) NOT NULL,
  `descripcion_sucursal` varchar(50) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_complejo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`id_sucursal`, `descripcion_sucursal`, `direccion`, `estado`, `rela_complejo`) VALUES
(1, 'Sucursal YPF 1', 'Avenida avellaneda 235', 1, 1),
(2, 'Sucursal YPF 2', 'Avenida Napoleon 565', 1, 1),
(3, 'Sucursal YPF 3', 'Barrio Don Bosco calle sarmiento 277', 1, 1),
(4, 'LeClub 1', 'Avenida Gutniski', 1, 2),
(5, 'LeClub 2', 'Calle Domingo sarmiento 200', 1, 2),
(6, 'Sucursal FutBar1', 'Junin 728', 1, 3),
(7, 'Sucursal FutBar2', 'Avenida Uriburu 900', 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifa`
--

CREATE TABLE `tarifa` (
  `id_tarifa` int(11) NOT NULL,
  `precio` float DEFAULT NULL,
  `horario_desde` time DEFAULT NULL,
  `horario_hasta` time DEFAULT NULL,
  `turno` enum('dia','noche') DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarifa`
--

INSERT INTO `tarifa` (`id_tarifa`, `precio`, `horario_desde`, `horario_hasta`, `turno`, `estado`) VALUES
(1, 10000, '20:00:00', '06:00:00', 'noche', 1),
(2, 8000, '06:00:00', '20:00:00', 'dia', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_contacto`
--

CREATE TABLE `tipo_contacto` (
  `id_tipo_contacto` int(11) NOT NULL,
  `descripcion_tipo_contacto` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_contacto`
--

INSERT INTO `tipo_contacto` (`id_tipo_contacto`, `descripcion_tipo_contacto`, `estado`) VALUES
(1, 'E-mail', 1),
(2, 'Telefono', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `id_tipo_documento` int(11) NOT NULL,
  `descripcion_tipo_documento` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`id_tipo_documento`, `descripcion_tipo_documento`, `estado`) VALUES
(1, 'DNI', 1),
(2, 'Libreta', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_terreno`
--

CREATE TABLE `tipo_terreno` (
  `id_tipo_terreno` int(11) NOT NULL,
  `descripcion_tipo_terreno` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_terreno`
--

INSERT INTO `tipo_terreno` (`id_tipo_terreno`, `descripcion_tipo_terreno`, `estado`) VALUES
(1, 'Cesped', 1),
(2, 'Piso', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expiry` datetime DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'no verificado',
  `rela_contacto` int(11) NOT NULL,
  `rela_perfil` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `username`, `password`, `token`, `expiry`, `estado`, `rela_contacto`, `rela_perfil`) VALUES
(8, 'rinngley', '$2y$10$sAyr1WYs6d7KzyM.9Azrguwp5W0DwiaVaDmSXTy7GeAnpSIMtHTxS', 'fb21b596674f9fde250f257c12a8c833', '2024-06-05 21:59:03', 'verificado', 12, 1),
(9, 'martincito', '$2y$10$Q/TyJqvCcA8WDEi9YXmihOoD.oBTddFEwpSSHBKORpqdxfIAbC8VC', '7eb706de3dd7aaabfb0837362d1c8016', '2024-06-05 21:59:06', 'verificado', 13, 3),
(11, 'maurinasd0001', '$2y$10$cm9TCyYPUoRNRdXxjbAcTuhk91ZvURe9RBfpt2QoeNlt0lxJK2XJO', 'b8eece7c33c2da2cb3e3edb2c50b475e', '2024-06-28 20:20:42', 'verificado', 15, 2),
(13, 'usuario canchero', '$2y$10$UuSwyMbcYO6I7Xa7hkvdlOoSY01s4AIf.GkLoNlVgiPVaqL4pKdUy', '435bdfaec7362a006ac5879ffa2a2f2f', '2024-07-04 03:01:11', 'verificado', 16, 3),
(14, 'USUARIO PRUEBA CONSULTA', 'dsadwa', 'dasd', NULL, 'roungorwgw', 17, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zona`
--

CREATE TABLE `zona` (
  `id_zona` int(11) NOT NULL,
  `descripcion_zona` varchar(50) DEFAULT NULL,
  `dimension` varchar(50) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `rela_tipo_terreno` int(11) DEFAULT NULL,
  `rela_formato_deporte` int(11) DEFAULT NULL,
  `rela_estado_zona` int(11) DEFAULT NULL,
  `rela_sucursal` int(11) DEFAULT NULL,
  `rela_servicio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `zona`
--

INSERT INTO `zona` (`id_zona`, `descripcion_zona`, `dimension`, `estado`, `rela_tipo_terreno`, `rela_formato_deporte`, `rela_estado_zona`, `rela_sucursal`, `rela_servicio`) VALUES
(1, 'Cancha 1', NULL, 1, 1, 1, 1, 1, 1),
(2, 'Cancha 2', NULL, 1, 2, 2, 3, 1, 1),
(3, 'cancha 1', NULL, 1, 1, 1, 1, 4, 1),
(4, 'cancha 2', 'Cancha 2', 1, 2, 2, 1, 5, 1),
(5, 'cancha 1', NULL, 1, 1, 2, 1, 2, 1),
(6, 'cancha 1', NULL, 1, 1, 1, 1, 6, 1),
(7, 'cancha 1', NULL, 1, 1, 1, 1, 3, 1),
(8, 'cancha2', NULL, 1, 1, 2, 1, 6, 1),
(9, 'cancha1', NULL, 1, 2, 1, 2, 7, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignacion_perfil_modulo`
--
ALTER TABLE `asignacion_perfil_modulo`
  ADD PRIMARY KEY (`id_asignacion_perfil_modulo`),
  ADD KEY `rela_perfil` (`rela_perfil`),
  ADD KEY `rela_modulo` (`rela_modulo`);

--
-- Indices de la tabla `asignacion_persona_complejo`
--
ALTER TABLE `asignacion_persona_complejo`
  ADD PRIMARY KEY (`id_asignacion_persona_complejo`),
  ADD KEY `rela_persona` (`rela_persona`),
  ADD KEY `rela_complejo` (`rela_complejo`);

--
-- Indices de la tabla `asignacion_persona_domicilio`
--
ALTER TABLE `asignacion_persona_domicilio`
  ADD PRIMARY KEY (`id_asignacion_persona_domicilio`),
  ADD KEY `rela_persona` (`rela_persona`),
  ADD KEY `rela_barrio` (`rela_barrio`);

--
-- Indices de la tabla `asignacion_sucursal_domicilio`
--
ALTER TABLE `asignacion_sucursal_domicilio`
  ADD PRIMARY KEY (`id_asignacion_sucursal_domicilio`),
  ADD KEY `rela_sucursal` (`rela_sucursal`),
  ADD KEY `rela_barrio` (`rela_barrio`);

--
-- Indices de la tabla `asignacion_tarifa_servicio`
--
ALTER TABLE `asignacion_tarifa_servicio`
  ADD PRIMARY KEY (`id_asignacion_tarifa_servicio`),
  ADD KEY `rela_tarifa` (`rela_tarifa`),
  ADD KEY `rela_servicio` (`rela_servicio`);

--
-- Indices de la tabla `barrio`
--
ALTER TABLE `barrio`
  ADD PRIMARY KEY (`id_barrio`),
  ADD KEY `rela_localidad` (`rela_localidad`);

--
-- Indices de la tabla `complejo`
--
ALTER TABLE `complejo`
  ADD PRIMARY KEY (`id_complejo`);

--
-- Indices de la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`id_contacto`),
  ADD UNIQUE KEY `descripcion_contacto` (`descripcion_contacto`),
  ADD KEY `rela_tipo_contacto` (`rela_tipo_contacto`),
  ADD KEY `rela_persona` (`rela_persona`);

--
-- Indices de la tabla `control`
--
ALTER TABLE `control`
  ADD PRIMARY KEY (`id_control`),
  ADD KEY `rela_estado_control` (`rela_estado_control`),
  ADD KEY `rela_reserva` (`rela_reserva`);

--
-- Indices de la tabla `deporte`
--
ALTER TABLE `deporte`
  ADD PRIMARY KEY (`id_deporte`);

--
-- Indices de la tabla `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`id_documento`),
  ADD UNIQUE KEY `descripcion_documento` (`descripcion_documento`,`rela_tipo_documento`),
  ADD KEY `rela_tipo_documento` (`rela_tipo_documento`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id_empleado`),
  ADD KEY `rela_persona` (`rela_persona`),
  ADD KEY `rela_sucursal` (`rela_sucursal`);

--
-- Indices de la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`id_equipo`),
  ADD KEY `rela_evento` (`rela_evento`);

--
-- Indices de la tabla `estado_control`
--
ALTER TABLE `estado_control`
  ADD PRIMARY KEY (`id_estado_control`);

--
-- Indices de la tabla `estado_reserva`
--
ALTER TABLE `estado_reserva`
  ADD PRIMARY KEY (`id_estado_reserva`);

--
-- Indices de la tabla `estado_zona`
--
ALTER TABLE `estado_zona`
  ADD PRIMARY KEY (`id_estado_zona`);

--
-- Indices de la tabla `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id_evento`),
  ADD KEY `rela_servicio` (`rela_servicio`);

--
-- Indices de la tabla `formato_deporte`
--
ALTER TABLE `formato_deporte`
  ADD PRIMARY KEY (`id_formato_deporte`),
  ADD KEY `rela_deporte` (`rela_deporte`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`id_horario`);

--
-- Indices de la tabla `localidad`
--
ALTER TABLE `localidad`
  ADD PRIMARY KEY (`id_localidad`),
  ADD KEY `rela_provincia` (`rela_provincia`);

--
-- Indices de la tabla `marcador_asistencia`
--
ALTER TABLE `marcador_asistencia`
  ADD PRIMARY KEY (`id_marcador_asistencia`),
  ADD KEY `rela_empleado` (`rela_empleado`);

--
-- Indices de la tabla `membresia`
--
ALTER TABLE `membresia`
  ADD PRIMARY KEY (`id_membresia`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`id_modulo`);

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id_perfil`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_persona`),
  ADD KEY `rela_documento` (`rela_documento`),
  ADD KEY `rela_sexo` (`rela_sexo`);

--
-- Indices de la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD PRIMARY KEY (`id_provincia`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `rela_estado_reserva` (`rela_estado_reserva`),
  ADD KEY `rela_persona` (`rela_persona`),
  ADD KEY `rela_zona` (`rela_zona`),
  ADD KEY `rela_horario` (`rela_horario`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indices de la tabla `sexo`
--
ALTER TABLE `sexo`
  ADD PRIMARY KEY (`id_sexo`);

--
-- Indices de la tabla `socio`
--
ALTER TABLE `socio`
  ADD PRIMARY KEY (`id_socio`),
  ADD KEY `rela_complejo` (`rela_complejo`),
  ADD KEY `rela_membresia` (`rela_membresia`),
  ADD KEY `fk_rela_persona` (`rela_persona`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD PRIMARY KEY (`id_sucursal`),
  ADD KEY `rela_complejo` (`rela_complejo`);

--
-- Indices de la tabla `tarifa`
--
ALTER TABLE `tarifa`
  ADD PRIMARY KEY (`id_tarifa`);

--
-- Indices de la tabla `tipo_contacto`
--
ALTER TABLE `tipo_contacto`
  ADD PRIMARY KEY (`id_tipo_contacto`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`id_tipo_documento`);

--
-- Indices de la tabla `tipo_terreno`
--
ALTER TABLE `tipo_terreno`
  ADD PRIMARY KEY (`id_tipo_terreno`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `rela_contacto` (`rela_contacto`),
  ADD KEY `rela_perfil` (`rela_perfil`);

--
-- Indices de la tabla `zona`
--
ALTER TABLE `zona`
  ADD PRIMARY KEY (`id_zona`),
  ADD KEY `rela_tipo_terreno` (`rela_tipo_terreno`),
  ADD KEY `rela_formato_deporte` (`rela_formato_deporte`),
  ADD KEY `rela_estado_zona` (`rela_estado_zona`),
  ADD KEY `rela_sucursal` (`rela_sucursal`),
  ADD KEY `rela_servicio` (`rela_servicio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignacion_perfil_modulo`
--
ALTER TABLE `asignacion_perfil_modulo`
  MODIFY `id_asignacion_perfil_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `asignacion_persona_complejo`
--
ALTER TABLE `asignacion_persona_complejo`
  MODIFY `id_asignacion_persona_complejo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `asignacion_persona_domicilio`
--
ALTER TABLE `asignacion_persona_domicilio`
  MODIFY `id_asignacion_persona_domicilio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asignacion_sucursal_domicilio`
--
ALTER TABLE `asignacion_sucursal_domicilio`
  MODIFY `id_asignacion_sucursal_domicilio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asignacion_tarifa_servicio`
--
ALTER TABLE `asignacion_tarifa_servicio`
  MODIFY `id_asignacion_tarifa_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `barrio`
--
ALTER TABLE `barrio`
  MODIFY `id_barrio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `complejo`
--
ALTER TABLE `complejo`
  MODIFY `id_complejo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `contacto`
--
ALTER TABLE `contacto`
  MODIFY `id_contacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `control`
--
ALTER TABLE `control`
  MODIFY `id_control` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `deporte`
--
ALTER TABLE `deporte`
  MODIFY `id_deporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `documento`
--
ALTER TABLE `documento`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id_equipo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_control`
--
ALTER TABLE `estado_control`
  MODIFY `id_estado_control` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_reserva`
--
ALTER TABLE `estado_reserva`
  MODIFY `id_estado_reserva` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_zona`
--
ALTER TABLE `estado_zona`
  MODIFY `id_estado_zona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `evento`
--
ALTER TABLE `evento`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `formato_deporte`
--
ALTER TABLE `formato_deporte`
  MODIFY `id_formato_deporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `localidad`
--
ALTER TABLE `localidad`
  MODIFY `id_localidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `marcador_asistencia`
--
ALTER TABLE `marcador_asistencia`
  MODIFY `id_marcador_asistencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `membresia`
--
ALTER TABLE `membresia`
  MODIFY `id_membresia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id_modulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `provincia`
--
ALTER TABLE `provincia`
  MODIFY `id_provincia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sexo`
--
ALTER TABLE `sexo`
  MODIFY `id_sexo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `socio`
--
ALTER TABLE `socio`
  MODIFY `id_socio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  MODIFY `id_sucursal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tarifa`
--
ALTER TABLE `tarifa`
  MODIFY `id_tarifa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_contacto`
--
ALTER TABLE `tipo_contacto`
  MODIFY `id_tipo_contacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `id_tipo_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_terreno`
--
ALTER TABLE `tipo_terreno`
  MODIFY `id_tipo_terreno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `zona`
--
ALTER TABLE `zona`
  MODIFY `id_zona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignacion_perfil_modulo`
--
ALTER TABLE `asignacion_perfil_modulo`
  ADD CONSTRAINT `asignacion_perfil_modulo_ibfk_1` FOREIGN KEY (`rela_perfil`) REFERENCES `perfil` (`id_perfil`),
  ADD CONSTRAINT `asignacion_perfil_modulo_ibfk_2` FOREIGN KEY (`rela_modulo`) REFERENCES `modulo` (`id_modulo`);

--
-- Filtros para la tabla `asignacion_persona_complejo`
--
ALTER TABLE `asignacion_persona_complejo`
  ADD CONSTRAINT `asignacion_persona_complejo_ibfk_1` FOREIGN KEY (`rela_persona`) REFERENCES `persona` (`id_persona`),
  ADD CONSTRAINT `asignacion_persona_complejo_ibfk_2` FOREIGN KEY (`rela_complejo`) REFERENCES `complejo` (`id_complejo`);

--
-- Filtros para la tabla `asignacion_persona_domicilio`
--
ALTER TABLE `asignacion_persona_domicilio`
  ADD CONSTRAINT `asignacion_persona_domicilio_ibfk_1` FOREIGN KEY (`rela_persona`) REFERENCES `persona` (`id_persona`),
  ADD CONSTRAINT `asignacion_persona_domicilio_ibfk_2` FOREIGN KEY (`rela_barrio`) REFERENCES `barrio` (`id_barrio`);

--
-- Filtros para la tabla `asignacion_sucursal_domicilio`
--
ALTER TABLE `asignacion_sucursal_domicilio`
  ADD CONSTRAINT `asignacion_sucursal_domicilio_ibfk_1` FOREIGN KEY (`rela_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  ADD CONSTRAINT `asignacion_sucursal_domicilio_ibfk_2` FOREIGN KEY (`rela_barrio`) REFERENCES `barrio` (`id_barrio`);

--
-- Filtros para la tabla `asignacion_tarifa_servicio`
--
ALTER TABLE `asignacion_tarifa_servicio`
  ADD CONSTRAINT `asignacion_tarifa_servicio_ibfk_1` FOREIGN KEY (`rela_tarifa`) REFERENCES `tarifa` (`id_tarifa`),
  ADD CONSTRAINT `asignacion_tarifa_servicio_ibfk_2` FOREIGN KEY (`rela_servicio`) REFERENCES `servicio` (`id_servicio`);

--
-- Filtros para la tabla `barrio`
--
ALTER TABLE `barrio`
  ADD CONSTRAINT `barrio_ibfk_1` FOREIGN KEY (`rela_localidad`) REFERENCES `localidad` (`id_localidad`);

--
-- Filtros para la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD CONSTRAINT `contacto_ibfk_1` FOREIGN KEY (`rela_tipo_contacto`) REFERENCES `tipo_contacto` (`id_tipo_contacto`),
  ADD CONSTRAINT `contacto_ibfk_2` FOREIGN KEY (`rela_persona`) REFERENCES `persona` (`id_persona`);

--
-- Filtros para la tabla `control`
--
ALTER TABLE `control`
  ADD CONSTRAINT `control_ibfk_1` FOREIGN KEY (`rela_estado_control`) REFERENCES `estado_control` (`id_estado_control`),
  ADD CONSTRAINT `control_ibfk_2` FOREIGN KEY (`rela_reserva`) REFERENCES `reserva` (`id_reserva`);

--
-- Filtros para la tabla `documento`
--
ALTER TABLE `documento`
  ADD CONSTRAINT `documento_ibfk_1` FOREIGN KEY (`rela_tipo_documento`) REFERENCES `tipo_documento` (`id_tipo_documento`);

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`rela_persona`) REFERENCES `persona` (`id_persona`),
  ADD CONSTRAINT `empleado_ibfk_2` FOREIGN KEY (`rela_sucursal`) REFERENCES `sucursal` (`id_sucursal`);

--
-- Filtros para la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD CONSTRAINT `equipo_ibfk_1` FOREIGN KEY (`rela_evento`) REFERENCES `evento` (`id_evento`);

--
-- Filtros para la tabla `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`rela_servicio`) REFERENCES `servicio` (`id_servicio`);

--
-- Filtros para la tabla `formato_deporte`
--
ALTER TABLE `formato_deporte`
  ADD CONSTRAINT `formato_deporte_ibfk_1` FOREIGN KEY (`rela_deporte`) REFERENCES `deporte` (`id_deporte`);

--
-- Filtros para la tabla `localidad`
--
ALTER TABLE `localidad`
  ADD CONSTRAINT `localidad_ibfk_1` FOREIGN KEY (`rela_provincia`) REFERENCES `provincia` (`id_provincia`);

--
-- Filtros para la tabla `marcador_asistencia`
--
ALTER TABLE `marcador_asistencia`
  ADD CONSTRAINT `marcador_asistencia_ibfk_1` FOREIGN KEY (`rela_empleado`) REFERENCES `empleado` (`id_empleado`);

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `persona_ibfk_1` FOREIGN KEY (`rela_documento`) REFERENCES `documento` (`id_documento`),
  ADD CONSTRAINT `persona_ibfk_2` FOREIGN KEY (`rela_sexo`) REFERENCES `sexo` (`id_sexo`);

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`rela_estado_reserva`) REFERENCES `estado_reserva` (`id_estado_reserva`),
  ADD CONSTRAINT `reserva_ibfk_2` FOREIGN KEY (`rela_persona`) REFERENCES `persona` (`id_persona`),
  ADD CONSTRAINT `reserva_ibfk_3` FOREIGN KEY (`rela_zona`) REFERENCES `zona` (`id_zona`),
  ADD CONSTRAINT `reserva_ibfk_4` FOREIGN KEY (`rela_horario`) REFERENCES `horario` (`id_horario`);

--
-- Filtros para la tabla `socio`
--
ALTER TABLE `socio`
  ADD CONSTRAINT `fk_rela_persona` FOREIGN KEY (`rela_persona`) REFERENCES `persona` (`id_persona`) ON DELETE CASCADE,
  ADD CONSTRAINT `socio_ibfk_1` FOREIGN KEY (`rela_complejo`) REFERENCES `complejo` (`id_complejo`),
  ADD CONSTRAINT `socio_ibfk_2` FOREIGN KEY (`rela_membresia`) REFERENCES `membresia` (`id_membresia`);

--
-- Filtros para la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD CONSTRAINT `sucursal_ibfk_1` FOREIGN KEY (`rela_complejo`) REFERENCES `complejo` (`id_complejo`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rela_contacto`) REFERENCES `contacto` (`id_contacto`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`rela_perfil`) REFERENCES `perfil` (`id_perfil`);

--
-- Filtros para la tabla `zona`
--
ALTER TABLE `zona`
  ADD CONSTRAINT `zona_ibfk_1` FOREIGN KEY (`rela_tipo_terreno`) REFERENCES `tipo_terreno` (`id_tipo_terreno`),
  ADD CONSTRAINT `zona_ibfk_2` FOREIGN KEY (`rela_formato_deporte`) REFERENCES `formato_deporte` (`id_formato_deporte`),
  ADD CONSTRAINT `zona_ibfk_3` FOREIGN KEY (`rela_estado_zona`) REFERENCES `estado_zona` (`id_estado_zona`),
  ADD CONSTRAINT `zona_ibfk_4` FOREIGN KEY (`rela_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  ADD CONSTRAINT `zona_ibfk_5` FOREIGN KEY (`rela_servicio`) REFERENCES `servicio` (`id_servicio`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
