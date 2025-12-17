
CREATE DATABASE IF NOT EXISTS `pte` 
USE `pte`;


CREATE TABLE IF NOT EXISTS `alternativas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `option_one` varchar(100) DEFAULT '120',
  `option_certa` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE IF NOT EXISTS `aulas` (
  `nome_aula` varchar(100) DEFAULT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  `id_modulo` int DEFAULT NULL,
  `excluido` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Aulas_Modulos_FK` (`id_modulo`),
  CONSTRAINT `Aulas_Modulos_FK` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE IF NOT EXISTS `midias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `desc_midia` varchar(100) DEFAULT NULL,
  `id_aula` int DEFAULT NULL,
  `conteudo` longblob,
  PRIMARY KEY (`id`),
  KEY `Midias_Aulas_FK` (`id_aula`),
  CONSTRAINT `Midias_Aulas_FK` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



CREATE TABLE IF NOT EXISTS `modulos` (
  `nome_modolu` varchar(100) DEFAULT NULL,
  `id_curso` int DEFAULT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `Modulos_treinamentos_FK` (`id_curso`),
  CONSTRAINT `Modulos_treinamentos_FK` FOREIGN KEY (`id_curso`) REFERENCES `treinamentos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(80) NOT NULL,
  `conteudo` varchar(255) NOT NULL,
  `data_noticia` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;




CREATE TABLE IF NOT EXISTS `treinamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `criado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE IF NOT EXISTS `use_treinamentos` (
  `matricula` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `id_curso` int NOT NULL,
  `status_curso` varchar(1) DEFAULT NULL,
  `data_curso` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_fim` datetime DEFAULT NULL,
  PRIMARY KEY (`matricula`) USING BTREE,
  KEY `id_usuario` (`id_usuario`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `use_treinamentos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `use_treinamentos_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `treinamentos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


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
) ENGINE=InnoDB AUTO_INCREMENT=229 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Copiando dados para a tabela pte.usuarios: ~1 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `email`, `senha`, `ativos`, `Foto`, `token`, `tipo`, `nome`, `acess`) VALUES
	(228, 'matheusaparecido779944@gmail.com', '$2y$10$kAUAwGqiUzqLUpTFHSbyXun2QB3q61qGM1gYUnTSWLvL6HhWqeCrG', 1, NULL, '595b693f674f56d49fbc8546b81f62f76ea0b4330c1e505f63b8dc493face111', '2', 'matheus', 1);




