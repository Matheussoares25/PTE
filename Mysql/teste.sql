

-- Copiando estrutura do banco de dados para pte
CREATE DATABASE IF NOT EXISTS `pte` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `pte`;

-- Copiando estrutura para tabela pte.alternativas
CREATE TABLE IF NOT EXISTS `alternativas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_questao` int DEFAULT NULL,
  `texto` text COLLATE utf8mb4_general_ci,
  `correta` tinyint DEFAULT NULL,
  `id_prova` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_alternativas_questoes` (`id_questao`),
  KEY `FK_alternativas_aulas` (`id_prova`),
  CONSTRAINT `FK_alternativas_aulas` FOREIGN KEY (`id_prova`) REFERENCES `aulas` (`id`),
  CONSTRAINT `FK_alternativas_questoes` FOREIGN KEY (`id_questao`) REFERENCES `questoes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.alternativas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.aulas
CREATE TABLE IF NOT EXISTS `aulas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_aula` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_modulo` int DEFAULT NULL,
  `excluido` tinyint DEFAULT NULL,
  `tipo` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Aulas_Modulos_FK` (`id_modulo`),
  CONSTRAINT `Aulas_Modulos_FK` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.aulas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.candidaturas
CREATE TABLE IF NOT EXISTS `candidaturas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `id_vaga` int DEFAULT NULL,
  `data_cand` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_vaga` (`id_vaga`),
  CONSTRAINT `FK_candidaturas_usuarios` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `FK_candidaturas_vagas` FOREIGN KEY (`id_vaga`) REFERENCES `vagas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.candidaturas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.midias
CREATE TABLE IF NOT EXISTS `midias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `desc_midia` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_aula` int DEFAULT NULL,
  `conteudo` longblob,
  `caminho_video` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Midias_Aulas_FK` (`id_aula`),
  CONSTRAINT `Midias_Aulas_FK` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.midias: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.modulos
CREATE TABLE IF NOT EXISTS `modulos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome_modolu` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_curso` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Modulos_treinamentos_FK` (`id_curso`),
  CONSTRAINT `Modulos_treinamentos_FK` FOREIGN KEY (`id_curso`) REFERENCES `treinamentos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.modulos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.noticias
CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `conteudo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `data_noticia` datetime DEFAULT NULL,
  `vaga` int DEFAULT NULL /*!80023 INVISIBLE */,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.noticias: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.progress
CREATE TABLE IF NOT EXISTS `progress` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_aula` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `assistido` int DEFAULT NULL,
  `data_assistida` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_aula_id_user` (`id_aula`,`id_user`),
  KEY `FK_progress_usuarios` (`id_user`),
  CONSTRAINT `FK_progress_aulas` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id`),
  CONSTRAINT `FK_progress_usuarios` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.progress: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.questoes
CREATE TABLE IF NOT EXISTS `questoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pergunta` text COLLATE utf8mb4_general_ci,
  `id_prova` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_questoes_aulas` (`id_prova`),
  CONSTRAINT `FK_questoes_aulas` FOREIGN KEY (`id_prova`) REFERENCES `aulas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.questoes: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.treinamentos
CREATE TABLE IF NOT EXISTS `treinamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `criado` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.treinamentos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.use_prova
CREATE TABLE IF NOT EXISTS `use_prova` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `id_prova` int DEFAULT NULL,
  `acertos` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.use_prova: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.use_treinamentos
CREATE TABLE IF NOT EXISTS `use_treinamentos` (
  `matricula` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_curso` int NOT NULL,
  `status_curso` varchar(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `data_curso` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_fim` datetime DEFAULT NULL,
  `modulo` int DEFAULT NULL,
  PRIMARY KEY (`matricula`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `use_treinamentos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `use_treinamentos_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `treinamentos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.use_treinamentos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(260) COLLATE utf8mb4_general_ci NOT NULL,
  `ativos` int DEFAULT NULL,
  `Foto` mediumblob,
  `token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipo` varchar(1) COLLATE utf8mb4_general_ci NOT NULL,
  `nome` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
  `acess` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=233 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.usuarios: ~2 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `email`, `senha`, `ativos`, `Foto`, `token`, `tipo`, `nome`, `acess`) VALUES
	(231, 'funcionario@email.com', '$2y$12$nqR1Y5NuTDqfDiIFFgwNCOsKfMwgWFXmxm8ugIimVn3WY4JAfzyCi', 1, NULL, '59dd1c448e21f8382502243f433e42d9d6ab1d7befe02ad61dfd3f8760b41596', '1', 'funcionario', 1);
INSERT INTO `usuarios` (`id`, `email`, `senha`, `ativos`, `Foto`, `token`, `tipo`, `nome`, `acess`) VALUES
	(232, 'pte@pte', '$2y$12$EjcoXphCxCsyJBDBKnmHEeRdz43mJtvmspQRc16I.kMyMcqGCPS3O', 1, NULL,NULL,'1','pte',1);

-- Copiando estrutura para tabela pte.vagas
CREATE TABLE IF NOT EXISTS `vagas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `conteudo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `data_vaga` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.vagas: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
