
CREATE DATABASE IF NOT EXISTS PTE
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_0900_ai_ci;

USE PTE;


CREATE TABLE IF NOT EXISTS `noticias` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `titulo` VARCHAR(80) NOT NULL,
    `conteudo` VARCHAR(255) NOT NULL,
    `data_noticia` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB 
  AUTO_INCREMENT=3 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_0900_ai_ci;



CREATE TABLE IF NOT EXISTS `treinamentos` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(50) DEFAULT NULL,
    `status` TINYINT(1) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB 
  AUTO_INCREMENT=4 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_0900_ai_ci;

  INSERT INTO `treinamentos` (`id`, `nome`, `status`) VALUES
	(1, 'teste', 2),
	(2, 'Treinamento nota fiscal', 1),
	(3, 'Treinamento recebimento', 1);




CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `Email` VARCHAR(250) NOT NULL,
    `senha` VARCHAR(260) NOT NULL,
    `ativos` INT DEFAULT NULL,
    `Foto` MEDIUMBLOB,
    `token` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB 
  AUTO_INCREMENT=213 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_0900_ai_ci;




