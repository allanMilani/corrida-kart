SET sql_mode = '';

CREATE TABLE
  `file_logs` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `arquivo` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE
  `log_corridas` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `hora` time DEFAULT NULL,
    `piloto` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
    `volta` int DEFAULT NULL,
    `tempo_volta` time DEFAULT NULL,
    `velocidade_media` decimal(10, 3) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `numero_piloto` int DEFAULT NULL,
    `file_log_id` int unsigned DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `log_corridas_relation_1` (`file_log_id`),
    CONSTRAINT `log_corridas_relation_1` FOREIGN KEY (`file_log_id`) REFERENCES `file_logs` (`id`)
  ) ENGINE = InnoDB AUTO_INCREMENT = 70 DEFAULT CHARSET = latin1 COLLATE = latin1_bin;