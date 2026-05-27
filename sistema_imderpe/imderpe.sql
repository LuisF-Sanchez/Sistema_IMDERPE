-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-05-2026 a las 05:16:19
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
  `empleado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `nombre_actividad`, `fecha`, `lugar`, `tipo_id`, `empleado_id`) VALUES
(1, 'Rally deportivo', '2026-05-22', 'Plaza Bolivar', 2, 6),
(2, 'Rally deportivo', '2026-05-23', 'Plaza Bolivar', 2, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `nombre` varchar(40) NOT NULL,
  `cedula` int(8) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `tipo` enum('administrador') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`nombre`, `cedula`, `telefono`, `correo`, `contraseña`, `tipo`) VALUES
('pedro', 3102912, '042441287', 'pedroloz@gmail.com', '12345', 'administrador'),
('luis', 31185743, '04269907063', 'admin123@gmail.com', '12345', 'administrador');

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
  `representante_id` int(150) NOT NULL,
  `entrenador_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `atletas`
--

INSERT INTO `atletas` (`id`, `cedula`, `nombre`, `apellido`, `fecha_nacimiento`, `genero`, `estado`, `representante_id`, `entrenador_id`, `disciplina_id`) VALUES
(1, '30123456', 'pedro', 'Reina', '2006-08-08', '', 'activo', 1, 1, 1),
(2, '99998888', 'pochita', 'cascada', '2026-05-08', '', 'activo', 2, 1, 2),
(3, '3423432523', 'dfsaasfasdf', 'adfdafadsfsa', '2026-05-09', '', 'activo', 1, 1, 3),
(4, '123456', 'juan', 'perez', '2026-03-10', '', 'activo', 4, 1, 1);

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
(1, 'fútbol'),
(2, 'beisbol'),
(3, 'volleyball');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `cedula` varchar(8) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `estado` enum('activo','inactivo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `cedula`, `nombre`, `apellido`, `telefono`, `correo`, `estado`) VALUES
(1, '10320890', 'maria', 'magdalena', '02513341567', 'magda300@gmail.com', 'activo'),
(2, '19203489', 'Roberto', 'Placenta', '04261993043', 'Robertp@gmail.com', 'activo'),
(3, '4818921', 'prueba', 'test', '124144565', 'qwirfihuas@gmail.com', 'activo'),
(5, '21781408', 'ciruela', 'pollito', '532414123', 'papas@gmail.com', 'activo'),
(6, '2353', 'chinchulin', 'dfwewq', '13123214', 'asdasdqg@gmail.com', 'activo');

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
(1, '8978101', 'fulano ', 'marruecos', 1, '04247159074', 'fulanomar@gmail.com', 'inactivo'),
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
(2, '898989898', 'alvarado', 'monserat', '04122128830', '', 'casa club'),
(3, '423414', 'dsgsdgsg', 'dfhdfghds', '322523423', '', 'sfdagsagsdf'),
(4, '323231', 'dsgsdgsfff', 'fdafdfafd', '22421324', '', 'accacacaca');

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
  `nombre` varchar(40) NOT NULL,
  `cedula` int(8) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `tipo` enum('usuario') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`nombre`, `cedula`, `telefono`, `correo`, `contraseña`, `tipo`) VALUES
('Macarena Reina', 14693646, '04264561888', 'macarena@gmail.com', '1567882', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empleado_id` (`empleado_id`);

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`cedula`);

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
  ADD PRIMARY KEY (`cedula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `atletas`
--
ALTER TABLE `atletas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `id` int(150) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipos_actividad`
--
ALTER TABLE `tipos_actividad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `actividades_ibfk_1` FOREIGN KEY (`empleado_id`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `atletas`
--
ALTER TABLE `atletas`
  ADD CONSTRAINT `atletas_ibfk_1` FOREIGN KEY (`representante_id`) REFERENCES `representantes` (`id`),
  ADD CONSTRAINT `fk_disciplina_atleta` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON UPDATE CASCADE;

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
