-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 20/03/2026 às 15:33
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `teste`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alternativas`
--

CREATE TABLE `alternativas` (
  `id` int(11) NOT NULL,
  `id_questao` int(11) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `correta` tinyint(4) DEFAULT NULL,
  `id_prova` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `aulas`
--

CREATE TABLE `aulas` (
  `nome_aula` varchar(100) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `excluido` tinyint(4) DEFAULT NULL,
  `tipo` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `midias`
--

CREATE TABLE `midias` (
  `id` int(11) NOT NULL,
  `desc_midia` varchar(100) DEFAULT NULL,
  `id_aula` int(11) DEFAULT NULL,
  `conteudo` longblob DEFAULT NULL,
  `caminho_video` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `modulos`
--

CREATE TABLE `modulos` (
  `id` int(11) NOT NULL,
  `nome_modolu` varchar(100) DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `noticias`
--

CREATE TABLE `noticias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(80) NOT NULL,
  `conteudo` varchar(255) NOT NULL,
  `data_noticia` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `progress`
--

CREATE TABLE `progress` (
  `id` int(11) NOT NULL,
  `id_aula` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `assistido` int(11) DEFAULT NULL,
  `data_assistida` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `questoes`
--

CREATE TABLE `questoes` (
  `id` int(11) NOT NULL,
  `pergunta` text DEFAULT NULL,
  `id_prova` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `treinamentos`
--

CREATE TABLE `treinamentos` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `criado` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `use_prova`
--

CREATE TABLE `use_prova` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_prova` int(11) DEFAULT NULL,
  `acertos` varchar(50) DEFAULT NULL,
  `data_conclusao` datetime DEFAULT NULL,
  `data_inicio` datetime DEFAULT NULL,
  `aprovado` int(11) DEFAULT NULL,
  `porcentagem` int(11) DEFAULT NULL,
  `qtd_questoes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `use_treinamentos`
--

CREATE TABLE `use_treinamentos` (
  `matricula` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `status_curso` varchar(1) DEFAULT NULL,
  `data_curso` datetime DEFAULT current_timestamp(),
  `data_fim` datetime DEFAULT NULL,
  `modulo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `senha` varchar(260) NOT NULL,
  `ativos` int(11) DEFAULT NULL,
  `Foto` mediumblob DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `tipo` varchar(1) NOT NULL,
  `nome` varchar(80) NOT NULL,
  `acess` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `email`, `senha`, `ativos`, `Foto`, `token`, `tipo`, `nome`, `acess`) VALUES
(228, 'pte@Pte', '$2y$10$kAUAwGqiUzqLUpTFHSbyXun2QB3q61qGM1gYUnTSWLvL6HhWqeCrG', 1, NULL, '2a0ba1ab5824f8a78fd104a78a4b71588fa7876e4143540955bea4c78d327efe', '2', 'matheus', 2),
(231, 'funcionario@email.com', '$2y$12$nqR1Y5NuTDqfDiIFFgwNCOsKfMwgWFXmxm8ugIimVn3WY4JAfzyCi', 1, NULL, '59dd1c448e21f8382502243f433e42d9d6ab1d7befe02ad61dfd3f8760b41596', '1', 'funcionario', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alternativas`
--
ALTER TABLE `alternativas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_questao` (`id_questao`),
  ADD KEY `FK_alternativas_aulas` (`id_prova`) USING BTREE;

--
-- Índices de tabela `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Aulas_Modulos_FK` (`id_modulo`);

--
-- Índices de tabela `midias`
--
ALTER TABLE `midias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Midias_Aulas_FK` (`id_aula`);

--
-- Índices de tabela `modulos`
--
ALTER TABLE `modulos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Modulos_treinamentos_FK` (`id_curso`);

--
-- Índices de tabela `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `progress`
--
ALTER TABLE `progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_aula_id_user` (`id_aula`,`id_user`),
  ADD KEY `id_aula` (`id_aula`),
  ADD KEY `id_user` (`id_user`);

--
-- Índices de tabela `questoes`
--
ALTER TABLE `questoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_prova` (`id_prova`);

--
-- Índices de tabela `treinamentos`
--
ALTER TABLE `treinamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `use_prova`
--
ALTER TABLE `use_prova`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_use_prova_usuarios` (`id_user`),
  ADD KEY `FK_use_prova_aulas` (`id_prova`);

--
-- Índices de tabela `use_treinamentos`
--
ALTER TABLE `use_treinamentos`
  ADD PRIMARY KEY (`matricula`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alternativas`
--
ALTER TABLE `alternativas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `midias`
--
ALTER TABLE `midias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `modulos`
--
ALTER TABLE `modulos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `progress`
--
ALTER TABLE `progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `questoes`
--
ALTER TABLE `questoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `treinamentos`
--
ALTER TABLE `treinamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `use_prova`
--
ALTER TABLE `use_prova`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `use_treinamentos`
--
ALTER TABLE `use_treinamentos`
  MODIFY `matricula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `alternativas`
--
ALTER TABLE `alternativas`
  ADD CONSTRAINT `FK_alternativas_aulas` FOREIGN KEY (`id_prova`) REFERENCES `aulas` (`id`),
  ADD CONSTRAINT `FK_alternativas_questoes` FOREIGN KEY (`id_questao`) REFERENCES `questoes` (`id`);

--
-- Restrições para tabelas `aulas`
--
ALTER TABLE `aulas`
  ADD CONSTRAINT `Aulas_Modulos_FK` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id`);

--
-- Restrições para tabelas `midias`
--
ALTER TABLE `midias`
  ADD CONSTRAINT `Midias_Aulas_FK` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id`);

--
-- Restrições para tabelas `modulos`
--
ALTER TABLE `modulos`
  ADD CONSTRAINT `Modulos_treinamentos_FK` FOREIGN KEY (`id_curso`) REFERENCES `treinamentos` (`id`);

--
-- Restrições para tabelas `progress`
--
ALTER TABLE `progress`
  ADD CONSTRAINT `FK_progress_aulas` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id`),
  ADD CONSTRAINT `FK_progress_usuarios` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `questoes`
--
ALTER TABLE `questoes`
  ADD CONSTRAINT `FK_questoes_aulas` FOREIGN KEY (`id_prova`) REFERENCES `aulas` (`id`);

--
-- Restrições para tabelas `use_prova`
--
ALTER TABLE `use_prova`
  ADD CONSTRAINT `FK_use_prova_aulas` FOREIGN KEY (`id_prova`) REFERENCES `aulas` (`id`),
  ADD CONSTRAINT `FK_use_prova_usuarios` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `use_treinamentos`
--
ALTER TABLE `use_treinamentos`
  ADD CONSTRAINT `use_treinamentos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `use_treinamentos_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `treinamentos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
