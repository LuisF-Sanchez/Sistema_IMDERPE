-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-07-2026 a las 06:09:36
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
(8, 'Carrera Deporitva', '2026-07-11', 'Avenida perimetral ', 2, 'Este es un texto para probar que funciona la reseña historica', 'actividad_1784134572.jpg');

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
(2, '99998888', 'pochita', 'cascada', '2026-05-08', 'femenino', 'activo', 'infantil', '', 2, 1, 2),
(3, '3423432523', 'dfsaasfasdf', 'adfdafadsfsa', '2026-05-09', 'masculino', 'activo', 'infantil', 'villa', 1, 1, 1),
(4, '123456', 'juan', 'perez', '2026-03-10', 'masculino', 'activo', 'infantil', 'jobito', 4, 1, 1);

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
(2, '19203489', 'Roberto', 'Placenta', 'empleado_19203489_1783621530.jfif', 'Promotor Deportivo', '04261993043', 'Robertp@gmail.com', 'activo', NULL),
(3, '4818921', 'prueba', 'test', 'defaultavatar.png', 'Por asignar', '124144565', 'qwirfihuas@gmail.com', 'activo', NULL),
(5, '21781408', 'ciruela', 'pollito', 'defaultavatar.png', 'Por asignar', '532414123', 'papas@gmail.com', 'activo', NULL),
(6, '2353', 'chinchulin', 'dfwewq', 'defaultavatar.png', 'Por asignar', '13123214', 'asdasdqg@gmail.com', 'activo', NULL),
(7, '17612823', 'Joan', 'Escalona', 'empleado_17.612.823_1783620820.jfif', 'Presidente', '04269987345', 'joanpro@gmail.com', 'activo', '2025-08-01'),
(8, '21130372', 'keily', 'mendez', 'empleado_21130372_1783620990.jfif', 'Analista de RRHH', '0414175933', 'keily@gmail.com', 'activo', '2024-07-09'),
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
(1, '8978101', 'fulano ', 'marruecos', 1, '04247159074', 'fulanomar@gmail.com', 'activo'),
(2, '12345', 'dfadfaf', 'adfafasfasd', 2, '12455314', 'afkja@gmail.com', 'activo');

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
(5, '10182109', 'Yoswar', 'Mendez', '04264414253', '', 'Sabanita');

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
-- Indices de la tabla `atletas`
--
ALTER TABLE `atletas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula_atletas` (`cedula`),
  ADD KEY `representante_id` (`representante_id`),
  ADD KEY `fk_disciplina_atleta` (`disciplina_id`),
  ADD KEY `entrenador_id` (`entrenador_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `atletas`
--
ALTER TABLE `atletas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- AUTO_INCREMENT de la tabla `representantes`
--
ALTER TABLE `representantes`
  MODIFY `id` int(150) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Filtros para la tabla `atletas`
--
ALTER TABLE `atletas`
  ADD CONSTRAINT `atletas_ibfk_1` FOREIGN KEY (`representante_id`) REFERENCES `representantes` (`id`);

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
