
CREATE DATABASE IF NOT EXISTS `pte` 
USE `pte`;


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


INSERT INTO `usuarios` (`id`, `email`, `senha`, `ativos`, `Foto`, `token`, `tipo`, `nome`, `acess`) VALUES
	(228, 'matheusaparecido779944@gmail.com', '$2y$10$kAUAwGqiUzqLUpTFHSbyXun2QB3q61qGM1gYUnTSWLvL6HhWqeCrG', 1, NULL, '2db708d29f2c97a6a55d6e3ee861a7149dec778e9cb8319dbfe527ccea9fac34', '1', 'matheus', 2);



-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.0.45 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.14.0.7165
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para pte
CREATE DATABASE IF NOT EXISTS `pte` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `pte`;

-- Copiando estrutura para tabela pte.alternativas
CREATE TABLE IF NOT EXISTS `alternativas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_questao` int DEFAULT NULL,
  `texto` text,
  `correta` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_questao` (`id_questao`),
  CONSTRAINT `FK_alternativas_questoes` FOREIGN KEY (`id_questao`) REFERENCES `questoes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela pte.aulas
CREATE TABLE IF NOT EXISTS `aulas` (
  `nome_aula` varchar(100) DEFAULT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  `id_modulo` int DEFAULT NULL,
  `excluido` tinyint DEFAULT NULL,
  `tipo` tinyint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Aulas_Modulos_FK` (`id_modulo`),
  CONSTRAINT `Aulas_Modulos_FK` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela pte.midias
CREATE TABLE IF NOT EXISTS `midias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `desc_midia` varchar(100) DEFAULT NULL,
  `id_aula` int DEFAULT NULL,
  `conteudo` longblob,
  PRIMARY KEY (`id`),
  KEY `Midias_Aulas_FK` (`id_aula`),
  CONSTRAINT `Midias_Aulas_FK` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela pte.modulos
CREATE TABLE IF NOT EXISTS `modulos` (
  `nome_modolu` varchar(100) DEFAULT NULL,
  `id_curso` int DEFAULT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `Modulos_treinamentos_FK` (`id_curso`),
  CONSTRAINT `Modulos_treinamentos_FK` FOREIGN KEY (`id_curso`) REFERENCES `treinamentos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela pte.noticias
CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(80) NOT NULL,
  `conteudo` varchar(255) NOT NULL,
  `data_noticia` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela pte.questoes
CREATE TABLE IF NOT EXISTS `questoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pergunta` text NOT NULL,
  `id_prova` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_prova` (`id_prova`),
  CONSTRAINT `FK_questoes_aulas` FOREIGN KEY (`id_prova`) REFERENCES `aulas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela pte.treinamentos
CREATE TABLE IF NOT EXISTS `treinamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `criado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela pte.use_treinamentos
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;








