-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 19/03/2025 às 17:20
-- Versão do servidor: 9.1.0
-- Versão do PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ncom_copy_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `processo_id` int NOT NULL,
  `valor_consumo` decimal(15,2) DEFAULT NULL,
  `valor_permanente` decimal(15,2) DEFAULT NULL,
  `valor_servico` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `processo_id` (`processo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `processo_id`, `valor_consumo`, `valor_permanente`, `valor_servico`, `created_at`, `updated_at`) VALUES
(9, 34, 500.00, 600.00, 700.00, '2025-02-28 17:16:06', '2025-02-28 17:16:06'),
(10, 35, 500.00, 100.00, 700.00, '2025-03-10 16:58:53', '2025-03-10 16:58:53'),
(11, 36, 500.00, 100.00, 700.00, '2025-03-17 18:27:12', '2025-03-17 18:27:12'),
(12, 37, 500.00, 100.00, 700.00, '2025-03-19 17:07:55', '2025-03-19 17:07:55'),
(13, 38, 500.00, 20.00, 700.00, '2025-03-19 17:11:53', '2025-03-19 17:11:53'),
(14, 39, 500.00, 20.00, 700.00, '2025-03-19 17:19:15', '2025-03-19 17:19:15'),
(15, 40, 500.00, 20.00, 700.00, '2025-03-19 17:20:44', '2025-03-19 17:20:44'),
(16, 41, 500.00, 100.00, 60.00, '2025-03-19 20:27:24', '2025-03-19 20:27:24');

-- --------------------------------------------------------

--
-- Estrutura para tabela `contratos`
--

DROP TABLE IF EXISTS `contratos`;
CREATE TABLE IF NOT EXISTS `contratos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `processo_id` int NOT NULL,
  `numero_contrato` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor_contrato` decimal(10,2) NOT NULL,
  `data_inicial_contrato` date NOT NULL,
  `data_final_contrato` date NOT NULL,
  `observacoes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `nome_empresa_contrato` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnpj_contrato` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_telefone_contrato` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_contratos_processos` (`processo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `contratos`
--

INSERT INTO `contratos` (`id`, `processo_id`, `numero_contrato`, `valor_contrato`, `data_inicial_contrato`, `data_final_contrato`, `observacoes`, `created_at`, `updated_at`, `nome_empresa_contrato`, `cnpj_contrato`, `numero_telefone_contrato`) VALUES
(12, 34, '27575753', 5.00, '2025-02-28', '2025-03-07', 'asdfasdasdfasd', '2025-02-28 17:16:06', '2025-02-28 17:16:06', NULL, NULL, NULL),
(13, 37, '27575753', 5.00, '2025-03-19', '2025-03-29', 'obs teste contrato', '2025-03-19 17:07:55', '2025-03-19 17:07:55', NULL, NULL, NULL),
(14, 38, '27575753', 600.00, '2025-03-19', '2025-03-22', 'testestestestestes', '2025-03-19 17:11:53', '2025-03-19 17:11:53', NULL, NULL, NULL),
(15, 39, '27575753', 5.00, '2025-03-19', '2025-03-21', NULL, '2025-03-19 17:19:15', '2025-03-19 17:19:15', 'RODRIGO THAUÃ', '6546546546546545', '565465465465465454'),
(16, 40, '27575753', 600000.00, '2025-03-19', '2025-03-21', NULL, '2025-03-19 17:20:45', '2025-03-19 17:20:45', 'RODRIGO THAUÃ', '6546546546546545', '565465465465465454'),
(17, 40, '654654654654', 700.00, '2025-03-19', '2025-03-21', NULL, '2025-03-19 17:20:45', '2025-03-19 17:20:45', 'PEDRO HENRIQUE', '65459+84951651', '123549687');

-- --------------------------------------------------------

--
-- Estrutura para tabela `detalhes_despesa`
--

DROP TABLE IF EXISTS `detalhes_despesa`;
CREATE TABLE IF NOT EXISTS `detalhes_despesa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categorias_id` int NOT NULL,
  `pa_consumo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pa_permanente` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pa_servico` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nd_consumo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nd_permanente` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nd_servico` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `categorias_processo_id` (`categorias_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `detalhes_despesa`
--

INSERT INTO `detalhes_despesa` (`id`, `categorias_id`, `pa_consumo`, `pa_permanente`, `pa_servico`, `nd_consumo`, `nd_permanente`, `nd_servico`, `created_at`, `updated_at`) VALUES
(3, 9, '1.1.11.11', '2.2.22.22', '3.3.33.33', '1212341', '12341234123', '567857857856', '2025-02-28 17:16:06', '2025-02-28 17:16:06'),
(4, 10, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-10 16:58:53', '2025-03-10 16:58:53'),
(5, 11, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-17 18:27:12', '2025-03-17 18:27:12'),
(6, 12, '456789', '654987', '456321', '2.2.22.22', '1.1.11.11', '3.3.33.33', '2025-03-19 17:07:55', '2025-03-19 17:07:55'),
(7, 13, '456789', '654987', '456321', '1.1.11.11', '2.2.22.22', '3.3.33.33', '2025-03-19 17:11:53', '2025-03-19 17:11:53'),
(8, 14, '456789', '654987', '456321', '1.1.11.11', '2.2.22.22', '3.3.33.33', '2025-03-19 17:19:15', '2025-03-19 17:19:15'),
(9, 15, '456789', '654987', '456321', '1.1.11.11', '2.2.22.22', '3.3.33.33', '2025-03-19 17:20:45', '2025-03-19 17:20:45'),
(10, 16, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-19 20:27:25', '2025-03-19 20:27:25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `processos`
--

DROP TABLE IF EXISTS `processos`;
CREATE TABLE IF NOT EXISTS `processos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_processo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `requisitante` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_entrada` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modalidade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `procedimentos_auxiliares` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_processo` (`numero_processo`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `processos`
--

INSERT INTO `processos` (`id`, `numero_processo`, `descricao`, `requisitante`, `data_entrada`, `created_at`, `updated_at`, `modalidade`, `procedimentos_auxiliares`) VALUES
(34, '12131.231', 'asdfasdfa', 'GELOG', '2025-02-28', '2025-02-28 17:16:06', '2025-02-28 17:16:06', 'CONCORRÊNCIA', 'SISTEMA DE REGISTRO DE PREÇÕS'),
(35, '12142.341234/123', 'adfadfasd', 'GECONV', '2025-03-10', '2025-03-10 16:58:53', '2025-03-10 16:58:53', NULL, NULL),
(36, '4565.456465/4654-6', 'teste 11920', 'GELOG', '2025-03-17', '2025-03-17 18:27:12', '2025-03-17 18:27:12', NULL, NULL),
(37, '1214.234123/4123-45', 'teste novo contrato', 'FUNESP', '2025-03-19', '2025-03-19 17:07:55', '2025-03-19 17:07:55', 'PREGÃO', 'PRÉ-QUALIFICADO'),
(38, '9575.743565/4654-65', 'novo testeeeee', 'GAVE', '2025-03-19', '2025-03-19 17:11:53', '2025-03-19 17:11:53', 'CONCORRÊNCIA', 'PRÉ-QUALIFICADO'),
(39, '1214.234123/4123-65', 'asdfasdfasdfas', 'GECONV', '2025-03-19', '2025-03-19 17:19:15', '2025-03-19 17:19:15', 'PREGÃO', 'PRÉ-QUALIFICADO'),
(40, '1214.234123/4123-', 'teste + de 1', 'GELOG', '2025-03-19', '2025-03-19 17:20:44', '2025-03-19 17:20:44', 'CONCORRÊNCIA', 'PROCEDIMENTO DE MANIFESTAÇÃO DE INTERESSE'),
(41, '6546.545564/4665-', 'dghdghdfg', 'GETEC', '2025-03-19', '2025-03-19 20:27:24', '2025-03-19 20:27:24', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Rodrigo Thauã', 'rodrigothaua@gmail.com', NULL, '$2y$10$2wmchkx7PQiJ/fB4P4wJqebOJqgtnEHoprtusWjqvjXjdQS/Oeuce', NULL, NULL, NULL),
(2, 'Teste', 'teste@email.com', NULL, '$2y$10$xP3Ma5no6quo3WNQ.NcxterkrvH6usz3KVFrdSwiq8pkwCDN3//Yq', NULL, '2025-02-18 17:52:57', '2025-02-18 17:52:57');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
