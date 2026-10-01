-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-09-2026 a las 16:16:00
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `imderpe`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `nombre_actividad` varchar(150) NOT NULL,
  `fecha` date NOT NULL,
  `lugar` varchar(255) NOT NULL,
  `tipo_id` int(11) NOT NULL,
  `resena` text DEFAULT NULL,
  `foto_actividad` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `nombre_actividad`, `fecha`, `lugar`, `tipo_id`, `resena`, `foto_actividad`) VALUES
(1, 'Rally deportivo', '2026-05-22', 'Plaza Bolivar', 2, NULL, NULL),
(2, 'Rally deportivo', '2026-05-23', 'Plaza Bolivar', 2, NULL, NULL),
(3, 'mantenimiento a la cancha', '2026-05-30', 'limoncito', 4, NULL, NULL),
(4, 'carrera de bici ', '2026-05-31', 'avenida ', 2, NULL, NULL),
(5, 'Construcción de nueva cancha', '2026-06-13', 'Villanueva', 5, NULL, NULL),
(6, 'actividad ejemplar', '2026-07-12', 'lugar ejemplar', 1, 'Este texto es ejemplar para probar el detalle histórico', 'actividad_1783913321.jpg'),
(7, 'Ciclismo atletico', '2026-07-15', 'En las villas', 2, 'Breve texto de ejemplo', 'actividad_1784133022.jfif'),
(8, 'Carrera Deporitva', '2026-07-11', 'Avenida perimetral ', 2, 'Este es un texto para probar que funciona la reseña historica', 'actividad_1784134572.jpg'),
(9, 'Carrera 5 km', '2026-07-23', 'La minta', 2, 'TEXTO DE EJEMPLO', 'actividad_1784830505.jfif');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_responsables`
--

CREATE TABLE `actividad_responsables` (
  `actividad_id` int(11) NOT NULL,
  `empleado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividad_responsables`
--

INSERT INTO `actividad_responsables` (`actividad_id`, `empleado_id`) VALUES
(1, 6),
(2, 6),
(3, 5),
(4, 6),
(5, 1),
(6, 2),
(7, 6),
(8, 1),
(8, 2),
(8, 7),
(9, 3),
(9, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atletas`
--

