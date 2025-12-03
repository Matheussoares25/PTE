
CREATE DATABASE IF NOT EXISTS PTE
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci;

USE PTE;


CREATE TABLE IF NOT EXISTS usuarios (
  Email VARCHAR(250) NOT NULL,
  senha VARCHAR(260) NOT NULL,
  id_user INT NOT NULL AUTO_INCREMENT,
  ativos INT DEFAULT NULL,
  Foto MEDIUMBLOB,
  PRIMARY KEY (id_user)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE IF NOT EXISTS treinamentos (
  id_treinamentos INT NOT NULL AUTO_INCREMENT,
  Nome VARCHAR(250) NOT NULL,
  status_treinamento INT NOT NULL,
  Data DATE NOT NULL,
  PRIMARY KEY (id_treinamentos)
)


-- Copiando estrutura do banco de dados para PTE
CREATE DATABASE IF NOT EXISTS `PTE` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `PTE`;

-- Copiando estrutura para tabela PTE.noticias
CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(80) NOT NULL DEFAULT '0',
  `conteudo` varchar(255) NOT NULL DEFAULT '0',
  `data_noticia` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela PTE.treinamentos
CREATE TABLE IF NOT EXISTS `treinamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela PTE.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Email` varchar(250) NOT NULL,
  `senha` varchar(260) NOT NULL,
  `ativos` int DEFAULT NULL,
  `Foto` mediumblob,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=213 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
