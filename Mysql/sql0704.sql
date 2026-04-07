-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.16.0.7229
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
CREATE DATABASE IF NOT EXISTS `pte` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `pte`;

-- Copiando estrutura para tabela pte.alternativas
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.alternativas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.aulas
CREATE TABLE IF NOT EXISTS `aulas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_aula` varchar(100) DEFAULT NULL,
  `id_modulo` int(11) DEFAULT NULL,
  `excluido` tinyint(4) DEFAULT NULL,
  `tipo` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Aulas_Modulos_FK` (`id_modulo`),
  CONSTRAINT `Aulas_Modulos_FK` FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.aulas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.candidaturas
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.candidaturas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.certificado
CREATE TABLE IF NOT EXISTS `certificado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `emitido` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `FK_certificado_usuarios` FOREIGN KEY (`id_user`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.certificado: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.midias
CREATE TABLE IF NOT EXISTS `midias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desc_midia` varchar(100) DEFAULT NULL,
  `id_aula` int(11) DEFAULT NULL,
  `conteudo` longblob DEFAULT NULL,
  `caminho_video` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Midias_Aulas_FK` (`id_aula`),
  CONSTRAINT `Midias_Aulas_FK` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.midias: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.modulos
CREATE TABLE IF NOT EXISTS `modulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_modolu` varchar(100) DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Modulos_treinamentos_FK` (`id_curso`),
  CONSTRAINT `Modulos_treinamentos_FK` FOREIGN KEY (`id_curso`) REFERENCES `treinamentos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.modulos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.notas
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.notas: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.noticias
CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(80) NOT NULL,
  `conteudo` varchar(255) NOT NULL,
  `data_noticia` datetime DEFAULT NULL,
  `vaga` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.noticias: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.progress
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.progress: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.questoes
CREATE TABLE IF NOT EXISTS `questoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pergunta` text DEFAULT NULL,
  `id_prova` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_questoes_aulas` (`id_prova`),
  CONSTRAINT `FK_questoes_aulas` FOREIGN KEY (`id_prova`) REFERENCES `aulas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.questoes: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.treinamentos
CREATE TABLE IF NOT EXISTS `treinamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `criado` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.treinamentos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.use_prova
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.use_prova: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.use_treinamentos
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.use_treinamentos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela pte.usuarios
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
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.usuarios: ~3 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `email`, `senha`, `ativos`, `Foto`, `token`, `tipo`, `nome`, `acess`) VALUES
	(231, 'funcionario@email.com', '$2y$12$nqR1Y5NuTDqfDiIFFgwNCOsKfMwgWFXmxm8ugIimVn3WY4JAfzyCi', 1, NULL, '9d03cfcfd15b85fd666aded5c8e72d5007f6ce27e856f51a5eac0372d5806915', '1', 'Funcionario pereiroa', 1),
	(232, 'pte@pte', '$2y$12$EjcoXphCxCsyJBDBKnmHEeRdz43mJtvmspQRc16I.kMyMcqGCPS3O', 1, NULL, '07bb1dbbfc576caa23ef777b7a6afd57d65baebd1eb3e07d8ff28e08d6127bec', '2', 'pte', 1),
	(234, 'mvc@mvc.com', '$2y$10$6PYVz0da1yRKKBGeAnwc4Owmyb2jwz8RDtu9quIOO1jVDfdE7bWCu', 1, NULL, 'e736dae67798076e561b95651191558802812fbde7fefbd02225c54446a21152', '1', 'mvc', 1);

-- Copiando estrutura para tabela pte.vagas
CREATE TABLE IF NOT EXISTS `vagas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(80) NOT NULL,
  `conteudo` varchar(255) NOT NULL,
  `data_vaga` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela pte.vagas: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