CREATE TABLE `atletas` (
  `id` int(11) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` enum('masculino','femenino') NOT NULL,
  `estado` enum('activo','inactivo','suspendido') NOT NULL,
  `categoria` enum('infantil','juvenil') NOT NULL,
  `comuna` varchar(100) NOT NULL,
  `representante_id` int(150) NOT NULL,
  `entrenador_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `atletas`
--

INSERT INTO `atletas` (`id`, `cedula`, `nombre`, `apellido`, `fecha_nacimiento`, `genero`, `estado`, `categoria`, `comuna`, `representante_id`, `entrenador_id`, `disciplina_id`) VALUES
(1, '30123456', 'pedro', 'Reina', '2006-08-08', 'masculino', 'activo', 'infantil', '', 1, 1, 1),
(2, '99998888', 'pochita', 'cascada', '2026-05-08', 'masculino', 'activo', 'infantil', 'casita peña', 2, 2, 2),
(3, '3423432523', 'dfsaasfasdf', 'adfdafadsfsa', '2026-05-09', 'masculino', 'activo', 'infantil', 'villa', 1, 1, 1),
(4, '123456', 'juan', 'perez', '2026-03-10', 'masculino', 'activo', 'infantil', 'jobito', 1, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autogobierno`
--

CREATE TABLE `autogobierno` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `comuna` varchar(150) NOT NULL,
  `direccion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `autogobierno`
--

INSERT INTO `autogobierno` (`id`, `nombre`, `apellido`, `cedula`, `telefono`, `correo`, `comuna`, `direccion`) VALUES
(1, 'Lucas', 'Cabrera', '2001329', '04264561888', 'Lucpro@gmail.com', 'Jobitox', 'calle 13 con carrera 10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora_sistema`
--

CREATE TABLE `bitacora_sistema` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `usuario_nombre` varchar(150) NOT NULL,
  `usuario_rol` varchar(50) NOT NULL,
  `accion` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `bitacora_sistema`
--

INSERT INTO `bitacora_sistema` (`id`, `usuario_id`, `usuario_nombre`, `usuario_rol`, `accion`, `descripcion`, `fecha_hora`) VALUES
(1, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado maria lopez (C.I. 1032089) a \'inactivo\'.', '2026-07-26 19:44:28'),
(2, 2, 'luis', 'administrador', 'Edición de Atleta', 'El Administrador luis ha actualizado los datos del atleta juan perez (C.I. 123456).', '2026-07-26 19:48:40'),
(3, 2, 'luis', 'administrador', 'Edición de Atleta', 'El Administrador luis ha actualizado los datos del atleta juan perez (C.I. 123456).', '2026-07-26 19:49:15'),
(4, 2, 'luis', 'administrador', 'Registro de Representante', 'El Administrador luis ha registrado al representante lucia yepez (C.I. 40756891).', '2026-07-26 19:57:13'),
(5, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-07-26 20:03:07'),
(6, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-07-30 22:53:37'),
(7, 2, 'luis', 'administrador', 'Cambio de Estado Atleta', 'El Administrador luis ha cambiado el estado del atleta juan perez (C.I. 123456) a \'inactivo\'.', '2026-07-30 23:16:45'),
(8, 2, 'luis', 'administrador', 'Cambio de Estado Atleta', 'El Administrador luis ha cambiado el estado del atleta juan perez (C.I. 123456) a \'activo\'.', '2026-07-30 23:16:46'),
(9, 2, 'luis', 'administrador', 'Edición de Atleta', 'El Administrador luis ha actualizado los datos del atleta juan perez (C.I. 123456).', '2026-07-30 23:16:52'),
(10, 2, 'luis', 'administrador', 'Cambio de Estado Atleta', 'El Administrador luis ha cambiado el estado del atleta dfsaasfasdf adfdafadsfsa (C.I. 3423432523) a \'inactivo\'.', '2026-07-30 23:16:54'),
(11, 2, 'luis', 'administrador', 'Cambio de Estado Atleta', 'El Administrador luis ha cambiado el estado del atleta dfsaasfasdf adfdafadsfsa (C.I. 3423432523) a \'activo\'.', '2026-07-30 23:16:55'),
(12, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-07-30 23:16:57'),
(13, 1, 'Macarena Reina', 'usuario', 'Inicio de Sesión', 'El Usuario Macarena Reina ha iniciado sesión en el sistema.', '2026-07-30 23:17:11'),
(14, 1, 'Macarena Reina', 'usuario', 'Cierre de Sesión', 'El Usuario Macarena Reina ha cerrado sesión en el sistema.', '2026-07-30 23:17:12'),
(15, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-07-30 23:17:20'),
(16, 2, 'luis', 'administrador', 'Edición de Empleado', 'El Administrador luis ha actualizado los datos del empleado Roberto Placenta (C.I. 19203489).', '2026-07-30 23:20:14'),
(17, 2, 'luis', 'administrador', 'Edición de Empleado', 'El Administrador luis ha actualizado los datos del empleado Joan Escalona (C.I. 17612823).', '2026-07-30 23:20:25'),
(18, 2, 'luis', 'administrador', 'Edición de Empleado', 'El Administrador luis ha actualizado los datos del empleado keily mendez (C.I. 21130372).', '2026-07-30 23:20:35'),
(19, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-07-30 23:22:53'),
(20, 1, 'Macarena Reina', 'usuario', 'Inicio de Sesión', 'El Usuario Macarena Reina ha iniciado sesión en el sistema.', '2026-07-30 23:23:03'),
(21, 1, 'Macarena Reina', 'usuario', 'Cierre de Sesión', 'El Usuario Macarena Reina ha cerrado sesión en el sistema.', '2026-07-30 23:23:53'),
(22, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-07-30 23:24:03'),
(23, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-07-30 23:24:57'),
(24, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-07-31 00:53:03'),
(25, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-07-31 00:55:16'),
(26, 1, 'Macarena Reina', 'usuario', 'Inicio de Sesión', 'El Usuario Macarena Reina ha iniciado sesión en el sistema.', '2026-07-31 00:55:30'),
(27, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-07-31 01:16:50'),
(28, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-07-31 01:19:39'),
(29, 1, 'Macarena Reina', 'usuario', 'Inicio de Sesión', 'El Usuario Macarena Reina ha iniciado sesión en el sistema.', '2026-07-31 01:19:53'),
(30, 1, 'Macarena Reina', 'usuario', 'Cierre de Sesión', 'El Usuario Macarena Reina ha cerrado sesión en el sistema.', '2026-07-31 01:20:03'),
(31, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-07-31 01:23:30'),
(32, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado maria lopez (C.I. 1032089) a \'activo\'.', '2026-07-31 01:24:49'),
(33, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-07-31 01:30:51'),
(34, 1, 'Macarena Reina', 'usuario', 'Inicio de Sesión', 'El Usuario Macarena Reina ha iniciado sesión en el sistema.', '2026-07-31 01:31:02'),
(35, 1, 'Macarena Reina', 'usuario', 'Cierre de Sesión', 'El Usuario Macarena Reina ha cerrado sesión en el sistema.', '2026-07-31 01:35:22'),
(36, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-07-31 02:12:34'),
(37, 2, 'luis', 'administrador', 'Cambio de Estado Entrenador', 'El Administrador luis ha cambiado el estado del entrenador dfadfaf adfafasfasd (C.I. 12345) a \'inactivo\'.', '2026-07-31 02:17:25'),
(38, 2, 'luis', 'administrador', 'Cambio de Estado Atleta', 'El Administrador luis ha cambiado el estado del atleta juan perez (C.I. 123456) a \'inactivo\'.', '2026-07-31 02:18:17'),
(39, 2, 'luis', 'administrador', 'Cambio de Estado Atleta', 'El Administrador luis ha cambiado el estado del atleta juan perez (C.I. 123456) a \'activo\'.', '2026-07-31 02:18:22'),
(40, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado maria lopez (C.I. 1032089) a \'inactivo\'.', '2026-07-31 02:18:29'),
(41, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado maria lopez (C.I. 1032089) a \'activo\'.', '2026-07-31 02:18:37'),
(42, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-07-31 02:25:42'),
(43, 1, 'Macarena Reina', 'usuario', 'Inicio de Sesión', 'El Usuario Macarena Reina ha iniciado sesión en el sistema.', '2026-07-31 02:25:54'),
(44, 1, 'Macarena Reina', 'usuario', 'Cierre de Sesión', 'El Usuario Macarena Reina ha cerrado sesión en el sistema.', '2026-07-31 02:31:40'),
(45, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-18 14:19:30'),
(46, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado maria lopez (C.I. 1032089) a \'inactivo\'.', '2026-09-18 14:20:20'),
(47, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado maria lopez (C.I. 1032089) a \'activo\'.', '2026-09-18 14:20:22'),
(48, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-21 13:06:33'),
(49, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-09-21 14:00:19'),
(50, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-21 14:00:34'),
(51, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-23 10:58:35'),
(52, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-09-23 11:31:16'),
(53, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-24 02:47:05'),
(54, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado maria lopez (C.I. 1032089) a \'inactivo\'.', '2026-09-24 02:47:37'),
(55, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado maria lopez (C.I. 1032089) a \'activo\'.', '2026-09-24 02:47:40'),
(56, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-09-24 04:04:25'),
(57, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-24 04:04:41'),
(58, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-24 14:00:08'),
(59, 2, 'luis', 'administrador', 'Edición de Atleta', 'El Administrador luis ha actualizado los datos del atleta juan perez (C.I. 123456).', '2026-09-24 15:45:24'),
(60, 2, 'luis', 'administrador', 'Edición de Atleta', 'El Administrador luis ha actualizado los datos del atleta juan perez (C.I. 123456).', '2026-09-24 15:51:01'),
(61, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-25 14:53:16'),
(62, 2, 'luis', 'administrador', 'Edición de Atleta', 'El Administrador luis ha actualizado los datos del atleta pochita cascada (C.I. 99998888).', '2026-09-25 16:19:38'),
(63, 2, 'luis', 'administrador', 'Edición de Entrenador', 'El Administrador luis ha actualizado los datos del entrenador fulano marruecos (C.I. 8978101).', '2026-09-25 16:33:21'),
(64, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-25 16:56:39'),
(65, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-25 16:58:13'),
(66, 2, 'luis', 'administrador', 'Edición de Autogobierno', 'El Administrador luis ha actualizado los datos del responsable de autogobierno Lucas Cabrera (C.I. 2001329).', '2026-09-25 17:37:34'),
(67, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-27 03:58:39'),
(68, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-09-27 04:01:35'),
(69, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-27 04:04:04'),
(70, 2, 'luis', 'administrador', 'Registro de Profesor', 'El Administrador luis ha registrado al profesor de educación física Memo Ochoa (C.I. 17188594).', '2026-09-27 04:07:23'),
(71, 2, 'luis', 'administrador', 'Edición de Profesor', 'El Administrador luis ha actualizado los datos del profesor de educación física Memo Ochoa (C.I. 17188593).', '2026-09-27 04:08:01'),
(72, 2, 'luis', 'administrador', 'Cambio de Estado Atleta', 'El Administrador luis ha cambiado el estado del atleta juan perez (C.I. 123456) a \'inactivo\'.', '2026-09-27 04:09:42'),
(73, 2, 'luis', 'administrador', 'Cambio de Estado Atleta', 'El Administrador luis ha cambiado el estado del atleta juan perez (C.I. 123456) a \'activo\'.', '2026-09-27 04:09:43'),
(74, 2, 'luis', 'administrador', 'Edición de Usuario', 'El Administrador luis ha actualizado los datos del usuario luis (C.I. 31185743) (Rol: administrador).', '2026-09-27 04:38:47'),
(75, 2, 'luis', 'administrador', 'Edición de Usuario', 'El Administrador luis ha actualizado los datos del usuario luis (C.I. 31185743) (Rol: administrador).', '2026-09-27 04:38:51'),
(76, 2, 'luis', 'administrador', 'Edición de Usuario', 'El Administrador luis ha actualizado los datos del usuario luis (C.I. 31185743) (Rol: administrador).', '2026-09-27 04:39:17'),
(77, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-27 08:46:42'),
(78, 2, 'luis', 'administrador', 'Edición de Atleta', 'El Administrador luis ha actualizado los datos del atleta juan perez (C.I. 123456).', '2026-09-27 08:49:41'),
(79, 2, 'luis', 'administrador', 'Edición de Atleta', 'El Administrador luis ha actualizado los datos del atleta juan perez (C.I. 123456).', '2026-09-27 08:49:48'),
(80, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado maria lopez (C.I. 1032089) a \'inactivo\'.', '2026-09-27 08:51:22'),
(81, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado maria lopez (C.I. 1032089) a \'activo\'.', '2026-09-27 08:51:26'),
(82, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-27 08:54:44'),
(83, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-09-27 08:58:53'),
(84, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-27 09:30:56'),
(85, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-09-27 09:37:05'),
(86, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-27 09:46:35'),
(87, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-09-27 09:58:54'),
(88, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-27 10:05:48'),
(89, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-09-27 10:07:16'),
(90, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-27 10:07:50'),
(91, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-09-27 10:08:01'),
(92, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-27 10:09:00'),
(93, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-09-27 10:09:08'),
(94, 2, 'luis', 'administrador', 'Inicio de Sesión', 'El Administrador luis ha iniciado sesión en el sistema.', '2026-09-27 10:10:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disciplinas`
--

CREATE TABLE `disciplinas` (
  `id` int(11) NOT NULL,
  `nombre_disciplina` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `disciplinas`
--

INSERT INTO `disciplinas` (`id`, `nombre_disciplina`) VALUES
(1, 'Fútbol'),
(2, 'Beisbol'),
(3, 'Beisbol five'),
(4, 'Boxeo'),
(5, 'Ciclismo'),
(6, 'Atletismo'),
(7, 'Kickingbol');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `cedula` varchar(8) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `foto` varchar(255) NOT NULL DEFAULT 'defaultavatar.png',
  `cargo` enum('Por asignar','Presidente','Administrador','Jefe de Planificación','Jefe de la Oficina de la OAC','Promotor Deportivo','Médica','Supervisor Deportivo','Asistente Administrativo','Secretaria','Entrenador Deportivo','Analista de RRHH','Obrero Fijo','Obrero Contratado') NOT NULL DEFAULT 'Por asignar',
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL,
  `fecha_ingreso` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `cedula`, `nombre`, `apellido`, `foto`, `cargo`, `telefono`, `correo`, `estado`, `fecha_ingreso`) VALUES
(1, '1032089', 'maria', 'lopez', 'defaultavatar.png', 'Secretaria', '02513341567', 'magda300@gmail.com', 'activo', NULL),
(2, '19203489', 'Roberto', 'Placenta', 'empleado_19203489_1785468014.avif', 'Promotor Deportivo', '04261993043', 'Robertp@gmail.com', 'activo', NULL),
(3, '4818921', 'prueba', 'test', 'defaultavatar.png', 'Por asignar', '124144565', 'qwirfihuas@gmail.com', 'activo', NULL),
(5, '21781408', 'ciruela', 'pollito', 'defaultavatar.png', 'Por asignar', '532414123', 'papas@gmail.com', 'activo', NULL),
(6, '2353', 'chinchulin', 'dfwewq', 'defaultavatar.png', 'Por asignar', '13123214', 'asdasdqg@gmail.com', 'activo', NULL),
(7, '17612823', 'Joan', 'Escalona', 'empleado_17612823_1785468025.png', 'Presidente', '04269987345', 'joanpro@gmail.com', 'activo', '2025-08-01'),
(8, '21130372', 'keily', 'mendez', 'empleado_21130372_1785468035.png', 'Analista de RRHH', '0414175933', 'keily@gmail.com', 'activo', '2024-07-09'),
(9, '324113', 'asfafa', 'asfasfasfasf', 'defaultavatar.png', 'Obrero Fijo', '1413124', 'wqiuhrfuwaq@gmail.com', 'activo', '2026-07-16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenadores`
--

CREATE TABLE `entrenadores` (
  `id` int(11) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `disciplina_id` int(11) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entrenadores`
--

INSERT INTO `entrenadores` (`id`, `cedula`, `nombre`, `apellido`, `disciplina_id`, `telefono`, `correo`, `estado`) VALUES
(1, '8978101', 'fulano', 'marruecos', 1, '04247159074', 'afkja@gmail.com', 'activo'),
(2, '12345', 'dfadfaf', 'adfafasfasd', 2, '12455314', 'afkja@gmail.com', 'inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenador_disciplina`
--

CREATE TABLE `entrenador_disciplina` (
  `id` int(11) NOT NULL,
  `entrenador_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor_edu`
--

CREATE TABLE `profesor_edu` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `instituto_educativo` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `profesor_edu`
--

INSERT INTO `profesor_edu` (`id`, `nombre`, `apellido`, `cedula`, `telefono`, `correo`, `instituto_educativo`) VALUES
(1, 'mrworld', 'paul', '2636813', '27367612', 'aiuhfas@gmail.com', 'asiudhasuhd'),
(2, 'Memo', 'Ochoa', '17188593', '342343', 'memoochoa123@gmail.com', 'las vilas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representantes`
--

CREATE TABLE `representantes` (
  `id` int(150) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `direccion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `representantes`
--

INSERT INTO `representantes` (`id`, `cedula`, `nombre`, `apellido`, `telefono`, `correo`, `direccion`) VALUES
(1, '10833678', 'carlitos', 'londres', '04261993043', '', 'sabanita'),
(2, '898989898', 'alvaradok', 'monserat', '04122128830', 'alvaradox9@gmail.com', 'casa club'),
(3, '423414', 'dsgsdgsg', 'dfhdfghds', '322523423', '', 'sfdagsagsdf'),
(4, '323231', 'dsgsdgsfff', 'fdafdfafd', '22421324', '', 'accacacaca'),
(5, '10182109', 'Yoswar', 'Mendez', '04264414253', '', 'Sabanita'),
(6, '40756891', 'lucia', 'yepez', '0426990374', '', 'casa cuna'),
(7, '3232131', 'sadasdasd', 'asdasdasdsad', '1433', 'sadasdas', 'sadasd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_actividad`
--

CREATE TABLE `tipos_actividad` (
  `id` int(11) NOT NULL,
  `nombre_tipo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_actividad`
--

INSERT INTO `tipos_actividad` (`id`, `nombre_tipo`) VALUES
(5, 'Construcción'),
(2, 'Deportiva'),
(3, 'Limpieza'),
(4, 'Mantenimiento'),
(1, 'Recreativa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `cedula` int(8) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `tipo` enum('administrador','usuario') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `cedula`, `telefono`, `correo`, `contraseña`, `tipo`) VALUES
(1, 'Macarena Reina', 14693646, '04264561888', 'macarena@gmail.com', '1567882', 'usuario'),
(2, 'luis', 31185743, '04269907063', 'admin123@gmail.com', '12345', 'administrador');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `actividad_responsables`
--
ALTER TABLE `actividad_responsables`
  ADD PRIMARY KEY (`actividad_id`,`empleado_id`),
  ADD KEY `empleado_id` (`empleado_id`);

--
-- Indices de la tabla `atletas`
--
ALTER TABLE `atletas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula_atletas` (`cedula`),
  ADD KEY `representante_id` (`representante_id`),
  ADD KEY `fk_disciplina_atleta` (`disciplina_id`),
  ADD KEY `entrenador_id` (`entrenador_id`);

--
-- Indices de la tabla `autogobierno`
--
ALTER TABLE `autogobierno`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `bitacora_sistema`
--
ALTER TABLE `bitacora_sistema`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bitacora_usuario` (`usuario_id`);

--
-- Indices de la tabla `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula_empleado` (`cedula`);

--
-- Indices de la tabla `entrenadores`
--
ALTER TABLE `entrenadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `fk_entrenador_disciplina` (`disciplina_id`);

--
-- Indices de la tabla `entrenador_disciplina`
--
ALTER TABLE `entrenador_disciplina`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entrenador_id` (`entrenador_id`),
  ADD KEY `disciplina_id` (`disciplina_id`);

--
-- Indices de la tabla `profesor_edu`
--
ALTER TABLE `profesor_edu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indices de la tabla `representantes`
--
ALTER TABLE `representantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula_representantes` (`cedula`);

--
-- Indices de la tabla `tipos_actividad`
--
ALTER TABLE `tipos_actividad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_tipo` (`nombre_tipo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `cedula_2` (`cedula`),
  ADD UNIQUE KEY `cedula_3` (`cedula`),
  ADD KEY `cedula_4` (`cedula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `atletas`
--
ALTER TABLE `atletas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `autogobierno`
--
ALTER TABLE `autogobierno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `bitacora_sistema`
--
ALTER TABLE `bitacora_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de la tabla `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `entrenadores`
--
ALTER TABLE `entrenadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `entrenador_disciplina`
--
ALTER TABLE `entrenador_disciplina`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `profesor_edu`
--
ALTER TABLE `profesor_edu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `representantes`
--
ALTER TABLE `representantes`
  MODIFY `id` int(150) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipos_actividad`
--
ALTER TABLE `tipos_actividad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividad_responsables`
--
ALTER TABLE `actividad_responsables`
  ADD CONSTRAINT `actividad_responsables_ibfk_1` FOREIGN KEY (`actividad_id`) REFERENCES `actividades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `actividad_responsables_ibfk_2` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `atletas`
--
ALTER TABLE `atletas`
  ADD CONSTRAINT `atletas_ibfk_1` FOREIGN KEY (`representante_id`) REFERENCES `representantes` (`id`);

--
-- Filtros para la tabla `bitacora_sistema`
--
ALTER TABLE `bitacora_sistema`
  ADD CONSTRAINT `fk_bitacora_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `entrenadores`
--
ALTER TABLE `entrenadores`
  ADD CONSTRAINT `entrenadores_ibfk_1` FOREIGN KEY (`id`) REFERENCES `atletas` (`representante_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_entrenador_disciplina` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `entrenador_disciplina`
--
ALTER TABLE `entrenador_disciplina`
  ADD CONSTRAINT `entrenador_disciplina_ibfk_1` FOREIGN KEY (`entrenador_id`) REFERENCES `entrenadores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `entrenador_disciplina_ibfk_2` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
