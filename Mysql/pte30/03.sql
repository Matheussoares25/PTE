CREATE DATABASE IF NOT EXISTS `pte` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `pte`;

-- 1️⃣ Tabelas base (sem dependência)
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) NOT NULL,
  `senha` varchar(260) NOT NULL,
  `ativos` int(11) DEFAULT NULL,
  `Foto` mediumblob DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `tipo` varchar(1) NOT NULL,
  `nome` varchar(80) NOT NULL,
  `acess` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `usuarios` (`id`, `email`, `senha`, `ativos`, `Foto`, `token`, `tipo`, `nome`, `acess`) VALUES
(231, 'funcionario@email.com', '$2y$12$nqR1Y5NuTDqfDiIFFgwNCOsKfMwgWFXmxm8ugIimVn3WY4JAfzyCi', 1, NULL, 'c22eb9f8c94a4b7de3e831a6373b054975867898a12a1df13f989884c491db7f', '1', 'Funcionario pereiroa', 1),
(232, 'pte@pte', '$2y$12$EjcoXphCxCsyJBDBKnmHEeRdz43mJtvmspQRc16I.kMyMcqGCPS3O', 1, NULL, '6c1e2fb9ec18644bcb95e5b81be18cfabf5085234ec2db4eeab32ff7e5bee6b6', '2', 'pte', 1);


CREATE TABLE IF NOT EXISTS `treinamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `criado` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `vagas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(80) NOT NULL,
  `conteudo` varchar(255) NOT NULL,
  `data_vaga` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(80) NOT NULL,
  `conteudo` varchar(255) NOT NULL,
  `data_noticia` datetime DEFAULT NULL,
  `vaga` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- 2️⃣ Dependem de treinamentos
CREATE TABLE IF NOT EXISTS `modulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_modolu` varchar(100) DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Modulos_treinamentos_FK` (`id_curso`),
  CONSTRAINT `Modulos_treinamentos_FK` FOREIGN KEY (`id_curso`) REFERENCES `treinamentos` (`id`)
) ENGINE=InnoDB;

-- 3️⃣ Dependem de modulos
CREATE TABLE IF NOT EXISTS `aulas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_aula` varchar(100) DEFAULT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `excluido` tinyint(4) DEFAULT NULL,
  `tipo` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Aulas_Modulos_FK` (`id_modulo`),
  CONSTRAINT `Aulas_Modulos_FK` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id`)
) ENGINE=InnoDB;

-- 4️⃣ Dependem de aulas
CREATE TABLE IF NOT EXISTS `questoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pergunta` text DEFAULT NULL,
  `id_prova` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_questoes_aulas` (`id_prova`),
  CONSTRAINT `FK_questoes_aulas` FOREIGN KEY (`id_prova`) REFERENCES `aulas` (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `midias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desc_midia` varchar(100) DEFAULT NULL,
  `id_aula` int(11) DEFAULT NULL,
  `conteudo` longblob DEFAULT NULL,
  `caminho_video` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Midias_Aulas_FK` (`id_aula`),
  CONSTRAINT `Midias_Aulas_FK` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `notas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_prova` int(11) DEFAULT NULL,
  `id_aluno` int(11) DEFAULT NULL,
  `nota` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_prova` (`id_prova`),
  KEY `id_aluno` (`id_aluno`),
  CONSTRAINT `FK_notas_aulas` FOREIGN KEY (`id_prova`) REFERENCES `aulas` (`id`),
  CONSTRAINT `FK_notas_usuarios` FOREIGN KEY (`id_aluno`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `use_prova` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_prova` int(11) DEFAULT NULL,
  `acertos` varchar(50) DEFAULT NULL,
  `data_conclusao` datetime DEFAULT NULL,
  `data_inicio` datetime DEFAULT NULL,
  `aprovado` int(11) DEFAULT NULL,
  `porcentagem` int(11) DEFAULT NULL,
  `qtd_questoes` int(11) DEFAULT NULL,
  `nota` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_use_prova_usuarios` (`id_user`),
  KEY `FK_use_prova_aulas` (`id_prova`),
  CONSTRAINT `FK_use_prova_aulas` FOREIGN KEY (`id_prova`) REFERENCES `aulas` (`id`),
  CONSTRAINT `FK_use_prova_usuarios` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB;

-- 5️⃣ Dependem de questoes + aulas
CREATE TABLE IF NOT EXISTS `alternativas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_questao` int(11) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `correta` tinyint(4) DEFAULT NULL,
  `id_prova` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_alternativas_questoes` (`id_questao`),
  KEY `FK_alternativas_aulas` (`id_prova`),
  CONSTRAINT `FK_alternativas_aulas` FOREIGN KEY (`id_prova`) REFERENCES `aulas` (`id`),
  CONSTRAINT `FK_alternativas_questoes` FOREIGN KEY (`id_questao`) REFERENCES `questoes` (`id`)
) ENGINE=InnoDB;

-- 6️⃣ Dependem de usuarios + vagas
CREATE TABLE IF NOT EXISTS `candidaturas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_vaga` int(11) DEFAULT NULL,
  `data_cand` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_vaga` (`id_vaga`),
  CONSTRAINT `FK_candidaturas_usuarios` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `FK_candidaturas_vagas` FOREIGN KEY (`id_vaga`) REFERENCES `vagas` (`id`)
) ENGINE=InnoDB;

-- 7️⃣ Dependem de usuarios + aulas
CREATE TABLE IF NOT EXISTS `progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_aula` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `assistido` int(11) DEFAULT NULL,
  `data_assistida` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_aula_id_user` (`id_aula`,`id_user`),
  KEY `FK_progress_usuarios` (`id_user`),
  CONSTRAINT `FK_progress_aulas` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id`),
  CONSTRAINT `FK_progress_usuarios` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB;

-- 8️⃣ Dependem de usuarios + treinamentos
CREATE TABLE IF NOT EXISTS `use_treinamentos` (
  `matricula` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `status_curso` varchar(1) DEFAULT NULL,
  `data_curso` datetime DEFAULT current_timestamp(),
  `data_fim` datetime DEFAULT NULL,
  `modulo` int(11) DEFAULT NULL,
  PRIMARY KEY (`matricula`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `use_treinamentos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `use_treinamentos_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `treinamentos` (`id`)
) ENGINE=InnoDB;