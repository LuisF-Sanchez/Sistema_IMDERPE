-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-10-2026 a las 12:10:51
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
(9, 'Carrera 5 km', '2026-07-23', 'La minta', 2, 'TEXTO DE EJEMPLO', 'actividad_1784830505.jfif'),
(10, 'Carrera de buses', '2026-10-18', 'Puente ', 2, 'Carrera de buses, carrera de buses, carrera de buses ', NULL),
(11, 'Mantener estadios', '2026-10-16', 'las villas', 4, 'mantener las villas', NULL),
(12, 'Carrera en cancu', '2026-10-27', 'cancun', 2, 'asdajsd', NULL);

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
(9, 7),
(10, 7),
(10, 8),
(11, 1),
(12, 12),
(12, 13);

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
(4, '123456', 'juan', 'perez', '2026-03-10', 'masculino', 'activo', 'infantil', 'jobito', 1, 1, 2),
(6, '321312312', 'sAsAS', 'ASASas', '2026-10-18', '', 'activo', 'juvenil', 'ASKODJA', 5, 4, 3),
(7, '23112314124', 'sadasdasdas', 'asdasasdasdas', '2026-10-18', '', 'activo', 'infantil', 'sadasjd', 6, 1, 4),
(8, 'gjghjhg', 'fdsfsdfa', 'sdfsdfsdfsdaf', '2026-10-18', '', 'activo', 'juvenil', 'qwewdsa', 6, 1, 4),
(9, '123213435', 'SADADAS', 'sdaasdasda', '2026-10-18', '', 'activo', 'infantil', 'adasdASD', 2, 4, 6),
(10, '4353453', 'HGFHFGgg', 'gfhhfdhfghgh', '2026-10-18', '', 'activo', 'infantil', 'fgdhfhgd', 6, 1, 3),
(11, '5674456', 'jhgghkghjf', 'jhgghkghjf', '2026-10-01', '', 'activo', 'juvenil', 'jhgghkghjf', 5, 4, 1),
(12, '4353452', 'hfdfdhfghfgh', 'dfgsdgdsfgh', '2026-10-19', '', 'activo', 'infantil', 'hdfhfgfdgh', 3, 2, 6),
(13, '3244523452', 'sadasd', 'sdaasdsadsas', '2026-10-27', '', 'activo', 'juvenil', 'asdfasdas', 6, 2, 2),
(14, '0231093', 'sdaadasd', 'sadasdsad', '2026-10-27', 'masculino', 'activo', 'juvenil', 'sadasdas', 8, 1, 2);

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
(1, 'Lucas', 'Cabrera', '2001329', '04264561888', 'Lucpro@gmail.com', 'Jobitox', 'calle 13 con carrera 10'),
(3, 'ludwing', 'manchester', '21343130', '04121507823', 'manchester23@gmail.com', 'clorito', 'avenida principal con calle 7'),
(4, 'sjadhsa', 'dssadf', '2831721', '23213', 'sadsdsad', 'sadsadsa', 'asdajs');

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
(1, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado fgfdgagf fgasfgaff (C.I. 4452342) a \'inactivo\'.', '2026-10-27 05:53:54'),
(2, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado fgfdgagf fgasfgaff (C.I. 4452342) a \'activo\'.', '2026-10-27 05:53:55'),
(3, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado asjdasjda dfssdafsdafsfs (C.I. 24234234) a \'inactivo\'.', '2026-10-27 05:53:57'),
(4, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado asjdasjda dfssdafsdafsfs (C.I. 24234234) a \'activo\'.', '2026-10-27 05:53:58'),
(5, 2, 'luis', 'administrador', 'Cambio de Estado Atleta', 'El Administrador luis ha cambiado el estado del atleta sadasd sdaasdsadsas (C.I. 3244523452) a \'inactivo\'.', '2026-10-27 05:54:21'),
(6, 2, 'luis', 'administrador', 'Cambio de Estado Atleta', 'El Administrador luis ha cambiado el estado del atleta sadasd sdaasdsadsas (C.I. 3244523452) a \'activo\'.', '2026-10-27 05:54:22'),
(7, 2, 'luis', 'administrador', 'Cambio de Estado Atleta', 'El Administrador luis ha cambiado el estado del atleta hfdfdhfghfgh dfgsdgdsfgh (C.I. 4353452) a \'inactivo\'.', '2026-10-27 05:54:23'),
(8, 2, 'luis', 'administrador', 'Cambio de Estado Atleta', 'El Administrador luis ha cambiado el estado del atleta hfdfdhfghfgh dfgsdgdsfgh (C.I. 4353452) a \'activo\'.', '2026-10-27 05:54:24'),
(9, 2, 'luis', 'administrador', 'Cambio de Estado Entrenador', 'El Administrador luis ha cambiado el estado del entrenador Oswaldo Cardenas (C.I. 8376543) a \'inactivo\'.', '2026-10-27 05:54:37'),
(10, 2, 'luis', 'administrador', 'Cambio de Estado Entrenador', 'El Administrador luis ha cambiado el estado del entrenador Oswaldo Cardenas (C.I. 8376543) a \'activo\'.', '2026-10-27 05:54:38'),
(11, 2, 'luis', 'administrador', 'Registro de Usuario', 'El Administrador luis ha registrado un nuevo usuario del sistema: copito (C.I. 2131) (Rol: administrador).', '2026-10-27 05:55:16'),
(12, 2, 'luis', 'administrador', 'Registro de Usuario', 'El Administrador luis ha registrado un nuevo usuario del sistema: sddads (C.I. 412321321) (Rol: usuario).', '2026-10-27 05:55:32'),
(13, 2, 'luis', 'administrador', 'Registro de Empleado', 'El Administrador luis ha registrado al empleado Juan Jose Gutierrez (C.I. 27130893).', '2026-10-27 05:57:08'),
(14, 2, 'luis', 'administrador', 'Edición de Empleado', 'El Administrador luis ha actualizado los datos del empleado Juan Jose Gutierrez (C.I. 27130893).', '2026-10-27 05:58:15'),
(15, 2, 'luis', 'administrador', 'Registro de Actividad', 'El Administrador luis ha registrado una nueva actividad \'Carrera en cancu\'.', '2026-10-27 05:59:37'),
(16, 2, 'luis', 'administrador', 'Cambio de Estado Empleado', 'El Administrador luis ha cambiado el estado del empleado Juan Jose Gutierrez (C.I. 27130893) a \'activo\'.', '2026-10-27 06:02:00'),
(17, 2, 'luis', 'administrador', '2', 'REGISTRO_REPRESENTANTE_RAPIDO', '2026-10-27 06:04:34'),
(18, 2, 'luis', 'administrador', 'Registro de Atleta', 'El Administrador luis ha registrado al atleta sdaadasd sadasdsad (C.I. 0231093).', '2026-10-27 06:04:36'),
(19, 2, 'luis', 'administrador', 'Registro de Representante', 'El Administrador luis ha registrado al representante asdasdsads tuxedo (C.I. 231213).', '2026-10-27 06:10:06'),
(20, 2, 'luis', 'administrador', 'Registro de Entrenador', 'El Administrador luis ha registrado al entrenador copito Silverhand (C.I. 23871287312).', '2026-10-27 06:24:20'),
(21, 2, 'luis', 'administrador', 'Registro de Autogobierno', 'El Administrador luis ha registrado al responsable de autogobierno sjadhsa dssadf (C.I. 2831721).', '2026-10-27 06:25:04'),
(22, 2, 'luis', 'administrador', 'Edición de Empleado', 'El Administrador luis ha actualizado los datos del empleado Juan Jose Gutierrez (C.I. 27130893).', '2026-10-27 06:25:21'),
(23, 2, 'luis', 'administrador', 'Edición de Atleta', 'El Administrador luis ha actualizado los datos del atleta sdaadasd sadasdsad (C.I. 0231093).', '2026-10-27 06:41:51'),
(24, 2, 'luis', 'administrador', 'Edición de Representante', 'El Administrador luis ha actualizado los datos del representante asdasdsads tuxedo (C.I. 231213).', '2026-10-27 06:41:59'),
(25, 2, 'luis', 'administrador', 'Edición de Entrenador', 'El Administrador luis ha actualizado los datos del entrenador copito Silverhand (C.I. 23871287312).', '2026-10-27 06:42:05'),
(26, 2, 'luis', 'administrador', 'Edición de Autogobierno', 'El Administrador luis ha actualizado los datos del responsable de autogobierno Lucas Cabrera (C.I. 2001329).', '2026-10-27 06:42:11'),
(27, 2, 'luis', 'administrador', 'Edición de Profesor', 'El Administrador luis ha actualizado los datos del profesor de educación física Memo Ochoa (C.I. 17188593).', '2026-10-27 06:42:16'),
(28, 2, 'luis', 'administrador', 'Edición de Usuario', 'El Administrador luis ha actualizado los datos del usuario copito (C.I. 2131) (Rol: administrador).', '2026-10-27 06:42:25'),
(29, 2, 'luis', 'administrador', 'Emisión de Constancia', 'El Administrador luis ha generado la constancia de participación para el ciudadano(a) SADSADS (C.I. 23132123).', '2026-10-27 07:04:30'),
(30, 2, 'luis', 'administrador', 'Cierre de Sesión', 'El Administrador luis ha cerrado sesión en el sistema.', '2026-10-27 07:06:05');

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
(9, '324113', 'asfafa', 'asfasfasfasf', 'defaultavatar.png', 'Obrero Fijo', '1413124', 'wqiuhrfuwaq@gmail.com', 'activo', '2026-07-16'),
(11, 'Stefanoh', 'adsfadsfdasf', 'sdffdsafsfsd', 'defaultavatar.png', 'Por asignar', '84232732', 'dsfsdfsa', 'activo', '2026-10-07'),
(12, '24234234', 'asjdasjda', 'dfssdafsdafsfs', 'defaultavatar.png', 'Por asignar', '423423', 'asdsasafdas', 'activo', '2026-10-14'),
(13, '4452342', 'fgfdgagf', 'fgasfgaff', 'defaultavatar.png', 'Por asignar', '5234353', 'sdafafdsfds', 'activo', '2026-10-07'),
(14, '27130893', 'Juan Jose', 'Gutierrez', 'defaultavatar.png', 'Médica', '324134', 'juanjox@gmail.com', 'activo', '2026-10-23');

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
(2, '12345', 'dfadfaf', 'adfafasfasd', 2, '12455314', 'afkja@gmail.com', 'activo'),
(4, '8376543', 'Oswaldo', 'Cardenas', 7, '04141716873', 'Oswaldc123@gmail.com', 'activo'),
(5, '23871287312', 'copito', 'Silverhand', 6, 'sads', 'SDJHSAHS', 'activo');

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
(7, '3232131', 'sadasdasd', 'asdasdasdsad', '1433', 'sadasdas', 'sadasd'),
(8, '21312312', 'manuel', 'Carrillo', '21312', 'manuelcarri@gmail.com', 'sdsadsadasd'),
(9, '231213', 'asdasdsads', 'tuxedo', 'sdasdsa', 'sdasdsadas', 'sadasdsadasd');

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
(2, 'luis', 31185743, '04269907063', 'admin123@gmail.com', '12345', 'administrador'),
(3, 'copito', 2131, '321432', 'asdasdsa', 'sadsadas', 'administrador'),
(4, 'sddads', 412321321, '341123', 'saddsasa', 'sdadsas', 'usuario');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `atletas`
--
ALTER TABLE `atletas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `autogobierno`
--
ALTER TABLE `autogobierno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `bitacora_sistema`
--
ALTER TABLE `bitacora_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `entrenadores`
--
ALTER TABLE `entrenadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(150) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tipos_actividad`
--
ALTER TABLE `tipos_actividad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
