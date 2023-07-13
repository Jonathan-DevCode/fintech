-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 186.202.152.79
-- Generation Time: 16-Maio-2023 às 15:57
-- Versão do servidor: 5.7.32-35-log
-- PHP Version: 5.6.40-0+deb8u12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fintech_2022`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_login` varchar(255) DEFAULT NULL,
  `admin_senha` varchar(255) DEFAULT NULL,
  `admin_nome` varchar(255) DEFAULT NULL,
  `admin_permissao` int(11) DEFAULT NULL,
  `admin_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_login`, `admin_senha`, `admin_nome`, `admin_permissao`, `admin_created`, `admin_updated`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrador 01', 1, '2022-07-19 13:29:20', '2022-07-19 13:29:20'),
(3, 'marcos', 'c5e3539121c4944f2bbe097b425ee774', 'marcos', 2, '2022-08-10 20:55:15', '2022-08-10 20:55:15');

-- --------------------------------------------------------

--
-- Estrutura da tabela `config`
--

CREATE TABLE `config` (
  `config_id` int(11) NOT NULL,
  `config_site_status` int(1) DEFAULT NULL,
  `config_site_label_desativado` text,
  `config_qtd_fintechs_segunda_etapa` int(11) DEFAULT NULL,
  `config_etapa_atual` int(11) DEFAULT NULL,
  `config_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `config_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `config`
--

INSERT INTO `config` (`config_id`, `config_site_status`, `config_site_label_desativado`, `config_qtd_fintechs_segunda_etapa`, `config_etapa_atual`, `config_created`, `config_updated`) VALUES
(1, 1, 'Ambiente desativado! Esse processo foi concluído. Obrigado.', 12, 1, '2022-07-19 13:29:20', '2022-11-08 20:14:52');

-- --------------------------------------------------------

--
-- Estrutura da tabela `criterio`
--

CREATE TABLE `criterio` (
  `criterio_id` int(11) NOT NULL,
  `criterio_nome` text,
  `criterio_descricao` text,
  `criterio_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `criterio_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `criterio`
--

INSERT INTO `criterio` (`criterio_id`, `criterio_nome`, `criterio_descricao`, `criterio_created`, `criterio_updated`) VALUES
(2, 'Experiência do Cliente', 'Qual necessidade do cliente é atendida? Como o projeto oferece uma grande experiência ao cliente?\r\n', '2022-07-16 03:45:12', '2022-07-19 13:30:49'),
(4, 'Audácia', 'A Tecnologia oferece soluções para grandes problemas? Qual tipo de problema ou área de oportunidade a tecnologia alcança? ', '2022-07-16 03:54:29', '2022-07-19 13:30:49'),
(5, 'Inovação e Simplicidade', 'A Tecnologia é inovadora? \r\nA Tecnologia simplifica um processo complexo?', '2022-07-16 03:54:45', '2022-07-19 13:30:49'),
(6, 'Escalabilidade', 'O projeto tem possibilidade de crescimento/futuro? Qual o prazo esperado de retorno? ', '2022-07-16 03:54:55', '2022-07-19 13:30:49'),
(7, 'Sinergia com as Instituições Financeiras', 'A solução pode ser utilizada por Instituições Financeiras e pelos clientes que fazem parte de seus ecossistemas e dos de seus parceiros? A solução agrega valor a produtos e serviços oferecidos pelas Instituições Financeiras e as empresas com os quais se relacionam?', '2022-07-16 03:56:08', '2022-07-23 14:18:05');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fintech`
--

CREATE TABLE `fintech` (
  `fintech_id` int(11) NOT NULL,
  `fintech_nome` text,
  `fintech_descricao` text,
  `fintech_ordem` int(11) DEFAULT NULL,
  `fintech_status` int(1) NOT NULL DEFAULT '1',
  `fintech_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fintech_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `fintech`
--

INSERT INTO `fintech` (`fintech_id`, `fintech_nome`, `fintech_descricao`, `fintech_ordem`, `fintech_status`, `fintech_created`, `fintech_updated`) VALUES
(1, '4KST', '4KST', 1, 1, '2022-07-20 14:16:19', '2022-07-20 14:16:19'),
(2, 'AKROPOLI', 'AKROPOLI', 1, 1, '2022-07-20 14:16:50', '2022-07-20 14:16:50'),
(3, 'AVALIA SYSTEMS', '<p><b><span style=\"font-family: Arial;\">(Não&nbsp;</span><span style=\"font-size: 16px; font-family: Arial;\">vai concorrer)</span></b></p>', 3, 0, '2022-07-20 14:17:26', '2022-07-20 16:45:23'),
(4, 'BELVO', 'BELVO', 4, 1, '2022-07-20 14:17:54', '2022-07-20 14:17:54'),
(5, 'CASHU', 'CASHU', 5, 1, '2022-07-20 14:18:18', '2022-07-20 14:18:18'),
(7, 'DATARISK', 'DATARISK', 7, 1, '2022-07-20 14:19:18', '2022-07-20 14:19:18'),
(8, 'DATA RUDDER DECISAO POR DADOS LTDA', 'DATA RUDDER DECISAO POR DADOS LTDA', 8, 1, '2022-07-20 14:20:26', '2022-07-20 14:20:26'),
(9, 'DILETTA', 'DILETTA', 9, 1, '2022-07-20 14:20:51', '2022-07-20 14:20:51'),
(10, 'GAT INFOSEC', 'GAT INFOSEC', 10, 1, '2022-07-20 14:21:13', '2022-07-20 14:21:13'),
(11, 'GYRAMAIS', 'GYRAMAIS', 11, 1, '2022-07-20 14:22:12', '2022-07-20 14:22:12'),
(12, 'GORILA DESENVOLVIMENTO E CUSTOMIZAÇÃO DE SOFTWARE DE INVESTIMENTOS LTDA', '<b><span style=\"font-family: Arial;\">(Não vai concorrer)</span></b>', 12, 0, '2022-07-20 14:22:47', '2022-07-20 16:46:03'),
(13, 'I9 DC', '<span style=\"font-family: Arial;\"><b>(Não vai concorrer)</b></span>', 13, 0, '2022-07-20 14:23:20', '2022-07-20 16:46:33'),
(14, 'INNVO', 'INNVO', 14, 1, '2022-07-20 14:23:52', '2022-07-20 14:23:52'),
(15, 'MINTECH', 'MINTECH', 15, 1, '2022-07-20 14:24:19', '2022-07-20 14:24:19'),
(16, 'NUVIDIO', 'NUVIDIO', 16, 1, '2022-07-20 14:26:09', '2022-07-20 14:26:09'),
(17, 'PO27', '<span style=\"font-size: 16px; font-weight: bolder; color: rgb(33, 37, 41); font-family: Arial; white-space: nowrap; background-color: rgba(0, 0, 0, 0.075);\">(Não vai concorrer)</span><br>', 17, 0, '2022-07-20 14:26:36', '2022-08-01 19:31:48'),
(18, 'PLUGGY', 'PLUGGY', 18, 1, '2022-07-20 14:27:03', '2022-07-20 14:27:03'),
(19, 'QUASH', 'QUASH', 19, 1, '2022-07-20 14:27:29', '2022-07-20 14:27:29'),
(20, 'RESILIENCE X', '<span style=\"font-size: 16px; font-weight: bolder; color: rgb(33, 37, 41); font-family: Arial; white-space: nowrap; background-color: rgba(0, 0, 0, 0.075);\">(Não vai concorrer)</span><br>', 20, 0, '2022-07-20 14:28:03', '2022-08-01 19:32:03');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fintech_finalista`
--

CREATE TABLE `fintech_finalista` (
  `fintech_finalista_id` int(11) NOT NULL,
  `fintech_finalista_id_anterior` int(11) DEFAULT NULL,
  `fintech_finalista_nome` text,
  `fintech_finalista_descricao` text,
  `fintech_finalista_ordem` int(11) DEFAULT NULL,
  `fintech_finalista_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fintech_finalista_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `jurado`
--

CREATE TABLE `jurado` (
  `jurado_id` int(11) NOT NULL,
  `jurado_nome` varchar(255) DEFAULT NULL,
  `jurado_login` varchar(255) DEFAULT NULL,
  `jurado_senha` varchar(255) DEFAULT NULL,
  `jurado_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `jurado_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `jurado`
--

INSERT INTO `jurado` (`jurado_id`, `jurado_nome`, `jurado_login`, `jurado_senha`, `jurado_created`, `jurado_updated`) VALUES
(1, 'Jurado1', 'Jurado1', '827ccb0eea8a706c4c34a16891f84e7b', '2022-07-20 14:32:53', '2022-09-12 11:19:33'),
(3, 'Jurado3', 'Jurado3', '202cb962ac59075b964b07152d234b70', '2022-07-20 16:49:51', '2022-07-20 16:49:51'),
(4, 'Jurado4', 'Jurado4', '202cb962ac59075b964b07152d234b70', '2022-07-20 16:50:27', '2022-07-20 16:50:27'),
(6, 'Jurado6', 'Jurado6', '202cb962ac59075b964b07152d234b70', '2022-08-02 15:24:46', '2022-08-02 15:24:46'),
(7, 'Jurado7', 'Jurado7', '202cb962ac59075b964b07152d234b70', '2022-08-02 15:25:09', '2022-08-02 15:25:09'),
(8, 'Jurado8', 'Jurado8', '202cb962ac59075b964b07152d234b70', '2022-08-02 15:25:32', '2022-08-02 15:25:32'),
(9, 'Jurado9', 'Jurado9', '202cb962ac59075b964b07152d234b70', '2022-08-02 15:25:54', '2022-08-02 15:25:54'),
(10, 'Jurado10', 'Jurado10', '202cb962ac59075b964b07152d234b70', '2022-08-02 15:26:18', '2022-08-02 15:26:18'),
(11, 'Jurado2', 'Jurado2', '827ccb0eea8a706c4c34a16891f84e7b', '2022-08-10 09:26:30', '2022-09-12 11:19:58'),
(12, 'Jurado5', 'Jurado5', '202cb962ac59075b964b07152d234b70', '2022-08-10 09:27:15', '2022-08-10 09:27:15');

-- --------------------------------------------------------

--
-- Estrutura da tabela `voto`
--

CREATE TABLE `voto` (
  `voto_id` int(11) NOT NULL,
  `voto_jurado_id` int(11) DEFAULT NULL,
  `voto_fintech_id` int(11) DEFAULT NULL,
  `voto_criterio_id` int(11) DEFAULT NULL,
  `voto_nota` decimal(10,2) DEFAULT NULL,
  `voto_etapa` int(1) DEFAULT '1',
  `voto_comentario` text,
  `voto_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `voto_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`config_id`);

--
-- Indexes for table `criterio`
--
ALTER TABLE `criterio`
  ADD PRIMARY KEY (`criterio_id`);

--
-- Indexes for table `fintech`
--
ALTER TABLE `fintech`
  ADD PRIMARY KEY (`fintech_id`);

--
-- Indexes for table `fintech_finalista`
--
ALTER TABLE `fintech_finalista`
  ADD PRIMARY KEY (`fintech_finalista_id`);

--
-- Indexes for table `jurado`
--
ALTER TABLE `jurado`
  ADD PRIMARY KEY (`jurado_id`);

--
-- Indexes for table `voto`
--
ALTER TABLE `voto`
  ADD PRIMARY KEY (`voto_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `config_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `criterio`
--
ALTER TABLE `criterio`
  MODIFY `criterio_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `fintech`
--
ALTER TABLE `fintech`
  MODIFY `fintech_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `fintech_finalista`
--
ALTER TABLE `fintech_finalista`
  MODIFY `fintech_finalista_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurado`
--
ALTER TABLE `jurado`
  MODIFY `jurado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `voto`
--
ALTER TABLE `voto`
  MODIFY `voto_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
