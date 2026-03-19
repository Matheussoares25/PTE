CREATE DATABASE IF NOT EXISTS `pte`;
USE `pte`;

CREATE TABLE IF NOT EXISTS `treinamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `criado` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(250) NOT NULL,
  `senha` varchar(260) NOT NULL,
  `ativos` int DEFAULT NULL,
  `Foto` mediumblob,
  `token` varchar(255) DEFAULT NULL,
  `tipo` varchar(1) NOT NULL,
  `nome` varchar(80) NOT NULL,
  `acess` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `modulos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_modolu` varchar(100) DEFAULT NULL,
  `id_curso` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Modulos_treinamentos_FK` (`id_curso`),
  CONSTRAINT `Modulos_treinamentos_FK` FOREIGN KEY (`id_curso`) REFERENCES `treinamentos` (`id`)
);

CREATE TABLE IF NOT EXISTS `aulas` (
  `nome_aula` varchar(100) DEFAULT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  `id_modulo` int DEFAULT NULL,
  `excluido` tinyint DEFAULT NULL,
  `tipo` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Aulas_Modulos_FK` (`id_modulo`),
  CONSTRAINT `Aulas_Modulos_FK` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id`)
);

CREATE TABLE IF NOT EXISTS `questoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pergunta` text,
  `id_prova` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_prova` (`id_prova`),
  CONSTRAINT `FK_questoes_aulas` FOREIGN KEY (`id_prova`) REFERENCES `aulas` (`id`)
);

CREATE TABLE IF NOT EXISTS `alternativas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_questao` int DEFAULT NULL,
  `texto` text,
  `correta` tinyint DEFAULT NULL,
  `id_prova` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_questao` (`id_questao`),
  KEY `FK_alternativas_aulas` (`id_prova`) USING BTREE,
  CONSTRAINT `FK_alternativas_aulas` FOREIGN KEY (`id_prova`) REFERENCES `aulas` (`id`),
  CONSTRAINT `FK_alternativas_questoes` FOREIGN KEY (`id_questao`) REFERENCES `questoes` (`id`)
);

CREATE TABLE IF NOT EXISTS `midias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `desc_midia` varchar(100) DEFAULT NULL,
  `id_aula` int DEFAULT NULL,
  `conteudo` longblob,
  `caminho_video` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Midias_Aulas_FK` (`id_aula`),
  CONSTRAINT `Midias_Aulas_FK` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id`)
);

CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(80) NOT NULL,
  `conteudo` varchar(255) NOT NULL,
  `data_noticia` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `progress` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_aula` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `assistido` int DEFAULT NULL,
  `data_assistida` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_aula_id_user` (`id_aula`,`id_user`),
  KEY `id_aula` (`id_aula`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `FK_progress_aulas` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id`),
  CONSTRAINT `FK_progress_usuarios` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`)
) ;

CREATE TABLE IF NOT EXISTS `use_prova` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `id_prova` int DEFAULT NULL,
  `acertos` varchar(50) DEFAULT NULL,
  `data_conclusao` datetime DEFAULT NULL,
  `data_inicio` datetime DEFAULT NULL,
  `aprovado` int DEFAULT NULL,
  `porcentagem` int DEFAULT NULL,
  `qtd_questoes` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_use_prova_usuarios` (`id_user`),
  KEY `FK_use_prova_aulas` (`id_prova`),
  CONSTRAINT `FK_use_prova_aulas` FOREIGN KEY (`id_prova`) REFERENCES `aulas` (`id`),
  CONSTRAINT `FK_use_prova_usuarios` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`)
) ;

CREATE TABLE IF NOT EXISTS `use_treinamentos` (
  `matricula` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_curso` int NOT NULL,
  `status_curso` varchar(1) DEFAULT NULL,
  `data_curso` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_fim` datetime DEFAULT NULL,
  `modulo` int DEFAULT NULL,
  PRIMARY KEY (`matricula`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `use_treinamentos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `use_treinamentos_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `treinamentos` (`id`)
);

INSERT INTO `usuarios` (`id`, `email`, `senha`, `ativos`, `Foto`, `token`, `tipo`, `nome`, `acess`) VALUES
(228, 'matheusaparecido779944@gmail.com', '$2y$10$kAUAwGqiUzqLUpTFHSbyXun2QB3q61qGM1gYUnTSWLvL6HhWqeCrG', 1, NULL, 'b0038777b9845ec702a46614b10d93cc3401df0ba8e351820f30d259cf4cca0a', '2', 'matheus', 2),
(231, 'funcionario@email.com', '$2y$12$nqR1Y5NuTDqfDiIFFgwNCOsKfMwgWFXmxm8ugIimVn3WY4JAfzyCi', 1, NULL, '0e91b4b3f158d8caed92af238aea3c31c6c99e7645dce54efb6a4db35dbc8e9a', '1', 'funcionario', 1);