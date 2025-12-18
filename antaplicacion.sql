-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2025 at 02:24 PM
-- Server version: 9.2.0
-- PHP Version: 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `antaplicacion`
--

-- --------------------------------------------------------

--
-- Table structure for table `edit_requests`
--

CREATE TABLE `edit_requests` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `archive_id` int DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `admin_comment` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `edit_requests`
--

INSERT INTO `edit_requests` (`id`, `user_id`, `archive_id`, `status`, `admin_comment`, `created_at`, `updated_at`) VALUES
(33, 12, 16, 'completed', NULL, '2025-12-16 03:05:42', '2025-12-16 03:08:33'),
(34, 13, 17, 'completed', NULL, '2025-12-16 03:07:07', '2025-12-16 03:10:33'),
(35, 12, 19, 'completed', NULL, '2025-12-16 03:09:33', '2025-12-16 03:14:44'),
(36, 13, 20, 'completed', NULL, '2025-12-16 03:13:01', '2025-12-16 03:14:00'),
(37, 12, 24, 'completed', NULL, '2025-12-16 14:13:02', '2025-12-16 14:14:35');

-- --------------------------------------------------------

--
-- Table structure for table `reports_archive`
--

CREATE TABLE `reports_archive` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `school_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_data` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports_archive`
--

INSERT INTO `reports_archive` (`id`, `user_id`, `school_type`, `school_name`, `form_data`, `created_at`) VALUES
(21, 13, 'professional', 'chile', '{\"school_type\":\"professional\",\"step1\":{\"school_type\":\"professional\",\"escuela_nombre\":\"chile\",\"provincia\":\"\",\"canton\":\"\",\"ruc\":\"\",\"direccion_insitu\":\"\",\"direccion_ant\":\"\",\"direccion_sri\":\"\",\"gerente_nombre\":\"\",\"telefono_fijo\":\"\",\"telefono\":\"\",\"email\":\"\",\"resolucion_nro\":\"\",\"resoluciones_extra\":\"\",\"patente_municipal\":\"\",\"impuesto_predial\":\"\",\"permiso_bomberos\":\"\",\"permiso_sanitario\":\"\"}}', '2025-12-16 03:11:17'),
(22, 13, 'professional', 'Colombia', '{\"school_type\":\"professional\",\"step1\":{\"school_type\":\"professional\",\"escuela_nombre\":\"Colombia\",\"provincia\":\"\",\"canton\":\"\",\"ruc\":\"\",\"direccion_insitu\":\"\",\"direccion_ant\":\"\",\"direccion_sri\":\"\",\"gerente_nombre\":\"\",\"telefono_fijo\":\"\",\"telefono\":\"\",\"email\":\"\",\"resolucion_nro\":\"\",\"resoluciones_extra\":\"\",\"patente_municipal\":\"\",\"impuesto_predial\":\"\",\"permiso_bomberos\":\"\",\"permiso_sanitario\":\"\"}}', '2025-12-16 03:14:00'),
(23, 12, 'professional', 'Uruguay', '{\"school_type\":\"professional\",\"step1\":{\"school_type\":\"professional\",\"escuela_nombre\":\"Uruguay\",\"provincia\":\"\",\"canton\":\"\",\"ruc\":\"\",\"direccion_insitu\":\"\",\"direccion_ant\":\"\",\"direccion_sri\":\"\",\"gerente_nombre\":\"\",\"telefono_fijo\":\"\",\"telefono\":\"\",\"email\":\"\",\"resolucion_nro\":\"\",\"resoluciones_extra\":\"\",\"patente_municipal\":\"\",\"impuesto_predial\":\"\",\"permiso_bomberos\":\"\",\"permiso_sanitario\":\"\"}}', '2025-12-16 03:14:44'),
(25, 12, 'non_professional', 'Ecuador', '{\"school_type\":\"non_professional\",\"step1\":{\"school_type\":\"non_professional\",\"escuela_nombre\":\"Ecuador\",\"provincia\":\"\",\"canton\":\"\",\"ruc\":\"\",\"telefono_fijo\":\"\",\"telefono\":\"\",\"email\":\"\",\"gerente_nombre\":\"\",\"direccion_insitu\":\"\",\"direccion_ant\":\"\",\"direccion_sri\":\"\"},\"step2\":{\"resolucion_nro\":\"\",\"resoluciones_extra\":\"\",\"ruc\":\"\",\"estado_sri\":\"Activo\",\"gerente_nombre\":\"\",\"tipo_tenencia\":\"Propia\",\"impuesto_predial\":\"\",\"patente_municipal\":\"\"},\"step3\":[],\"step4\":{\"area_detalle\":[\"\"],\"rotulos_salida\":[\"\"],\"senales_incendios\":[\"\"]},\"step5\":{\"aul_num\":[\"\"],\"aul_tipo\":[\"\"],\"aul_res_func\":[\"\"],\"aul_cap\":[\"\"],\"aul_ventilacion\":[\"Si\"],\"aul_iluminacion\":[\"Si\"],\"aul_tec\":[\"Si\"],\"aul_pizarra\":[\"Si\"],\"aul_estacion\":[\"Si\"],\"aul_otro_mat\":[\"\"]},\"step6\":{\"taller_curso\":[\"\"],\"taller_existe\":[\"\"],\"plat_curso\":[\"\"],\"plat_existe\":[\"\"],\"plat_doc\":[\"\"],\"plat_matricula\":[\"Si\"],\"plat_asistencia_in\":[\"Si\"],\"plat_asistencia_out\":[\"Si\"],\"plat_calif\":[\"Si\"],\"plat_interaccion\":[\"Si\"],\"plat_eval\":[\"Si\"]},\"step7\":{\"pista_propiedad\":\"\",\"fechaarrendada\":\"\",\"pista_direccion\":\"\",\"pista_predio\":\"\",\"pista_ubikilome\":\"\",\"metros_pista\":\"\",\"tipo_cerramiento\":\"\",\"p_perimetrales\":\"\",\"postes_altura\":\"\",\"rampa_elevada\":\"\",\"rampa_material\":\"\",\"dispositivos_cono\":\"\",\"trave_num\":\"\",\"num_indi\":\"\"},\"step13\":{\"estudiantes\":[{\"nombres\":\"\",\"cedula\":\"\",\"nombre_oficio\":\"\",\"tipo_curso\":\"\",\"fichas_teoricas\":\"\",\"fichas_practicas\":\"\",\"fecha_nacimiento\":\"\",\"edad\":\"\",\"nivel_instruccion\":\"\",\"valoracion_psico\":\"\",\"valoracion_psicologica\":\"\",\"matricula\":\"\",\"emision_permiso\":\"\",\"jornadas\":\"\",\"materias\":[{\"materia\":\"\",\"calificaciones\":\"\",\"asistencia\":\"\",\"clase_tipo\":\"\",\"fecha_inicio\":\"\",\"fecha_fin\":\"\",\"estado\":\"\"}]}],\"costo_nombres\":[\"\"],\"costo_tipo_curso\":[\"\"],\"costo_num_factura\":[\"\"],\"costo_fecha_factura\":[\"\"],\"costo_valor_curso\":[\"\"],\"costo_valor_permiso\":[\"\"],\"costo_valor_examen\":[\"\"],\"cert_contiene_nombre\":\"\",\"cert_contiene_resolucion\":\"\",\"cert_contiene_domicilio\":\"\",\"cert_contiene_titulo\":\"\",\"cert_contiene_estudiante\":\"\",\"cert_contiene_fecha\":\"\",\"cert_contiene_categoria\":\"\",\"cert_contiene_tipo\":\"\",\"cert_contiene_firmas\":\"\",\"cert_contiene_lugar\":\"\",\"cert_contiene_calificacion\":\"\",\"camp_nro\":[\"\"],\"camp_fecha\":[\"\"],\"camp_beneficiarios\":[\"\"],\"camp_metodologia\":[\"\"],\"camp_temas\":[\"\"],\"camp_fecha_programacion\":[\"\"]}}', '2025-12-16 14:14:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `job_title`, `gender`, `password`, `role`, `created_at`) VALUES
(3, 'Administrador', '', '', 'male', '$2y$10$BS/7qx4juB/DENjijg.2De0UPOvcvrRFfOnSWau339EOM9uG9PKbG', 'admin', '2025-12-14 00:07:03'),
(12, 'Jonathan', 'Andrés Murillo', 'Contable', 'male', '$2y$10$onahz4Y4OEPadll8w.xzw.KxT.xm2LDXyGBsmLz6qk9yfVVt6KnUq', 'user', '2025-12-16 02:54:56'),
(13, 'Maria', 'María Quiñonez', 'Recepcionista', 'female', '$2y$10$y09JdVlbTxpsgf8ixqqxL.fiJdi7iY4hEWfRN3vHT6kzaw/282PXK', 'user', '2025-12-16 03:06:25');

-- --------------------------------------------------------

--
-- Table structure for table `user_progress`
--

CREATE TABLE `user_progress` (
  `user_id` int NOT NULL,
  `form_data` longtext COLLATE utf8mb4_unicode_ci,
  `last_step` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_progress`
--

INSERT INTO `user_progress` (`user_id`, `form_data`, `last_step`, `updated_at`) VALUES
(12, '{}', 0, '2025-12-16 14:14:35'),
(13, NULL, 0, '2025-12-16 03:40:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `edit_requests`
--
ALTER TABLE `edit_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports_archive`
--
ALTER TABLE `reports_archive`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `edit_requests`
--
ALTER TABLE `edit_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `reports_archive`
--
ALTER TABLE `reports_archive`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `edit_requests`
--
ALTER TABLE `edit_requests`
  ADD CONSTRAINT `edit_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports_archive`
--
ALTER TABLE `reports_archive`
  ADD CONSTRAINT `reports_archive_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
